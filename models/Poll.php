<?php

namespace humhub\modules\polls\models;

use Yii;
use humhub\modules\content\components\ContentActiveRecord;
use humhub\modules\polls\models\PollAnswer;
use humhub\modules\polls\models\PollAnswerUser;

/**
 * This is the model class for table "poll".
 *
 * The followings are the available columns in table 'poll':
 *
 * @property integer $id
 * @property string $question
 * @property integer $allow_multiple
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @package humhub.modules.polls.models
 * @since 0.5
 * @author Luke
 */
class Poll extends ContentActiveRecord implements \humhub\modules\search\interfaces\Searchable
{

    const MIN_REQUIRED_ANSWERS = 2;

    public $answersText;
    public $autoAddToWall = true;
    public $wallEntryClass = 'humhub\modules\polls\widgets\WallEntry';

    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return 'poll';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array(['question', 'answersText'], 'required'),
            array(['answersText'], 'validateAnswersText'),
            array(['allow_multiple'], 'integer'),
            array(['question'], 'string', 'max' => 600),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'answersText' => Yii::t('PollsModule.models_Poll', 'Answers'),
            'question' => Yii::t('PollsModule.models_Poll', 'Question'),
            'allow_multiple' => Yii::t('PollsModule.models_Poll', 'Multiple answers per user'),
        );
    }

    public function getAnswers()
    {
        $query = $this->hasMany(PollAnswer::className(), ['poll_id' => 'id']);
        return $query;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {
            $answers = explode("\n", $this->answersText);
            foreach ($answers as $answerText) {
                $answer = new PollAnswer();
                $answer->poll_id = $this->id;
                $answer->answer = $answerText;
                $answer->save();
            }
        }

        return true;
    }

    /**
     * Deletes a Poll including its dependencies.
     */
    public function beforeDelete()
    {
        foreach ($this->answers as $answer) {
            foreach ($answer->votes as $answerUser) {
                $answerUser->delete();
            }
            $answer->delete();
        }
        return parent::beforeDelete();
    }

    /**
     * Checks if user has voted
     *
     * @param type $userId
     * @return type
     */
    public function hasUserVoted($userId = "")
    {

        if ($userId == "")
            $userId = Yii::$app->user->id;

        $answer = PollAnswerUser::findOne(array('created_by' => $userId, 'poll_id' => $this->id));

        if ($answer == null)
            return false;

        return true;
    }

    public function vote($votes = array())
    {

        if ($this->hasUserVoted()) {
            return;
        }

        $voted = false;

        foreach ($votes as $answerId) {
            $answer = PollAnswer::findOne(array('id' => $answerId, 'poll_id' => $this->id));

            $userVote = new PollAnswerUser();
            $userVote->poll_id = $this->id;
            $userVote->poll_answer_id = $answer->id;

            if ($userVote->save()) {
                $voted = true;
            }
        }

        if ($voted) {
            $activity = new \humhub\modules\polls\activities\NewVote();
            $activity->source = $this;
            $activity->originator = Yii::$app->user->getIdentity();
            $activity->create();
        }
    }

    /**
     * Resets all answers from a user
     *
     * @param type $userId
     */
    public function resetAnswer($userId = "")
    {

        if ($userId == "")
            $userId = Yii::$app->user->id;

        if ($this->hasUserVoted($userId)) {

            $answers = PollAnswerUser::findAll(array('created_by' => $userId, 'poll_id' => $this->id));
            foreach ($answers as $answer) {
                $answer->delete();
            }

            //ToDo: Delete Activity
        }
    }

    /**
     * @inheritdoc
     */
    public function getContentName()
    {
        return Yii::t('PollsModule.models_Poll', "Question");
    }

    /**
     * @inheritdoc
     */
    public function getContentDescription()
    {
        return $this->question;
    }

    public function validateAnswersText()
    {

        $answers = explode("\n", $this->answersText);
        $answerCount = 0;
        $answerTextNew = "";

        foreach ($answers as $answer) {
            if (trim($answer) != "") {
                $answerCount++;
                $answerTextNew .= $answer . "\n";
            }
        }

        if ($answerCount < self::MIN_REQUIRED_ANSWERS) {
            $this->addError('answersText', Yii::t('PollsModule.models_Poll', "Please specify at least {min} answers!", array("{min}" => self::MIN_REQUIRED_ANSWERS)));
        }

        $this->answersText = $answerTextNew;
    }

    /**
     * @inheritdoc
     */
    public function getSearchAttributes()
    {

        $itemAnswers = "";

        foreach($this->answers as $answer) {
            $itemAnswers .= $answer->answer;
        }

        return array(
            'question' => $this->question,
            'itemAnswers' => $itemAnswers
        );



    }

}

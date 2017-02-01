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
    const SCENARIO_CREATE = 'create';
    const SCENARIO_EDIT = 'edit';
    const SCENARIO_CLOSE = 'close';

    public $newAnswers;
    public $editAnswers;
    public $autoAddToWall = true;
    public $wallEntryClass = 'humhub\modules\polls\widgets\WallEntry';

    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return 'poll';
    }
    
    public function scenarios()
    {
        return [
            self::SCENARIO_CLOSE => [],
            self::SCENARIO_CREATE => ['question', 'anonymous', 'is_random', 'newAnswers', 'allow_multiple'],
            self::SCENARIO_EDIT => ['question', 'anonymous', 'is_random', 'newAnswers', 'editAnswers', 'allow_multiple']
        ];
    }
    

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            [['question'], 'required'],
            [['question'], 'string'],
            [['anonymous', 'is_random'], 'boolean'],
            [['newAnswers'], 'required', 'on' => self::SCENARIO_CREATE],
            [['newAnswers'], 'minTwoNewAnswers', 'on' => self::SCENARIO_CREATE],
            //we use the question attribute since its always required, otherwise it would not be called for editAnswers if editAnswers is empty...
            [['question'], 'minTwoAnswers', 'on' => self::SCENARIO_EDIT],
            [['allow_multiple'], 'integer'],
            
        );
    }
    
    public function minTwoNewAnswers($attribute)
    {
        if(count($this->newAnswers) < self::MIN_REQUIRED_ANSWERS) {
            $this->addError($attribute, Yii::t('PollsModule.models_Poll', "Please specify at least {min} answers!", array("{min}" => self::MIN_REQUIRED_ANSWERS)));
        }
    }
    
    public function minTwoAnswers($attribute)
    {
        $count = count($this->newAnswers) + count($this->editAnswers);
        if ($count < self::MIN_REQUIRED_ANSWERS) {
            $this->addError('editAnswers', Yii::t('PollsModule.models_Poll', "Please specify at least {min} answers!", array("{min}" => self::MIN_REQUIRED_ANSWERS)));
        }
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'newAnswers' => Yii::t('PollsModule.models_Poll', 'Answers'),
            'editAnswers' => Yii::t('PollsModule.models_Poll', 'Answers'),
            'question' => Yii::t('PollsModule.models_Poll', 'Question'),
            'allow_multiple' => Yii::t('PollsModule.models_Poll', 'Multiple answers per user'),
            'is_random' => Yii::t('PollsModule.widgets_views_pollForm', 'Display answers in random order?'),
            'anonymous' => Yii::t('PollsModule.widgets_views_pollForm', 'Anonymous Votes?')
        );
    }
    
    public function isResetAllowed()
    {
        return $this->hasUserVoted() && !$this->closed;
    }

    /**
     * @return ActiveRecord containing all answers of thes poll
     */
    public function getAnswers()
    {
        $query = $this->hasMany(PollAnswer::className(), ['poll_id' => 'id']);
        return $query;
    }

    public function getViewAnswers()
    {
        if ($this->is_random) {
            $result = [];
            foreach ($this->answers as $key => $value) {
                $result[$key] = $value;
            }
            shuffle($result);
            return $result;
        } else {
            return $this->answers;
        }
    }

    /**
     * Saves new answers (if set) and updates answers given editanswers (if set)
     * @param type $insert
     * @param type $changedAttributes
     * @return boolean
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if (!$insert) {
            $this->updateAnswers();
        }

        $this->saveNewAnswers();

        return true;
    }

    public function saveNewAnswers()
    {
        if ($this->newAnswers == null) {
            return;
        }

        foreach ($this->newAnswers as $answerText) {
            $this->addAnswer($answerText);
        }
        
        // Reset cached answers
        unset($this->answers);
    }

    public function addAnswer($answerText)
    {
        if (trim($answerText) === '') {
            return;
        }

        $answer = new PollAnswer();
        $answer->poll_id = $this->id;
        $answer->answer = $answerText;
        $answer->save();
        return $answer;
    }

    public function updateAnswers()
    {
        if ($this->editAnswers == null && $this->newAnswers == null) {
            return;
        }

        foreach ($this->answers as $answer) {
            if (!array_key_exists($answer->id, $this->editAnswers)) {
                $answer->delete();
            } else if ($answer->answer !== $this->editAnswers[$answer->id]) {
                $answer->answer = $this->editAnswers[$answer->id];
                $answer->update();
            }
        }
    }

    /**
     * Sets the newAnswers array, which is used for creating and updating (afterSave)
     * the poll, by saving all valid answertexts contained in the given array.
     * @param type $newAnswerArr
     */
    public function setNewAnswers($newAnswerArr)
    {
        $this->newAnswers = PollAnswer::filterValidAnswers($newAnswerArr);
    }

    /**
     * Sets the editAnswers array, which is used for updating (afterSave)
     * the poll. The given array has to contain poll answer ids as key and an answertext
     * as values.
     * @param type $newAnswerArr
     */
    public function setEditAnswers($editAnswerArr)
    {
        $this->editAnswers = PollAnswer::filterValidAnswers($editAnswerArr);
    }

    /**
     * Deletes a Poll including its dependencies.
     */
    public function beforeDelete()
    {
        foreach ($this->answers as $answer) {
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
            if ($answer) {
                $userVote = new PollAnswerUser();
                $userVote->poll_id = $this->id;
                $userVote->poll_answer_id = $answer->id;

                if ($userVote->save()) {
                    $voted = true;
                }
            }
        }

        if ($voted && !$this->anonymous) {
            $activity = new \humhub\modules\polls\activities\NewVote();
            $activity->source = $this;
            $activity->originator = Yii::$app->user->getIdentity();
            $activity->create();
        }
    }

    /**
     * Resets all answers from a user only if the poll is not closed yet.
     *
     * @param type $userId
     */
    public function resetAnswer($userId = "")
    {

        if($this->closed) {
            return;
        }
        
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

    /**
     * @inheritdoc
     */
    public function getSearchAttributes()
    {

        $itemAnswers = "";

        foreach ($this->answers as $answer) {
            $itemAnswers .= $answer->answer;
        }

        return array(
            'question' => $this->question,
            'itemAnswers' => $itemAnswers
        );
    }

}

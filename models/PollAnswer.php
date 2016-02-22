<?php

namespace humhub\modules\polls\models;

use Yii;
use humhub\components\ActiveRecord;
use humhub\modules\polls\models\Poll;
use humhub\modules\polls\models\PollAnswerUser;

/**
 * This is the model class for table "poll_answer".
 *
 * The followings are the available columns in table 'poll_answer':
 * @property integer $id
 * @property integer $question_id
 * @property string $answer
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @package humhub.modules.polls.models
 * @since 0.5
 * @author Luke
 */
class PollAnswer extends ActiveRecord
{
    
    public $active = true;

    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return 'poll_answer';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array(['poll_id', 'answer'], 'required'),
            array(['poll_id'], 'integer'),
            array(['answer'], 'string', 'max' => 255),
        );
    }

    public function getPoll()
    {
        return $this->hasOne(Poll::className(), ['id' => 'poll_id']);
    }

    public function getVotes()
    {
        $query = $this->hasMany(PollAnswerUser::className(), ['poll_answer_id' => 'id']);
        return $query;
    }
    
    public function beforeDelete() {
        foreach ($this->votes as $answerUser) {
            $answerUser->delete();
        }
        
        return parent::beforeDelete();
    }

    /**
     * Returns the percentage of users voted for this answer
     *
     * @return int
     */
    public function getPercent()
    {
        $total = PollAnswerUser::find()->where(array('poll_id' => $this->poll_id))->count();
        if ($total == 0)
            return 0;

        return $this->getTotal() / $total * 100;
    }

    /**
     * Returns the total number of users voted for this answer
     *
     * @return int
     */
    public function getTotal()
    {

        return PollAnswerUser::find()->where(array('poll_answer_id' => $this->id))->count();
    }
    
    public static function filterValidAnswers($answerArr) 
    {
        if($answerArr == null) {
            return;
        }
        
        $result = [];
        foreach ($answerArr as $key => $answerText) {
            if($answerText != null && $answerText !== '') {
                $result[$key] = $answerText;
            }
        }
        return $result;
    }
}

<?php

namespace humhub\modules\polls\models;

use humhub\components\ActiveRecord;
use humhub\modules\user\models\User;

/**
 * This is the model class for table "poll_answer_user".
 *
 * The followings are the available columns in table 'poll_answer_user':
 * @property int $id
 * @property int $question_id
 * @property int $question_answer_id
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 *
 * @property-read Poll $poll
 * @property-read User $user
 *
 * @package humhub.modules.polls.models
 * @since 0.5
 * @author Luke
 */
class PollAnswerUser extends ActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return 'poll_answer_user';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            [['poll_answer_id', 'poll_id'], 'required'],
            [['poll_answer_id', 'poll_id'], 'integer'],
        ];
    }

    public function getPoll()
    {
        return $this->hasOne(Poll::className(), ['id' => 'poll_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

}

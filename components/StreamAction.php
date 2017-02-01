<?php

namespace humhub\modules\polls\components;

use Yii;
use humhub\modules\stream\actions\ContentContainerStream;
use humhub\modules\polls\models\Poll;

class StreamAction extends ContentContainerStream
{

    public function setupFilters()
    {
        if (in_array('polls_notAnswered', $this->filters) || in_array('polls_mine', $this->filters)) {

            $this->activeQuery->leftJoin('poll', 'content.object_id=poll.id AND content.object_model=:pollClass', [':pollClass' => Poll::className()]);

            if (in_array('polls_notAnswered', $this->filters)) {
                $this->activeQuery->leftJoin('poll_answer_user', 'poll.id=poll_answer_user.poll_id AND poll_answer_user.created_by=:userId', [':userId' => Yii::$app->user->id]);
                $this->activeQuery->andWhere(['is', 'poll_answer_user.id', new \yii\db\Expression('NULL')]);
            }
        }
    }

}

?>

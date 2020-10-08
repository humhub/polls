<?php

namespace humhub\modules\polls\components;

use humhub\modules\content\models\Content;
use humhub\modules\polls\models\PollAnswerUser;
use humhub\modules\stream\models\filters\DefaultStreamFilter;
use Yii;
use humhub\modules\stream\actions\ContentContainerStream;
use humhub\modules\polls\models\Poll;

class StreamAction extends ContentContainerStream
{

    public function setupFilters()
    {
        if (in_array('filter_polls_notAnswered', $this->streamQuery->filters) || in_array('entry_mine', $this->filters)) {

            $this->streamQuery->query()->leftJoin('poll', 'content.object_id=poll.id AND content.object_model=:pollClass', [':pollClass' => Poll::className()]);

            if (in_array('filter_polls_notAnswered', $this->streamQuery->filters)) {
                $this->streamQuery->query()->leftJoin('poll_answer_user', 'poll.id=poll_answer_user.poll_id AND poll_answer_user.created_by=:userId', [':userId' => Yii::$app->user->id]);
                $this->streamQuery->query()->andWhere(['is', 'poll_answer_user.id', new \yii\db\Expression('NULL')]);
            }
        }
    }

}



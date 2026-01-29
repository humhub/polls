<?php

namespace humhub\modules\polls\activities;

use humhub\modules\activity\components\BaseContentActivity;
use humhub\modules\activity\interfaces\ConfigurableActivityInterface;
use Yii;

class NewVote extends BaseContentActivity implements ConfigurableActivityInterface
{
    public static function getTitle(): string
    {
        return Yii::t('PollsModule.base', 'Polls');
    }

    public static function getDescription(): string
    {
        return Yii::t('PollsModule.base', 'Whenever someone participates in a poll.');
    }

    protected function getMessage(array $params): string
    {
        // Backward compatibility of translation placeholders
        $params['userName'] = $params['displayName'];
        $params['question'] = $params['contentTitle'];

        return Yii::t('PollsModule.base', '{userName} answered the {question}.', $params);
    }
}

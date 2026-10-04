<?php

use humhub\modules\polls\models\Poll;
use humhub\widgets\bootstrap\Link;

/* @var Poll $poll */
?>
<li>
    <?= $poll->closed
        ? Link::to(Yii::t('PollsModule.base', 'Reopen Poll'))
            ->action('close', $poll->content->container->createUrl('/polls/poll/open', ['id' => $poll->id]), '[data-poll=' . $poll->id . ']')
            ->icon('check')
            ->cssClass('dropdown-item')
        : Link::to(Yii::t('PollsModule.base', 'Close Poll'))
            ->action('close', $poll->content->container->createUrl('/polls/poll/close', ['id' => $poll->id]), '[data-poll=' . $poll->id . ']')
            ->icon('times')
            ->cssClass('dropdown-item') ?>
</li>

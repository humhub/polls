<?php

use humhub\modules\polls\models\Poll;
use humhub\widgets\bootstrap\Link;

/* @var Poll $poll */
?>
<li>
    <?= Link::to(Yii::t('PollsModule.base', 'Reset my vote'))
        ->action('reset', $poll->content->container->createUrl('/polls/poll/answer-reset', ['pollId' => $poll->id]), '[data-poll=' . $poll->id . ']')
        ->icon('undo')
        ->cssClass('dropdown-item') ?>
</li>

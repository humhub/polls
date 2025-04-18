<?php

use humhub\modules\content\widgets\richtext\RichText;
use humhub\modules\polls\models\Poll;
use humhub\helpers\Html;
use humhub\widgets\bootstrap\Badge;
use humhub\widgets\bootstrap\Button;
use humhub\widgets\bootstrap\Alert;

humhub\modules\polls\assets\PollsAsset::register($this);

/** @var $poll Poll **/
?>

<div data-poll="<?= $poll->id ?>" data-content-component="polls.Poll" data-content-key="<?= $poll->content->id ?>">

    <?php if ($poll->closed) : ?>
        &nbsp;<?= Badge::danger(Yii::t('PollsModule.base', 'Closed'))->cssClass('ms-1')->right() ?>
    <?php endif; ?>

    <?php if ($poll->anonymous) : ?>
        &nbsp;<?= Badge::success(Yii::t('PollsModule.base', 'Anonymous'))->right() ?>
    <?php endif; ?>

    <?= Html::beginForm($contentContainer->createUrl('/polls/poll/answer', ['pollId' => $poll->id])) ?>

    <div data-ui-markdown>
        <?= RichText::output($poll->description) ?>
    </div>

    <br><br>

    <?php foreach ($poll->getViewAnswers() as $answer) : ?>
        <?= $this->render('_answer', ['poll' => $poll, 'answer' => $answer, 'contentContainer' => $contentContainer]) ?>
    <?php endforeach; ?>

    <?php if(!$poll->isShowResult()) : ?>
        <br>
        <?= Alert::light(Yii::t('PollsModule.base', '<strong>Note:</strong> The result is hidden until the poll is closed by a moderator.'))->cssClass('m-0')) ?>
    <?php endif; ?>

    <?php if (!$poll->hasUserVoted() && !Yii::$app->user->isGuest && !$poll->closed) : ?>
        <br>
        <?= Button::primary(Yii::t('PollsModule.base', 'Vote'))->options([
            'data-action-click' => 'vote',
            'data-action-submit' => true,
            'data-ui-loader' => true
        ]); ?>
        <br>
    <?php endif; ?>

    <?php if (Yii::$app->user->isGuest && !$poll->closed) : ?>
        <?= Button::primary(Yii::t('PollsModule.base', 'Vote'))->link(Yii::$app->user->loginUrl)->options(['data-bs-target' => '#globalModal']) ?>
    <?php endif; ?>

    <div class="clearFloats"></div>

    <?= Html::endForm(); ?>
</div>

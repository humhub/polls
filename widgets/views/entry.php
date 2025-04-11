<?php

use humhub\modules\content\widgets\richtext\RichText;
use humhub\modules\polls\models\Poll;
use yii\helpers\Html;

humhub\modules\polls\assets\PollsAsset::register($this);

/** @var $poll Poll **/
?>

<div data-poll="<?= $poll->id ?>" data-content-component="polls.Poll" data-content-key="<?= $poll->content->id ?>">

    <?php if ($poll->closed) : ?>
        &nbsp;<span style="margin-left:3px;" class="label label-danger pull-right"><?= Yii::t('PollsModule.base', 'Closed') ?></span>
    <?php endif; ?>

    <?php if ($poll->anonymous) : ?>
        &nbsp;<span class="label label-success pull-right"><?= Yii::t('PollsModule.base', 'Anonymous') ?></span>
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
        <div class="alert alert-default" style="margin:0">
            <?= Yii::t('PollsModule.base', '<strong>Note:</strong> The result is hidden until the poll is closed by a moderator.') ?>
        </div>
    <?php endif; ?>

    <?php if (!$poll->hasUserVoted() && !Yii::$app->user->isGuest && !$poll->closed) : ?>
        <br>
        <button data-action-click="vote" data-action-submit data-ui-loader class="btn btn-primary">
            <?= Yii::t('PollsModule.base', 'Vote') ?>
        </button>
        <br>
    <?php endif; ?>

    <?php if (Yii::$app->user->isGuest && !$poll->closed) : ?>
        <?= Html::a(Yii::t('PollsModule.base', 'Login to vote'), Yii::$app->user->loginUrl, ['class' => 'btn btn-primary', 'data-target' => '#globalModal']); ?>
    <?php endif; ?>


    <div class="clearFloats"></div>

    <?= Html::endForm(); ?>
</div>

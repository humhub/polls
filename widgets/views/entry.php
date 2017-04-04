<?php

use yii\helpers\Html;

humhub\modules\polls\assets\PollsAsset::register($this);
?>

<div data-poll="<?= $poll->id ?>" data-content-component="polls.Poll" data-content-key="<?= $poll->content->id ?>">

    <?php if ($poll->closed) : ?>
        &nbsp;<span class="label label-danger pull-right"><?= Yii::t('PollsModule.widgets_views_entry', 'Closed') ?></span>
    <?php endif; ?>

    <?php if ($poll->anonymous) : ?>
        &nbsp;<span class="label label-success pull-right"><?= Yii::t('PollsModule.widgets_views_entry', 'Anonymous') ?></span>
    <?php endif; ?>

    <?= Html::beginForm($contentContainer->createUrl('/polls/poll/answer', ['pollId' => $poll->id])); ?>
    <div data-ui-markdown>
        <?= humhub\widgets\RichText::widget(['text' => $poll->question]); ?>
    </div>

    <br><br>

    <?php foreach ($poll->getViewAnswers() as $answer) : ?>
        <?= $this->render('_answer', ['poll' => $poll, 'answer' => $answer, 'contentContainer' => $contentContainer]); ?>
    <?php endforeach; ?>

    <?php if (!$poll->hasUserVoted() && !Yii::$app->user->isGuest && !$poll->closed) : ?>
        <br>
        <button data-action-click="vote" data-action-submit data-ui-loader class="btn btn-primary">
            <?= Yii::t('PollsModule.widgets_views_entry', 'Vote') ?>
        </button>
        <br>
    <?php endif; ?>

    <?php if (Yii::$app->user->isGuest && !$poll->closed) : ?>
        <?php echo Html::a(Yii::t('PollsModule.widgets_views_entry', 'Vote'), Yii::$app->user->loginUrl, array('class' => 'btn btn-primary', 'data-target' => '#globalModal')); ?>
    <?php endif; ?>


    <div class="clearFloats"></div>

    <?php echo Html::endForm(); ?>
</div>
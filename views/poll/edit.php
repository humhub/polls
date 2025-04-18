<?php

use humhub\modules\content\widgets\richtext\RichTextField;
use humhub\widgets\form\ActiveForm;
use humhub\helpers\Html;
use humhub\modules\polls\widgets\AddAnswerInput;
use humhub\widgets\bootstrap\Button;
use humhub\widgets\bootstrap\Alert;
use humhub\modules\ui\icon\widgets\Icon;

/** @var  $poll \humhub\modules\polls\models\Poll */

$disabled = ($poll->closed) ? 'disabled="disabled"' : '';
?>

<div data-poll="<?= $poll->id ?>" data-content-component="polls.Poll" data-content-key="<?= $poll->content->id ?>"
     class="content_edit" id="poll_edit_<?= $poll->id; ?>">
     <?= Alert::danger('<span class="errorMessage"></span>')->cssClass(['d-none']) ?>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($poll, 'question')->textInput($poll->closed ? ['disabled' => 'disabled'] : []) ?>

    <?= $form->field($poll, 'description')->widget(RichTextField::class, ['disabled' => $poll->closed, 'placeholder' => Yii::t('PollsModule.base', 'Edit your poll question...')]) ?>

    <div class="contentForm_options">
        <?= Html::activeLabel($poll, 'answersText', ['label' => Yii::t('PollsModule.base', 'Answers'), 'class' => 'control-label']); ?>
        <?php foreach ($poll->answers as $answer) : ?>
            <div class="mb-3">
                <div class="input-group">
                    <input type="text" name="answers[<?= $answer->id ?>]" <?= $disabled ?>
                           title="<?= count($answer->votes) . ' ' . Yii::t('PollsModule.base', 'votes') ?>"
                           value="<?= Html::encode($answer->answer) ?>"
                           class="form-control tt poll_answer_old_input"
                           placeholder="<?= Yii::t('PollsModule.base', "Edit answer (empty answers will be removed)...") ?>"/>
                    <div class="input-group-text" style="cursor:pointer;" data-action-click="removePollAnswer">
                        <?= Icon::get('trash') ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (!$poll->closed) : ?>
            <?= AddAnswerInput::widget(['name' => 'newAnswers[]', 'showTitle' => true]); ?>
        <?php endif; ?>


        <?= $form->field($poll, 'is_random')->checkbox(['id' => 'edit_poll_is_random_' . $poll->id]) ?>

        <?php if (!$poll->anonymous) : ?>
            <?= $form->field($poll, 'anonymous')->checkbox(['id' => 'edit_poll_anonymous' . $poll->id]) ?>
        <?php endif; ?>

        <?= $form->field($poll, 'show_result_after_close')->checkbox(['id' => 'edit_poll_show_result_after_close' . $poll->id]) ?>

    </div>
    <?= Button::primary(Yii::t('PollsModule.base', "Save"))->options([
        'data-action-click' => 'editSubmit',
        'data-action-submit' => true,
        'data-action-url' => $poll->content->container->createUrl('/polls/poll/edit', ['id' => $poll->id]),
        'data-ui-loader' => true
    ]); ?>

    <?= Button::danger(Yii::t('PollsModule.base', "Cancel"))->options([
        'data-action-click' => 'editCancel',
        'data-action-url' => $poll->content->container->createUrl('/polls/poll/reload', ['id' => $poll->id]),
        'data-ui-loader' => true
    ]); ?>

    <?php ActiveForm::end(); ?>
</div>

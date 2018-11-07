<?php

use humhub\widgets\RichtextField;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use humhub\modules\polls\widgets\AddAnswerInput;

/** @var  $poll \humhub\modules\polls\models\Poll */

$disabled = ($poll->closed) ? 'disabled="disabled"' : '';
?>

<div data-poll="<?= $poll->id ?>" data-content-component="polls.Poll" data-content-key="<?= $poll->content->id ?>" class="content_edit" id="poll_edit_<?= $poll->id; ?>">
    <div  class="alert alert-danger" role="alert" style="display:none">
        <span class="errorMessage"></span>
    </div>
    
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($poll, 'question')->widget(RichtextField::class, ['disabled' => $poll->closed, 'placeholder' => Yii::t('PollsModule.widgets_views_pollForm', 'Edit your poll question...')]) ?>

    <div class="contentForm_options">
        <?= Html::activeLabel($poll, 'answersText', ['class' => 'control-label']); ?>
        <?php foreach ($poll->answers as $answer) :?>
            <div class="form-group">
                <div class="input-group">
                    <input type="text" name="answers[<?= $answer->id ?>]" <?= $disabled ?>
                           title="<?= count($answer->votes) . ' ' . Yii::t('PollsModule.widgets_views_entry', 'votes') ?>" 
                           value="<?= Html::encode($answer->answer) ?>"
                           class="form-control tt poll_answer_old_input"
                           placeholder="<?= Yii::t('PollsModule.widgets_views_pollForm', "Edit answer (empty answers will be removed)...") ?>"/>
                    <div class="input-group-addon" style="cursor:pointer;" data-action-click="removePollAnswer">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (!$poll->closed) : ?>
            <?= AddAnswerInput::widget(['name' => 'newAnswers[]', 'showTitle' => true]); ?>
        <?php endif; ?> 

        
        <?= $form->field($poll, 'is_random')->checkbox(['id' => 'edit_poll_is_random_'.$poll->id]) ?>
        
        <?php if (!$poll->anonymous) : ?>
            <?= $form->field($poll, 'anonymous')->checkbox(['id' => 'edit_poll_anonymous'.$poll->id]) ?>
        <?php endif; ?>

        <?= $form->field($poll, 'show_result_after_close')->checkbox(['id' => 'edit_poll_show_result_after_close'.$poll->id]) ?>
        
    </div>
    
    <a href="#" class="btn btn-primary" 
       data-action-click="editSubmit" data-action-submit 
       data-action-url="<?= $poll->content->container->createUrl('/polls/poll/edit', ['id' => $poll->id]) ?>"
       data-ui-loader>
        <?= Yii::t('PollsModule.base', "Save") ?>
    </a>
    
    <a href="#" class="btn btn-danger" 
       data-action-click="editCancel" 
       data-action-url="<?= $poll->content->container->createUrl('/polls/poll/reload', ['id' => $poll->id]) ?>"
       data-ui-loader>
        <?= Yii::t('PollsModule.base', "Cancel") ?>
    </a>
    <?php ActiveForm::end(); ?>
</div>
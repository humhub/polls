<?php

use humhub\compat\CActiveForm;

$disabled = ($poll->closed) ? 'disabled="disabled"' : '';
?>

<div data-poll="<?= $poll->id ?>" data-content-component="polls.Poll" data-content-key="<?= $poll->content->id ?>" class="content_edit" id="poll_edit_<?= $poll->id; ?>">
    <div  class="alert alert-danger" role="alert" style="display:none">
        <span class="errorMessage"></p>
    </div>
    
    <?php $form = CActiveForm::begin(); ?>
    
    <?= \humhub\widgets\RichtextField::widget([
        'form' => $form,
        'model' => $poll,
        'label' => true,
        'attribute' => "question",
        'disabled' => $poll->closed,
        'placeholder' => Yii::t('PollsModule.widgets_views_pollForm', 'Edit your poll question...')
    ]); ?>

    <div class="contentForm_options">
        <?= $form->label($poll, "answersText", ['class' => 'control-label']); ?>
        <?php foreach ($poll->answers as $answer) :?>
            <div class="form-group">
                <div class="input-group">
                    <input type="text" name="answers[<?= $answer->id ?>]" <?= $disabled ?>
                           title="<?= count($answer->votes) . ' ' . Yii::t('PollsModule.widgets_views_entry', 'votes') ?>" 
                           value="<?= $answer->answer ?>" 
                           class="form-control tt poll_answer_old_input"
                           placeholder="<?= Yii::t('PollsModule.widgets_views_pollForm', "Edit answer (empty answers will be removed)...") ?>"/>
                    <div class="input-group-addon" style="cursor:pointer;" data-action-click="removePollAnswer">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (!$poll->closed) : ?>
            <?= humhub\modules\polls\widgets\AddAnswerInput::widget(['name' => 'newAnswers[]', 'showTitle' => true]); ?>
        <?php endif; ?> 

        
        <?= $form->field($poll, 'is_random')->checkbox() ?>
        
        <?php if (!$poll->anonymous) : ?>
            <?= $form->field($poll, 'anonymous')->checkbox() ?>
        <?php endif; ?>
        
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
    <?php CActiveForm::end(); ?>
</div>
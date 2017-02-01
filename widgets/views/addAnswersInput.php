<?php
/* @var $name String */
/* @var $showTitle Boolean */

$title = ($showTitle) ? '0' . Yii::t('PollsModule.widgets_views_entry', 'votes') : '';
?>

<div class="form-group">
    <div class="input-group">
        <input type="text" name="<?= $name ?>" 
               class="form-control poll_answer_new_input contentForm"
               placeholder="<?= Yii::t('PollsModule.widgets_views_pollForm', "Add answer...") ?>"
               title="<?= $title ?>"/>
        <div class="input-group-addon" style="cursor:pointer;" data-action-click="removePollAnswer">
            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="input-group">
        <input type="text" name="<?= $name ?>" 
               class="form-control poll_answer_new_input contentForm"
               placeholder="<?= Yii::t('PollsModule.widgets_views_pollForm', "Add answer...") ?>"
               title="<?= $title ?>"/>
        <div class="input-group-addon addPollAnswerButton" data-action-click="addPollAnswer" style="cursor:pointer">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
        </div>
    </div>
</div>
<?php

use humhub\modules\ui\icon\widgets\Icon;

/* @var $name String */
/* @var $showTitle Boolean */

$title = ($showTitle) ? '0' . Yii::t('PollsModule.base', 'votes') : '';
?>

<div class="mb-3">
    <div class="input-group">
        <input type="text" name="<?= $name ?>" 
               class="form-control poll_answer_new_input contentForm"
               placeholder="<?= Yii::t('PollsModule.base', "Add answer...") ?>"
               title="<?= $title ?>"/>
        <div class="input-group-addon" style="cursor:pointer;" data-action-click="removePollAnswer">
            <?= Icon::get('trash') ?>
        </div>
    </div>
</div>
<div class="mb-3">
    <div class="input-group">
        <input type="text" name="<?= $name ?>" 
               class="form-control poll_answer_new_input contentForm"
               placeholder="<?= Yii::t('PollsModule.base', "Add answer...") ?>"
               title="<?= $title ?>"/>
        <div class="input-group-addon addPollAnswerButton" data-action-click="addPollAnswer" style="cursor:pointer">
            <?= Icon::get('plus') ?>
        </div>
    </div>
</div>

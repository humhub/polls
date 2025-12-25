<?php

use humhub\helpers\Html;
use humhub\modules\polls\models\Poll;
use humhub\modules\ui\icon\widgets\Icon;

/* @var $model Poll */
/* @var $name String */
/* @var $showTitle Boolean */

$title = ($showTitle) ? '0' . Yii::t('PollsModule.base', 'votes') : '';
?>

<div class="mb-3">
    <div class="input-group">
        <?= Html::activeInput('text', $model, $name, [
            'class' => 'form-control poll_answer_new_input contentForm',
            'placeholder' => Yii::t('PollsModule.base', "Add answer..."),
            'title' => $title,
        ]) ?>
        <div class="input-group-text" style="cursor:pointer;" data-action-click="removePollAnswer">
            <?= Icon::get('trash') ?>
        </div>
    </div>
</div>
<div class="mb-3">
    <div class="input-group">
        <?= Html::activeInput('text', $model, $name, [
            'class' => 'form-control poll_answer_new_input contentForm',
            'placeholder' => Yii::t('PollsModule.base', "Add answer..."),
            'title' => $title,
        ]) ?>
        <div class="input-group-text addPollAnswerButton" data-action-click="addPollAnswer" style="cursor:pointer">
            <?= Icon::get('plus') ?>
        </div>
    </div>
</div>

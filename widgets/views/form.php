<?php

use humhub\modules\content\widgets\richtext\RichTextField;
use humhub\modules\polls\assets\PollsAsset;
use humhub\modules\polls\widgets\AddAnswerInput;
use yii\bootstrap\Html;

/** @var $model \yii\base\Model * */

PollsAsset::register($this);

?>

<?= Html::activeTextInput($model, 'question', [
    'placeholder' => Yii::t('PollsModule.widgets_views_pollForm', 'Question'),
    'class' => 'form-control',
]) ?>

<div class="contentForm_options" data-content-component="polls.Poll">
    <?= RichTextField::widget([
        'name' => 'Poll[description]',
        'placeholder' => Yii::t('PollsModule.widgets_views_pollForm', 'Description'),
        'options' => ['style' => 'margin:20px 0 15px'],
    ]); ?>

    <?= Html::activeLabel($model, 'answersText', ['label' => Yii::t('PollsModule.base', 'Answers'), 'class' => 'control-label']); ?>
    <?= AddAnswerInput::widget(['name' => 'newAnswers[]', 'showTitle' => false]); ?>

    <div class="row">
        <div class="col-sm-6">
            <div class="checkbox regular-checkbox-container">
                <?= Html::activeCheckbox($model, 'allow_multiple'); ?>
            </div>
            <div class="checkbox regular-checkbox-container">
                <?= Html::activeCheckbox($model, 'is_random'); ?>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="checkbox regular-checkbox-container">
                <?= Html::activeCheckbox($model, 'anonymous'); ?>
            </div>
            <div class="checkbox regular-checkbox-container">
                <?= Html::activeCheckbox($model, 'show_result_after_close'); ?>
            </div>
        </div>
    </div>
</div>

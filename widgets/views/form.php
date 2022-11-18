<?php

use humhub\modules\content\widgets\richtext\RichTextField;
use humhub\modules\content\widgets\WallCreateContentFormFooter;
use humhub\modules\polls\assets\PollsAsset;
use humhub\modules\polls\models\Poll;
use humhub\modules\polls\widgets\AddAnswerInput;
use humhub\modules\ui\form\widgets\ActiveForm;
use yii\bootstrap\Html;

/* @var $model Poll */
/* @var $form ActiveForm */
/* @var $submitUrl string */

PollsAsset::register($this);
?>

<?= $form->field($model, 'question')->textInput(['placeholder' => Yii::t('PollsModule.widgets_views_pollForm', 'Question')])->label(false) ?>

<div class="contentForm_options" data-content-component="polls.Poll">
    <?= $form->field($model, 'description')->widget(RichTextField::class, [
        'placeholder' => Yii::t('PollsModule.widgets_views_pollForm', 'Description'),
        'options' => ['style' => 'margin:15px 0 0'],
    ])->label(false) ?>

    <?= Html::activeLabel($model, 'answersText', ['label' => Yii::t('PollsModule.base', 'Answers'), 'class' => 'control-label']); ?>
    <?= AddAnswerInput::widget(['name' => 'newAnswers[]', 'showTitle' => false]); ?>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'allow_multiple')->checkbox() ?>
            <?= $form->field($model, 'is_random')->checkbox() ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'anonymous')->checkbox() ?>
            <?= $form->field($model, 'show_result_after_close')->checkbox() ?>
        </div>
    </div>
</div>

<?= WallCreateContentFormFooter::widget([
    'contentContainer' => $model->content->container,
    'submitUrl' => $submitUrl,
]) ?>
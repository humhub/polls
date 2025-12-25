<?php

use humhub\helpers\Html;
use humhub\modules\content\widgets\richtext\RichTextField;
use humhub\modules\content\widgets\WallCreateContentFormFooter;
use humhub\modules\polls\assets\PollsAsset;
use humhub\modules\polls\models\Poll;
use humhub\modules\polls\widgets\AddAnswerInput;
use humhub\widgets\form\ActiveForm;

/* @var $model Poll */
/* @var $form ActiveForm */
/* @var $submitUrl string */

PollsAsset::register($this);
?>

<?= $form->field($model, 'question')->textInput(['placeholder' => Yii::t('PollsModule.base', 'Question')])->label(false) ?>

<div class="contentForm_options" data-content-component="polls.Poll">
    <?= $form->field($model, 'description')->widget(RichTextField::class, [
        'placeholder' => Yii::t('PollsModule.base', 'Description'),
    ])->label(false) ?>

    <?= Html::activeLabel($model, 'answersText', ['label' => Yii::t('PollsModule.base', 'Answers'), 'class' => 'control-label']); ?>
    <?= AddAnswerInput::widget(['model' => $model, 'name' => 'newAnswers[]', 'showTitle' => false]); ?>

    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'allow_multiple')->checkbox() ?>
                <?= $form->field($model, 'is_random')->checkbox() ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'anonymous')->checkbox() ?>
                <?= $form->field($model, 'show_result_after_close')->checkbox() ?>
            </div>
        </div>
    </div>
</div>

<?= WallCreateContentFormFooter::widget([
    'contentContainer' => $model->content->container,
    'submitUrl' => $submitUrl,
]) ?>

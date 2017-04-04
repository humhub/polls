<?php

use yii\helpers\Html;

\humhub\modules\polls\assets\PollsAsset::register($this);

?>

<?= \humhub\widgets\RichtextField::widget([
        'name' => 'question',
        'placeholder' => Yii::t('PollsModule.widgets_views_pollForm', "Ask something...")
]); ?>

<div class="contentForm_options" data-content-component="polls.Poll">
    <?= humhub\modules\polls\widgets\AddAnswerInput::widget(['name' => 'newAnswers[]', 'showTitle' => false]); ?>
    
    <div class="checkbox">
        <label>
            <?= Html::checkbox("allowMultiple", "", ['id' => "contentForm_allowMultiple", 'class' => 'checkbox contentForm', "tabindex" => "4"]); ?> <?= Yii::t('PollsModule.widgets_views_pollForm', 'Allow multiple answers per user?'); ?>
        </label>
    </div>
    <div class="checkbox">
        <label>
            <?= Html::checkbox("is_random", "", ['id' => "contentForm_is_random", 'class' => 'checkbox contentForm', "tabindex" => "6"]); ?> <?= Yii::t('PollsModule.widgets_views_pollForm', 'Display answers in random order?'); ?>
        </label>
    </div>
    <div class="checkbox">
        <label>
            <?= Html::checkbox("anonymous", "", ['id' => "contentForm_anonymous", 'class' => 'checkbox contentForm', "tabindex" => "5"]); ?> <?= Yii::t('PollsModule.widgets_views_pollForm', 'Anonymous Votes?'); ?>
        </label>
    </div>
</div>

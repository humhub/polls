<?php

use yii\helpers\Html;
?>
<?php echo Html::textArea("question", '', array('id' => 'contentForm_question', 'class' => 'form-control autosize contentForm', 'rows' => '1', "tabindex" => "1", 'placeholder' => Yii::t('PollsModule.widgets_views_pollForm', "Ask something..."))); ?>

<?php

/* Modify textarea for mention input */
echo \humhub\widgets\RichTextEditor::widget(array(
    'id' => 'contentForm_question',
));
?>


<div class="contentForm_options">
    <?php echo humhub\modules\polls\widgets\AddAnswerInput::widget(['name' => 'newAnswers[]', 'showTitle' => false]); ?>
    
    <div class="checkbox">
        <label>
            <?php echo Html::checkbox("allowMultiple", "", array('id' => "contentForm_allowMultiple", 'class' => 'checkbox contentForm', "tabindex" => "4")); ?> <?php echo Yii::t('PollsModule.widgets_views_pollForm', 'Allow multiple answers per user?'); ?>
        </label>
    </div>
    <div class="checkbox">
        <label>
            <?php echo Html::checkbox("is_random", "", array('id' => "contentForm_is_random", 'class' => 'checkbox contentForm', "tabindex" => "6")); ?> <?php echo Yii::t('PollsModule.widgets_views_pollForm', 'Display answers in random order?'); ?>
        </label>
    </div>
    <div class="checkbox">
        <label>
            <?php echo Html::checkbox("anonymous", "", array('id' => "contentForm_anonymous", 'class' => 'checkbox contentForm', "tabindex" => "5")); ?> <?php echo Yii::t('PollsModule.widgets_views_pollForm', 'Anonymous Votes?'); ?>
        </label>
    </div>
</div>

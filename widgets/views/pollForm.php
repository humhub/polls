<?php echo CHtml::textArea("question", "", array('id'=>'contentForm_question', 'class' => 'form-control autosize contentForm', 'rows' => '1', "tabindex" => "1", "placeholder" => Yii::t('PollsModule.widgets_views_pollForm', "Ask something..."))); ?>

<div class="contentForm_options">
    <?php echo CHtml::textArea("answersText", "", array('id' => "contentForm_answersText", 'rows' => '5', 'style' => 'height: auto !important;', "class" => "form-control contentForm", "tabindex" => "2", "placeholder" => Yii::t('PollsModule.widgets_views_pollForm', "Possible answers (one per line)"))); ?>
    <div class="checkbox">
        <label>
            <?php echo CHtml::checkbox("allowMultiple", "", array('id' => "contentForm_allowMultiple", 'class' => 'checkbox contentForm', "tabindex" => "4")); ?> <?php echo Yii::t('PollsModule.widgets_views_pollForm', 'Allow multiple answers per user?'); ?>
        </label>
    </div>
    
</div>

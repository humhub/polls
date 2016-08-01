<?php

use humhub\compat\CActiveForm;

$disabled = ($poll->closed) ? 'disabled="disabled"' : '';
?>

<div class="content_edit" id="poll_edit_<?php echo $poll->id; ?>">
    <p class="errorMessage" style="color:#ff8989;display:none"></p>
    <?php
    $form = CActiveForm::begin(['id' => 'poll-edit-form_' . $poll->id]);
    echo $form->label($poll, "question", ['class' => 'control-label']);
    ?>

    <?php echo $form->textArea($poll, 'question', array('class' => 'form-control', 'id' => 'poll_input_question_' . $poll->id, 'placeholder' => Yii::t('PollsModule.widgets_views_pollForm', 'Edit your poll question...'))); ?>

    <!-- create contenteditable div for HEditorWidget to place the data -->
    <div id="poll_input_question_<?php echo $poll->id; ?>_contenteditable" 
         class="form-control atwho-input"
         style="cursor:<?= ($poll->closed) ? 'not-allowed' : 'auto' ?>"
         contenteditable="<?= ($poll->closed) ? 'false' : 'true' ?>">
             <?php echo \humhub\widgets\RichText::widget(['text' => $poll->question, 'edit' => true]); ?>
    </div>

    <?= \humhub\widgets\RichTextEditor::widget(['id' => 'poll_input_question_' . $poll->id, 'inputContent' => $poll->question]); ?>

    <div class="contentForm_options">
        <?php
        echo $form->label($poll, "answersText", ['class' => 'control-label']);
        $tabIndex = 2;
        foreach ($poll->answers as $answer) :
            ?>
            <div class="form-group">
                <div class="input-group">
                    <input type="text" name="answers[<?= $answer->id ?>]" 
                    <?= $disabled ?>
                           title="<?= count($answer->votes) . ' ' . Yii::t('PollsModule.widgets_views_entry', 'votes') ?>" 
                           value="<?= $answer->answer ?>" 
                           class="form-control poll_answer_old_input" tabindex="<?= $tabIndex ?>"
                           placeholder="<?= Yii::t('PollsModule.widgets_views_pollForm', "Edit answer (empty answers will be removed)...") ?>"/>
                    <div class="input-group-addon removePollAnswerButton">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </div>
                </div>
            </div>
            <?php
            $tabIndex++;
        endforeach;
        ?>

        <?php
        if (!$poll->closed) {
            echo humhub\modules\polls\widgets\AddAnswerInput::widget(['name' => 'newAnswers[]', 'showTitle' => true]);
        }
        ?> 

        <div class="checkbox">
            <label>
                <?php
                echo \yii\helpers\Html::activeCheckbox($poll, "is_random", ['id' => "editForm_random_" . $poll->id,
                    'class' => 'checkbox', "tabindex" => "5", 'label' => Yii::t('PollsModule.widgets_views_pollForm', 'Display answers in random order?')]);
                ?>
            </label>
        </div>
        <?php if (!$poll->anonymous) : ?>
            <div class="checkbox">
                <label>
                    <?php
                    echo \yii\helpers\Html::activeCheckbox($poll, "anonymous", ['id' => "editForm_anonymous_" . $poll->id,
                        'class' => 'checkbox', "tabindex" => "5", 'label' => Yii::t('PollsModule.widgets_views_pollForm', 'Anonymous Votes?')]);
                    ?>
                </label>
            </div>
        <?php endif; ?>
    </div>
    <script type="text/javascript">

        var editPollResultHandler = function (json) {
            $("#pollform-loader_<?= $poll->id ?>").addClass("hidden");
            var $entry = $(".wall_<?= $poll->getUniqueId() ?>");
            if (json.success) {
                $entry.replaceWith(json.output);
            } else if (json.errors) {
                var $errorMessage = $entry.find('.errorMessage');
                var errors = '';
                $.each(json.errors, function (key, value) {
                    errors += value + '<br />';
                });
                $errorMessage.html(errors).show();
            }
        };

        var editPollBeforeSendHandler = function () {
            $(".wall_<?= $poll->getUniqueId() ?>").find('.errorMessage').empty().hide();
            $("#pollform-loader_<?= $poll->id ?>").removeClass("hidden");
        };



    </script>
    <div class="content_edit">
        <hr />
        <?php if (version_compare(Yii::$app->version, '1.0.0-beta.1', 'gt')) : ?>
            <?php echo \humhub\widgets\LoaderWidget::widget(["id" => 'pollform-loader_' . $poll->id, 'cssClass' => 'loader-postform hidden']); ?>
        <?php endif; ?>
        <?php
        echo \humhub\widgets\AjaxButton::widget([
            'label' => 'Save',
            'ajaxOptions' => [
                'dataType' => 'json',
                'type' => 'POST',
                'beforeSend' => 'editPollBeforeSendHandler',
                'success' => 'editPollResultHandler',
                'url' => $poll->content->container->createUrl('/polls/poll/edit', ['id' => $poll->id]),
            ],
            'htmlOptions' => [
                'class' => 'btn btn-primary btn-comment-submit',
                'id' => 'poll_edit_post_' . $poll->id,
                'type' => 'submit'
            ]
        ]);
        echo '&nbsp;';
        echo \humhub\widgets\AjaxButton::widget([
            'label' => 'Cancel',
            'ajaxOptions' => [
                'type' => 'POST',
                'success' => new yii\web\JsExpression('function(html){ $(".wall_' . $poll->getUniqueId() . '").replaceWith(html); }'),
                'url' => $poll->content->container->createUrl('/polls/poll/reload', ['id' => $poll->id]),
            ],
            'htmlOptions' => [
                'class' => 'btn btn-danger btn-comment-submit',
                'id' => 'poll_edit_cancel_post_' . $poll->id
            ]
        ]);
        ?>
        <br />
    </div>
    <?php CActiveForm::end(); ?>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('.poll_answer_old_input').tooltip({
            html: true,
            container: 'body'
        });

        $('.removePollAnswerButton').each(function () {
            var $this = $(this);
            if (!$this.prev('input').is(':disabled')) {
                $this.css('cursor', 'pointer');
            } else {
                $this.css('cursor', 'not-allowed');
            }
        });

        $(document).off('click.poll', '.removePollAnswerButton');

        $(document).on('click.poll', '.removePollAnswerButton', function () {
            $(this).closest('.form-group').remove();
        });
    });
</script>
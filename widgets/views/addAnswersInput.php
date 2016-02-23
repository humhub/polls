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
        <div class="input-group-addon removePollAnswerButton">
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
        <div class="input-group-addon addPollAnswerButton">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        $('.addPollAnswerButton').css('cursor', 'pointer');

        $(document).off('click.poll', '.addPollAnswerButton');
        $(document).on('click.poll', '.addPollAnswerButton', function () {
            var $this = $(this);
            $this.prev('input').tooltip({
                html: true,
                container: 'body'
            });
            var $newInputGroup = $this.closest('.form-group').clone(false);
            var $input = $newInputGroup.find('input');
            //var $tabIndex = $input.attr('tabIndex')

            $input.val('');
            $newInputGroup.hide();
            $this.closest('.form-group').after($newInputGroup);
            $this.children('span').removeClass('glyphicon-plus').addClass('glyphicon-trash');
            $this.removeClass('addPollAnswerButton').addClass('removePollAnswerButton');
            $newInputGroup.fadeIn('fast');
        });

        $(document).off('click.poll', '.removePollAnswerButton');

        $(document).on('click.poll', '.removePollAnswerButton', function () {
            $(this).closest('.form-group').remove();
        });
    });
</script>
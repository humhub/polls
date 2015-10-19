<?php

use yii\helpers\Html;
?>

<?php echo Html::beginForm(); ?>

<?php print nl2br($poll->question); ?><br><br>

<!-- Loop and Show Answers -->
<?php foreach ($poll->answers as $answer): ?>

    <div class="row">
        <?php if (!$poll->hasUserVoted()) : ?>
            <div class="col-md-1" style="padding-right: 0;">
                <?php if ($poll->allow_multiple) : ?>
                    <div class="checkbox">
                        <label>
                            <?php echo Html::checkBox('answers[' . $answer->id . ']'); ?>
                        </label>
                    </div>

                <?php else: ?>
                    <div class="radio">
                        <label>
                            <?php echo Html::radio('answers', false, array('value' => $answer->id, 'id' => 'answer_' . $answer->id)); ?>
                        </label>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php
        $percent = round($answer->getPercent());
        $color = "progress-bar-info";
        ?>

        <div class="col-md-6">
            <b><?php echo $answer->answer; ?></b><br>

            <div class="progress">
                <div id="progress_<?php echo $answer->id; ?>" class="progress-bar <?php echo $color; ?>" role="progressbar" aria-valuenow="<?php echo $percent; ?>" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
            </div>
            <script type="text/javascript">
                $('#progress_<?php echo $answer->id; ?>').css('width', '<?php echo $percent; ?>%');
            </script>
        </div>

        <div class="col-md-4">

            <?php
            $userlist = ""; // variable for users output
            $maxUser = 10; // limit for rendered users inside the tooltip


            for ($i = 0; $i < count($answer->votes); $i++) {

                // if only one user likes
                // check if exists more user as limited
                if ($i == $maxUser) {
                    // output with the number of not rendered users
                    $userlist .= Yii::t('PollsModule.widgets_views_entry', 'and {count} more vote for this.', array('{count}' => (intval(count($answer->votes) - $maxUser))));

                    // stop the loop
                    break;
                } else {
                    $userlist .= "<strong>" . Html::encode($answer->votes[$i]->user->displayName) . "</strong><br>";
                }
            }
            ?>
            <p style="margin-top: 14px;">
                <?php if (count($answer->votes) > 0) { ?>
                    <a href="<?php echo $contentContainer->createUrl('/polls/poll/user-list-results', array('pollId' => $poll->id, 'answerId' => $answer->id)); ?>"
                       class="tt"
                       data-placement="top" title="" data-target="#globalModal" data-toggle="tooltip"
                       data-original-title="<?php echo $userlist; ?>"><?php echo count($answer->votes) . " " . Yii::t('PollsModule.widgets_views_entry', 'votes'); ?></a>


                <?php } else { ?>
                    0 <?php echo Yii::t('PollsModule.widgets_views_entry', 'votes'); ?>
                <?php } ?>
            </p>

        </div>


    </div>
    <div class="clearFloats"></div>
<?php endforeach; ?>


<?php if (!$poll->hasUserVoted() && !Yii::$app->user->isGuest) : ?>
    <br>
    <?php
    echo \humhub\widgets\AjaxButton::widget([
        'label' => Yii::t('PollsModule.widgets_views_entry', 'Vote'),
        'ajaxOptions' => [
            'type' => 'POST',
            'success' => "function(json) {  $('#wallEntry_'+json.wallEntryId).html(parseHtml(json.output)); }",
            'url' => $contentContainer->createUrl('/polls/poll/answer', array('pollId' => $poll->id)),
        ],
        'htmlOptions' => [
            'class' => 'btn btn-primary', 'id' => 'PollAnswerButton_' . $poll->id
        ]
    ]);
    ?>
    <br>
<?php endif; ?>

<?php if (Yii::$app->user->isGuest) : ?>
    <?php echo Html::a(Yii::t('PollsModule.widgets_views_entry', 'Vote'), Yii::$app->user->loginUrl, array('class' => 'btn btn-primary', 'data-target' => '#globalModal', 'data-toggle' => 'modal')); ?>
<?php endif; ?>


<div class="clearFloats"></div>

<?php echo Html::endForm(); ?>

<?php if ($poll->hasUserVoted()) : ?>
    <br>
    <?php
    echo \humhub\widgets\AjaxButton::widget([
        'label' => Yii::t('PollsModule.widgets_views_entry', 'Reset my vote'),
        'ajaxOptions' => [
            'dataType' => 'json',
            'type' => 'POST',
            'success' => "function(json) { $('#wallEntry_'+json.wallEntryId).html(parseHtml(json.output)); $('#wallEntry_'+json.wallEntryId).find(':checkbox, :radio').flatelements(); }",
            'url' => $contentContainer->createUrl('/polls/poll/answer-reset', array('pollId' => $poll->id)),
        ],
        'htmlOptions' => [
            'class' => 'btn btn-danger', 'id' => 'PollAnswerResetButton_' . $poll->id
        ]
    ]);
    ?>
    <br>
<?php endif; ?>

<script type="text/javascript">

$(document).ready(function() {
  // show Tooltips on elements inside the views, which have the class 'tt'
  $('.tt').tooltip({
    html: true,
    container: 'body'
  });
});

</script>

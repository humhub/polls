<?php

use yii\helpers\Html;

if($poll->closed) {
?>

&nbsp;<span class="label label-danger pull-right"><?= Yii::t('PollsModule.widgets_views_entry', 'Closed') ?></span>

<?php
}

if($poll->anonymous) {
?>

&nbsp;<span class="label label-success pull-right"><?= Yii::t('PollsModule.widgets_views_entry', 'Anonymous') ?></span>

<?php
}

echo Html::beginForm(); 
print humhub\widgets\RichText::widget(['text' => $poll->question, 'record' => $poll]);

?>

<br><br>

<!-- Loop and Show Answers -->
<?php 
    foreach ($poll->getViewAnswers() as $answer) {
        echo $this->render('_answer', ['poll' => $poll, 'answer' => $answer, 'contentContainer' => $contentContainer]);
    } 
 ?> 


<?php if (!$poll->hasUserVoted() && !Yii::$app->user->isGuest && !$poll->closed) : ?>
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

<?php if (Yii::$app->user->isGuest && !$poll->closed) : ?>
    <?php echo Html::a(Yii::t('PollsModule.widgets_views_entry', 'Vote'), Yii::$app->user->loginUrl, array('class' => 'btn btn-primary', 'data-target' => '#globalModal')); ?>
<?php endif; ?>


<div class="clearFloats"></div>

<?php echo Html::endForm(); ?>

<?php if ($poll->hasUserVoted() && !$poll->closed) : ?>
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

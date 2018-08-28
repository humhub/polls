<?php

use humhub\modules\polls\permissions\CreatePoll;
use humhub\modules\polls\widgets\WallCreateForm;
use humhub\modules\stream\widgets\StreamViewer;


$canCreatePolls = $contentContainer->permissionManager->can(new CreatePoll());
?>

<?=  WallCreateForm::widget([
    'contentContainer' => $contentContainer,
    'submitButtonText' => Yii::t('PollsModule.widgets_PollFormWidget', 'Ask'),
]); ?>

<?php
 $filters = [
     'filter_polls_notAnswered' => Yii::t('PollsModule.widgets_views_stream', 'No answered yet'),
     'filter_entry_mine' => Yii::t('PollsModule.widgets_views_stream', 'Asked by me'),
     'filter_visibility_public' => Yii::t('PollsModule.widgets_views_stream', 'Only public polls'),
     'filter_visibility_private' => Yii::t('PollsModule.widgets_views_stream', 'Only private polls'),

 ];

 if(version_compare(Yii::$app->version, '1.3', '>=')) {
     $filters['topic'] = null;
 }

?>

<?= StreamViewer::widget([
    'contentContainer' => $contentContainer,
    'streamAction' => '/polls/poll/stream',
    'messageStreamEmpty' => ($canCreatePolls) ?
            Yii::t('PollsModule.widgets_views_stream', '<b>There are no polls yet!</b><br>Be the first and create one...') :
            Yii::t('PollsModule.widgets_views_stream', '<b>There are no polls yet!</b>'),
    'messageStreamEmptyCss' => ($canCreatePolls) ? 'placeholder-empty-stream' : '',
    'filters' => $filters
]);
?>

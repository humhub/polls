<?php $this->beginContent('application.modules_core.activity.views.activityLayoutMail', array('activity' => $activity, 'showSpace' => true)); ?>
<?php echo Yii::t('PollsModule.views_activities_PollCreated', '{userName} created a new {question}.', array(
    '{userName}' => '<strong>'. $user->displayName .'</strong>',
    '{question}' => $target->getContentTitle()
)); ?>
<?php $this->endContent(); ?>
<?php $this->beginContent('application.modules_core.activity.views.activityLayout', array('activity' => $activity)); ?>
<?php echo Yii::t('PollsModule.views_activities_PollAnswered', '{userName} voted the {question}.', array(
    '{userName}' => '<strong>'. $user->displayName. '</strong>',
    '{question}' => $target->getContentTitle()
)); ?>
<?php $this->endContent(); ?>


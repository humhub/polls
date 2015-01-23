<?php $this->beginContent('application.modules_core.activity.views.activityLayoutMail', array('activity' => $activity, 'showSpace' => true)); ?>
<?php

echo Yii::t('PollsModule.views_activities_PollAnswered', '{userName} voted the {question}.', array(
    '{userName}' => '<strong>' . CHtml::encode($user->displayName) . '</strong>',
    '{question}' => ActivityModule::formatOutput($target->getContentTitle())
));
?>
<?php $this->endContent(); ?>

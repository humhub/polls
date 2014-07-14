<?php $this->beginContent('application.modules_core.activity.views.activityLayout', array('activity' => $activity)); ?>
<?php echo Yii::t('SpaceModule.activities', '{userName} created a new {question}.', array(
    '{userName}' => '<strong>'. $user->displayName .'</strong>',
    '{question}' => $target->getContentTitle()
)); ?>
<?php $this->endContent(); ?>



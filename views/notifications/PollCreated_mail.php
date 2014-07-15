<?php $this->beginContent('application.modules_core.notification.views.notificationLayoutMail', array('notification' => $notification, 'showSpace' => true)); ?>
<?php echo Yii::t('PollsModule.views_notifications_PollCreated', '{userName} created a new poll and assigned you.', array(
    '{userName}' => '<strong>'. $creator->displayName .'</strong>'
)); ?>
<?php $this->endContent(); ?>

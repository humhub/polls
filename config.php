<?php

use humhub\modules\space\widgets\Menu;
use humhub\modules\user\models\User;
use humhub\commands\IntegrityController;

return [
    'id' => 'polls',
    'class' => 'humhub\modules\polls\Module',
    'namespace' => 'humhub\modules\polls',
    'events' => array(
        ['class' => humhub\modules\content\widgets\WallEntryControls::className(), 'event' => humhub\modules\content\widgets\WallEntryControls::EVENT_INIT, 'callback' => ['humhub\modules\polls\Events', 'onWallEntryControlsInit']],
        array('class' => User::className(), 'event' => User::EVENT_BEFORE_DELETE, 'callback' => array('humhub\modules\polls\Events', 'onUserDelete')),
        array('class' => Menu::className(), 'event' => Menu::EVENT_INIT, 'callback' => array('humhub\modules\polls\Events', 'onSpaceMenuInit')),
        array('class' => IntegrityController::className(), 'event' => IntegrityController::EVENT_ON_RUN, 'callback' => array('humhub\modules\polls\Events', 'onIntegrityCheck')),
        array('class' => 'humhub\modules\installer\controllers\ConfigController', 'event' => 'install_sample_data', 'callback' => array('humhub\modules\polls\Events', 'onSampleDataInstall')),
    ),
];
?>
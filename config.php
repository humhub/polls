<?php

use humhub\modules\polls\Events;
use humhub\modules\user\models\User;
use humhub\commands\IntegrityController;
use humhub\modules\content\widgets\WallEntryControls;

/** @noinspection MissedFieldInspection */
return [
    'id' => 'polls',
    'class' => 'humhub\modules\polls\Module',
    'namespace' => 'humhub\modules\polls',
    'events' => [
        ['class' => WallEntryControls::class, 'event' => WallEntryControls::EVENT_INIT, 'callback' => [Events::class, 'onWallEntryControlsInit']],
        ['class' => User::class, 'event' => User::EVENT_BEFORE_DELETE, 'callback' => [Events::class, 'onUserDelete']],
        ['class' => IntegrityController::class, 'event' => IntegrityController::EVENT_ON_RUN, 'callback' => [Events::class, 'onIntegrityCheck']],
        ['class' => 'humhub\modules\installer\controllers\ConfigController', 'event' => 'install_sample_data', 'callback' => [Events::class, 'onSampleDataInstall']],
        ['class' => 'humhub\modules\rest\Module', 'event' => 'restApiAddRules', 'callback' => [Events::class, 'onRestApiAddRules']],
        ['class' => 'humhub\modules\custom_pages\modules\template\services\ElementTypeService', 'event' => 'init', 'callback' => [Events::class, 'onCustomPagesTemplateElementTypeServiceInit']],
    ],
];

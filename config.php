<?php

use humhub\modules\space\widgets\Menu;
use humhub\modules\user\models\User;

return [
    'id' => 'polls',
    'class' => 'module\polls\Module',
    'events' => array(
        array('class' => User::className(), 'event' => User::EVENT_BEFORE_DELETE, 'callback' => array('module\polls\Module', 'onUserDelete')),
        array('class' => Menu::className(), 'event' => Menu::EVENT_INIT, 'callback' => array('module\polls\Module', 'onSpaceMenuInit')),
    ),
];
?>
<?php

use yii\helpers\Html;

echo Yii::t('PollsModule.base', '{userName} answered the {question}.', array(
    '{userName}' => '<strong>' . Html::encode($originator->displayName) . '</strong>',
    '{question}' => $this->context->getContentInfo($source)
));
?>

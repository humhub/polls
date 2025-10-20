<?php

use humhub\helpers\Html;

echo Yii::t('PollsModule.base', '{userName} answered the {question}.', [
    '{userName}' => '<strong>' . Html::encode($originator->displayName) . '</strong>',
    '{question}' => $this->context->getContentInfo($source)
]);
?>

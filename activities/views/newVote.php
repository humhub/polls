<?php

use yii\helpers\Html;

echo Yii::t('PollsModule.views_activities_PollAnswered', '{userName} answered the {question}.', array(
    '{userName}' => '<strong>' . Html::encode($originator->displayName) . '</strong>',
    '{question}' => Html::encode($this->context->getContentInfo($source))
));
?>
<?php

namespace humhub\modules\polls;

use Yii;
use humhub\modules\polls\models\Poll;
use humhub\modules\space\models\Space;
use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\content\components\ContentContainerModule;

/**
 * PollsModule is the WebModule for the polling system.
 *
 * This class is also used to process events catched by the autostart.php listeners.
 *
 * @package humhub.modules.polls
 * @since 0.5
 * @author Luke
 */
class Module extends ContentContainerModule
{

    /**
     * @inheritdoc
     */
    public function getContentContainerTypes()
    {
        return [
            Space::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function disable()
    {
        foreach (Poll::find()->all() as $poll) {
            $poll->delete();
        }

        parent::disable();
    }

    /**
     * @inheritdoc
     */
    public function disableContentContainer(ContentContainerActiveRecord $container)
    {
        parent::disableContentContainer($container);

        foreach (Poll::find()->contentContainer($container)->all() as $poll) {
            $poll->delete();
        }
    }

    /**
     * @inheritdoc
     */
    public function getPermissions($contentContainer = null)
    {
        if ($contentContainer instanceof \humhub\modules\space\models\Space) {
            return [
                new permissions\CreatePoll()
            ];
        }

        return [];
    }

    /**
     * @inheritdoc
     */
    public function getContentContainerName(ContentContainerActiveRecord $container)
    {
        return Yii::t('PollsModule.base', 'Polls');
    }

    /**
     * @inheritdoc
     */
    public function getContentContainerDescription(ContentContainerActiveRecord $container)
    {
        return Yii::t('PollsModule.base', 'Allows to start polls.');
    }

}

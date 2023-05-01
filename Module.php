<?php

namespace humhub\modules\polls;

use humhub\components\console\Application as ConsoleApplication;
use humhub\modules\user\models\User;
use humhub\modules\polls\models\Poll;
use humhub\modules\space\models\Space;
use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\content\components\ContentContainerModule;
use Yii;

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
    public function init()
    {
        parent::init();

        if (Yii::$app instanceof ConsoleApplication) {
            // Prevents the Yii HelpCommand from crawling all web controllers and possibly throwing errors at REST endpoints if the REST module is not available.
            $this->controllerNamespace = 'polls/commands';
        }
    }

    /**
     * @inheritdoc
     */
    public function getContentContainerTypes()
    {
        return [
            Space::class,
            User::class
        ];
    }

    /**
     * @inheritdoc
     */
    public function disable()
    {
        foreach (Poll::find()->all() as $poll) {
            $poll->hardDelete();
        }

        parent::disable();
    }

    /**
     * @inheritdoc
     */
    public function disableContentContainer(ContentContainerActiveRecord $container)
    {
        foreach (Poll::find()->contentContainer($container)->all() as $poll) {
            $poll->hardDelete();
        }

        parent::disableContentContainer($container);
    }

    /**
     * @inheritdoc
     */
    public function getPermissions($contentContainer = null)
    {
        if ($contentContainer) {
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

    /**
     * @inheritdoc
     */
    public function getContentClasses(): array
    {
        return [Poll::class];
    }

}

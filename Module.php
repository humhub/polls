<?php

namespace humhub\modules\polls;

use humhub\modules\polls\models\Poll;

/**
 * PollsModule is the WebModule for the polling system.
 *
 * This class is also used to process events catched by the autostart.php listeners.
 *
 * @package humhub.modules.polls
 * @since 0.5
 * @author Luke
 */
class Module extends \humhub\components\Module
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            \humhub\modules\space\behaviors\SpaceModule::className(),
        ];
    }

    /**
     * On global module disable, delete all created content
     */
    public function disable()
    {
        if (parent::disable()) {

            foreach (Poll::find()->all() as $poll) {
                $poll->delete();
            }

            return true;
        }

        return false;
    }

    /**
     * On disabling this module on a space, deleted all module -> space related content/data.
     * Method stub is provided by "SpaceModuleBehavior"
     *
     * @param Space $space
     */
    public function disableSpaceModule(Space $space)
    {
        foreach (Poll::find()->contentContainer($space)->all() as $poll) {
            $poll->delete();
        }
    }

}

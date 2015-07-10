<?php

namespace module\polls;

use Yii;
use module\poll\models\Poll;
use module\poll\models\PollAnswerUser;

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

    /**
     * On build of a Space Navigation, check if this module is enabled.
     * When enabled add a menu item
     *
     * @param type $event
     */
    public static function onSpaceMenuInit($event)
    {
        $space = $event->sender->space;

        // Is Module enabled on this workspace?
        if ($space->isModuleEnabled('polls')) {
            $event->sender->addItem(array(
                'label' => Yii::t('PollsModule.base', 'Polls'),
                'group' => 'modules',
                'url' => $space->createUrl('/polls/poll/show'),
                'icon' => '<i class="fa fa-question-circle"></i>',
                'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'polls'),
            ));
        }
    }

    /**
     * On User delete, delete all poll answers by this user
     *
     * @param type $event
     */
    public static function onUserDelete($event)
    {
        foreach (PollAnswerUser::findAll(array('created_by' => $event->sender->id)) as $answer) {
            $answer->delete();
        }

        return true;
    }

}

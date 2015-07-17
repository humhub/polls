<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\polls;

use Yii;
use humhub\modules\polls\models\PollAnswer;
use humhub\modules\polls\models\PollAnswerUser;

/**
 * Description of Events
 *
 * @author luke
 */
class Events extends \yii\base\Object
{

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

    /**
     * Callback to validate module database records.
     *
     * @param Event $event
     */
    public static function onIntegrityCheck($event)
    {
        $integrityController = $event->sender;
        $integrityController->showTestHeadline("Polls Module - Answers (" . PollAnswer::find()->count() . " entries)");
        foreach (PollAnswer::find()->joinWith('poll')->all() as $answer) {
            if ($answer->poll === null) {
                if ($integrityController->showFix("Deleting poll answer id " . $answer->id . " without existing poll!")) {
                    $answer->delete();
                }
            }
        }

        $integrityController->showTestHeadline("Polls Module - Answers User (" . PollAnswerUser::find()->count() . " entries)");
        foreach (PollAnswerUser::find()->joinWith(['poll', 'user'])->all() as $answerUser) {
            if ($answerUser->poll === null) {
                if ($integrityController->showFix("Deleting poll answer id " . $answerUser->id . " without existing poll!")) {
                    $answerUser->delete();
                }
            }
            if ($answerUser->user === null) {
                if ($integrityController->showFix("Deleting poll answer id " . $answerUser->id . " without existing user!")) {
                    $answerUser->delete();
                }
            }
        }
    }

}

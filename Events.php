<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\polls;

use humhub\modules\space\models\Space;
use humhub\modules\user\models\User;
use humhub\modules\polls\models\Poll;
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
    public static function onWallEntryControlsInit($event)
    {
        $object = $event->sender->object;
        
        if(!$object instanceof Poll) {
            return;
        }
        
        if($object->content->canWrite()) {
            $event->sender->addWidget(\humhub\modules\polls\widgets\CloseButton::className(), [
                'poll' => $object
            ]);
        }
        
        if($object->isResetAllowed()) {
            $event->sender->addWidget(\humhub\modules\polls\widgets\ResetButton::className(), [
                'poll' => $object
            ]);
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

    /**
     * Create installer sample data
     * 
     * @param \yii\base\Event $event
     */
    public static function onSampleDataInstall($event)
    {

        $space = Space::find()->where(['id' => 1])->one();

        // activate module at space
        if (!$space->isModuleEnabled("polls")) {
            $space->enableModule("polls");
        }

        // Switch Identity
        $user = User::find()->where(['id' => 1])->one();
        Yii::$app->user->switchIdentity($user);

        $poll = new Poll();
        $poll->scenario = Poll::SCENARIO_CREATE;
        $poll->question = Yii::t('PollsModule.events', "Right now, we are in the planning stages for our next meetup and we would like to know from you, where you would like to go?");
        $poll->newAnswers = [
            Yii::t('PollsModule.events', "To Daniel"),
            Yii::t('PollsModule.events', "Club A Steakhouse"),
            Yii::t('PollsModule.events', "Pisillo Italian Panini")
        ];
        $poll->content->container = $space;
        $poll->allow_multiple = Yii::$app->request->post('allowMultiple', 0);
        $poll->save();

        // load users
        $user2 = User::find()->where(['id' => 2])->one();
        $user3 = User::find()->where(['id' => 3])->one();

        // Switch Identity
        Yii::$app->user->switchIdentity($user2);

        // vote
        $poll->vote([2]);

        $comment = new \humhub\modules\comment\models\Comment();
        $comment->message = Yii::t('PollsModule.events', "Why don't we go to Bemelmans Bar?");
        $comment->object_model = $poll->className();
        $comment->object_id = $poll->getPrimaryKey();
        $comment->save();

        // Switch Identity
        Yii::$app->user->switchIdentity($user3);

        // vote
        $poll->vote([3]);

        $comment = new \humhub\modules\comment\models\Comment();
        $comment->message = Yii::t('PollsModule.events', "Again? ;Weary;");
        $comment->object_model = $poll->className();
        $comment->object_id = $poll->getPrimaryKey();
        $comment->save();

        // Switch Identity
        Yii::$app->user->switchIdentity($user);


    }

}

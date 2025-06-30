<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\polls;

use humhub\modules\polls\extensions\custom_pages\elements\PollElement;
use humhub\modules\polls\extensions\custom_pages\elements\PollsElement;
use humhub\modules\polls\models\Poll;
use humhub\modules\polls\models\PollAnswer;
use humhub\modules\polls\models\PollAnswerUser;
use humhub\modules\polls\widgets\CloseButton;
use humhub\modules\polls\widgets\ResetButton;
use humhub\modules\space\models\Space;
use humhub\modules\user\models\User;
use Yii;
use yii\base\Event;

/**
 * Description of Events
 *
 * @author luke
 */
class Events
{
    public static function onWallEntryControlsInit($event)
    {
        $object = $event->sender->object;

        if (!$object instanceof Poll) {
            return;
        }

        if ($object->content->canEdit()) {
            $event->sender->addWidget(CloseButton::class, [
                'poll' => $object,
            ]);
        }

        if ($object->isResetAllowed()) {
            $event->sender->addWidget(ResetButton::class, [
                'poll' => $object,
            ]);
        }
    }

    /**
     * On User delete, delete all poll answers by this user
     *
     * @param Event $event
     * @return bool
     */
    public static function onUserDelete($event)
    {
        foreach (PollAnswerUser::findAll(['created_by' => $event->sender->id]) as $answer) {
            $answer->delete();
        }

        return true;
    }

    /**
     * Callback to validate module database records.
     *
     * @param Event $event
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public static function onIntegrityCheck($event)
    {
        $integrityController = $event->sender;
        $integrityController->showTestHeadline("Polls Module - Answers (" . PollAnswer::find()->count() . " entries)");
        foreach (PollAnswer::find()->joinWith('poll')->each() as $answer) {
            if ($answer->poll === null) {
                if ($integrityController->showFix("Deleting poll answer id " . $answer->id . " without existing poll!")) {
                    $answer->delete();
                }
            }
        }

        $integrityController->showTestHeadline("Polls Module - Answers User (" . PollAnswerUser::find()->count() . " entries)");
        foreach (PollAnswerUser::find()->joinWith(['poll', 'user'])->each() as $answerUser) {
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
     * @throws \yii\base\Exception
     */
    public static function onSampleDataInstall($event)
    {
        /* @var Space $space */
        $space = Space::find()->orderBy(['id' => SORT_ASC])->one();

        // activate module at space
        $space->moduleManager->enable('polls');

        // Switch Identity
        $user = User::find()->orderBy(['id' => SORT_ASC])->one();
        Yii::$app->user->switchIdentity($user);

        $poll = new Poll();
        $poll->scenario = Poll::SCENARIO_CREATE;

        $poll->question = Yii::t('PollsModule.base', 'Location of the next meeting');
        $poll->description = Yii::t('PollsModule.base', "Right now, we are in the planning stages for our next meetup and we would like to know from you, where you would like to go?");
        $poll->newAnswers = [
            Yii::t('PollsModule.base', "To Daniel"),
            Yii::t('PollsModule.base', "Club A Steakhouse"),
            Yii::t('PollsModule.base', "Pisillo Italian Panini"),
        ];
        $poll->content->container = $space;
        $poll->allow_multiple = Yii::$app->request->post('allowMultiple', 0);
        if (!$poll->save()) {
            return;
        }

        // load users
        $user2 = User::find()->where(['username' => 'david1986'])->one();
        $user3 = User::find()->where(['username' => 'sara1989'])->one();

        // Switch Identity
        Yii::$app->user->switchIdentity($user2);

        // vote
        $poll->vote([2]);

        $comment = new \humhub\modules\comment\models\Comment();
        $comment->message = Yii::t('PollsModule.base', "Why don't we go to Bemelmans Bar?");
        $comment->object_model = $poll->className();
        $comment->object_id = $poll->getPrimaryKey();
        $comment->save();

        // Switch Identity
        Yii::$app->user->switchIdentity($user3);

        // vote
        $poll->vote([3]);

        $comment = new \humhub\modules\comment\models\Comment();
        $comment->message = Yii::t('PollsModule.base', "Again? ;Weary;");
        $comment->object_model = $poll->className();
        $comment->object_id = $poll->getPrimaryKey();
        $comment->save();

        // Switch Identity
        Yii::$app->user->switchIdentity($user);


    }

    public static function onRestApiAddRules()
    {
        /* @var \humhub\modules\rest\Module $restModule */
        $restModule = Yii::$app->getModule('rest');
        $restModule->addRules([

            // List polls
            ['pattern' => 'polls', 'route' => 'polls/rest/polls/find', 'verb' => ['GET', 'HEAD']],
            ['pattern' => 'polls/container/<containerId:\d+>', 'route' => 'polls/rest/polls/find-by-container', 'verb' => ['GET', 'HEAD']],
            ['pattern' => 'polls/container/<containerId:\d+>', 'route' => 'polls/rest/polls/delete-by-container', 'verb' => 'DELETE'],

            // Poll CRUD
            ['pattern' => 'polls/container/<containerId:\d+>', 'route' => 'polls/rest/polls/create', 'verb' => 'POST'],
            ['pattern' => 'polls/poll/<id:\d+>', 'route' => 'polls/rest/polls/view', 'verb' => ['GET', 'HEAD']],
            ['pattern' => 'polls/poll/<id:\d+>', 'route' => 'polls/rest/polls/update', 'verb' => 'PUT'],
            ['pattern' => 'polls/poll/<id:\d+>', 'route' => 'polls/rest/polls/delete', 'verb' => 'DELETE'],

            // Close/Open Poll
            ['pattern' => 'polls/poll/<id:\d+>/close', 'route' => 'polls/rest/polls/close', 'verb' => 'PATCH'],
            ['pattern' => 'polls/poll/<id:\d+>/open', 'route' => 'polls/rest/polls/open', 'verb' => 'PATCH'],

            // Vote
            ['pattern' => 'polls/vote/<id:\d+>', 'route' => 'polls/rest/polls/vote', 'verb' => 'PUT'],
            ['pattern' => 'polls/vote/<id:\d+>', 'route' => 'polls/rest/polls/reset-vote', 'verb' => 'DELETE'],
            ['pattern' => 'polls/vote/<id:\d+>', 'route' => 'polls/rest/polls/votes', 'verb' => 'GET'],

        ], 'polls');
    }

    public static function onCustomPagesTemplateElementTypeServiceInit($event)
    {
        /* @var \humhub\modules\custom_pages\modules\template\services\ElementTypeService $elementTypeService */
        $elementTypeService = $event->sender;
        $elementTypeService->addType(PollElement::class);
        $elementTypeService->addType(PollsElement::class);
    }

}

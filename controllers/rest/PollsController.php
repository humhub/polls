<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2021 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\polls\controllers\rest;

use humhub\modules\content\components\ContentActiveRecord;
use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\content\models\ContentContainer;
use humhub\modules\polls\helpers\RestDefinitions;
use humhub\modules\polls\models\Poll;
use humhub\modules\polls\models\PollAnswerUser;
use humhub\modules\polls\permissions\CreatePoll;
use humhub\modules\rest\components\BaseContentController;
use Yii;

class PollsController extends BaseContentController
{
    public static $moduleId = 'polls';

    /**
     * @inheritdoc
     */
    public function getContentActiveRecordClass()
    {
        return Poll::class;
    }

    /**
     * @inheritdoc
     */
    public function returnContentDefinition(ContentActiveRecord $contentRecord)
    {
        /* @var Poll $contentRecord */
        return RestDefinitions::getPoll($contentRecord);
    }

    private function savePoll(Poll $poll): bool
    {
        return $poll->load($data = Yii::$app->request->post()) &&
            $poll->save() &&
            (!method_exists($this, 'updateContent') || $this->updateContent($poll, $data));
    }

    /**
     * @inheritdoc
     */
    public function actionCreate($containerId)
    {
        $containerRecord = ContentContainer::findOne(['id' => $containerId]);
        if ($containerRecord === null) {
            return $this->returnError(404, 'Content container not found!');
        }
        /* @var ContentContainerActiveRecord $container */
        $container = $containerRecord->getPolymorphicRelation();

        if (! in_array(get_class($container), Yii::$app->getModule('polls')->getContentContainerTypes()) ||
            ! $container->permissionManager->can([CreatePoll::class])) {
            return $this->returnError(403, 'You are not allowed to create a poll!');
        }

        $poll = new Poll($container, ['scenario' => Poll::SCENARIO_CREATE]);

        if ($this->savePoll($poll)) {
            return $this->returnContentDefinition(Poll::findOne(['id' => $poll->id]));
        }

        if ($poll->hasErrors()) {
            return $this->returnError(422, 'Validation failed', [
                'poll' => $poll->getErrors(),
            ]);
        } else {
            Yii::error('Could not create validated poll.', 'api');
            return $this->returnError(500, 'Internal error while save a poll!');
        }
    }

    /**
     * @inheritdoc
     */
    public function actionUpdate($id)
    {
        $poll = Poll::findOne(['id' => $id]);
        if (!$poll) {
            return $this->returnError(404, 'Poll is not found!');
        }

        if (!$poll->content->canEdit()) {
            return $this->returnError(403, 'You are not allowed to update this poll!');
        }

        $poll->scenario = Poll::SCENARIO_EDIT;
        if ($this->savePoll($poll)) {
            return $this->returnContentDefinition(Poll::findOne(['id' => $poll->id]));
        }

        if ($poll->hasErrors()) {
            return $this->returnError(422, 'Validation failed', [
                'poll' => $poll->getErrors(),
            ]);
        } else {
            Yii::error('Could not update validated poll.', 'api');
            return $this->returnError(500, 'Internal error while save the poll!');
        }
    }

    /**
     * Close a Poll by ID
     *
     * @param int $id Poll ID
     * @return array
     */
    public function actionClose(int $id): array
    {
        $poll = Poll::findOne(['id' => $id]);
        if (!$poll) {
            return $this->returnError(404, 'Poll is not found!');
        }

        if (!$poll->content->canEdit()) {
            return $this->returnError(403, 'You are not allowed to close this poll!');
        }

        if ($poll->closed) {
            return $this->returnSuccess('Poll is already closed.');
        }

        $poll->scenario = Poll::SCENARIO_CLOSE;
        $poll->closed = 1;

        if ($poll->save()) {
            return $this->returnSuccess('Poll has been successfully closed.');
        }

        return $this->returnError(500, 'Internal error while close this poll!');
    }

    /**
     * Open a Poll by ID
     *
     * @param int $id Poll ID
     * @return array
     */
    public function actionOpen(int $id): array
    {
        $poll = Poll::findOne(['id' => $id]);
        if (!$poll) {
            return $this->returnError(404, 'Poll is not found!');
        }

        if (!$poll->content->canEdit()) {
            return $this->returnError(403, 'You are not allowed to open this poll!');
        }

        if (!$poll->closed) {
            return $this->returnSuccess('Poll is already opened.');
        }

        $poll->scenario = Poll::SCENARIO_CLOSE;
        $poll->closed = 0;

        if ($poll->save()) {
            return $this->returnSuccess('Poll has been successfully reopened.');
        }

        return $this->returnError(500, 'Internal error while open this poll!');
    }

    /**
     * Vote on the Poll
     *
     * @param int $id Poll ID
     * @return array
     */
    public function actionVote(int $id): array
    {
        $poll = Poll::findOne(['id' => $id]);
        if (!$poll) {
            return $this->returnError(404, 'Poll is not found!');
        }

        $answers = Yii::$app->request->post('answers');

        $votes = array();
        if (is_array($answers)) {
            foreach ($answers as $answerId) {
                $votes[] = (int)$answerId;
            }
        } else {
            $votes[] = $answers;
        }

        if (!$poll->allow_multiple && count($votes) > 1) {
            return $this->returnError(403, 'Voting for multiple answers is disabled!');
        }

        if ($poll->hasUserVoted()) {
            return $this->returnSuccess('You are already voted on this poll.');
        }

        if ($poll->vote($votes)) {
            return $this->returnSuccess('You have voted.');
        }

        return $this->returnError(500, 'Internal error while vote this poll!');
    }

    /**
     * Reset a vote on the Poll
     *
     * @param int $id Poll ID
     * @return array
     */
    public function actionResetVote(int $id): array
    {
        $poll = Poll::findOne(['id' => $id]);
        if (!$poll) {
            return $this->returnError(404, 'Poll is not found!');
        }

        if (!$poll->hasUserVoted()) {
            return $this->returnSuccess('You are not voted yet on this poll.');
        }

        $poll->resetAnswer();

        return $this->returnSuccess('You have reset your vote.');
    }

    /**
     * Get answers voted on the Poll by current User
     *
     * @param int $id Poll ID
     * @return array
     */
    public function actionVotes(int $id): array
    {
        $poll = Poll::findOne(['id' => $id]);
        if (!$poll) {
            return $this->returnError(404, 'Poll is not found!');
        }

        return PollAnswerUser::find()
            ->select('poll_answer_id')
            ->where(['created_by' => Yii::$app->user->id])
            ->andWhere(['poll_id' => $id])
            ->column();
    }
}
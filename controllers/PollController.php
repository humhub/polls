<?php

namespace humhub\modules\polls\controllers;

use humhub\modules\polls\widgets\WallCreateForm;
use Yii;
use yii\web\HttpException;
use yii\helpers\Html;
use humhub\modules\user\models\User;
use humhub\modules\user\widgets\UserListBox;
use humhub\modules\content\components\ContentContainerController;
use humhub\modules\polls\models\Poll;
use humhub\modules\polls\models\PollAnswer;

/**
 * PollController handles all poll related actions.
 *
 * @package humhub.modules.polls.controllers
 * @since 0.5
 * @author Luke
 */
class PollController extends ContentContainerController
{

    public function actions()
    {
        return [
            'stream' => [
                'class' => \humhub\modules\polls\components\StreamAction::className(),
                'includes' => Poll::class,
                'mode' => \humhub\modules\polls\components\StreamAction::MODE_NORMAL,
                'contentContainer' => $this->contentContainer
            ],
        ];
    }

    /**
     * Shows the questions tab
     */
    public function actionShow()
    {
        return $this->render('show', [
                    'contentContainer' => $this->contentContainer
        ]);
    }

    public function actionCreate()
    {
        if (!$this->contentContainer->permissionManager->can(new \humhub\modules\polls\permissions\CreatePoll())) {
            throw new HttpException(400, 'Access denied!');
        }
        
        $poll = new Poll(['scenario' => Poll::SCENARIO_CREATE]);
        $poll->load(Yii::$app->request->post());
        $poll->setNewAnswers(Yii::$app->request->post('newAnswers'));
        return WallCreateForm::create($poll, $this->contentContainer);
    }

    /**
     * Reloads a single entry
     */
    public function actionReload()
    {
        Yii::$app->response->format = 'json';
        $id = Yii::$app->request->get('id');
        $model = Poll::findOne(['id' => $id]);

        if (!$model->content->canView()) {
            throw new HttpException(403, Yii::t('PollsModule.controllers_PollController', 'Access denied!'));
        }

        return \humhub\modules\stream\actions\Stream::getContentResultEntry($model->content);
    }

    public function actionEdit()
    {
        $request = Yii::$app->request;
        $id = $request->get('id');

        $edited = false;
        $model = Poll::findOne(['id' => $id]);
        $wasAnonymous = $model->anonymous;
        $model->scenario = Poll::SCENARIO_EDIT;

        if (!$model->content->canEdit() || $model->closed) {
            throw new HttpException(403, Yii::t('PollsModule.controllers_PollController', 'Access denied!'));
        }

        //Set newAnswers, and editAnswers which will be saved by afterSave of the poll class
        $model->setNewAnswers($request->post('newAnswers'));
        $model->setEditAnswers($request->post('answers'));

        if ($model->load($request->post())) {
            if ($wasAnonymous && !$model->anonymous) {
                //This is only possible per post hacks... just to get sure...
                throw new HttpException(403, Yii::t('PollsModule.controllers_PollController', 'Access denied!'));
            }
            Yii::$app->response->format = 'json';
            $result = [];
            if ($model->validate() && $model->save()) {
                // Reload record to get populated updated_at field
                $model = Poll::findOne(['id' => $id]);
                return \humhub\modules\stream\actions\Stream::getContentResultEntry($model->content);
            } else {
                $result['errors'] = $model->getErrors();
            }
            return $result;
        }

        return $this->renderAjax('edit', ['poll' => $model, 'edited' => $edited]);
    }

    public function actionOpen()
    {
        return $this->asJson($this->setClosed(Yii::$app->request->get('id'), false)); 
    }

    public function actionClose()
    {
        return  $this->asJson($this->setClosed(Yii::$app->request->get('id'), true)); 
    }

    public function setClosed($id, $closed)
    {
        $this->forcePostRequest();

        $model = Poll::findOne(['id' => $id]);
        $model->scenario = Poll::SCENARIO_CLOSE;

        if (!$model->content->canEdit()) {
            throw new HttpException(403, Yii::t('PollsModule.controllers_PollController', 'Access denied!'));
        }

        $model->closed = $closed;
        $model->save();
        // Refresh updated_at
        $model->content->refresh();
        
        return \humhub\modules\stream\actions\Stream::getContentResultEntry($model->content);
    }

    /**
     * Answers a polls
     */
    public function actionAnswer()
    {
        Yii::$app->response->format = 'json';
        $poll = $this->getPollByParameter();
        $answers = Yii::$app->request->post('answers');

        // Build array of answer ids
        $votes = array();
        if (is_array($answers)) {
            foreach ($answers as $answer_id => $flag) {
                $votes[] = (int) $answer_id;
            }
        } else {
            $votes[] = $answers;
        }

        if (count($votes) > 1 && !$poll->allow_multiple) {
            throw new HttpException(401, Yii::t('PollsModule.controllers_PollController', 'Voting for multiple answers is disabled!'));
        }

        $poll->vote($votes);
        return \humhub\modules\stream\actions\Stream::getContentResultEntry($poll->content);
    }

    /**
     * Resets users question answers
     */
    public function actionAnswerReset()
    {
        Yii::$app->response->format = 'json';
        $poll = $this->getPollByParameter();
        $poll->resetAnswer();
        return \humhub\modules\stream\actions\Stream::getContentResultEntry($poll->content);
    }

    /**
     * Returns a user list including the pagination which contains all results
     * for an answer
     */
    public function actionUserListResults()
    {
        $poll = $this->getPollByParameter();

        if ($poll->anonymous) {
            throw new HttpException(401, Yii::t('PollsModule.controllers_PollController', 'Anonymous poll!'));
        }

        $answerId = (int) Yii::$app->request->get('answerId', '');
        $answer = PollAnswer::findOne(['id' => $answerId]);
        if ($answer == null || $poll->id != $answer->poll_id) {
            throw new HttpException(401, Yii::t('PollsModule.controllers_PollController', 'Invalid answer!'));
        }

        $query = User::find();
        $query->leftJoin('poll_answer_user', 'poll_answer_user.created_by=user.id');
        $query->andWhere(['poll_answer_user.poll_id' => $answer->poll_id]);
        $query->andWhere(['poll_answer_user.poll_answer_id' => $answerId]);
        $query->orderBy('poll_answer_user.created_at DESC');

        $title = Yii::t('PollsModule.controllers_PollController', "Users voted for: <strong>{answer}</strong>", array('{answer}' => Html::encode($answer->answer)));

        return $this->renderAjaxContent(UserListBox::widget(['query' => $query, 'title' => $title]));
    }

    /**
     * Prints the given poll wall output include the affected wall entry id
     *
     * @param Poll $poll
     */
    private function renderPollOut($question)
    {
        Yii::$app->response->format = 'json';

        $json = array();
        $json['output'] = $this->renderAjaxContent($question->getWallOut());

        return $json;
    }

    /**
     * Returns a given poll by given request parameter.
     *
     * This method also validates access rights of the requested poll object.
     */
    private function getPollByParameter()
    {

        $pollId = (int) Yii::$app->request->get('pollId');
        $poll = Poll::find()->contentContainer($this->contentContainer)->readable()->where(['poll.id' => $pollId])->one();

        if ($poll == null) {
            throw new HttpException(401, Yii::t('PollsModule.controllers_PollController', 'Could not load poll!'));
        }

        if (!$poll->content->canView()) {
            throw new HttpException(401, Yii::t('PollsModule.controllers_PollController', 'You have insufficient permissions to perform that operation!'));
        }

        return $poll;
    }

}

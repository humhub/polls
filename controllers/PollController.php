<?php

namespace module\polls\controllers;

use Yii;
use yii\web\HttpException;
use yii\helpers\Html;
use humhub\modules\user\models\User;
use humhub\modules\user\widgets\UserListBox;
use humhub\modules\content\components\ContentContainerController;
use module\polls\models\Poll;
use module\polls\models\PollAnswer;

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
        return array(
            'stream' => array(
                'class' => \module\polls\components\StreamAction::className(),
                'mode' => \module\polls\components\StreamAction::MODE_NORMAL,
                'contentContainer' => $this->contentContainer
            ),
        );
    }

    /**
     * Shows the questions tab
     */
    public function actionShow()
    {
        return $this->render('show', array(
                    'contentContainer' => $this->contentContainer
        ));
    }

    /**
     * Posts a new question  throu the question form
     *
     * @return type
     */
    public function actionCreate()
    {
        $poll = new Poll();
        $poll->question = Yii::$app->request->post('question');
        $poll->answersText = Yii::$app->request->post('answersText');
        $poll->allow_multiple = Yii::$app->request->post('allowMultiple', 0);

        return \module\polls\widgets\WallCreateForm::save($poll);
    }

    /**
     * Answers a polls
     */
    public function actionAnswer()
    {
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
        return $this->renderPollOut($poll);
    }

    /**
     * Resets users question answers
     */
    public function actionAnswerReset()
    {
        $poll = $this->getPollByParameter();
        $poll->resetAnswer();
        return $this->renderPollOut($poll);
    }

    /**
     * Returns a user list including the pagination which contains all results
     * for an answer
     */
    public function actionUserListResults()
    {
        $poll = $this->getPollByParameter();

        $answerId = (int) Yii::$app->request->get('answerId', '');
        $answer = PollAnswer::findOne(['id' => $answerId]);
        if ($answer == null || $poll->id != $answer->poll_id) {
            throw new HttpException(401, Yii::t('PollsModule.controllers_PollController', 'Invalid answer!'));
        }

        $query = User::find();
        $query->leftJoin('poll_answer_user', 'poll_answer_user.created_by=user.id');
        $query->andWhere('poll_answer_user.poll_id IS NOT NULL');
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
        $json['wallEntryId'] = $question->content->getFirstWallEntryId();

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

        if (!$poll->content->canRead()) {
            throw new HttpException(401, Yii::t('PollsModule.controllers_PollController', 'You have insufficient permissions to perform that operation!'));
        }

        return $poll;
    }

}

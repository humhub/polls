<?php

namespace polls\api;

use Codeception\Util\HttpCode;
use humhub\modules\content\models\Content;
use humhub\modules\polls\models\Poll;
use humhub\modules\user\models\User;
use polls\ApiTester;
use Yii;
use tests\codeception\_support\HumHubApiTestCest;

class VoteCest extends HumHubApiTestCest
{
    public function testVotePoll(ApiTester $I)
    {
        if (!$this->isRestModuleEnabled()) {
            return;
        }

        $I->wantTo('vote on a poll');
        $I->amAdmin();
        $I->createSamplePoll();

        $I->sendPut('polls/vote/1', ['answers' => 2]);
        $I->seeSuccessMessage('You have voted.');

        $I->sendPut('polls/vote/1', ['answers' => 2]);
        $I->seeSuccessMessage('You are already voted on this poll.');
    }

    public function testResetPoll(ApiTester $I)
    {
        if (!$this->isRestModuleEnabled()) {
            return;
        }

        $I->wantTo('reset vote on a poll');
        $I->amAdmin();
        $I->createSamplePoll();

        $I->sendPut('polls/vote/1', ['answers' => 2]);
        $I->seeSuccessMessage('You have voted.');

        $I->sendDelete('polls/vote/1');
        $I->seeSuccessMessage('You have reset your vote.');
    }

    public function testGetVotes(ApiTester $I)
    {
        if (!$this->isRestModuleEnabled()) {
            return;
        }

        $I->wantTo('get votes on a poll for current user');
        $I->amAdmin();
        $I->createPoll(
            'Sample poll question?',
            'Sample poll description',
            ['Answer 1', 'Answer 2', 'Answer 3'],
            ['allow_multiple' => 1],
        );

        $I->sendPut('polls/vote/1', ['answers' => [2,3]]);
        $I->seeSuccessMessage('You have voted.');

        $I->sendGet('polls/vote/1');
        $I->seeCodeResponseContainsJson(HttpCode::OK, ['2','3']);
    }

    public function testVoteClosedPoll(ApiTester $I)
    {
        if (!$this->isRestModuleEnabled()) {
            return;
        }

        $I->wantTo('not vote or reset a vote on a closed poll');
        $I->amAdmin();
        $I->createSamplePoll();

        $I->sendPut('polls/vote/1', ['answers' => 2]);
        $I->seeSuccessMessage('You have voted.');

        $I->sendPatch('polls/poll/1/close');
        $I->seeSuccessMessage('Poll has been successfully closed.');

        $I->sendDelete('polls/vote/1');
        $I->seeCodeResponseContainsJson(HttpCode::FORBIDDEN, ['message' => 'Poll is closed!']);

        $I->sendPatch('polls/poll/1/open');
        $I->sendDelete('polls/vote/1');
        $I->seeSuccessMessage('You have reset your vote.');

        $I->sendPatch('polls/poll/1/close');
        $I->sendPut('polls/vote/1', ['answers' => 2]);
        $I->seeCodeResponseContainsJson(HttpCode::FORBIDDEN, ['message' => 'Poll is closed!']);
    }

    public function testVoteInaccessiblePoll(ApiTester $I)
    {
        if (!$this->isRestModuleEnabled()) {
            return;
        }

        $I->wantTo('not vote on a poll which the user cannot view');

        // Private poll in the profile of the Admin
        Yii::$app->user->switchIdentity(User::findOne(['username' => 'admin']));
        $poll = new Poll(User::findOne(['username' => 'admin']), ['scenario' => Poll::SCENARIO_CREATE]);
        $poll->content->visibility = Content::VISIBILITY_PRIVATE;
        $poll->question = 'Private poll?';
        $poll->setNewAnswers(['Yes', 'No']);
        \PHPUnit\Framework\Assert::assertTrue($poll->save());
        Yii::$app->user->switchIdentity(null);

        $I->amUser1();
        $I->sendGet('polls/poll/' . $poll->id);
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);

        $I->sendPut('polls/vote/' . $poll->id, ['answers' => $poll->answers[0]->id]);
        $I->seeCodeResponseContainsJson(HttpCode::FORBIDDEN, ['message' => 'You cannot view this content!']);

        $I->sendGet('polls/vote/' . $poll->id);
        $I->seeCodeResponseContainsJson(HttpCode::FORBIDDEN, ['message' => 'You cannot view this content!']);

        $I->sendDelete('polls/vote/' . $poll->id);
        $I->seeCodeResponseContainsJson(HttpCode::FORBIDDEN, ['message' => 'You cannot view this content!']);
    }

}

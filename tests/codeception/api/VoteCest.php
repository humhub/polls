<?php

namespace polls\api;

use Codeception\Util\HttpCode;
use polls\ApiTester;
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

}

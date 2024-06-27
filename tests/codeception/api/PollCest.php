<?php

namespace polls\api;

use Codeception\Util\HttpCode;
use polls\ApiTester;
use tests\codeception\_support\HumHubApiTestCest;

class PollCest extends HumHubApiTestCest
{
    public function testCreatePoll(ApiTester $I)
    {
        if (!$this->isRestModuleEnabled()) {
            return;
        }

        $I->wantTo('create a poll');
        $I->amAdmin();
        $I->createSamplePoll();
        $I->seeLastCreatedPollDefinition();

        $I->amGoingTo('create a poll with error');
        $I->sendPost('polls/container/1', ['Poll' => ['allow_multiple' => 1]]);
        $I->seeCodeResponseContainsJson(HttpCode::UNPROCESSABLE_ENTITY, ['message' => 'Validation failed']);
    }

    public function testGetPollById(ApiTester $I)
    {
        if (!$this->isRestModuleEnabled()) {
            return;
        }

        $I->wantTo('see poll by id');
        $I->amAdmin();
        $I->createSamplePoll();
        $I->sendGet('polls/poll/1');
        $I->seePollDefinitionById(1);
    }

    public function testUpdatePollById(ApiTester $I)
    {
        if (!$this->isRestModuleEnabled()) {
            return;
        }

        $I->wantTo('update poll by id');
        $I->amAdmin();

        $I->sendPut('polls/poll/1');
        $I->seeNotFoundMessage('Poll is not found!');

        $I->createSamplePoll();
        $I->sendPut('polls/poll/1', [
            'Poll' => [
                'question' => 'Updated question',
                'description' => 'Updated description',
                'allow_multiple' => 1,
                'is_random' => 0,
                'anonymous' => 0,
                'show_result_after_close' => 0,
            ],
            'answers' => [1 => 'Updated Answer 1', 2 => 'Updated Answer 2'],
            'newAnswers' => ['New added Answer 3'],
        ]);
        $I->seePollDefinitionById(1);
    }

    public function testDeletePollById(ApiTester $I)
    {
        if (!$this->isRestModuleEnabled()) {
            return;
        }

        $I->wantTo('delete poll by id');
        $I->amAdmin();

        $I->sendDelete('polls/poll/1');
        $I->seeNotFoundMessage('Content record not found!');

        $I->createSamplePoll();
        $I->sendDelete('polls/poll/1');
        $I->seeSuccessMessage('Successfully deleted!');
    }

    public function testCloseOpenPoll(ApiTester $I)
    {
        if (!$this->isRestModuleEnabled()) {
            return;
        }

        $I->wantTo('close/open poll by id');
        $I->amAdmin();

        $I->createSamplePoll();
        $I->sendPatch('polls/poll/1/close');
        $I->seeSuccessMessage('Poll has been successfully closed.');

        $I->sendPatch('polls/poll/1/close');
        $I->seeSuccessMessage('Poll is already closed.');

        $I->amGoingTo('open poll by id');
        $I->sendPatch('polls/poll/1/open');
        $I->seeSuccessMessage('Poll has been successfully reopened.');

        $I->sendPatch('polls/poll/1/open');
        $I->seeSuccessMessage('Poll is already opened.');
    }
}

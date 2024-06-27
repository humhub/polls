<?php

namespace polls\api;

use polls\ApiTester;
use tests\codeception\_support\HumHubApiTestCest;

class ListCest extends HumHubApiTestCest
{
    public function testEmptyList(ApiTester $I)
    {
        if (!$this->isRestModuleEnabled()) {
            return;
        }

        $I->wantTo('see empty polls');
        $I->amAdmin();
        $I->seePaginationPollsResponse('polls', []);
    }

    public function testFilledList(ApiTester $I)
    {
        if (!$this->isRestModuleEnabled()) {
            return;
        }

        $I->wantTo('see sample created polls');
        $I->amAdmin();
        $I->createPoll('First question?', 'Sample description for the first poll.', ['Answer 1', 'Answer 2']);
        $I->createPoll('Second question?', 'Sample description for the second poll.', ['Answer 1', 'Answer 2']);
        $I->createPoll('Third question?', 'Sample description for the third poll.', ['Answer 1', 'Answer 2', 'Answer 3']);
        $I->createPoll('Fourth question?', 'Sample description for the fourth poll.', ['Answer 1', 'Answer 2', 'Answer 3', 'Answer 4', 'Answer 5']);
        $I->seePaginationPollsResponse('polls', [1, 2, 3, 4]);
    }

    public function testListByContainer(ApiTester $I)
    {
        if (!$this->isRestModuleEnabled()) {
            return;
        }

        $I->wantTo('see polls by container');
        $I->amAdmin();
        $I->sendGet('polls/container/123');
        $I->seeNotFoundMessage('Content container not found!');

        $I->createPoll('Sample poll title 1', 'Sample poll content 1', ['Answer 1', 'Answer 2'], ['containerId' => 1]);
        $I->createPoll('Sample poll title 2', 'Sample poll content 2', ['Answer 1', 'Answer 2'], ['containerId' => 4]);
        $I->createPoll('Sample poll title 3', 'Sample poll content 3', ['Answer 1', 'Answer 2'], ['containerId' => 6]);
        $I->createPoll('Sample poll title 4', 'Sample poll content 4', ['Answer 1', 'Answer 2'], ['containerId' => 4]);
        $I->createPoll('Sample poll title 5', 'Sample poll content 5', ['Answer 1', 'Answer 2'], ['containerId' => 7]);
        $I->createPoll('Sample poll title 6', 'Sample poll content 6', ['Answer 1', 'Answer 2'], ['containerId' => 4]);

        $I->seePaginationPollsResponse('polls/container/1', [1]);
        $I->seePaginationPollsResponse('polls/container/4', [2, 4, 6]);
        $I->seePaginationPollsResponse('polls/container/6', [3]);
        $I->seePaginationPollsResponse('polls/container/7', [5]);
    }

    public function testDeleteByContainer(ApiTester $I)
    {
        if (!$this->isRestModuleEnabled()) {
            return;
        }

        $I->wantTo('delete polls by container');
        $I->amAdmin();

        $I->createPoll('Sample poll title 1', 'Sample poll content 1', ['Answer 1', 'Answer 2'], ['containerId' => 4]);
        $I->createPoll('Sample poll title 2', 'Sample poll content 2', ['Answer 1', 'Answer 2'], ['containerId' => 4]);
        $I->createPoll('Sample poll title 3', 'Sample poll content 3', ['Answer 1', 'Answer 2'], ['containerId' => 4]);

        $I->seePaginationPollsResponse('polls/container/4', [1, 2, 3]);
        $I->sendDelete('polls/container/4');
        $I->seeSuccessMessage('3 records successfully deleted!');
        $I->seePaginationPollsResponse('polls/container/4', []);
    }

}

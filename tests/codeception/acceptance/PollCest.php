<?php

namespace polls\acceptance;

use Codeception\Util\Locator;
use polls\AcceptanceTester;

class PollCest
{
    public function testCreatePoll(AcceptanceTester $I)
    {
        $I->amAdmin();
        $I->wantToTest('the creation of a poll entry');
        $I->amGoingTo('submit a the poll entry');

        $I->enableModule(1, 'polls');
        $I->amOnSpace1();

        $I->click('#contentFormBody');
        $I->waitForElementVisible('#contentFormMenu');
        $I->click('Poll');
        $I->waitForElement('#poll-question');
        $I->click('#poll-question');
        $I->expect('to see the poll form');
        $I->waitForElementVisible('.contentForm_options');

        $I->fillField('#poll-question', 'My Poll Question');
        $I->click(Locator::elementAt('.addPollAnswerButton', 1)); //Ass answers
        $I->fillField(Locator::elementAt('.poll_answer_new_input', 1), 'Answer 1');
        $I->fillField(Locator::elementAt('.poll_answer_new_input', 2), 'Answer 2');
        $I->fillField(Locator::elementAt('.poll_answer_new_input', 3), 'Answer 3');

        $I->scrollTo('#post_submit_button');
        $I->wait(1);
        $I->click('#post_submit_button');
        $I->waitForElementVisible('.wall-entry .wall_humhubmodulespollsmodelsPoll_1');
        $I->see('My Poll Question');
        $I->see('Answer 1');
        $I->see('Answer 2');
        $I->see('Answer 3');

        $I->click('Vote', '#wallStream');

        $I->seeWarning('At least one answer is required');

        $I->jsClick('#answer_1');
        $I->click('Vote', '#wallStream');

        $I->seeSuccess('Saved');
        $I->see('1 vote', '.wall-entry');
        $I->click('1 vote', '.wall-entry');
        $I->waitForText('Users voted for: Answer 1', 10, '#globalModal');
    }

}

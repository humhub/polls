<?php
namespace polls\acceptance;


use polls\AcceptanceTester;

class BreakingNewsCest
{
    
    public function testCreatePoll(AcceptanceTester $I)
    {
        $I->amAdmin();
        $I->wantToTest('the creation of a poll entry');
        $I->amGoingTo('submit a the poll entry');
        $I->amOnPage('index-test.php?r=polls/poll/show&sguid=5396d499-20d6-4233-800b-c6c86e5fa34a');
        
        $I->click('#contentForm_question_contenteditable');
        $I->expect('to see the poll form');
        $I->seeElement('.contentForm_options');
        
        $I->click('/html/body/div[3]/div[2]/div[2]/div[1]/div/form/div[2]/div[2]/div/div'); //Ass answers
        $I->fillField('#contentForm_question_contenteditable', 'My Poll Question');
        $I->fillField('/html/body/div[3]/div[2]/div[2]/div[1]/div/form/div[2]/div[1]/div/input', 'Answer 1');
        $I->fillField('/html/body/div[3]/div[2]/div[2]/div[1]/div/form/div[2]/div[2]/div/input', 'Answer 2');
        $I->fillField('/html/body/div[3]/div[2]/div[2]/div[1]/div/form/div[2]/div[3]/div/input', 'Answer 3');
       
        
        $I->click('#post_submit_button');
        $I->wait(6);
        $I->seeElement('#wall_content_humhubmodulespollsmodelsPoll_1');
        $I->see('My Poll Question');
        $I->see('Answer 1');
        $I->see('Answer 2');
        $I->see('Answer 3');
    }
   
}
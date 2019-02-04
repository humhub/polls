<?php

namespace tests\codeception\unit\modules\breakingnews;

use tests\codeception\_support\HumHubDbTestCase;

use humhub\modules\polls\models\Poll;


class PollAttributesTest extends HumHubDbTestCase
{

    public function setUp() {
        $this->poll = new Poll();
        $this->poll->scenario = Poll::SCENARIO_CREATE;
        $this->poll->question = "He?";
        $this->poll->setEditAnswers(['']);
        $this->poll->setNewAnswers(['Egyik valasz','Masik valasz']);
    }
    public function testTheDebateAttributeIsBoolean() {
        $this->poll->debate = false;
        $validated = $this->poll->validate();
        $this->assertTrue($validated);
        $this->assertEquals('boolean', gettype($this->poll->debate));
    }

    public function testTheDebateAttributeIsValidatedInCreation() {
        $this->poll->debate = "hello";
        $validated = $this->poll->validate();
        $this->assertFalse($validated);
    }
    public function testTheDebateAttributeIsValidatedInEdit() {
        $this->poll->scenario = Poll::SCENARIO_EDIT;
        $this->poll->debate = "hello";
        $validated = $this->poll->validate();
        $this->assertFalse($validated);
    }

    public function testTheStateAttributeIsInteger() {
        $this->poll->state = 2;
        $validated = $this->poll->validate();
        $this->assertTrue($validated);
        $this->assertEquals('integer', gettype($this->poll->state));
    }

    public function testThereIsAStateSTATE_PROPOSAL() {
        Poll::STATE_PROPOSAL;
    }
    public function testThereIsAStateSTATE_DEBATE() {
        Poll::STATE_DEBATE;
    }
    public function testThereIsAStateSTATE_VOTING() {
        Poll::STATE_VOTING;
    }
    public function testThereIsAStateSTATE_CLOSED() {
        Poll::STATE_CLOSED;
    }
    public function testTheStateAttributeIsValidatedInCreation() {
        $this->poll->state = "hello";
        $validated = $this->poll->validate();
        $this->assertFalse($validated);
    }
    public function testTheStateAttributeIsValidatedInEdit() {
        $this->poll->scenario = Poll::SCENARIO_EDIT;
        $this->poll->state = "hello";
        $validated = $this->poll->validate();
        $this->assertFalse($validated);
    }
}

/*
Poll:
poll has a debate boolean debate attribute
poll has an integer state attribute
there is a state STATE_PROPOSAL
there is a state STATE_DEBATE
there is a state STATE_VOTING
there is a state STATE_CLOSED
state cannot have values other than the above
if debate is true then allow_multiple is true
poll has a text polldata attribute
the polldata attribute is not settable through any interfaces other than setstate
poll has an integer promoter threshold attribute
setstate sets the state of the poll to the given state
setstate STATE_VOTING is not possible if the number of promoters is below promoter threshold
setstate STATE_VOTING stores the list of answers above the promoter threshold to the polldata attribute in json
setstate STATE_CLOSED closes the state
setstate STATE_CLOSED calculates the result and stores it in the polldata attribute
the result is in json format
if there was any vote then the result is calculated with Schwarz method
if there was no vote then that is stated
the result contains the list of winners in order
if there is a condorcet winner then it is marked as such
if there is no condorcet winner than that is stated
the winners have beat ratios for all the winners ranked below them
the result contains the number of votes

CondorcetVote:
condorcet vote has an integer id attribute
condorcet vote has an integer question_id attribute
question_id attribute references the poll
condorcet vote has an integer answer_id attribute
condorcet vote has a boolean deleted attribute
answer_id attribute references the answer
condorcet vote has an integer user_id attribute
user_id attribute references the user
condorcet vote has an integer created_at attribute
condorcet vote has an integer rank attribute
there is a constant RANK_QUESTION_PROMOTION which is -1
there is a constant RANK_ANSWER_PROMOTION which is -2

getVotes returns a list which contains a list for each user id with nonnegative votes
the list for each user contains the answer ids in descending order of rank
getVote does not take negatively ranked votes into account
getVote does not take deleted votes into account
getVotePromotions returns the list of users for the question where the rank was RANK_QUESTION_PROMOTION
getAnswerPromotions returns the list of users for the answer where the rank was RANK_ANSWER_PROMOTION

PollAnswer
poll answer have an integer updated by attribute
ubdated by reference the user
updated by is filled in automatically with the user when a poll is created
updated by is filled in automatically with the user when a poll is created, even if it is not a debate

actions:

proposeAnswer
propose answer can be done by those who can comment on the vote
propose answer can be used only when debate is true
propose answer creates a new poll answer
propose answer sets the user as a promoter of the answer//by adding a CondorcetVote with RANK_ANSWER_PROMOTION

promoteQuestion
promote question can be done by those who can comment on the vote
promote question sets the user as a promoter of the question

promoteAnswer
promote answer can be done by those who can comment on the vote
promote answer sets the user as a promoter of the answer

demoteQuestion
demote question can be done by those who can comment on the vote
demote question removes a promotion to the question
demote question checks user id
demote question checks question id
demote question checks that rank is RANK_QUESTION_PROMOTION
  
demoteAnswer
demote answer can be done by those who can comment on the vote
demote answer removes a promotion to the answer
demote answer checks user id
demote answer checks question id
demote answer checks answer id
demote answer checks that rank is RANK_ANSWER_PROMOTION
  

condorcetVote
condorcet vote can be done by those who can comment on the vote
condorcetVote receives a question id and pairs of rank and answer id
an answer id can be only once in the list
the rank cannot be negative
condorcetvote creates a record in condorcet vote for each of the rank answer id pairs
condorcetvote fills in the userid in the records
condorcetvote fills in the question id in the records
condorcetvote fills in the created_at attribute with the current timestamp
condorcetvote fills in the deleted attribute to be false
condorcetvote sets the deleted attribute to true in all the previous records with nonnegative rank, the same question id and same user id 

setState
set state can be done by those who can modify the poll
set state calls the set state method of the model

*/

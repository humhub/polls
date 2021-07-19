<?php
namespace polls;

use humhub\modules\polls\helpers\RestDefinitions;
use humhub\modules\polls\models\Poll;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
*/
class ApiTester extends \ApiTester
{
    use _generated\ApiTesterActions;

    /**
     * Define custom actions here
     */

    public function createPoll($question, $description, $answers = [], $params = [])
    {
        $params = array_merge([
            'containerId' => 1,
            'allow_multiple' => 0,
            'is_random' => 0,
            'anonymous' => 0,
            'show_result_after_close' => 0,
        ], $params);

        $this->amGoingTo('create a sample poll');
        $this->sendPost('polls/container/' . $params['containerId'], [
            'Poll' => [
                'question' => $question,
                'description' => $description,
                'allow_multiple' => $params['allow_multiple'],
                'is_random' => $params['is_random'],
                'anonymous' => $params['anonymous'],
                'show_result_after_close' => $params['show_result_after_close'],
            ],
            'newAnswers' => $answers,
        ]);
    }

    public function createSamplePoll()
    {
        $this->createPoll('Sample poll question?', 'Sample poll description', ['Answer 1', 'Answer 2']);
    }

    public function getPollDefinitionById($pollId)
    {
        $poll = Poll::findOne(['id' => $pollId]);
        return ($poll ? RestDefinitions::getPoll($poll) : []);
    }

    public function seeLastCreatedPollDefinition()
    {
        $poll = Poll::find()
            ->orderBy(['id' => SORT_DESC])
            ->one();
        $pollDefinition = ($poll ? RestDefinitions::getPoll($poll) : []);
        $this->seeSuccessResponseContainsJson($pollDefinition);
    }

    public function seePollDefinitionById($pollId)
    {
        $this->seeSuccessResponseContainsJson($this->getPollDefinitionById($pollId));
    }

    public function seePaginationPollsResponse($url, $pollIds)
    {
        $pollDefinitions = [];
        foreach ($pollIds as $pollId) {
            $pollDefinitions[] = $this->getPollDefinitionById($pollId);
        }

        $this->seePaginationGetResponse($url, $pollDefinitions);
    }
}

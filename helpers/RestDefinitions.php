<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2021 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\polls\helpers;

use humhub\modules\polls\models\Poll;
use humhub\modules\polls\models\PollAnswer;
use humhub\modules\rest\definitions\ContentDefinitions;
use humhub\modules\rest\definitions\UserDefinitions;

/**
 * Class RestDefinitions
 *
 * @package humhub\modules\rest\definitions
 */
class RestDefinitions
{
    public static function getPoll(Poll $poll): array
    {
        return [
            'id' => $poll->id,
            'contentcontainer_id' => $poll->content->contentcontainer_id,
            'question' => $poll->question,
            'description' => $poll->description,
            'allow_multiple' => (int)$poll->allow_multiple,
            'created_at' => $poll->created_at,
            'created_by' => UserDefinitions::getUserShort($poll->getOwner()),
            'updated_at' => $poll->updated_at,
            'updated_by' => UserDefinitions::getUserShort($poll->content->updatedBy),
            'is_random' => (int)$poll->is_random,
            'closed' => (int)$poll->closed,
            'anonymous' => (int)$poll->anonymous,
            'show_result_after_close' => (int)$poll->show_result_after_close,
            'answers' => self::getAnswersByPollId($poll->id),
            'content' => ContentDefinitions::getContent($poll->content),
        ];
    }

    public static function getAnswersByPollId(int $pollId): array
    {
        $pollAnswers = PollAnswer::findAll(['poll_id' => $pollId]);

        $answersArray = [];
        foreach ($pollAnswers as $pollAnswer) {
            $answersArray[] = self::getAnswer($pollAnswer);
        }

        return $answersArray;
    }

    public static function getAnswer(PollAnswer $pollAnswer): array
    {
        return [
            'id' => $pollAnswer->id,
            'poll_id' => $pollAnswer->poll_id,
            'answer' => $pollAnswer->answer,
            'created_at' => $pollAnswer->created_at,
            'created_by' => UserDefinitions::getUserShort($pollAnswer->createdBy),
            'updated_at' => $pollAnswer->updated_at,
            'updated_by' => UserDefinitions::getUserShort($pollAnswer->updatedBy),
        ];
    }
}

<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\polls\extensions\custom_pages\elements;

use humhub\modules\custom_pages\modules\template\elements\BaseContentRecordElementVariable;
use humhub\modules\custom_pages\modules\template\elements\BaseRecordElementVariable;
use humhub\modules\polls\models\Poll;
use yii\db\ActiveRecord;

class PollElementVariable extends BaseContentRecordElementVariable
{
    public string $question;
    public string $description;
    public bool $closed;
    public array $answers = [];

    public function setRecord(?ActiveRecord $record): BaseRecordElementVariable
    {
        if ($record instanceof Poll) {
            $this->question = $record->question ?? '';
            $this->description = $record->description ?? '';
            $this->closed = (bool) $record->closed;

            foreach ($record->answers as $answer) {
                $this->answers[] = [
                    'answer' => $answer->answer,
                    'votes' => $answer->getVotes()->count(),
                ];
            }
        }

        return parent::setRecord($record);
    }
}

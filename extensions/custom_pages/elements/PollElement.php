<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\polls\extensions\custom_pages\elements;

use humhub\helpers\Html;
use humhub\modules\custom_pages\modules\template\elements\BaseContentRecordElement;
use humhub\modules\custom_pages\modules\template\elements\BaseElementVariable;
use humhub\modules\polls\models\Poll;
use Yii;

/**
 * Class to manage content record of the Poll
 *
 * @property-read Poll|null $record
 */
class PollElement extends BaseContentRecordElement implements \Stringable
{
    protected const RECORD_CLASS = Poll::class;

    /**
     * @inheritdoc
     */
    public function getLabel(): string
    {
        return Yii::t('PollsModule.base', 'Poll');
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'contentId' => Yii::t('PollsModule.base', 'Poll content ID'),
        ];
    }

    public function __toString(): string
    {
        return (string) Html::encode($this->record?->question);
    }

    /**
     * @inheritdoc
     */
    public function getTemplateVariable(): BaseElementVariable
    {
        return PollElementVariable::instance($this)->setRecord($this->getRecord());
    }
}

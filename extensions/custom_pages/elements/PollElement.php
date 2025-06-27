<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\polls\extensions\custom_pages\elements;

use humhub\libs\Html;
use humhub\modules\custom_pages\modules\template\elements\BaseContentRecordElement;
use humhub\modules\custom_pages\modules\template\elements\BaseElementVariable;
use humhub\modules\polls\models\Poll;
use humhub\modules\polls\widgets\PollPicker;
use humhub\modules\ui\form\widgets\ActiveForm;
use Yii;

/**
 * Class to manage content record of the Poll
 *
 * @property-read Poll|null $record
 */
class PollElement extends BaseContentRecordElement
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
            'contentRecordId' => Yii::t('PollsModule.base', 'Select poll'),
        ];
    }

    public function __toString()
    {
        return Html::encode($this->record->question);
    }

    /**
     * @inheritdoc
     */
    public function getTemplateVariable(): BaseElementVariable
    {
        return PollElementVariable::instance($this)->setRecord($this->getRecord());
    }

    /**
     * @inheritdoc
     */
    public function renderEditForm(ActiveForm $form): string
    {
        return $form->field($this, 'contentRecordId')
            ->widget(PollPicker::class, ['maxSelection' => 1]);
    }
}

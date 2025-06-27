<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\polls\extensions\custom_pages\elements;

use humhub\modules\custom_pages\modules\template\elements\BaseContentRecordsElement;
use humhub\modules\custom_pages\modules\template\elements\BaseElementVariable;
use humhub\modules\polls\models\Poll;
use Yii;

/**
 * Class to manage content records of the elements with Polls list
 */
class PollsElement extends BaseContentRecordsElement
{
    public const RECORD_CLASS = Poll::class;

    /**
     * @inheritdoc
     */
    public function getLabel(): string
    {
        return Yii::t('PollsModule.base', 'Polls');
    }

    /**
     * @inheritdoc
     */
    public function getTemplateVariable(): BaseElementVariable
    {
        return new PollsElementVariable($this);
    }
}

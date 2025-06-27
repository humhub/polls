<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\polls\widgets;

use humhub\modules\polls\models\Poll;
use humhub\modules\ui\form\widgets\BasePicker;

class PollPicker extends BasePicker
{
    /**
     * @inheritdoc
     */
    public $minInput = 2;

    /**
     * @inheritdoc
     */
    public $defaultRoute = '/polls/poll/picker-search';

    /**
     * @inheritdoc
     */
    public $itemClass = Poll::class;

    /**
     * @inheritdoc
     * @param Poll $item
     */
    protected function getItemText($item)
    {
        return $item->question;
    }

    /**
     * @inheritdoc
     */
    protected function getItemImage($item)
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    protected function getAttributes()
    {
        return array_merge(parent::getAttributes(), [
            'data-tags' => 'false',
        ]);
    }
}

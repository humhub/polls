<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\polls\extensions\custom_pages\elements;

use humhub\modules\custom_pages\modules\template\elements\BaseElementVariableIterator;

class PollsElementVariable extends BaseElementVariableIterator
{
    public function __construct(PollsElement $elementContent)
    {
        parent::__construct($elementContent);

        foreach ($elementContent->getItems() as $poll) {
            $this->items[] = PollElementVariable::instance($elementContent)->setRecord($poll);
        }
    }
}

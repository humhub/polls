<?php

namespace humhub\modules\polls\models;

use humhub\modules\polls\models\filters\PollsContentStreamFilter;
use humhub\modules\stream\models\ContentContainerStreamQuery;
use Yii;


/**
 * Description of PollsStreamQuery
 *
 */
class PollsStreamQuery extends ContentContainerStreamQuery
{
    protected function beforeApplyFilters()
    {
        $this->addFilterHandler(PollsContentStreamFilter::class);
        parent::beforeApplyFilters();
    }
}

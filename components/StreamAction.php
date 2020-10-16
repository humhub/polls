<?php

namespace humhub\modules\polls\components;


use humhub\modules\polls\models\PollsStreamQuery;
use humhub\modules\stream\models\StreamQuery;
use humhub\modules\stream\actions\ContentContainerStream;


class StreamAction extends ContentContainerStream
{
    /**
     * @var StreamQuery string
     */
    public $streamQueryClass = PollsStreamQuery::class;

}



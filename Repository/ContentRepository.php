<?php
declare(strict_types=1);

namespace humhub\modules\polls\Repository;

use humhub\modules\content\models\Content;
use humhub\modules\polls\models\Poll;

class ContentRepository
{
    public function findOnePollContent(int $postId): ?Content
    {
        return Content::findOne([
            'object_id' => $postId,
            'object_model' => Poll::className()
        ]);
    }

    public function updateSpecificContent(int $contentContainerId, int $postId): int
    {
        return Content::updateAll(
            ['contentcontainer_id' => $contentContainerId],
            ['object_id' => $postId, 'object_model' => Poll::className()]
        );
    }
}
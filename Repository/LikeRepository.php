<?php
declare(strict_types=1);

namespace humhub\modules\polls\Repository;

use humhub\modules\like\models\Like;
use humhub\modules\polls\models\Poll;

class LikeRepository
{
    public function findAllLikesFromPoll(int $postId): array
    {
        return Like::findAll([
            'object_id' => $postId,
            'object_model' => Poll::className()
        ]);
    }
}
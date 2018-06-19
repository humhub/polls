<?php
declare(strict_types=1);

namespace humhub\modules\polls\Repository;

use humhub\modules\comment\models\Comment;
use humhub\modules\polls\models\Poll;

class CommentRepository
{
    public function findAllCommentsFromPoll(int $postId): array
    {
        return Comment::find()
            ->select(['id'])
            ->where([
                'object_id' => $postId,
                'object_model' => Poll::className()
            ])
            ->all();
    }
}
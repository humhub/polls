<?php
declare(strict_types=1);

namespace humhub\modules\polls\Repository;

use humhub\modules\like\models\Like;
use humhub\modules\notification\models\Notification;

class NotificationRepository
{
    public function updateNotificationByLike(int $spaceId, int $likeId): int
    {
        return Notification::updateAll(
            ['space_id' => $spaceId],
            ['source_pk' => $likeId, 'source_class' => Like::className()]
        );
    }

    public function updateNotificationByComment(int $spaceId, int $commentId): int
    {
        return Notification::updateAll(
            ['space_id' => $spaceId],
            ['source_pk' => $commentId, 'source_class' => Comment::className()]
        );
    }
}
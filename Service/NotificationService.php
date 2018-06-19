<?php
declare(strict_types=1);

namespace humhub\modules\polls\Service;

use humhub\modules\comment\models\Comment;
use humhub\modules\like\models\Like;
use humhub\modules\polls\models\MovePollTransfer;
use humhub\modules\polls\Repository\CommentRepository;
use humhub\modules\polls\Repository\LikeRepository;
use humhub\modules\polls\Repository\NotificationRepository;

class NotificationService
{
    private $likeRepository;

    private $notificationRepository;

    private $commentRepository;

    public function __construct(
        LikeRepository $likeRepository,
        NotificationRepository $notificationRepository,
        CommentRepository $commentRepository
    )
    {
        $this->likeRepository = $likeRepository;
        $this->notificationRepository = $notificationRepository;
        $this->commentRepository = $commentRepository;
    }

    public function movePollDependencies(MovePollTransfer $movePollTransfer): void
    {
        $this->updateMovePollLikeNotification($movePollTransfer);
        $this->updateMovePollCommentNotification($movePollTransfer);
    }

    private function updateMovePollLikeNotification(MovePollTransfer $movePoll): void
    {
        $likes = $this->likeRepository->findAllLikesFromPoll($movePoll->getPostId());

        foreach ($likes as $like) {
            if (false === $like instanceof Like) {
                continue;
            }

            $this->notificationRepository
                ->updateNotificationByLike(
                    $movePoll->getSpaceId(),
                    $like->id
                );
        }
    }

    private function updateMovePollCommentNotification(MovePollTransfer $movePoll): void
    {
        $comments = $this->commentRepository->findAllCommentsFromPoll($movePoll->getPostId());

        foreach ($comments as $comment) {
            if (false === $comment instanceof Comment) {
                continue;
            }

            $this->notificationRepository
                ->updateNotificationByComment(
                    $movePoll->getSpaceId(),
                    $comment->id
                );
        }
    }
}

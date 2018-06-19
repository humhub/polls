<?php
declare(strict_types=1);

namespace humhub\modules\polls\models;

final class MovePollTransfer
{
    private $postId;

    private $spaceId;

    public function __construct(int $postId, int $spaceId)
    {
        $this->postId = $postId;
        $this->spaceId = $spaceId;
    }

    public function getPostId(): int
    {
        return $this->postId;
    }

    public function getSpaceId(): int
    {
        return $this->spaceId;
    }
}

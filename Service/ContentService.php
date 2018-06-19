<?php
declare(strict_types=1);

namespace humhub\modules\polls\Service;

use humhub\modules\content\models\Content;
use humhub\modules\content\models\ContentContainer;
use humhub\modules\polls\models\MovePollTransfer;
use humhub\modules\polls\Repository\ContentContainerRepository;
use humhub\modules\polls\Repository\ContentRepository;
use humhub\modules\polls\Repository\SpaceRepository;
use humhub\modules\space\models\Space;

class ContentService
{
    private $spaceRepository;

    private $contentContainerRepository;

    private $contentRepository;

    public function __construct(
        SpaceRepository $spaceRepository,
        ContentRepository $contentRepository,
        ContentContainerRepository $contentContainerRepository
    )
    {
        $this->spaceRepository = $spaceRepository;
        $this->contentRepository = $contentRepository;
        $this->contentContainerRepository = $contentContainerRepository;
    }

    public function movePoll(MovePollTransfer $movePollTransfer): bool
    {
        $updateContent = $this->updateMovePollContent($movePollTransfer);
        $updateWall = $this->updateMovePollWall($movePollTransfer);

        return $updateContent && $updateWall;
    }

    private function updateMovePollContent(MovePollTransfer $movePoll): bool
    {
        /** @var ContentContainer $contentContainer */
        $contentContainer = $this->contentContainerRepository->findAllContentFromSpaceId($movePoll->getSpaceId());

        if (false === $contentContainer instanceof ContentContainer) {
            return false;
        }

        return $this->contentRepository->updateSpecificContent(
                $contentContainer->id,
                $movePoll->getPostId()
            ) > 0;
    }

    private function updateMovePollWall(MovePollTransfer $movePoll): bool
    {
        /** @var Space $space */
        $space = $this->spaceRepository->findSpaceById($movePoll->getSpaceId());

        if ($space === null) {
            return false;
        }

        /** @var Content $content */
        $content = $this->contentRepository->findOnePollContent($movePoll->getPostId());

        if ($content === null) {
            return false;
        }

        $content->setContainer($space);
        $content->update();

        return true;
    }
}

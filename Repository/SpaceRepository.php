<?php
declare(strict_types=1);

namespace humhub\modules\polls\Repository;

use \humhub\modules\space\models\Space;

class SpaceRepository
{
    public function findAllActiveSpaces(): array
    {
        return Space::find()
            ->where(
                ['status' => 1]
            )
            ->all();
    }

    public function findSpaceById(int $spaceId): ?Space
    {
        return Space::findOne(sprintf('id = %d', $spaceId));
    }
}

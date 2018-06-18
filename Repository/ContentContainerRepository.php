<?php
declare(strict_types=1);

namespace humhub\modules\polls\Repository;

use humhub\modules\content\models\ContentContainer;

class ContentContainerRepository
{
    public function findAllContentFromSpaceId(int $spaceId)
    {
        return ContentContainer::find()
            ->where(['pk' => $spaceId])
            ->andWhere(
                sprintf(
                    'class LIKE %s%s%s',
                    '"%',
                    'space',
                    '%"'
                )
            )
            ->one();
    }
}
<?php
declare(strict_types=1);

namespace humhub\modules\polls\controllers;

use humhub\modules\admin\components\Controller;
use humhub\modules\polls\models\MovePollTransfer;
use humhub\modules\polls\Repository\CommentRepository;
use humhub\modules\polls\Repository\ContentContainerRepository;
use humhub\modules\polls\Repository\ContentRepository;
use humhub\modules\polls\Repository\LikeRepository;
use humhub\modules\polls\Repository\NotificationRepository;
use humhub\modules\polls\Repository\SpaceRepository;
use humhub\modules\polls\Service\ContentService;
use humhub\modules\polls\Service\NotificationService;

final class WidgetController extends Controller
{
    public function actionAjaxSpaceList(): string
    {
        $spaceRepository = new SpaceRepository();

        return $this->renderPartial(
            'spacelist',
            [
                'spaces' => $spaceRepository->findAllActiveSpaces(),
                'postId' => (int)\Yii::$app->request->post('postId'),
            ]
        );
    }

    public function actionMovePollToSpace(): string
    {
        $contentService = new ContentService(
            new SpaceRepository(),
            new ContentRepository(),
            new ContentContainerRepository()
        );

        $notificationService = new NotificationService(
            new LikeRepository(),
            new NotificationRepository(),
            new CommentRepository()
        );

        $movePoll = $this->buildMovePoll();

        $contentService->movePoll($movePoll);
        $notificationService->movePollDependencies($movePoll);

        \Yii::$app->getSession()->setFlash('success',
            \Yii::t('PollsModule.widgets_views_move_poll', 'poll was moved successfully')
        );

        return $this->renderPartial('setPollToNewSpace',
            [
                'postId' => $movePoll->getPostId()
            ]
        );
    }

    private function buildMovePoll(): MovePollTransfer
    {
        return new MovePollTransfer(
            (int)\Yii::$app->request->post('postId'),
            (int)\Yii::$app->request->post('spaceId')
        );
    }
}

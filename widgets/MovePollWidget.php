<?php
declare(strict_types=1);

namespace humhub\modules\polls\widgets;

use Yii;
use \humhub\components\Widget;

class MovePollWidget extends Widget
{
    public $content;

    public function run(): string
    {
        if (true === Yii::$app->user->isAdmin()) {
            return $this->render('movePoll', ['postId' => $this->content->id]);
        }

        return '';
    }
}

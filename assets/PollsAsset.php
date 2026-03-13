<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\polls\assets;

use humhub\components\assets\AssetBundle;
use Yii;

class PollsAsset extends AssetBundle
{
    public $jsOptions = ['position' => \yii\web\View::POS_END];
    public $sourcePath = '@polls/resources';
    public $js = [
        'js/humhub.polls.js',
    ];

    /**
     * @inheritdoc
     */
    public static function register($view)
    {
        $view->registerJsConfig('polls', [
            'text' => [
                'warn.answer_required' => Yii::t('PollsModule.base', 'At least one answer is required'),
            ],
        ]);
        return parent::register($view);
    }
}

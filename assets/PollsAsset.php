<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\polls\assets;

use humhub\modules\ui\view\components\View;
use Yii;
use yii\web\AssetBundle;

class PollsAsset extends AssetBundle
{

    public $jsOptions = ['position' => \yii\web\View::POS_END];
    public $sourcePath = '@polls/resources';
    public $css = [];
    public $js = [
        'js/humhub.polls.js'
    ];

    /**
     * @param View $view
     * @return AssetBundle
     */
    public static function register($view)
    {
        $view->registerJsConfig('polls', [
            'text' => [
                'warn.answer_required' => Yii::t('PollsModule.base', 'At least one answer is required')
            ]
        ]);
        return parent::register($view);
    }
}

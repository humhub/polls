<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\polls\assets;

use yii\web\AssetBundle;

class PollsAsset extends AssetBundle
{

    public $jsOptions = ['position' => \yii\web\View::POS_END];
    public $sourcePath = '@polls/resources';
    public $css = [];
    public $js = [
        'js/humhub.polls.js'
    ];
}

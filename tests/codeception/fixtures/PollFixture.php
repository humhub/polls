<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace tests\codeception\fixtures;

use humhub\modules\polls\models\PollAnswerUser;
use yii\test\ActiveFixture;

class PollFixture extends ActiveFixture
{

    public $modelClass = PollAnswerUser::class;

    public $depends = [
        PollAnswerUserFixture::class,
        PollAnswerFixture::class
    ];
}

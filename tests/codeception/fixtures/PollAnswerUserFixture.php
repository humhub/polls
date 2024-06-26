<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace tests\codeception\fixtures;

use humhub\modules\polls\models\PollAnswerUser;
use yii\test\ActiveFixture;

class PollAnswerUserFixture extends ActiveFixture
{
    public $modelClass = PollAnswerUser::class;
    public $dataFile = '@polls/tests/codeception/fixtures/data/pollAnswerUser.php';

}

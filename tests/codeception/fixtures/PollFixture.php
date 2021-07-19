<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace tests\codeception\fixtures;

use humhub\modules\polls\models\Poll;
use yii\test\ActiveFixture;

class PollFixture extends ActiveFixture
{

    public $modelClass = Poll::class;
    public $dataFile = '@polls/tests/codeception/fixtures/data/poll.php';

    public $depends = [
        PollAnswerUserFixture::class,
        PollAnswerFixture::class
    ];
}

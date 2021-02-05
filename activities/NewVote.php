<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\polls\activities;

use humhub\modules\activity\components\BaseActivity;
use humhub\modules\activity\interfaces\ConfigurableActivityInterface;
use Yii;

class NewVote extends BaseActivity implements ConfigurableActivityInterface
{

    /**
     * @inheritdoc
     */
    public $moduleId = 'polls';
    
    /**
     * @inheritdoc
     */
    public $viewName = 'newVote';

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        return Yii::t('PollsModule.activities', 'Polls');
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return Yii::t('PollsModule.activities', 'Whenever someone participates in a poll.');
    }

}

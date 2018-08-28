<?php

namespace humhub\modules\polls\widgets;

use humhub\modules\content\widgets\WallCreateContentForm;
use humhub\modules\polls\models\Poll;
use humhub\modules\polls\permissions\CreatePoll;
use humhub\modules\space\models\Space;

class WallCreateForm extends WallCreateContentForm
{

    /**
     * @inheritdoc
     */
    public $submitUrl = '/polls/poll/create';

    /**
     * @inheritdoc
     */
    public function renderForm()
    {
        return $this->render('form', ['model' => new Poll()]);
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($this->contentContainer instanceof Space) {
            if (!$this->contentContainer->permissionManager->can(new CreatePoll())) {
                return;
            }
        }

        return parent::run();
    }

}

?>
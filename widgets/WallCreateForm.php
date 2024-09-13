<?php

namespace humhub\modules\polls\widgets;

use humhub\modules\content\widgets\WallCreateContentForm;
use humhub\modules\polls\models\Poll;
use humhub\modules\space\models\Space;
use humhub\modules\ui\form\widgets\ActiveForm;

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
        return $this->render('form', ['model' => new Poll($this->contentContainer)]);
    }

    /**
     * @inheritdoc
     */
    public function renderActiveForm(ActiveForm $form): string
    {
        return $this->render('form', [
            'model' => new Poll($this->contentContainer),
            'form' => $form,
            'submitUrl' => $this->submitUrl,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($this->contentContainer instanceof Space) {
            if (!(new Poll($this->contentContainer))->content->canEdit()) {
                return '';
            }
        }

        return parent::run();
    }

}

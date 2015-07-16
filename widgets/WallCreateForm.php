<?php

namespace humhub\modules\polls\widgets;

class WallCreateForm extends \humhub\modules\content\widgets\WallCreateContentForm
{

    public $submitUrl = '/polls/poll/create';

    public function renderForm()
    {
        return $this->render('form', array());
    }

}

?>
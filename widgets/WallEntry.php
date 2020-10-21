<?php

namespace humhub\modules\polls\widgets;

use humhub\modules\content\widgets\stream\WallStreamModuleEntryWidget;
use Yii;

/**
 * PollWallEntryWidget is used to display a poll inside the stream.
 *
 * This Widget will used by the Poll Model in Method getWallOut().
 *
 * @since 0.5
 * @author Luke
 */
class WallEntry extends WallStreamModuleEntryWidget
{

    /**
     * @inheritDoc
     */
    public $editRoute = "/polls/poll/edit";

    /**
     * @inheritDoc
     */
    public function renderContent()
    {
        //We don't want an edit menu when the poll is closed
        if ($this->model->closed) {
            $this->editRoute = '';
        }

        return $this->render('entry', ['poll' => $this->model,
            'user' => $this->model->content->createdBy,
            'contentContainer' => $this->model->content->container]);
    }

    /**
     * @return string a non encoded plain text title (no html allowed) used in the header of the widget
     */
    protected function getTitle()
    {
        return trim($this->model->question) === '' ? $this->model->getContentName() : $this->model->question;
    }
}
<?php

namespace humhub\modules\polls\widgets;

use Yii;

/**
 * PollWallEntryWidget is used to display a poll inside the stream.
 *
 * This Widget will used by the Poll Model in Method getWallOut().
 *
 * @package humhub.modules.polls.widgets
 * @since 0.5
 * @author Luke
 */
class WallEntry extends \humhub\modules\content\widgets\WallEntry
{

    public $editRoute = "/polls/poll/edit";
    
    public function run()
    {
        //We don't want an edit menu when the poll is closed
        if(version_compare(Yii::$app->version, '1.0.0-beta.4', 'lt') || $this->contentObject->closed) {
            $this->editRoute = '';
        }

        return $this->render('entry', ['poll' => $this->contentObject,
                    'user' => $this->contentObject->content->createdBy,
                    'contentContainer' => $this->contentObject->content->container]);
    }

}
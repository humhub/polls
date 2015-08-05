<?php

namespace humhub\modules\polls\widgets;

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

    public function run()
    {

        return $this->render('entry', array('poll' => $this->contentObject,
                    'user' => $this->contentObject->content->user,
                    'contentContainer' => $this->contentObject->content->container));
    }

}

?>
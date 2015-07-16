<?php

namespace humhub\modules\polls\widgets;

use humhub\components\Widget;

/**
 * PollWallEntryWidget is used to display a poll inside the stream.
 *
 * This Widget will used by the Poll Model in Method getWallOut().
 *
 * @package humhub.modules.polls.widgets
 * @since 0.5
 * @author Luke
 */
class WallEntry extends Widget
{

    public $poll;

    public function run()
    {

        return $this->render('entry', array('poll' => $this->poll,
                    'user' => $this->poll->content->user,
                    'contentContainer' => $this->poll->content->container));
    }

}

?>
<?php

namespace humhub\modules\polls\widgets;

use humhub\components\Widget;

/**
 * ExportButton for export polls per poll dropdown menu
 *
 * This Widget will used by the Poll Module in Events.php
 *
 * @package humhub.modules.polls.widgets
 * @since 0.5
 * @author Informatico-madrid
 */
class ExportButton extends Widget
{
    public $poll;
    
    public function run()
    {
        return $this->render('exportButton', ['poll' => $this->poll]);
    }
}

?>
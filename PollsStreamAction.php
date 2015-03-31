<?php

/**
 * PollsStreamAction is modified version of the StreamAction to show only objects
 * of type Poll.
 *
 * This Action is inserted in PollController and shows with interaction of the
 * PollStreamWidget the Poll Stream.
 *
 * @package humhub.modules.polls
 * @since 0.5
 * @author Luke
 */
class PollsStreamAction extends ContentContainerStreamAction
{

    /**
     * Setup additional filters
     */
    public function setupFilters()
    {
        $this->criteria->condition .= " AND object_model='Poll'";
        
        if (in_array('polls_notAnswered', $this->filters) || in_array('polls_mine', $this->filters)) {
            
            $this->criteria->join .= " LEFT JOIN poll ON content.object_id=poll.id AND content.object_model = 'Poll'";
            
            if (in_array('polls_notAnswered', $this->filters)) {
                $this->criteria->join .= " LEFT JOIN poll_answer_user ON poll.id=poll_answer_user.poll_id AND poll_answer_user.created_by = '" . Yii::app()->user->id . "'";
                $this->criteria->condition .= " AND poll_answer_user.id is null"; 
            }
        }
    }

}

?>

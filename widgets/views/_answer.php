<?php

use yii\helpers\Html;
?>

<div class="row">
        <?php if (!$poll->hasUserVoted() && !$poll->closed) : ?>
            <div class="col-md-1" style="padding-right: 0;">
                <?php if ($poll->allow_multiple) : ?>
                    <div class="checkbox">
                        <label>
                            <?php echo Html::checkBox('answers[' . $answer->id . ']'); ?>
                        </label>
                    </div>

                <?php else: ?>
                    <div class="radio">
                        <label>
                            <?php echo Html::radio('answers', false, array('value' => $answer->id, 'id' => 'answer_' . $answer->id)); ?>
                        </label>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php
        $percent = round($answer->getPercent());
        $color = "progress-bar-info";
        ?>

        <div class="col-md-6">
            <span><?php echo $answer->answer; ?></span>

            <div class="progress">
                <div id="progress_<?php echo $answer->id; ?>" class="progress-bar <?php echo $color; ?>" role="progressbar" aria-valuenow="<?php echo $percent; ?>" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
            </div>
            <script type="text/javascript">
                $('#progress_<?php echo $answer->id; ?>').css('width', '<?php echo $percent; ?>%');
            </script>
        </div>

        <div class="col-md-4">

            <?php
            $userlist = ""; // variable for users output
            $maxUser = 10; // limit for rendered users inside the tooltip

            if (!$poll->anonymous) {
                for ($i = 0; $i < count($answer->votes); $i++) {

                    // if only one user likes
                    // check if exists more user as limited
                    if ($i == $maxUser) {
                        // output with the number of not rendered users
                        $userlist .= Yii::t('PollsModule.widgets_views_entry', 'and {count} more vote for this.', array('{count}' => (intval(count($answer->votes) - $maxUser))));

                        // stop the loop
                        break;
                    } else {
                        $userlist .= Html::encode($answer->votes[$i]->user->displayName) . "\n";
                    }
                }
            }

            $voteText = Yii::t('PollsModule.widgets_views_entry', 'votes');
            ?>
            <p style="margin-top: 14px; display:inline-block;" class="tt" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo $userlist; ?>">
                <?php if (!$poll->anonymous && count($answer->votes) > 0) { ?>
                    <a href="<?php echo $contentContainer->createUrl('/polls/poll/user-list-results', array('pollId' => $poll->id, 'answerId' => $answer->id)); ?>" data-target="#globalModal">
                           <?php echo count($answer->votes) . " " . $voteText ?>
                    </a>
                <?php } else if(count($answer->votes) > 0) { ?>
                    <?php echo count($answer->votes) . " " . $voteText ?>
                <?php } else { ?>
                    0 <?php echo $voteText ?>
                 <?php } ?>
                    
            </p>

        </div>


    </div>
    <div class="clearFloats"></div>
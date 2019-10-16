<?php

use humhub\libs\Html;

/** @var $poll \humhub\modules\polls\models\Poll **/
?>

<div class="row">
        <?php if (!$poll->hasUserVoted() && !$poll->closed) : ?>
            <div class="col-md-1" style="padding-right: 0;">
                <?php if ($poll->allow_multiple) : ?>
                    <div class="checkbox">
                        <label>
                            <?= Html::checkBox('answers[' . $answer->id . ']'); ?>
                        </label>
                    </div>

                <?php else: ?>
                    <div class="radio">
                        <label>
                            <?= Html::radio('answers', false, array('value' => $answer->id, 'id' => 'answer_' . $answer->id)); ?>
                        </label>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php
        $percent = $poll->isShowResult() ? round($answer->getPercent()) : 0;
        $color = "progress-bar-info";
        ?>

        <div class="col-md-6">
            <span><?= Html::encode($answer->answer) ?></span>

            <div class="progress">
                <div id="progress_<?= $answer->id; ?>" class="progress-bar <?= $color; ?>" role="progressbar" aria-valuenow="<?= $percent; ?>" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
            </div>
            <?= Html::script(" $('#progress_{$answer->id}').css('width', '{$percent}%');")?>
        </div>

        <div class="col-md-4">
            <?php if($poll->isShowResult()) : ?>
                <?php
                $userlist = ""; // variable for users output
                $maxUser = 10; // limit for rendered users inside the tooltip

                if (!$poll->anonymous) {
                    for ($i = 0; $i < count($answer->votes); $i++) {

                        // if only one user likes check if exists more user as limited
                        if ($i == $maxUser) {
                            $userlist .= Yii::t('PollsModule.widgets_views_entry', 'and {count} more vote for this.', ['{count}' => (intval(count($answer->votes) - $maxUser))]);
                            break;
                        } else {
                            $userlist .= Html::encode($answer->votes[$i]->user->displayName) . "\n";
                        }
                    }
                }

                $voteText = Yii::t('PollsModule.widgets_views_entry', 'votes');
                ?>
                <p style="margin-top: 14px; display:inline-block;" class="tt" data-toggle="tooltip" data-placement="top" data-original-title="<?= $userlist; ?>">
                    <?php if (!$poll->anonymous && count($answer->votes) > 0) { ?>
                        <a href="<?= $contentContainer->createUrl('/polls/poll/user-list-results', array('pollId' => $poll->id, 'answerId' => $answer->id)); ?>" data-target="#globalModal">
                               <?= count($answer->votes) . " " . $voteText ?>
                        </a>
                    <?php } else if(count($answer->votes) > 0) { ?>
                        <?= count($answer->votes) . " " . $voteText ?>
                    <?php } else { ?>
                        0 <?= $voteText ?>
                     <?php } ?>

                </p>
            <?php endif; ?>
        </div>
    </div>

    <div class="clearFloats"></div>
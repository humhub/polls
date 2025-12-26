<?php

use humhub\helpers\Html;
use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\polls\models\Poll;
use humhub\modules\polls\models\PollAnswer;
use humhub\modules\polls\models\PollAnswerUser;
use humhub\widgets\bootstrap\Link;

/* @var $poll Poll */
/* @var $answer PollAnswer */
/* @var $contentContainer ContentContainerActiveRecord */

$percent = $poll->isShowResult() ? round($answer->getPercent()) : 0;
$voteCount = count($answer->votes);
$voteText = Yii::t('PollsModule.base', '{n,plural,=1{# {htmlTagBegin}vote{htmlTagEnd}}other{# {htmlTagBegin}votes{htmlTagEnd}}}', [
    'n' => $voteCount,
    'htmlTagBegin' => '<span class="d-none d-sm-inline">',
    'htmlTagEnd' => '</span>',
]);

$userlist = ''; // variable for users output
$maxUser = 10; // limit for rendered users inside the tooltip
if (!$poll->anonymous) {
    foreach ($answer->votes as $i => $vote) {
        /* @var $vote PollAnswerUser */
        // if only one user likes check if exists more user as limited
        if ($i == $maxUser) {
            $userlist .= Yii::t('PollsModule.base', 'and {count} more vote for this.', ['{count}' => $voteCount - $maxUser]);
            break;
        } else {
            $userlist .= Html::encode($vote->user->displayName) . "\n";
        }
    }
}
?>
<div class="container">
    <div class="row m-0">
        <?php if (!$poll->hasUserVoted() && !$poll->closed) : ?>
            <div class="col-1 mt-2 ps-0">
                <?php if ($poll->allow_multiple) : ?>
                    <?= Html::checkBox('answers[' . $answer->id . ']'); ?>
                <?php else : ?>
                    <?= Html::radio('answers', false, ['value' => $answer->id, 'id' => 'answer_' . $answer->id]) ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="col-md-7 col-9 ps-0">
            <span><?= Html::encode($answer->answer) ?></span>
            <div class="progress">
                <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="<?= $percent ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?= $percent ?>%"></div>
            </div>
        </div>

        <?php if ($poll->isShowResult()) : ?>
            <div class="col-2 text-nowrap mt-3 p-0">
                <?= !$poll->anonymous && $voteCount
                    ? Link::to($voteText, $contentContainer->createUrl('/polls/poll/user-list-results', [
                            'pollId' => $poll->id,
                            'answerId' => $answer->id,
                        ]))
                        ->cssClass('link-accent')
                        ->options(['data-bs-target' => '#globalModal'])
                        ->tooltip($userlist)
                    : $voteText ?>
            </div>
        <?php endif; ?>
    </div>
</div>

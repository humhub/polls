<?php

use humhub\libs\Html;
use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\polls\models\Poll;
use humhub\modules\polls\models\PollAnswer;
use humhub\modules\polls\models\PollAnswerUser;
use humhub\widgets\Link;

/* @var $poll Poll */
/* @var $answer PollAnswer */
/* @var $contentContainer ContentContainerActiveRecord */

$percent = $poll->isShowResult() ? round($answer->getPercent()) : 0;
$voteCount = count($answer->votes);
$voteText = Yii::t('PollsModule.base', '{n,plural,=1{# {htmlTagBegin}vote{htmlTagEnd}}other{# {htmlTagBegin}votes{htmlTagEnd}}}', [
    'n' => $voteCount,
    'htmlTagBegin' => '<span class="hidden-xs">',
    'htmlTagEnd' => '</span>',
]);

$userlist = ''; // variable for users output
$maxUser = 10; // limit for rendered users inside the tooltip
if (!$poll->anonymous) {
    foreach ($answer->votes as $i => $vote) {
        /* @var $vote PollAnswerUser */
        // if only one user likes check if exists more user as limited
        if ($i == $maxUser) {
            $userlist .= Yii::t('PollsModule.widgets_views_entry', 'and {count} more vote for this.', ['{count}' => $voteCount - $maxUser]);
            break;
        } else {
            $userlist .= Html::encode($vote->user->displayName) . "\n";
        }
    }
}
?>
<div class="row" style="margin:0">
    <?php if (!$poll->hasUserVoted() && !$poll->closed) : ?>
        <div class="col-xs-1" style="margin-top:6px;padding-left:0">
            <?php if ($poll->allow_multiple) : ?>
                <?= Html::checkBox('answers[' . $answer->id . ']'); ?>
            <?php else : ?>
                <?= Html::radio('answers', false, ['value' => $answer->id, 'id' => 'answer_' . $answer->id]) ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="col-sm-7 col-xs-9" style="padding-left:0">
        <span><?= Html::encode($answer->answer) ?></span>
        <div class="progress">
            <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="<?= $percent ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?= $percent ?>%"></div>
        </div>
    </div>

    <?php if ($poll->isShowResult()) : ?>
        <div class="col-xs-2 text-nowrap tt" style="margin-top:14px;padding:0" data-toggle="tooltip" data-placement="top" data-original-title="<?= $userlist ?>">
            <?= !$poll->anonymous && $voteCount
                ? Link::asLink($voteText, $contentContainer->createUrl('/polls/poll/user-list-results', [
                    'pollId' => $poll->id,
                    'answerId' => $answer->id,
                ]))->options(['data-target' => '#globalModal'])
                : $voteText ?>
        </div>
    <?php endif; ?>
</div>

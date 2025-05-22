<li>
    <a class="dropdown-item" href="#" data-action-click="reset"
       data-action-target="[data-poll='<?= $poll->id ?>']" 
       data-action-url="<?= $poll->content->container->createUrl('/polls/poll/answer-reset', ['pollId' => $poll->id]); ?>">
        <i class="fa fa-undo"></i>
        <?= Yii::t('PollsModule.base', 'Reset my vote') ?>
    </a>
</li>

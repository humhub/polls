<li>
    <a href="#" data-action-click="reset"
       data-action-target="[data-poll='<?= $poll->id ?>']" 
       data-action-url="<?= $poll->content->container->createUrl('/polls/poll/answer-reset', ['pollId' => $poll->id]); ?>">
        <i class="fa fa-undo"></i>
        <?= Yii::t('PollsModule.widgets_views_entry', 'Reset my vote') ?>
    </a>
</li>
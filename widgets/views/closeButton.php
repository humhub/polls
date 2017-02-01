<li>
    <?php if ($poll->closed) : ?>
        <a href="#" data-action-click="close" data-action-target="[data-poll='<?= $poll->id ?>']" 
           data-action-url="<?= $poll->content->container->createUrl('/polls/poll/open', ['id' => $poll->id]); ?>">
            <i class="fa fa-check"></i>
            <?= Yii::t('PollsModule.widgets_views_entry', 'Reopen Poll') ?>
        </a>
    <?php else : ?>
        <a data-action-click="close" data-action-target="[data-poll='<?= $poll->id ?>']" 
           data-action-url="<?= $poll->content->container->createUrl('/polls/poll/close', ['id' => $poll->id]); ?>">
            <i class="fa fa-times"></i>
            <?= Yii::t('PollsModule.widgets_views_entry', 'Complete Poll') ?>
        </a>
    <?php endif; ?>
</li>

<li>
    <?php if ($poll->closed) : ?>
        <a class="dropdown-item" href="#" data-action-click="close" data-action-target="[data-poll='<?= $poll->id ?>']" 
           data-action-url="<?= $poll->content->container->createUrl('/polls/poll/open', ['id' => $poll->id]); ?>">
            <i class="fa fa-check"></i>
            <?= Yii::t('PollsModule.base', 'Reopen Poll') ?>
        </a>
    <?php else : ?>
        <a class="dropdown-item" data-action-click="close" data-action-target="[data-poll='<?= $poll->id ?>']" 
           data-action-url="<?= $poll->content->container->createUrl('/polls/poll/close', ['id' => $poll->id]); ?>">
            <i class="fa fa-times"></i>
            <?= Yii::t('PollsModule.base', 'Close Poll') ?>
        </a>
    <?php endif; ?>
</li>

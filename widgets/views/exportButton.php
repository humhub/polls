<li>
    <a id="<?= 'export_poll_link_' . $poll->id ?>" href="<?= $poll->content->container->createUrl('/polls/poll/export', ['pollId' => $poll->id]); ?>">
        <i class="fa fa-file-excel-o"></i>
        <?= Yii::t('PollsModule.widgets_views_entry', 'Export poll') ?>
    </a>
</li>
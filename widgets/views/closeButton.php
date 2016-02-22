<li>
     <?php
        $labelKey = ($poll->closed) ? 'Open' : 'Close';
        $action = ($poll->closed) ? '/polls/poll/open' : '/polls/poll/close';
        $cssClass = ($poll->closed) ? 'fa-check' : 'fa-times';
        $url = $poll->content->container->createUrl($action, ['id' => $poll->id]);
        $linkId = 'close_poll_link_' . $poll->id;
    ?>
    
    <a id="<?= $linkId ?>">
            <i class="fa <?= $cssClass ?>"></i>
            <?= Yii::t('PollsModule.widgets_views_entry', $labelKey) ?>
    </a>
    <script type="text/javascript">
        $('#<?= $linkId ?>').on('click', function() {
            $.ajax({
                type: 'post',
                url: '<?= $url ?>'
            }).done(function (html) {
                $(".wall_<?=$poll->getUniqueId() ?>").replaceWith(html);
            });
        });
    </script>
</li>

<li>
    <?php
    $labelKey = ($poll->closed) ? 'Reopen Poll' : 'Complete Poll';
    $action = ($poll->closed) ? '/polls/poll/open' : '/polls/poll/close';
    $cssClass = ($poll->closed) ? 'fa-check' : 'fa-times';
    $url = $poll->content->container->createUrl($action, ['id' => $poll->id]);
    $linkId = 'close_poll_link_' . $poll->id;
    ?>

    <a id="<?= $linkId ?>" href="#">
        <i class="fa <?= $cssClass ?>"></i>
        <?= Yii::t('PollsModule.widgets_views_entry', $labelKey) ?>
    </a>
    <script type="text/javascript">
        $('#<?= $linkId ?>').on('click', function (event) {
            $.ajax({
                type: 'post',
                url: '<?= $url ?>',
                'beforeSend': function() {
                    $(".wall_<?= $poll->getUniqueId() ?>").find('.errorMessage').empty().hide();
                    $("#pollform-loader_<?= $poll->id ?>").removeClass("hidden");
                }
            }).done(function (html) {
                $("#pollform-loader_<?= $poll->id ?>").addClass("hidden");
                $(".wall_<?= $poll->getUniqueId() ?>").replaceWith(html);
            });
            event.preventDefault();
        });
    </script>
</li>

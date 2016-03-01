<li>
    <a id="<?= 'reset_poll_link_' . $poll->id ?>" href="#">
        <i class="fa fa-undo"></i>
        <?= Yii::t('PollsModule.widgets_views_entry', 'Reset my vote') ?>
    </a>
    <script type="text/javascript">
        $('#<?= 'reset_poll_link_' . $poll->id ?>').on('click', function (event) {
            event.preventDefault();
            $.ajax({
                type: 'post',
                url: '<?= $poll->content->container->createUrl('/polls/poll/answer-reset', ['pollId' => $poll->id]); ?>',
                'beforeSend': function() {
                    $(".wall_<?= $poll->getUniqueId() ?>").find('.errorMessage').empty().hide();
                    $("#pollform-loader_<?= $poll->id ?>").removeClass("hidden");
                }
            }).done(function(json) { 
                $('#wallEntry_'+json.wallEntryId).html(parseHtml(json.output)); 
                $('#wallEntry_'+json.wallEntryId).find(':checkbox, :radio').flatelements(); 
            });
            
        });
    </script>
</li>
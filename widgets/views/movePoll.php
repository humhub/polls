<?php

use yii\helpers\Url;

?>

<!-- Link in menu for reporting the post -->
<li>
    <a href="#"
       id="move-poll-modal<?php echo $postId; ?>"
       data-toggle="modal"
       data-target="#submit-move-poll-<?php echo $postId; ?>">
        <i class="fa fa-arrow-right"></i>
        <?php echo Yii::t('PollsModule.widgets_views_move_poll', 'move poll'); ?>
    </a>
</li>

<!-- Modal with list of spaces -->
<div class="modal" id="submit-move-poll-<?php echo $postId; ?>"
     tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-small animated pulse">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title text-left" id="myModalLabel">
                    <strong>
                        <?php echo Yii::t('MeAdminExtModule.widgets_views_movePostLink', 'spaceList'); ?>
                    </strong>
                </h4>
            </div>
            <div class="modal-body">
                <div class="loader">Spaces werden geladen...</div>
                <div class="content"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        var $pollId = $("#submit-move-poll-<?php echo $postId; ?>");
        // move modal to body
        $pollId.appendTo(document.body);

        $pollId.on("shown.bs.modal", function () {
            jQuery.ajax({
                type: "POST",
                url: "<?php echo Url::to(['/polls/widget/ajax-space-list']); ?>",
                data: {
                    postId: <?php echo $postId; ?>
                },
                success: function (msg) {
                    $pollId.find(".loader").hide();
                    $pollId.find(".content").html(msg);
                },
                error: function (xhr) {
                    $pollId.find('.content').html('<p>Es ist ein Fehler aufgetreten.</p>')
                }
            });
        });
    });
</script>

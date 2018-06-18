<?php

use yii\helpers\Url;

?>

<ul class="listSpaces">
    <?php
    /** @var Space $space */
    foreach ($spaces as $space) {
        ?>
        <li>
            <a class="set-poll-to-new-space" href="<?php
            echo Url::toRoute('/polls/widget/move-poll-to-space');
            ?>" data-space-id="<?php
            echo $space->id;
            ?>">
                <?php echo $space->name; ?>
            </a>
        </li>
        <?php
    }
    ?>
</ul>

<script type="text/javascript">
    $(document).ready(function () {
        // move modal to body
        $(".set-poll-to-new-space").click(function (e) {
            var currentElement = jQuery(this);
            e.preventDefault();
            jQuery("#submit-move-poll-<?php echo $postId; ?>").find(".loader").show();

            jQuery.ajax({
                type: "POST",
                url: "<?php echo Url::to(['/polls/widget/move-poll-to-space']); ?>",
                data: {
                    postId: <?php echo $postId; ?>,
                    spaceId: currentElement.data("space-id")
                },
                success: function (msg) {
                    jQuery("#submit-move-poll-<?php echo $postId; ?>").find(".loader").hide();
                    currentElement.parents(".content").html(msg);
                },
                error: function (xhr) {
                    console.log("failure");
                }
            });
        })
    });
</script>

<?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success alert-dismissable" id="data-saved">
        <h5><i class="icon fa fa-check"></i> <?php echo Yii::$app->session->getFlash('success'); ?></h5>
    </div>
<?php endif; ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#submit-move-poll-<?php echo $postId; ?>").on("hidden.bs.modal", function () {
            location.reload();
        });
    });
</script>

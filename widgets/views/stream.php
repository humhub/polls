<?php
/**
 * This view shows the stream of all available polls.
 * Used by PollStreamWidget.
 *
 * @property Space $space the current space
 *
 * @package humhub.modules.polls.widgets.views
 * @since 0.5
 */
?>
<ul class="nav nav-tabs wallFilterPanel" id="filter" style="display: none;">
    <li class=" dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo Yii::t('PollsModule.widgets_views_stream', 'Filter'); ?> <b class="caret"></b></a>
        <ul class="dropdown-menu">
            <!--<li><a href="#" class="wallFilter" id="filter_visibility_public"><i class="fa fa-check-square-o"></i> <?php echo Yii::t('PollsModule.widgets_views_stream', 'Display all'); ?></a></li>-->
            <li><a href="#" class="wallFilter" id="filter_polls_notAnswered"><i class="fa fa-square-o"></i> <?php echo Yii::t('PollsModule.widgets_views_stream', 'No answered yet'); ?></a></li>
            <li><a href="#" class="wallFilter" id="filter_entry_mine"><i class="fa fa-square-o"></i> <?php echo Yii::t('PollsModule.widgets_views_stream', 'Asked by me'); ?></a></li>
            <li><a href="#" class="wallFilter" id="filter_visibility_public"><i class="fa fa-square-o"></i> <?php echo Yii::t('PollsModule.widgets_views_stream', 'Only public polls'); ?></a></li>
            <li><a href="#" class="wallFilter" id="filter_visibility_private"><i class="fa fa-square-o"></i> <?php echo Yii::t('PollsModule.widgets_views_stream', 'Only private polls'); ?></a></li>
        </ul>
    </li>
    <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo Yii::t('PollsModule.widgets_views_stream', 'Sorting'); ?> <b class="caret"></b></a>
        <ul class="dropdown-menu">
            <li><a href="#" class="wallSorting" id="sorting_c"><i class="fa fa-check-square-o"></i> <?php echo Yii::t('PollsModule.widgets_views_stream', 'Creation time'); ?></a></li>
            <li><a href="#" class="wallSorting" id="sorting_u"><i class="fa fa-square-o"></i> <?php echo Yii::t('PollsModule.widgets_views_stream', 'Last update'); ?></a></li>
        </ul>
    </li>
</ul>

<div id="pollStream">

    <!-- DIV for an normal wall stream -->
    <div class="s2_stream" style="display:none">
        <div class="s2_streamContent"></div>
        <div class="loader streamLoader"></div>
        <div class="emptyStreamMessage">
            <?php if ($this->contentContainer->canWrite()) { ?>
                <div class="placeholder placeholder-empty-stream">
                    <?php echo Yii::t('PollsModule.widgets_views_stream', '<b>There are no polls yet!</b><br>Be the first to create one...'); ?>
                </div>
            <?php }?>
        </div>
        <div class="emptyFilterStreamMessage">
            <div class="placeholder">
                <b><?php echo Yii::t('PollsModule.widgets_views_stream', 'No poll found which matches your current filter(s)!'); ?></b>
            </div>
        </div>
    </div>

    <!-- DIV for an single wall entry -->
    <div class="s2_single" style="display: none;">
        <div class="back_button_holder">
            <a href="#" class="singleBackLink button_white"><?php echo Yii::t('WallModule.widgets_views_stream', 'Back to stream'); ?></a>
        </div>
        <div class="p_border"></div>

        <div class="s2_singleContent"></div>
        <div class="loader streamLoaderSingle"></div>
    </div>
</div>


<script>
    // Kill current stream
    if (currentStream) {
        currentStream.clear();
    }

    s = new Stream("#pollStream", "<?php echo $startUrl; ?>", "<?php echo $reloadUrl; ?>", "<?php echo $singleEntryUrl; ?>");
    s.showStream();
    currentStream = s;

</script>



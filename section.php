<?php
require_once 'connection.php';
global $con;

$top_features = cache_remember('home_top_features', function () use ($con) {
    $res = mysqli_query($con, "SELECT * FROM home_sections WHERE section_type = 'top_feature' AND status = 1 ORDER BY sort_order ASC");
    $data = [];
    while ($row = mysqli_fetch_assoc($res)) {
        $data[] = $row;
    }
    return $data;
}, 3600);
?>
<div class="vc_row wpb_row vc_row-fluid inside-slider">
    <div class="wpb_column vc_column_container vc_col-sm-12">
        <div class="vc_column-inner">
            <div class="wpb_wrapper">
                <div class="vc_row wpb_row vc_inner vc_row-fluid">
                    <?php if (!empty($top_features)): foreach ($top_features as $feature): ?>
                    <div class="wpb_column vc_column_container vc_col-sm-4">
                        <div class="vc_column-inner vc_custom_1509108645328">
                            <div class="wpb_wrapper">
                                <div class="media rt-info-box layout5" style="">
                                    <div class="rtin-icon rounded" style="">
                                        <i class="<?php echo htmlspecialchars($feature['icon']); ?>" aria-hidden="true" style=""></i>
                                    </div>
                                    <div class="media-body">
                                        <h3 class="media-heading" style=""><?php echo htmlspecialchars($feature['title']); ?></h3>
                                        <p class="mb0" style=""><?php echo htmlspecialchars($feature['value']); ?></p>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; else: ?>
                    <div class="col-12 text-center py-4">No features available.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
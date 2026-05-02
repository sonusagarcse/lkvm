<?php
require_once 'connection.php';
global $con;

$counters = cache_remember('home_counters', function () use ($con) {
    $res = mysqli_query($con, "SELECT * FROM home_sections WHERE section_type = 'counter' AND status = 1 ORDER BY sort_order ASC");
    $data = [];
    while ($row = mysqli_fetch_assoc($res)) {
        $data[] = $row;
    }
    return $data;
}, 3600);
?>
<div data-vc-full-width="true" data-vc-full-width-init="false" class="vc_row wpb_row vc_row-fluid vc_custom_1508848968428 vc_row-has-fill">
    <?php if (!empty($counters)): foreach ($counters as $cnt): ?>
    <div class="wpb_column vc_column_container vc_col-sm-6 vc_col-lg-3 vc_col-md-3">
        <div class="vc_column-inner">
            <div class="wpb_wrapper">
                <div class="rt-vc-counter ">
                    <div class="rtin-left">
                        <div class="rtin-counter" style="color:#ffffff;"><span class="rtin-counter-num"
                                data-num="<?php echo (int)$cnt['value']; ?>" data-rtSpeed="5000" data-rtSteps="10"><?php echo $cnt['value']; ?></span></div>
                    </div>
                    <div class="rtin-right">
                        <div class="rtin-title" style="color:#ffffff;"><?php echo htmlspecialchars($cnt['title']); ?></div>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; else: ?>
    <div class="col-12 text-center text-white py-4">No counters available.</div>
    <?php endif; ?>
</div>

<div class="vc_row-full-width vc_clearfix"></div>

<div class="vc_row-full-width vc_clearfix"></div>
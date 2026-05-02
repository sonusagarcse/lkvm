<?php
// Include database connection
require_once 'connection.php';
global $con;
// Cache webpage content for 1 hour
$webpage_data = cache_remember('webpage_12', function () use ($con) {
    $id = 12;
    $sel = $con->prepare("SELECT * FROM webpage WHERE id = ? ORDER BY id DESC");
    $sel->execute([$id]);
    $result = $sel->get_result();
    return $result->fetch_assoc();
}, 3600); // Cache for 1 hour
?>
<div data-vc-full-width="true" data-vc-full-width-init="false"
    class="vc_row wpb_row vc_row-fluid vc_custom_1515066600956 vc_row-has-fill" 
    style="<?php echo ($webpage_data && $webpage_data['img']) ? 'background-image: url(\'images/' . $webpage_data['img'] . '\') !important; background-size: cover; background-position: center;' : ''; ?>">
    <div class="wpb_column vc_column_container vc_col-sm-9 vc_col-lg-6 vc_col-md-6">
        <div class="vc_column-inner">
            <div class="wpb_wrapper">
                <div class="rt-vc-text-title  style3" style="">
                    <?php
                    if ($webpage_data) {
                        ?>
                        <h1 class="rtin-title"><?php echo htmlspecialchars($webpage_data['name']); ?></h1>
                        <p><?php echo $webpage_data['des']; ?></p>
                        <div class="rtin-btn"><a href="<?php echo $webpage_data['vdo'] ? $webpage_data['vdo'] : 'about_us'; ?>">READ MORE</a></div>
                        <?php
                    } else {
                        // Fallback content if no data found or cache miss/error
                        ?>
                        <h1 class="rtin-title">Welcome to <?php echo $SITE_NAME; ?></h1>
                        <p>We are dedicated to providing quality education and training programs to help you achieve your
                            goals. Our experienced instructors and comprehensive curriculum ensure that you receive the best
                            learning experience.</p>
                        <div class="rtin-btn"><a href="<?php echo $SITE_URL; ?>/about_us">READ MORE</a></div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="wpb_column vc_column_container vc_col-sm-3 vc_col-lg-6 vc_col-md-6">
        <div class="vc_column-inner">
            <div class="wpb_wrapper">
            </div>
        </div>
    </div>
</div>

<div class="vc_row-full-width vc_clearfix"></div>
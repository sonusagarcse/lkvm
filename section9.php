<?php
// Include database connection
require_once 'connection.php';
global $con;

// Cache logos for 30 minutes
$logos = cache_remember('logos_photos_10', function () use ($con) {
    $cid = 10;
    $sel = $con->prepare("SELECT * FROM photos WHERE cid = ? ORDER BY id DESC");
    $sel->execute([$cid]);
    $result = $sel->get_result();

    $logos = array();
    if ($result && mysqli_num_rows($result) > 0) {
        while ($res = $result->fetch_assoc()) {
            $logos[] = $res;
        }
    }
    return $logos;
}, 1800); // Cache for 30 minutes
?>
<div class="vc_row wpb_row vc_row-fluid vc_custom_1508915762011">
    <div class="wpb_column vc_column_container vc_col-sm-12">
        <div class="vc_column-inner">
            <div class="wpb_wrapper">
                <div class="rt-vc-logo-slider">
                    <div class="owl-theme owl-carousel rt-owl-carousel">
                        <?php
                        if (!empty($logos)) {
                            foreach ($logos as $res) {
                                ?>
                                <div class="rtin-item">
                                    <img src="images/photos/<?php echo htmlspecialchars($res['img']); ?>"
                                        style="height:130px; width:auto; max-width:100%; object-fit:contain; display:block; margin:0 auto;"
                                        alt="<?php echo htmlspecialchars($res['name']); ?>" decoding="async" loading="lazy" />
                                </div>
                                <?php
                            }
                        } else {
                            ?>
                            <div class="rtin-item">
                                <img src="images/photos/default-logo.jpg"
                                    style="height:130px; width:auto; max-width:100%; object-fit:contain; display:block; margin:0 auto;"
                                    alt="Default Logo" decoding="async" loading="lazy" />
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    jQuery(document).ready(function ($) {
        // Initialize logo carousel if not already initialized
        if (typeof $.fn.owlCarousel !== 'undefined') {
            $('.rt-vc-logo-slider .owl-carousel').each(function () {
                if (!$(this).hasClass('owl-loaded')) {
                    $(this).owlCarousel({
                        items: 4,
                        loop: true,
                        margin: 20,
                        nav: false,
                        dots: false,
                        autoplay: true,
                        autoplayTimeout: 5000,
                        autoplaySpeed: 200,
                        autoplayHoverPause: true,
                        responsive: {
                            0: { items: 2 },
                            480: { items: 3 },
                            768: { items: 4 },
                            992: { items: 4 },
                            1200: { items: 4 }
                        }
                    });
                }
            });
        }
    });
</script>
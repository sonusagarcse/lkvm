<?php
require_once 'connection.php';
global $con;

$testimonials = cache_remember('testimonials_10', function () use ($con) {
    $cid = 10;
    $sel = $con->prepare("SELECT * FROM webpage WHERE cid = ? ORDER BY id DESC");
    $sel->execute([$cid]);
    $result = $sel->get_result();
    $testimonials = array();
    while ($res = $result->fetch_assoc()) {
        $testimonials[] = $res;
    }
    return $testimonials;
}, 3600);
?>
<div class="vc_row wpb_row vc_row-fluid vc_custom_1508849149992">
    <div class="wpb_column vc_column_container vc_col-sm-12">
        <div class="vc_column-inner">
            <div class="wpb_wrapper">
                <div class="rt-vc-title style1">
                    <div class="vc_custom_1508849702754">
                        <h2 style="font-size:30px;">What Our Visitor Say</h2>
                    </div>
                </div>

                <div class="rt-vc-testimonial">
                    <div class="owl-carousel owl-theme rt-owl-carousel">
                        <?php if (!empty($testimonials)): foreach ($testimonials as $res): ?>
                        <div class="rtin-item">
                            <div class="rtin-content-wrap" style="background:#f8f9fa; padding:30px; border-radius:12px; border:1px solid #eee; min-height:200px;">
                                <div class="rtin-top" style="display:flex; align-items:center; gap:15px; margin-bottom:15px;">
                                    <div style="width:55px; height:55px; border-radius:50%; overflow:hidden; flex-shrink:0; border:3px solid #fff; box-shadow:0 3px 10px rgba(0,0,0,0.1);">
                                        <?php if (!empty($res['img'])): ?>
                                            <img src="images/<?php echo htmlspecialchars($res['img']); ?>" style="width:100%; height:100%; object-fit:cover;" />
                                        <?php else: ?>
                                            <div style="width:100%; height:100%; background:#eee; display:flex; align-items:center; justify-content:center; color:#ccc; font-size:20px;">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <h5 style="margin:0; font-weight:700; font-size:16px; color:#333;"><?php echo htmlspecialchars($res['name']); ?></h5>
                                        <small style="color:#6600cc; font-weight:600;"><?php echo htmlspecialchars($res['title']); ?></small>
                                    </div>
                                </div>
                                <p style="color:#666; font-size:15px; font-style:italic; line-height:1.6; margin:0;">
                                    "<?php echo strip_tags($res['des']); ?>"
                                </p>
                            </div>
                        </div>
                        <?php endforeach; else: ?>
                        <div class="text-center py-5">No testimonials yet.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
jQuery(document).ready(function ($) {
    if (typeof $.fn.owlCarousel !== 'undefined') {
        $('.rt-vc-testimonial .owl-carousel').owlCarousel({
            items: 2,
            loop: true,
            margin: 30,
            nav: false,
            dots: true,
            autoplay: true,
            autoplayTimeout: 5000,
            autoplayHoverPause: true,
            responsive: {
                0: { items: 1 },
                480: { items: 1 },
                768: { items: 2 }
            }
        });
    }
});
</script>
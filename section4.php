<?php
// Include database connection
require_once 'connection.php';
global $con;

// Cache member photos for 30 minutes
$members = cache_remember('members_photos_9', function () use ($con) {
    $cid = 9;
    $sel = $con->prepare("SELECT * FROM photos WHERE cid = ? ORDER BY id DESC");
    $sel->execute([$cid]);
    $result = $sel->get_result();

    $members = array();
    if ($result && mysqli_num_rows($result) > 0) {
        while ($res = $result->fetch_assoc()) {
            $members[] = $res;
        }
    }
    return $members;
}, 1800); // Cache for 30 minutes
?>
<div class="vc_row wpb_row vc_row-fluid vc_custom_1508848784627">
    <div class="wpb_column vc_column_container vc_col-sm-12">
        <div class="vc_column-inner">
            <div class="wpb_wrapper">
                <div class="rt-vc-instructor-4 owl-wrap rt-owl-nav-1">
                    <div class="section-title clearfix">
                        <h2 class="owl-custom-nav-title">Our Members</h2>
                        <div class="owl-custom-nav">
                            <div class="owl-prev"><i class="fas fa-angle-left"></i></div>
                            <div class="owl-next"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                    <div class="owl-theme owl-carousel rt-owl-carousel"
                        data-carousel-options="{&quot;nav&quot;:false,&quot;dots&quot;:false,&quot;autoplay&quot;:true,&quot;autoplayTimeout&quot;:&quot;5000&quot;,&quot;autoplaySpeed&quot;:&quot;200&quot;,&quot;autoplayHoverPause&quot;:true,&quot;loop&quot;:true,&quot;margin&quot;:20,&quot;responsive&quot;:{&quot;0&quot;:{&quot;items&quot;:1},&quot;480&quot;:{&quot;items&quot;:2},&quot;768&quot;:{&quot;items&quot;:3},&quot;992&quot;:{&quot;items&quot;:4}}}">
                        <?php
                        if (!empty($members)) {
                            foreach ($members as $res) {
                                ?>
                                <div class="rtin-item">
                                    <div class="rtin-img">
                                        <a href="#"><img loading="lazy" alt="<?php echo htmlspecialchars($res['name']); ?>"
                                                src="images/photos/<?php echo htmlspecialchars($res['img']); ?>"
                                                class="avatar avatar-360 photo" height="360" width="360" /></a>
                                    </div>
                                    <div class="rtin-content">
                                        <h3 class="rtin-title"><a href="#"><?php echo htmlspecialchars($res['name']); ?></a>
                                        </h3>
                                        <div class="rtin-designation"><?php echo htmlspecialchars($res['title']); ?></div>
                                    </div>
                                </div>
                                <?php
                            }
                        } else {
                            ?>
                            <div class="rtin-item">
                                <div class="rtin-img">
                                    <a href="#"><img loading="lazy" alt="Default Member"
                                            src="images/photos/default-member.jpg" class="avatar avatar-360 photo"
                                            height="360" width="360" /></a>
                                </div>
                                <div class="rtin-content">
                                    <h3 class="rtin-title"><a href="#">No Members Available</a></h3>
                                    <div class="rtin-designation">Coming Soon</div>
                                </div>
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
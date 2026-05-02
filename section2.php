<?php
// Include database connection
require_once 'connection.php';
?>
<div data-vc-full-width="true" data-vc-full-width-init="false" class="vc_row wpb_row vc_row-fluid vc_custom_1508848182497 vc_row-has-fill"><div class="wpb_column vc_column_container vc_col-sm-12"><div class="vc_column-inner"><div class="wpb_wrapper"><div class="rt-vc-course-slider owl-wrap rt-owl-nav-1 style-3">
	<div class="section-title clearfix">
		<h2 class="owl-custom-nav-title">Featured Courses</h2>
		<div class="owl-custom-nav">
			<div class="owl-prev"><i class="fas fa-angle-left"></i></div><div class="owl-next"><i class="fas fa-angle-right"></i></div>
		</div>
	</div>
	<div class="owl-theme owl-carousel rt-owl-carousel" data-carousel-options="{&quot;nav&quot;:false,&quot;dots&quot;:false,&quot;autoplay&quot;:true,&quot;autoplayTimeout&quot;:&quot;5000&quot;,&quot;autoplaySpeed&quot;:&quot;200&quot;,&quot;autoplayHoverPause&quot;:true,&quot;loop&quot;:true,&quot;margin&quot;:20,&quot;responsive&quot;:{&quot;0&quot;:{&quot;items&quot;:1},&quot;480&quot;:{&quot;items&quot;:2},&quot;768&quot;:{&quot;items&quot;:2},&quot;992&quot;:{&quot;items&quot;:3},&quot;1200&quot;:{&quot;items&quot;:4}}}">
	
<?php 
$sel = $con->prepare("SELECT * FROM courses ORDER BY id DESC");
$exe = $sel->execute();

if ($exe) {
    $result = $sel->get_result();
    
    if ($result && mysqli_num_rows($result) > 0) {
        while($res = $result->fetch_assoc()) {
?>
<div class="rt-course-box-3 post-200 lp_course type-lp_course status-publish has-post-thumbnail hentry course_category-programming course_category-technology course_tag-android course_tag-javascript course">
<div class="rtin-thumbnail hvr-bounce-to-right">
<center>
<img style="width:100%;height:160px;" src="images/courses/<?php echo $res['img'];?>" class="attachment-rdtheme-size2 size-rdtheme-size2 wp-post-image" alt="<?php echo $res['name'];?>" decoding="async" loading="lazy" /> <a href="<?php echo $SITE_URL;?>/course_info?coursep=<?php echo $res['id'];?>" title="<?php echo $res['name'];?>"><i class="fas fa-link" aria-hidden="true"></i> </a>
</center>
</div>
<div class="rtin-content-wrap">
<div class="rtin-content">
<h3 class="rtin-title" style="text-align:center;"><a href="<?php echo $SITE_URL;?>/course_info?coursep=<?php echo $res['id'];?>" title="<?php echo $res['name'];?>"><?php echo $res['name'];?></a></h3>
</div>
</div>
<div class="clear"></div>
</div>							
<?php 
        }
    } else {
        // Fallback content if no courses found
?>
<div class="rt-course-box-3 post-200 lp_course type-lp_course status-publish has-post-thumbnail hentry course_category-programming course_category-technology course_tag-android course_tag-javascript course">
<div class="rtin-thumbnail hvr-bounce-to-right">
<center>
<img style="width:100%;height:160px;" src="images/courses/default-course.jpg" class="attachment-rdtheme-size2 size-rdtheme-size2 wp-post-image" alt="Default Course" decoding="async" loading="lazy" />
</center>
</div>
<div class="rtin-content-wrap">
<div class="rtin-content">
<h3 class="rtin-title" style="text-align:center;">No Courses Available</h3>
</div>
</div>
<div class="clear"></div>
</div>
<?php
    }
} else {
    // Fallback content if database error
?>
<div class="rt-course-box-3 post-200 lp_course type-lp_course status-publish has-post-thumbnail hentry course_category-programming course_category-technology course_tag-android course_tag-javascript course">
<div class="rtin-thumbnail hvr-bounce-to-right">
<center>
<img style="width:100%;height:160px;" src="images/courses/default-course.jpg" class="attachment-rdtheme-size2 size-rdtheme-size2 wp-post-image" alt="Default Course" decoding="async" loading="lazy" />
</center>
</div>
<div class="rtin-content-wrap">
<div class="rtin-content">
<h3 class="rtin-title" style="text-align:center;">Courses Coming Soon</h3>
</div>
</div>
<div class="clear"></div>
</div>
<?php
}
?>	
	
</div>
</div> </div></div></div></div>

<div class="vc_row-full-width vc_clearfix"></div>
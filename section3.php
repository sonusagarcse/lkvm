<?php
require_once 'connection.php';
global $con;

$website_links = cache_remember('home_website_links', function () use ($con) {
    $res = mysqli_query($con, "SELECT * FROM home_sections WHERE section_type = 'visit_website' AND status = 1 ORDER BY sort_order ASC");
    $data = [];
    while ($row = mysqli_fetch_assoc($res)) {
        $data[] = $row;
    }
    return $data;
}, 3600);
?>
<div data-vc-full-width="true" data-vc-full-width-init="false" class="vc_row wpb_row vc_row-fluid campus-tour-1 vc_custom_1515755231129 vc_row-has-fill">
<div class="wpb_column vc_column_container vc_col-sm-12">
<div class="vc_column-inner">
    <div class="wpb_wrapper">
        <div class="rt-vc-video rt-dark ">
	<div class="rtin-item">
	<h2 class="rtin-title">Visit Our Website</h2>
	</div>
</div>
        <style>
.col-md-3{margin-top:10px; margin-bottom:10px;}
.img-box img {
transition: transform .5s ease;
}
.img-box:hover img {
transform: scale(1.1);
}
.our-cources1 {
display: block;
background: url(images/business-bg.jpg) no-repeat center top / cover;
position: relative;
}
</style>
<div class="row ">
		  <?php if (!empty($website_links)): foreach ($website_links as $link): ?>
		  <div class="col-md-3">
		  <a class="example-image-link" href="<?php echo htmlspecialchars($link['link']); ?>" data-lightbox="example-set">
		  <div class="img-box" style="background:#fff; border: dotted 5px #fff; box-shadow: 0px 0px 3px 0px #9d9d9d; padding:20px; height:170px;">
		  <center>
		  <img src="images/<?php echo htmlspecialchars($link['icon']); ?>" style="width:<?php echo (strpos($link['icon'], 'talk') !== false) ? '120px' : '80px'; ?>; height:80px; object-fit: contain;">
		  <h3 style="text-align:center; font-weight:700; margin:0px; vertical-align:middle; font-size:15px; color:#993366;"><?php echo htmlspecialchars($link['title']); ?></h3>
		  </center>
		  </div>
		 </a>
		  </div>
		  <?php endforeach; else: ?>
		  <div class="col-12 text-center text-white py-4">No links available.</div>
		  <?php endif; ?>
</div>
</div>
</div>
</div>
</div>
<div class="vc_row-full-width vc_clearfix"></div>
<div class="vc_row-full-width vc_clearfix"></div>
<!doctype html>
<html lang="en-US">
<head>
<?php include('csslink.php'); ?>
<?php

// Include database connection
require_once 'connection.php';

$id = 30;
$sel = $con->prepare("SELECT * FROM webpage WHERE id = ? ORDER BY id DESC");
$exe = $sel->execute([$id]);

if ($exe) {
    $result = $sel->get_result();
    $res = $result->fetch_assoc();
}
else {
    $res = null;
}
?> 
<style id='eikra-style-inline-css' type='text/css'>
.entry-banner {
background: url(images/banner.jpg) no-repeat scroll center center / cover;
}
.content-area {
padding-top: 50px;
padding-bottom: 0px;
}
#learn-press-block-content span {
background-image: url("images/preloader.gif");
}
		
</style>
</head>
<body class="home page-template-default page page-id-54 wp-embed-responsive theme-eikra woocommerce-no-js Eikra-version-4.4.6 header-style-9 footer-style-2 has-topbar topbar-style-7 no-sidebar rt-course-grid-view product-grid-view wpb-js-composer js-comp-ver-6.9.0 vc_responsive">
<?//php include('loader.php');?>
<div id="page" class="site">
<?php include('header.php'); ?>

<div id="content" class="site-content">
<div class="entry-banner">
<div class="container">
<div class="entry-banner-content">
<h1 class="entry-title"><?php echo $res ? $res['name'] : 'Education'; ?></h1>
<div class="breadcrumb-area"><div class="entry-breadcrumb"><!-- Breadcrumb NavXT 7.2.0 -->
<span property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage" title="Home" href="<?php echo $SITE_URL; ?>" class="home" ><span property="name">Home</span></a><meta property="position" content="1"></span> &gt; <span property="itemListElement" typeof="ListItem"><span property="name" class="post post-page current-item"><?php echo $res ? $res['name'] : 'Education'; ?></span><meta property="url" content="#"><meta property="position" content="2"></span></div></div>
</div>
</div>
</div>

<div id="primary" class="content-area">
<div class="container">
<div class="row">
<div class="col-sm-12 col-12">
<main id="main" class="site-main">
<article id="post-1225" class="post-1225 page type-page status-publish hentry">
<div class="entry-content">
<div class="vc_row wpb_row vc_row-fluid vc_custom_1510748400939">
<div class="wpb_column vc_column_container vc_col-sm-12">
<div class="vc_column-inner">
<div class="wpb_wrapper">
<div class="wpb_text_column wpb_content_element " >
<div class="wpb_wrapper">
<p><?php if ($res && $res['des']): ?>
<p><?php echo $res['des']; ?></p>
<?php
else: ?>
<p>Content for this page is coming soon. Please check back later for updated information.</p>
<?php
endif; ?>	</p>
</div>
</div>

</div>
</div>
</div>
</div>

<div class="vc_row-full-width vc_clearfix"></div>
</div>

</article>
</main>

</div>
</div>
</div>
</div>
</div>
<!-- #content -->

<?php include('footer.php'); ?>
</body>
</html>
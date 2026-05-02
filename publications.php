<!doctype html>
<html lang="en-US">
<head>
<?php include('csslink.php');?>
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
<?php include('header.php');?>

<div id="content" class="site-content">
<div class="entry-banner">
<div class="container">
<div class="entry-banner-content">
<h1 class="entry-title">Publications</h1>
<div class="breadcrumb-area"><div class="entry-breadcrumb"><!-- Breadcrumb NavXT 7.2.0 -->
<span property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage" title="Home" href="<?php echo $SITE_URL;?>" class="home" ><span property="name">Home</span></a><meta property="position" content="1"></span> &gt; <span property="itemListElement" typeof="ListItem"><span property="name" class="post post-page current-item">Publications</span><meta property="url" content="#"><meta property="position" content="2"></span></div></div>
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

   <style>
.col-md-3{margin-top:10px; margin-bottom:10px;}	
.img-box img {
transition: transform .5s ease;
}
.img-box:hover img {
  transform: scale(1.1);
}
</style>

<div class="row ">
      <div class="col-lg-12 col-md-12 col-sm-12 about_cont_blog">
        <div class="full text_align_left">
           <div class="row ">
		   <link rel="stylesheet" href="css/lightbox.min.css">
<script src="js/lightbox-plus-jquery.min.js"></script>
		  <?php 
		$pcid=19; 
		$status=1;
		$sel=$con->prepare("select * from photos where cid=? and status=? order by id DESC");
		$exe=$sel->execute([$pcid,$status]);
		while($res=$sel->fetch())
		{
		?>
		 <div class="col-md-3">
		 <div class="img-box" style="box-shadow: 0px 0px 3px 0px #9d9d9d; border:solid 5px #fff;">
		 <center>
		  <a class="example-image-link" href="images/photos/<?php echo $res->img;?>" data-lightbox="example-set">
		  <img src="images/photos/<?php echo $res->img;?>" class="img-responsive1" style="width:100%;height:170px; border: solid 5px #fff;">
		 </a>
		 </center>
		 </div>
		  </div>
         <?php }?>
		 </div>
		 
        </div>
      </div>
   
    </div>



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

<?php include('footer.php');?>
</body>
</html>
<!doctype html>
<html lang="en-US">
<head>
<?php include('csslink.php'); ?>
<?php

$id = 13;
$sel = $con->prepare("select * from webpage where id=? order by id DESC");
$sel->bind_param("i", $id);
$sel->execute();
$res = $sel->get_result()->fetch_object();
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
<h1 class="entry-title">Login</h1>
<div class="breadcrumb-area"><div class="entry-breadcrumb"><!-- Breadcrumb NavXT 7.2.0 -->
<span property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage" title="Home" href="<?php echo $SITE_URL; ?>" class="home" ><span property="name">Home</span></a><meta property="position" content="1"></span> &gt; <span property="itemListElement" typeof="ListItem"><span property="name" class="post post-page current-item">Login</span><meta property="url" content="#"><meta property="position" content="2"></span></div></div>
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


<div class="row">
                 <div class="col-sm-3 ">
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
				<style>
				.login-form form {
    text-align: left;
    padding: 30px;
    border: 1px solid #f1f1f1;
    box-shadow: 1px 1px 20px #cccccc57;
}
.form-title {
    text-align: center;
    margin-bottom: 30px;
}
.form-title h2{
    margin-bottom: 20px;
    padding-bottom: 15px;
}

 h2{
    font-weight: 800;
    color: #212121;
    font-family: 'Raleway', sans-serif;
    margin-top: 0;
    line-height: 1.5;
    margin-bottom: 15px;
	text-align:center;
	font-size: 32px;
}
label {
    display: inline-block;
    color: #666;
    margin-bottom: 8px;
    font-weight: 400;
    font-size: 15px;
}
input[type=text], input[type=email], input[type=number], input[type=search], input[type=password], input[type=tel], textarea, select {
    font-size: 14px;
    font-weight: 300;
    background-color: #fff;
    border: 1px solid #060606;
    border-radius: 0;
    padding: 10px 15px;
    width: 100%;
    color: #444444;
    margin-bottom: 15px;
    font-family: 'Poppins', sans-serif;
    height: 42px;
    box-shadow: none;
    margin-bottom: 0;
}
.form-control {
    display: block;
    width: 100%;
    height: 34px;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.42857143;
    color: #555;
    background-color: #fff;
    background-image: none;
    border: 1px solid #2b2929;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgb(0 0 0 / 8%);
    box-shadow: inset 0 1px 1px rgb(0 0 0 / 8%);
    -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
    -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
}
.form-group {
    margin-bottom: 15px;
}
				</style>
                    <div class="login-form">
                        <form action="code/user_login" method="post">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-title">
                                        <h2>Login</h2>
                                         
                                    </div>
                                </div>
								</div>
								
                       <div class="row">         
					   <div class="form-group col-md-12">
					<?php
if (isset($_REQUEST['msg'])) {
    if ($_REQUEST['msg'] == 'error') { ?>
		  			<strong style="color:#FF0000;">"Oops!", "Your Details  Are Wrong!", "error"</strong><br/>
	  				 <?php
    }
}?>                        
   							<label>Enrollment Number</label>
                                    <input type="text" class="form-control" id="Name1" placeholder="Enter Your Enrollment Number" name="regno" required>
                                </div>
                                <div class="form-group col-md-12"> 
                                    <label>Password</label>
                                    <input type="password" class="form-control" id="email1" placeholder="Enter Your Password" name="password" required>
                                </div>
                                <div class="col-md-12">
                                    <div class="comment-btn">
                                        <button class="wpcf7-form-control has-spinner wpcf7-submit rdtheme-button-2">Login</button>
                                    </div>
                                </div>
                              
                        </form>
                    </div>
                </div>
                <div class="col-sm-3 ">
                    
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

<?php include('footer.php'); ?>
</body>
</html>
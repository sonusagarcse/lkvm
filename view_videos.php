<!doctype html>
<html lang="en-US">

<head>
	<?php include('csslink.php'); ?>
	<?php
	$vcid = (int) $_REQUEST['vcid'];
	$status = 1;

	$sel = $con->prepare("SELECT * FROM video_category WHERE id = ? AND status = ? ORDER BY id DESC");
	$sel->bind_param("ii", $vcid, $status);
	$sel->execute();
	$result = $sel->get_result();
	$res_5 = $result->fetch_assoc();
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

<body
	class="home page-template-default page page-id-54 wp-embed-responsive theme-eikra woocommerce-no-js Eikra-version-4.4.6 header-style-9 footer-style-2 has-topbar topbar-style-7 no-sidebar rt-course-grid-view product-grid-view wpb-js-composer js-comp-ver-6.9.0 vc_responsive">
	<?//php include('loader.php'); ?>
	<div id="page" class="site">
		<?php include('header.php'); ?>

		<div id="content" class="site-content">
			<div class="entry-banner">
				<div class="container">
					<div class="entry-banner-content">
						<h1 class="entry-title"><?php echo htmlspecialchars($res_5['name']); ?></h1>
						<div class="breadcrumb-area">
							<div class="entry-breadcrumb"><!-- Breadcrumb NavXT 7.2.0 -->
								<span property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage"
										title="Home" href="<?php echo $SITE_URL; ?>" class="home"><span
											property="name">Home</span></a>
									<meta property="position" content="1">
								</span> &gt; <span property="itemListElement" typeof="ListItem"><span property="name"
										class="post post-page current-item"><?php echo htmlspecialchars($res_5['name']); ?></span>
									<meta property="url" content="#">
									<meta property="position" content="2">
								</span>
							</div>
						</div>
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
														<div class="wpb_text_column wpb_content_element ">
															<div class="wpb_wrapper">


																<div class="row ">
																	<style>
																		.img-responsive1 {
																			height: 170px;
																			width: 100%;
																		}
																	</style>
																	<style>
																		.image-box img {
																			transition: transform .5s ease;
																		}

																		/* [3] Finally, transforming the image when container gets hovered */
																		.image-box:hover img {
																			transform: scale(1.1);
																		}
																	</style>

																	<?php
																	$sel = $con->prepare("select * from videos where cid=? order by id DESC");
																	$sel->bind_param("i", $vcid);
																	$sel->execute();
																	$result = $sel->get_result();
																	while ($res = $result->fetch_assoc()) {
																		?>
																		<div class="col-md-3">
																			<div class="image-box"
																				style="box-shadow: 0px 0px 3px 0px #9d9d9d; border:solid 5px #fff;">
																				<iframe width="100%" height="170"
																					src="<?php echo $res['vdo']; ?>"
																					frameborder="0"
																					allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
																					allowfullscreen></iframe><br />
																			</div>
																		</div>
																	<?php } ?>
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
</body></html>
<!doctype html>
<html lang="en-US">

<head>
	<?php
$page_title = "Contact Us";
include('csslink.php');
?>
	<style id='eikra-style-inline-css' type='text/css'>
		.entry-banner {
			background: url(images/banner.jpg) no-repeat scroll center center / cover;
		}

		.content-area {
			padding-top: 100px;
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
						<h1 class="entry-title">Contact us</h1>
						<div class="breadcrumb-area">
							<div class="entry-breadcrumb"><!-- Breadcrumb NavXT 7.2.0 -->
								<span property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage"
										title="Go to Home Page." href="<?php echo $SITE_URL; ?>" class="home"><span
											property="name">Home</span></a>
									<meta property="position" content="1">
								</span> &gt; <span property="itemListElement" typeof="ListItem"><span property="name"
										class="post post-page current-item">Contact us</span>
									<meta property="url"
										content="https://radiustheme.com/demo/wordpress/eikra/contact-1/">
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
								<article id="post-1111" class="post-1111 page type-page status-publish hentry">
									<div class="entry-content">
										<div class="vc_row wpb_row vc_row-fluid">
											<div class="wpb_column vc_column_container vc_col-sm-4">
												<div class="vc_column-inner vc_custom_1509100691886">
													<div class="wpb_wrapper">
														<div class="rt-vc-contact-1 ">
															<ul class="rtin-item">
																<li>
																	<i class="fas fa-map-marker-alt"
																		aria-hidden="true"></i>
																	<h3>Address</h3>
																	<p><?php echo $ADDRESS; ?></p>
																</li>
																<li>
																	<i class="far fa-envelope" aria-hidden="true"></i>
																	<h3>E-mail</h3>
																	<p><?php echo $CONTACT_EMAIL; ?></p>
																</li>

																<li>
																	<i class="fas fa-phone-alt" aria-hidden="true"></i>
																	<h3>Phone</h3>
																	<p><?php echo $CONTACT_MOBILE; ?></p>
																</li>
																<li>
																	<h3>Find Us On</h3>
																	<ul class="contact-social">
																		<?php if (!empty($settings['facebook_url'])) { ?>
																			<li><a target="_blank" href="<?php echo htmlspecialchars($settings['facebook_url']); ?>"><i class="fab fa-facebook-f"></i></a></li>
																		<?php
}?>
																		<?php if (!empty($settings['instagram_url'])) { ?>
																			<li><a target="_blank" href="<?php echo htmlspecialchars($settings['instagram_url']); ?>"><i class="fab fa-instagram"></i></a></li>
																		<?php
}?>
																		<?php if (!empty($settings['twitter_url'])) { ?>
																			<li><a target="_blank" href="<?php echo htmlspecialchars($settings['twitter_url']); ?>"><i class="fab fa-twitter"></i></a></li>
																		<?php
}?>
																		<?php if (!empty($settings['google_plus_url'])) { ?>
																			<li><a target="_blank" href="<?php echo htmlspecialchars($settings['google_plus_url']); ?>"><i class="fab fa-google-plus-g"></i></a></li>
																		<?php
}
else { ?>
																			<li><a target="_blank" href="https://goo.gl/maps/hFVKTvRoU2d2evNN9"><i class="fab fa-google-plus-g"></i></a></li>
																		<?php
}?>
																		<?php if (!empty($settings['youtube_url'])) { ?>
																			<li><a target="_blank" href="<?php echo htmlspecialchars($settings['youtube_url']); ?>"><i class="fab fa-youtube"></i></a></li>
																		<?php
}?>
																		<?php if (!empty($settings['linkedin_url'])) { ?>
																			<li><a target="_blank" href="<?php echo htmlspecialchars($settings['linkedin_url']); ?>"><i class="fab fa-linkedin-in"></i></a></li>
																		<?php
}?>
																	</ul>
																</li>

															</ul>
														</div>
													</div>
												</div>
											</div>

											<div class="wpb_column vc_column_container vc_col-sm-8">
												<div class="vc_column-inner">
													<div class="wpb_wrapper">
														<h2 style="font-size: 30px;color: #002147;line-height: 1.5;text-align: justify"
															class="vc_custom_heading">Contact With Us</h2>
														<div
															class="vc_separator wpb_content_element vc_separator_align_center vc_sep_width_10 vc_sep_border_width_3 vc_sep_pos_align_left vc_separator_no_text">
															<span class="vc_sep_holder vc_sep_holder_l"><span
																	style="border-color:#fdc800;"
																	class="vc_sep_line"></span></span><span
																class="vc_sep_holder vc_sep_holder_r"><span
																	style="border-color:#fdc800;"
																	class="vc_sep_line"></span></span>
														</div>
														<div class="wpcf7 no-js" id="wpcf7-f1976-p1111-o1" lang="en-US"
															dir="ltr">
															<div class="screen-reader-response">
																<p role="status" aria-live="polite" aria-atomic="true">
																</p>
																<ul></ul>
															</div>

															<?php
if (isset($_REQUEST['msg'])) {
	if ($_REQUEST['msg'] == 'send') {
?>
																	<h5 style="color:red; text-align:center;"> Thank You! <br>
																		<strong style="color:#000;">Your Message Submitted
																			Successfully <br /> We will contact you
																			soon.</strong> <a href="contact_us"
																			style="color:blue;">Click Here</a></h5>

																	<?php
	}
}
else { ?>

																<form action="code/contact" method="post"
																	class="wpcf7-form init" data-status="init">

																	<div class="contact-us-form">
																		<div class="row">
																			<div class="col-lg-6 col-md-6 col-sm-6">
																				<div class="form-group">
																					<p><span class="wpcf7-form-control-wrap"
																							data-name="text-215"><input
																								size="40"
																								class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required form-control"
																								required=""
																								placeholder="Name*" value=""
																								type="text"
																								name="name" /></span>
																					</p>
																				</div>
																			</div>
																			<div class="col-lg-6 col-md-6 col-sm-6">
																				<div class="form-group">
																					<p><span class="wpcf7-form-control-wrap"
																							data-name="email-788"><input
																								size="40"
																								class="wpcf7-form-control wpcf7-text wpcf7-email wpcf7-validates-as-required wpcf7-validates-as-email form-control"
																								required=""
																								placeholder="Email*"
																								value="" type="email"
																								name="email" /></span>
																					</p>
																				</div>
																			</div>
																			<div class="col-lg-6 col-md-6 col-sm-6">
																				<div class="form-group">
																					<p><span class="wpcf7-form-control-wrap"
																							data-name="text-216"><input
																								size="40"
																								class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required form-control"
																								aria-required="true"
																								aria-invalid="false"
																								placeholder="Subject*"
																								value="" type="text"
																								name="sub" /></span>
																					</p>
																				</div>
																			</div>
																			<div class="col-lg-6 col-md-6 col-sm-6">
																				<div class="form-group">
																					<p><span class="wpcf7-form-control-wrap"
																							data-name="tel-871"><input
																								size="40"
																								class="wpcf7-form-control wpcf7-text wpcf7-tel wpcf7-validates-as-tel form-control"
																								aria-invalid="false"
																								placeholder="Phone" value=""
																								type="tel"
																								name="mob" /></span>
																					</p>
																				</div>
																			</div>
																			<div class="col-lg-6 col-md-6 col-sm-6">
																				<div class="form-group">
																					<p><span class="wpcf7-form-control-wrap"
																							data-name="text-loc"><input
																								size="40"
																								class="wpcf7-form-control wpcf7-text form-control"
																								placeholder="Location"
																								value="" type="text"
																								name="loc" /></span>
																					</p>
																				</div>
																			</div>
																			<div class="col-lg-6 col-md-6 col-sm-6">
																				<div class="form-group">
																					<p><span
																							class="wpcf7-form-control-wrap">
																							<select name="gender"
																								class="form-control">
																								<option value="">Select
																									Gender</option>
																								<option value="Male">Male
																								</option>
																								<option value="Female">
																									Female</option>
																							</select>
																						</span>
																					</p>
																				</div>
																			</div>
																			<div class="col-lg-12 col-md-12 col-sm-12">
																				<div class="form-group">
																					<p><span class="wpcf7-form-control-wrap"
																							data-name="textarea-349"><textarea
																								cols="20" rows="7"
																								class="wpcf7-form-control wpcf7-textarea wpcf7-validates-as-required textarea form-control"
																								aria-required="true"
																								aria-invalid="false"
																								placeholder="Message*"
																								name="msg"></textarea></span>
																					</p>
																				</div>
																			</div>
																			<div class="col-lg-12 col-md-12 col-sm-12">
																				<p><input
																						class="wpcf7-form-control has-spinner wpcf7-submit rdtheme-button-2"
																						type="submit" name="submit"
																						value="SEND MESSAGE" />
																				</p>
																			</div>
																		</div>
																	</div>
																</form>
															<?php
}?>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</article>
							</main>
						</div>
					</div>
				</div>
			</div>
			<!-- Google Map Section -->
			<?php if (!empty($settings['map'])): ?>
			<div class="google-map-section mt-5">
				<div class="p-0">
					<?php echo $settings['map']; ?>
				</div>
			</div>
			<style>
				.google-map-section iframe {
					width: 100% !important;
					height: 450px !important;
					border: 0 !important;
					display: block;
				}
			</style>
			<?php
endif; ?>
		</div>
		<!-- #content -->
		<?php include('footer.php'); ?>
</body>

</html>
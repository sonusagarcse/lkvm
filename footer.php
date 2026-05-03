<?php
// Include database connection
require_once 'connection.php';

// Define contact variables from global settings
$ADDRESS = $ADDRESS ?? $settings['address'] ?? 'Address not available';
$MOBILE = $CONTACT_MOBILE ?? $settings['mobile'] ?? '+91 9801664500';
$EMAIL_ID = $CONTACT_EMAIL ?? $settings['email'] ?? 'lkvmimamganj@gmail.com';
?>
<footer class="site-footer-wrap">
    <div class="footer-top-area">
        <div class="container">
            <div class="row">
                <div id="nav_menu-5" class="widget col-lg-3 col-md-6 col-12 widget_nav_menu">
                    <h3 class="widgettitle">Who We Are</h3>
                    <div class="menu-company-container">
                        <ul id="menu-company" class="menu">
                            <li id="menu-item-2050"
                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-2050"><a
                                    href="<?php echo $SITE_URL; ?>/about_us">About us</a></li>
                            <li id="menu-item-2051"
                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-2051"><a
                                    href="<?php echo $SITE_URL; ?>/vision_mission">Vision & Mission</a></li>
                            <li id="menu-item-2052"
                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-2052"><a
                                    href="<?php echo $SITE_URL; ?>/our_team">Our Team</a></li>
                            <li id="menu-item-2053"
                                class="menu-item menu-item-type-custom menu-item-object-custom menu-item-2053"><a
                                    href="<?php echo $SITE_URL; ?>/annual_report">Annual Report</a></li>
                            <li id="menu-item-2054"
                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-2054"><a
                                    href="<?php echo $SITE_URL; ?>/where_we_work">Where We Work</a></li>
                            <li id="menu-item-2044"
                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-2044"><a
                                    href="<?php echo $SITE_URL; ?>/photo_gallery">Photo Gallery</a></li>
                            <li id="menu-item-2045"
                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-2045"><a
                                    href="<?php echo $SITE_URL; ?>/video_gallery">Video Gallery</a></li>
                        </ul>
                    </div>
                </div>

                <div id="nav_menu-5" class="widget col-lg-3 col-md-6 col-12 widget_nav_menu">
                    <h3 class="widgettitle">Our Work</h3>
                    <div class="menu-company-container">
                        <ul id="menu-company" class="menu">
                            <li id="menu-item-2050"
                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-2050"><a
                                    href="https://education.lkvmbihar.in/">Education</a></li>
                            <li id="menu-item-2051"
                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-2051"><a
                                    href="<?php echo $SITE_URL; ?>/skill_development">Skill Development</a></li>
                            <li id="menu-item-2052"
                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-2052"><a
                                    href="<?php echo $SITE_URL; ?>/health">Health</a></li>
                            <li id="menu-item-2053"
                                class="menu-item menu-item-type-custom menu-item-object-custom menu-item-2053"><a
                                    href="<?php echo $SITE_URL; ?>/livelihood">Livelihood</a></li>
                            <li id="menu-item-2054"
                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-2054"><a
                                    href="<?php echo $SITE_URL; ?>/environment">Environment</a></li>
                            <li id="menu-item-2044"
                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-2044"><a
                                    href="<?php echo $SITE_URL; ?>/media_room">Media Room</a></li>
                            <li id="menu-item-2048"
                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-2048"><a
                                    href="<?php echo $SITE_URL; ?>/donate_us">Donate us</a></li>
                        </ul>
                    </div>
                </div>

                <div id="nav_menu-6" class="widget col-lg-3 col-md-6 col-12 widget_nav_menu">
                    <h3 class="widgettitle">Useful Links</h3>
                    <div class="menu-explore-courses-container">
                        <ul id="menu-explore-courses" class="menu">
                            <li id="menu-item-2046"
                                class="menu-item menu-item-type-post_type menu-item-object-lp_course menu-item-2046"><a
                                    href="<?php echo $SITE_URL; ?>/privacy_policy">Privacy Policy</a></li>
                            <li id="menu-item-2046"
                                class="menu-item menu-item-type-post_type menu-item-object-lp_course menu-item-2046"><a
                                    href="<?php echo $SITE_URL; ?>/terms_conditions">Terms & Conditions</a></li>
                            <li id="menu-item-2046"
                                class="menu-item menu-item-type-post_type menu-item-object-lp_course menu-item-2046"><a
                                    href="<?php echo $SITE_URL; ?>/shipping_policy">Shipping Policy</a></li>
                            <li id="menu-item-2046"
                                class="menu-item menu-item-type-post_type menu-item-object-lp_course menu-item-2046"><a
                                    href="<?php echo $SITE_URL; ?>/refund">Refund & Cancellation </a></li>

                            <!--<li id="menu-item-2046" class="menu-item menu-item-type-post_type menu-item-object-lp_course menu-item-2046"><a href="<?php echo $SITE_URL; ?>/branch" target="_blank">Center Login</a></li>-->
                            <li id="menu-item-2048"
                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-2048"><a
                                    href="<?php echo $SITE_URL; ?>/admin" target="_blank">Admin Login</a></li>
                            <li id="menu-item-2049"
                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-2049"><a
                                    href="<?php echo $SITE_URL; ?>/coordinator" target="_blank">Centre Coordinator Login</a></li>
                        </ul>
                    </div>
                </div>

                <div id="rdtheme_info-3" class="widget col-lg-3 col-md-6 col-12 widget_rdtheme_info">
                    <h3 class="widgettitle">Contacts</h3>
                    <ul>
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <span class="d-none info-label">Location :</span>
                            <div class="info-item"><?php echo $ADDRESS; ?></div>
                        </li>

                        <li>
                            <i class="fas fa-phone-alt"></i>
                            <span class="d-none info-label">Call Us :</span>
                            <div class="info-item">
                                <a href="tel:<?php echo $MOBILE; ?>"><?php echo $MOBILE; ?></a>
                            </div>
                        </li>

                        <li>
                            <i class="fas fa-envelope"></i>
                            <span class="d-none info-label">Mail Us :</span>
                            <div class="info-item">
                                <a href="mailto:<?php echo $EMAIL_ID; ?>"><?php echo $EMAIL_ID; ?></a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-social-wrapper">
        <div class="social-icon">
            <?php if (!empty($settings['facebook_url'])) { ?>
                <a target="_blank" href="<?php echo htmlspecialchars($settings['facebook_url']); ?>">
                    <i class="fab fa-facebook-f"></i>
                </a>
            <?php } ?>
            <?php if (!empty($settings['instagram_url'])) { ?>
                <a target="_blank" href="<?php echo htmlspecialchars($settings['instagram_url']); ?>">
                    <i class="fab fa-instagram"></i>
                </a>
            <?php } ?>
            <?php if (!empty($settings['twitter_url'])) { ?>
                <a target="_blank" href="<?php echo htmlspecialchars($settings['twitter_url']); ?>">
                    <i class="fab fa-twitter"></i>
                </a>
            <?php } ?>
            <?php if (!empty($settings['youtube_url'])) { ?>
                <a target="_blank" href="<?php echo htmlspecialchars($settings['youtube_url']); ?>">
                    <i class="fab fa-youtube"></i>
                </a>
            <?php } ?>
            <?php if (!empty($settings['linkedin_url'])) { ?>
                <a target="_blank" href="<?php echo htmlspecialchars($settings['linkedin_url']); ?>">
                    <i class="fab fa-linkedin-in"></i>
                </a>
            <?php } ?>
        </div>
    </div>

    <div class="footer-bottom-area">
        <div class="container">
            <div class="row">

                <div class="col-sm-12 footer-bottom-inner" style="display: inline;">
                    <center>

                        <div class="footer-copyright-text">&copy; Copyright <?php echo $SITE_NAME; ?>
                            <?php echo date('Y'); ?>. Designed and Developed by <a rel="nofollow" target="_blank"
                                href="<?php echo $SITE_URL; ?>"><?php echo $SITE_NAME; ?></a></div>
                    </center>
                </div>

            </div>
        </div>
    </div>
</footer>
</div>
<!-- #page -->
<a href="#" class="scrollToTop"><i class="fas fa-arrow-up"></i></a>
<link rel='stylesheet' id='redux-custom-fonts-css-css' href='css/fonts.css?ver=1678078416' type='text/css'
    media='all' />
<link rel='stylesheet' id='owl-carousel-css' href='css/owl.carousel.min.css?ver=4.4.6' type='text/css' media='all' />
<link rel='stylesheet' id='owl-theme-default-css' href='css/owl.theme.default.min.css?ver=4.4.6' type='text/css'
    media='all' />
<link rel='stylesheet' id='dashicons-css' href='css/dashicons.min.css?ver=6.1.1' type='text/css' media='all' />
<style id='dashicons-inline-css' type='text/css'>
    [data-font="Dashicons"]:before {
        font-family: 'Dashicons' !important;
        content: attr(data-icon) !important;
        speak: none !important;
        font-weight: normal !important;
        font-variant: normal !important;
        text-transform: none !important;
        line-height: 1 !important;
        font-style: normal !important;
        -webkit-font-smoothing: antialiased !important;
        -moz-osx-font-smoothing: grayscale !important;
    }
</style>
<link rel='stylesheet' id='magnific-popup-css' href='css/magnific-popup.min.css?ver=4.4.6' type='text/css'
    media='all' />
<link rel='stylesheet' id='photoswipe-css' href='css/photoswipe.min.css?ver=7.4.1' type='text/css' media='all' />
<link rel='stylesheet' id='photoswipe-default-skin-css' href='css/default-skin.min.css?ver=7.4.1' type='text/css'
    media='all' />
<script type='text/javascript' src='js/wishlist.min.js?ver=4.0.5' id='lp-course-wishlist-js'></script>
<script type='text/javascript' src='js/jquery.selectBox.min.js?ver=1.2.0' id='jquery-selectBox-js'></script>
<script type='text/javascript' src='js/jquery.prettyPhoto.min.js?ver=3.1.6' id='prettyPhoto-js'></script>
<script type='text/javascript' src='js/jquery.yith-wcwl.min.js?ver=3.18.0' id='jquery-yith-wcwl-js'></script>
<script type='text/javascript' src='js/theme-my-login.min.js?ver=7.1.5' id='theme-my-login-js'></script>
<script type='text/javascript' src='js/js.cookie.min.js?ver=2.1.4-wc.7.4.1' id='js-cookie-js'></script>
<script type='text/javascript' src='js/woocommerce.min.js?ver=7.4.1' id='woocommerce-js'></script>
<script type='text/javascript' src='js/cart-fragments.min.js?ver=7.4.1' id='wc-cart-fragments-js'></script>
<script type='text/javascript' src='js/frontend.min.js?ver=1.24.0' id='yith-wcqv-frontend-js'></script>
<script type='text/javascript' src='js/bootstrap.min.js?ver=4.4.6' id='bootstrap-js'></script>
<script type='text/javascript' src='js/jquery.nav.min.js?ver=4.4.6' id='jquery-nav-js'></script>
<script type='text/javascript' id='eikra-main-js-extra'>
    /* <![CDATA[ */
    var EikraObj = { "ajaxurl": "#", "hasAdminBar": "0", "headerStyle": "9", "stickyMenu": "1", "primaryColor": "#002147", "seconderyColor": "#fdc800", "day": "Day", "hour": "Hour", "minute": "Minute", "second": "Second", "extraOffset": "70", "extraOffsetMobile": "52", "rtl": "no", "vcRtl": "no" };
    /* ]]> */
</script>
<script type='text/javascript' src='js/main.js?ver=4.4.6' id='eikra-main-js'></script>
<script type='text/javascript' id='wpb_composer_front_js-js-extra'>
    /* <![CDATA[ */
    var vcData = { "currentTheme": { "slug": "eikra" } };
    /* ]]> */
</script>
<script type='text/javascript' src='js/js_composer_front.min.js?ver=6.9.0' id='wpb_composer_front_js-js'></script>
<script type='text/javascript' src='js/owl.carousel.min.js?ver=4.4.6' id='owl-carousel-js'></script>
<script type='text/javascript' src='js/jquery.magnific-popup.min.js?ver=4.4.6' id='magnific-popup-js'></script>
<script type='text/javascript' src='js/waypoints.min.js?ver=4.4.6' id='waypoints-js'></script>
<script type='text/javascript' src='js/jquery.counterup.min.js?ver=4.4.6' id='counterup-js'></script>
<script type='text/javascript' id='layerslider-utils-js-extra'>
    /* <![CDATA[ */
    var LS_Meta = { "v": "7.2.5", "fixGSAP": "1" };
    /* ]]> */
</script>
<script type='text/javascript' src='js/layerslider.utils.js?ver=7.2.5' id='layerslider-utils-js'></script>
<script type='text/javascript' src='js/layerslider.kreaturamedia.jquery.js?ver=7.2.5' id='layerslider-js'></script>
<script type='text/javascript' id='layerslider-js-after'>
    jQuery(function () { _initLayerSlider('#layerslider_3_9akjpcdvwcma', { sliderVersion: '6.6.5.1516012254', type: 'fullwidth', pauseOnHover: 'enabled', skin: 'v6', hoverPrevNext: false, navStartStop: false, navButtons: false, showCircleTimer: false, useSrcset: true, skinsPath: 'skins' }); });
</script>
<script type='text/javascript' src='js/layerslider.transitions.js?ver=7.2.5' id='layerslider-transitions-js'></script>
<script type='text/javascript' defer src='js/forms.js?ver=4.8.11' id='mc4wp-forms-api-js'></script>
<script type='text/javascript' src='js/underscore.min.js?ver=1.13.4' id='underscore-js'></script>
<script type='text/javascript' src='js/wp-util.min.js?ver=6.1.1' id='wp-util-js'></script>
<script type='text/javascript' src='js/add-to-cart-variation.min.js?ver=7.4.1'
    id='wc-add-to-cart-variation-js'></script>
<script type='text/javascript' src='js/photoswipe.min.js?ver=4.1.1-wc.7.4.1' id='photoswipe-js'></script>
<script type='text/javascript' src='js/photoswipe-ui-default.min.js?ver=4.1.1-wc.7.4.1'
    id='photoswipe-ui-default-js'></script>
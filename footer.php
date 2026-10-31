<?php
// Include database connection
require_once __DIR__ . '/connection.php';

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

<!-- Site Welcome Card / Popup (Dynamic Layouts & Analytics Tracking) -->
<?php
$popup_status = 0;
$popup_title = 'Welcome to LKVM';
$popup_desc = 'Empowering communities through Art, Culture, Skill Development, and Quality Education across Bihar.';
$popup_btn_text = 'Explore More';
$popup_btn_link = 'about_us';
$popup_image = '';
$popup_layout = 'card_cover';
$popup_badge = 'Important Announcement';
$popup_btn_secondary_text = 'Close';
$popup_btn_secondary_link = '#close';
$popup_btn_color = '#002147';
$popup_delay = 2;
$popup_frequency = 'session';

if (isset($con)) {
    $q = mysqli_query($con, "SELECT welcome_status, welcome_title, welcome_desc, welcome_btn_text, welcome_btn_link, welcome_image, welcome_layout, welcome_badge, welcome_btn_secondary_text, welcome_btn_secondary_link, welcome_btn_color, welcome_delay, welcome_frequency FROM global_setting WHERE id = 1");
    if ($q && mysqli_num_rows($q) > 0) {
        $row = mysqli_fetch_assoc($q);
        $popup_status = intval($row['welcome_status'] ?? 0);
        $popup_title = !empty($row['welcome_title']) ? $row['welcome_title'] : $popup_title;
        $popup_desc = $row['welcome_desc'] ?? $popup_desc;
        $popup_btn_text = !empty($row['welcome_btn_text']) ? $row['welcome_btn_text'] : $popup_btn_text;
        $popup_btn_link = !empty($row['welcome_btn_link']) ? $row['welcome_btn_link'] : $popup_btn_link;
        $popup_image = $row['welcome_image'] ?? '';
        $popup_layout = !empty($row['welcome_layout']) ? $row['welcome_layout'] : 'card_cover';
        $popup_badge = !empty($row['welcome_badge']) ? $row['welcome_badge'] : $popup_badge;
        $popup_btn_secondary_text = $row['welcome_btn_secondary_text'] ?? '';
        $popup_btn_secondary_link = !empty($row['welcome_btn_secondary_link']) ? $row['welcome_btn_secondary_link'] : '#close';
        $popup_btn_color = !empty($row['welcome_btn_color']) ? $row['welcome_btn_color'] : '#002147';
        $popup_delay = max(1, intval($row['welcome_delay'] ?? 2));
        $popup_frequency = !empty($row['welcome_frequency']) ? $row['welcome_frequency'] : 'session';
    }
}
?>

<?php if ($popup_status == 1): ?>
<style>
.welcome-popup-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(15, 23, 42, 0.7);
    z-index: 99999;
    backdrop-filter: blur(8px);
    opacity: 0;
    transition: opacity 0.35s ease;
}

.welcome-popup-box {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -45%) scale(0.95);
    background: #ffffff;
    width: 92%;
    border-radius: 24px;
    box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.35);
    border: 1px solid rgba(255, 255, 255, 0.4);
    overflow: hidden;
    z-index: 100000;
    transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.35s ease;
    opacity: 0;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
}

.welcome-popup-box.active {
    transform: translate(-50%, -50%) scale(1);
    opacity: 1;
}

.welcome-popup-close-btn {
    position: absolute;
    top: 14px;
    right: 14px;
    background: rgba(255, 255, 255, 0.85);
    border: none;
    color: #1e293b;
    font-size: 20px;
    cursor: pointer;
    line-height: 1;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    z-index: 20;
    transition: all 0.2s ease;
    backdrop-filter: blur(4px);
}

.welcome-popup-close-btn:hover {
    background: #0f172a;
    color: #ffffff;
    transform: scale(1.05);
}

.welcome-popup-scrollable {
    overflow-y: auto;
    max-height: 90vh;
}

/* Card Cover Layout - Calibrated for 1280x720 Px (16:9 Aspect Ratio) */
.popup-layout-card_cover {
    max-width: 680px;
    width: 92%;
}
.popup-layout-card_cover .popup-cover-img {
    width: 100%;
    aspect-ratio: 1280 / 720;
    max-height: 400px;
    background-size: cover;
    background-position: center;
    position: relative;
}
.popup-layout-card_cover .popup-cover-img::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 45%;
    background: linear-gradient(to top, rgba(0,0,0,0.4), transparent);
}

/* Card Split Layout */
.popup-layout-card_split {
    max-width: 780px;
}
.popup-layout-card_split .popup-split-row {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    width: 100%;
}
.popup-layout-card_split .popup-split-img {
    width: 45%;
    min-width: 280px;
    background-size: cover;
    background-position: center;
    min-height: 340px;
}
.popup-layout-card_split .popup-split-body {
    flex: 1;
    min-width: 280px;
}

/* Card Minimal Layout */
.popup-layout-card_minimal {
    max-width: 500px;
}

@media (max-width: 767px) {
    .popup-layout-card_split .popup-split-img {
        width: 100% !important;
        height: 190px !important;
        min-height: 190px !important;
    }
    .popup-layout-card_cover .popup-cover-img {
        aspect-ratio: 1280 / 720 !important;
        width: 100% !important;
        height: auto !important;
    }
    .welcome-popup-body {
        padding: 25px 20px !important;
    }
    .welcome-popup-actions {
        flex-direction: column !important;
    }
    .welcome-popup-actions a,
    .welcome-popup-actions button {
        width: 100% !important;
        text-align: center !important;
    }
}
</style>

<div id="flash-card-overlay" class="welcome-popup-overlay">
    <div id="flash-card" class="welcome-popup-box popup-layout-<?php echo htmlspecialchars($popup_layout); ?>">
        <!-- Floating Close Button -->
        <button type="button" class="welcome-popup-close-btn" onclick="handlePopupClose()" aria-label="Close popup">&times;</button>

        <div class="welcome-popup-scrollable">
            <?php if ($popup_layout === 'card_cover'): ?>
                <!-- ==================== LAYOUT 1: TOP COVER CARD ==================== -->
                <?php if (!empty($popup_image)): ?>
                    <div class="popup-cover-img" style="background-image: url('images/<?php echo htmlspecialchars($popup_image); ?>');">
                        <?php if (!empty($popup_badge)): ?>
                            <span style="position: absolute; bottom: 15px; left: 20px; z-index: 2; background: rgba(255, 255, 255, 0.95); color: <?php echo htmlspecialchars($popup_btn_color); ?>; font-weight: 800; font-size: 11px; letter-spacing: 0.8px; text-transform: uppercase; padding: 5px 14px; border-radius: 50px; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                                <i class="fas fa-sparkles me-1 text-warning"></i><?php echo htmlspecialchars($popup_badge); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div style="background: linear-gradient(135deg, <?php echo htmlspecialchars($popup_btn_color); ?>, #0284c7); padding: 30px 25px 20px 25px; color: #fff;">
                        <?php if (!empty($popup_badge)): ?>
                            <span class="badge bg-white text-dark rounded-pill px-3 py-1 mb-2 font-weight-bold" style="font-size: 11px; letter-spacing: 0.5px;">
                                <?php echo htmlspecialchars($popup_badge); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <div class="welcome-popup-body" style="padding: 30px; background: #ffffff;">
                    <?php if (empty($popup_image) && !empty($popup_badge)): ?>
                    <?php elseif (!empty($popup_badge) && empty($popup_image)): ?>
                    <?php endif; ?>
                    
                    <h3 style="color: #0f172a; font-size: 24px; font-weight: 800; margin: 0 0 12px 0; line-height: 1.25;">
                        <?php echo htmlspecialchars($popup_title); ?>
                    </h3>
                    
                    <div style="font-size: 15px; line-height: 1.7; color: #475569; margin-bottom: 25px; white-space: pre-line;">
                        <?php echo htmlspecialchars($popup_desc); ?>
                    </div>

                    <div class="welcome-popup-actions" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
                        <a href="<?php echo htmlspecialchars($popup_btn_link); ?>" 
                           onclick="handlePopupClick('click_primary', this, event)"
                           style="background: <?php echo htmlspecialchars($popup_btn_color); ?>; color: #ffffff; padding: 13px 30px; border-radius: 50px; font-weight: 700; text-decoration: none; font-size: 14px; transition: transform 0.2s, box-shadow 0.2s; box-shadow: 0 8px 18px <?php echo htmlspecialchars($popup_btn_color); ?>33; display: inline-flex; align-items: center; justify-content: center; gap: 8px;">
                            <span><?php echo htmlspecialchars($popup_btn_text); ?></span>
                            <i class="fas fa-arrow-right fa-xs"></i>
                        </a>

                        <?php if (!empty($popup_btn_secondary_text)): ?>
                            <a href="<?php echo htmlspecialchars($popup_btn_secondary_link); ?>" 
                               onclick="handlePopupClick('click_secondary', this, event)"
                               style="background: transparent; color: #475569; border: 1.5px solid #cbd5e1; padding: 12px 24px; border-radius: 50px; font-weight: 600; text-decoration: none; font-size: 14px; transition: all 0.2s; display: inline-flex; align-items: center; justify-content: center;">
                                <?php echo htmlspecialchars($popup_btn_secondary_text); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

            <?php elseif ($popup_layout === 'card_split'): ?>
                <!-- ==================== LAYOUT 2: SPLIT CARD ==================== -->
                <div class="popup-split-row">
                    <?php if (!empty($popup_image)): ?>
                        <div class="popup-split-img" style="background-image: url('images/<?php echo htmlspecialchars($popup_image); ?>');"></div>
                    <?php endif; ?>

                    <div class="popup-split-body welcome-popup-body" style="padding: 40px 32px; display: flex; flex-direction: column; justify-content: center; background: #ffffff;">
                        <?php if (!empty($popup_badge)): ?>
                            <div style="margin-bottom: 15px;">
                                <span style="background: <?php echo htmlspecialchars($popup_btn_color); ?>15; color: <?php echo htmlspecialchars($popup_btn_color); ?>; font-weight: 700; font-size: 11px; letter-spacing: 0.8px; text-transform: uppercase; padding: 5px 14px; border-radius: 50px; display: inline-block;">
                                    <?php echo htmlspecialchars($popup_badge); ?>
                                </span>
                            </div>
                        <?php endif; ?>

                        <h3 style="color: #0f172a; font-size: 24px; font-weight: 800; margin: 0 0 14px 0; line-height: 1.25;">
                            <?php echo htmlspecialchars($popup_title); ?>
                        </h3>

                        <div style="font-size: 14px; line-height: 1.7; color: #475569; margin-bottom: 25px; white-space: pre-line;">
                            <?php echo htmlspecialchars($popup_desc); ?>
                        </div>

                        <div class="welcome-popup-actions" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
                            <a href="<?php echo htmlspecialchars($popup_btn_link); ?>" 
                               onclick="handlePopupClick('click_primary', this, event)"
                               style="background: <?php echo htmlspecialchars($popup_btn_color); ?>; color: #ffffff; padding: 13px 28px; border-radius: 50px; font-weight: 700; text-decoration: none; font-size: 14px; transition: transform 0.2s, box-shadow 0.2s; box-shadow: 0 8px 18px <?php echo htmlspecialchars($popup_btn_color); ?>33; display: inline-flex; align-items: center; justify-content: center; gap: 8px;">
                                <span><?php echo htmlspecialchars($popup_btn_text); ?></span>
                                <i class="fas fa-arrow-right fa-xs"></i>
                            </a>

                            <?php if (!empty($popup_btn_secondary_text)): ?>
                                <a href="<?php echo htmlspecialchars($popup_btn_secondary_link); ?>" 
                                   onclick="handlePopupClick('click_secondary', this, event)"
                                   style="background: transparent; color: #475569; border: 1.5px solid #cbd5e1; padding: 12px 22px; border-radius: 50px; font-weight: 600; text-decoration: none; font-size: 14px; transition: all 0.2s;">
                                    <?php echo htmlspecialchars($popup_btn_secondary_text); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            <?php else: ?>
                <!-- ==================== LAYOUT 3: MINIMAL CARD ==================== -->
                <div class="welcome-popup-body" style="padding: 40px 32px; background: #ffffff; text-align: center;">
                    <div style="background: <?php echo htmlspecialchars($popup_btn_color); ?>15; color: <?php echo htmlspecialchars($popup_btn_color); ?>; width: 60px; height: 60px; border-radius: 20px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                        <i class="fas fa-bullhorn" style="font-size: 24px;"></i>
                    </div>

                    <?php if (!empty($popup_badge)): ?>
                        <div style="margin-bottom: 12px;">
                            <span style="background: #f1f5f9; color: #334155; font-weight: 700; font-size: 11px; letter-spacing: 0.8px; text-transform: uppercase; padding: 4px 12px; border-radius: 50px; display: inline-block;">
                                <?php echo htmlspecialchars($popup_badge); ?>
                            </span>
                        </div>
                    <?php endif; ?>

                    <h3 style="color: #0f172a; font-size: 24px; font-weight: 800; margin: 0 0 14px 0;">
                        <?php echo htmlspecialchars($popup_title); ?>
                    </h3>

                    <div style="font-size: 15px; line-height: 1.7; color: #475569; margin-bottom: 28px; white-space: pre-line;">
                        <?php echo htmlspecialchars($popup_desc); ?>
                    </div>

                    <div class="welcome-popup-actions" style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                        <a href="<?php echo htmlspecialchars($popup_btn_link); ?>" 
                           onclick="handlePopupClick('click_primary', this, event)"
                           style="background: <?php echo htmlspecialchars($popup_btn_color); ?>; color: #ffffff; padding: 13px 30px; border-radius: 50px; font-weight: 700; text-decoration: none; font-size: 14px; box-shadow: 0 8px 18px <?php echo htmlspecialchars($popup_btn_color); ?>33;">
                            <?php echo htmlspecialchars($popup_btn_text); ?>
                        </a>

                        <?php if (!empty($popup_btn_secondary_text)): ?>
                            <a href="<?php echo htmlspecialchars($popup_btn_secondary_link); ?>" 
                               onclick="handlePopupClick('click_secondary', this, event)"
                               style="background: transparent; color: #475569; border: 1.5px solid #cbd5e1; padding: 12px 24px; border-radius: 50px; font-weight: 600; text-decoration: none; font-size: 14px;">
                                <?php echo htmlspecialchars($popup_btn_secondary_text); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
(function() {
    var frequency = "<?php echo addslashes($popup_frequency); ?>";
    var delaySec = <?php echo $popup_delay; ?>;
    var trackEndpoint = "<?php echo $SITE_URL; ?>/ajax_popup_track";

    function shouldShowPopup() {
        if (frequency === 'always') return true;
        if (frequency === 'daily') {
            var today = new Date().toISOString().slice(0, 10);
            return localStorage.getItem("welcomePopupDate") !== today;
        }
        // Default: once per session
        return !sessionStorage.getItem("welcomePopupSeen");
    }

    function markPopupSeen() {
        sessionStorage.setItem("welcomePopupSeen", "true");
        if (frequency === 'daily') {
            var today = new Date().toISOString().slice(0, 10);
            localStorage.setItem("welcomePopupDate", today);
        }
    }

    // Analytics Tracker
    window.trackPopupAnalytics = function(eventType, btnText, targetUrl) {
        var payload = {
            event_type: eventType,
            button_text: btnText || '',
            target_url: targetUrl || '',
            page_url: window.location.pathname + window.location.search
        };
        try {
            if (navigator.sendBeacon) {
                var blob = new Blob([JSON.stringify(payload)], { type: 'application/json' });
                navigator.sendBeacon(trackEndpoint, blob);
            } else {
                fetch(trackEndpoint, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload),
                    keepalive: true
                }).catch(function() {});
            }
        } catch(e) {}
    };

    window.closeFlashCard = function() {
        var overlay = document.getElementById("flash-card-overlay");
        var card = document.getElementById("flash-card");
        if (overlay && card) {
            card.classList.remove('active');
            overlay.style.opacity = "0";
            setTimeout(function() {
                overlay.style.display = "none";
            }, 350);
        }
        markPopupSeen();
    };

    window.handlePopupClose = function() {
        trackPopupAnalytics('close', 'Close Button', '#close');
        closeFlashCard();
    };

    window.handlePopupClick = function(eventType, elem, ev) {
        var text = (elem.innerText || elem.textContent || '').trim();
        var href = elem.getAttribute('href') || '';
        trackPopupAnalytics(eventType, text, href);

        if (href === '#close' || href === 'javascript:void(0)' || href === 'javascript:;') {
            if (ev) ev.preventDefault();
            closeFlashCard();
            return false;
        }
        // Allow regular navigation
        markPopupSeen();
    };

    // Close on overlay backdrop click
    document.addEventListener("DOMContentLoaded", function() {
        var overlay = document.getElementById("flash-card-overlay");
        var card = document.getElementById("flash-card");

        if (overlay) {
            overlay.addEventListener("click", function(e) {
                if (e.target === overlay) {
                    trackPopupAnalytics('close', 'Backdrop Click', '#backdrop');
                    closeFlashCard();
                }
            });
        }

        if (shouldShowPopup()) {
            setTimeout(function() {
                if (overlay && card) {
                    overlay.style.display = "block";
                    overlay.offsetWidth; // force reflow
                    overlay.style.opacity = "1";
                    card.classList.add('active');
                    // Track Impression Event
                    trackPopupAnalytics('impression', '', window.location.pathname);
                }
            }, delaySec * 1000);
        }
    });
})();
</script>
<?php endif; ?>

<script>
// Register Service Worker
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('<?php echo $SITE_URL; ?>/sw.js')
            .then(reg => console.log('Service Worker registered', reg))
            .catch(err => console.log('Service Worker registration failed', err));
    });
}

// Handle PWA Install Prompt
let deferredPrompt;
window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    deferredPrompt = e;
    console.log('PWA install prompt captured');
});
</script>

<!-- Floating Contact Buttons (WhatsApp & Call) -->
<div class="floating-contact-wrap">
    <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', ($settings['whatsapp'] ?? $MOBILE)); ?>" class="floating-btn whatsapp-btn" target="_blank" title="WhatsApp Us">
        <i class="fab fa-whatsapp"></i>
    </a>
    <a href="tel:<?php echo $MOBILE; ?>" class="floating-btn call-btn" title="Call Us">
        <i class="fas fa-phone-alt"></i>
    </a>
</div>

<style>
.floating-contact-wrap {
    position: fixed;
    bottom: 30px;
    left: 30px;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.floating-btn {
    width: 55px;
    height: 55px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff !important;
    font-size: 26px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.25);
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    text-decoration: none !important;
    position: relative;
}

.whatsapp-btn {
    background: linear-gradient(135deg, #25d366 0%, #128c7e 100%);
}

.call-btn {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
}

.floating-btn:hover {
    transform: scale(1.1) translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.35);
    color: #ffffff !important;
}

.floating-btn::after {
    content: '';
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: inherit;
    opacity: 0.5;
    z-index: -1;
    animation: floating-pulse 2s infinite;
}

@keyframes floating-pulse {
    0% { transform: scale(1); opacity: 0.5; }
    100% { transform: scale(1.6); opacity: 0; }
}

@media (max-width: 767px) {
    .floating-contact-wrap {
        bottom: 20px;
        left: 20px;
        gap: 12px;
    }
    .floating-btn {
        width: 48px;
        height: 48px;
        font-size: 22px;
    }
}
</style>
<script>
document.addEventListener("DOMContentLoaded", function() {
    var token = "<?php echo function_exists('csrf_token') ? csrf_token() : ''; ?>";
    if (token) {
        var forms = document.getElementsByTagName("form");
        for (var i = 0; i < forms.length; i++) {
            if (forms[i].method && forms[i].method.toLowerCase() === "post") {
                // Check if csrf_token input already exists
                if (!forms[i].querySelector('input[name="csrf_token"]')) {
                    var input = document.createElement("input");
                    input.type = "hidden";
                    input.name = "csrf_token";
                    input.value = token;
                    forms[i].appendChild(input);
                }
            }
        }
    }
});
</script>
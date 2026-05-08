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

<!-- Site Welcome Flash Card (Fully Dynamic with Optional Split Side Image Layout) -->
<?php
$popup_status = 0;
$popup_title = 'Welcome to LKVM';
$popup_desc = 'Empowering communities through Art, Culture, Skill Development, and Quality Education across Bihar.';
$popup_btn_text = 'Read More';
$popup_btn_link = 'about_us.php';
$popup_image = '';

if (isset($con)) {
    $q = mysqli_query($con, "SELECT welcome_status, welcome_title, welcome_desc, welcome_btn_text, welcome_btn_link, welcome_image FROM global_setting WHERE id = 1");
    if ($q && mysqli_num_rows($q) > 0) {
        $row = mysqli_fetch_assoc($q);
        $popup_status = intval($row['welcome_status'] ?? 0);
        $popup_title = $row['welcome_title'] ?? $popup_title;
        $popup_desc = $row['welcome_desc'] ?? $popup_desc;
        $popup_btn_text = $row['welcome_btn_text'] ?? $popup_btn_text;
        $popup_btn_link = $row['welcome_btn_link'] ?? $popup_btn_link;
        $popup_image = $row['welcome_image'] ?? '';
    }
}
?>

<?php if ($popup_status == 1): ?>
<style>
@media (max-width: 767px) {
    .welcome-popup-box {
        max-width: 420px !important;
    }
    .welcome-popup-img {
        width: 100% !important;
        height: 180px !important;
        min-height: 180px !important;
    }
}
</style>

<div id="flash-card-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.65); z-index: 99999; backdrop-filter: blur(8px); opacity: 0; transition: opacity 0.4s ease;">
    <div id="flash-card" class="welcome-popup-box" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -40%) scale(0.9); background: #ffffff; width: 90%; max-width: <?php echo !empty($popup_image) ? '760px' : '460px'; ?>; border-radius: 20px; box-shadow: 0 20px 50px rgba(0,0,0,0.3); border: 1px solid rgba(0,0,0,0.1); overflow: hidden; z-index: 100000; transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), opacity 0.4s ease; opacity: 0; display: flex; flex-direction: column;">
        
        <!-- Close button (Always positioned correctly) -->
        <button onclick="closeFlashCard()" style="position: absolute; top: 15px; right: 15px; background: rgba(255,255,255,0.9); border: none; color: #333; font-size: 26px; cursor: pointer; transition: all 0.2s; line-height: 1; width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.15); z-index: 10;" onmouseover="this.style.background='#002147'; this.style.color='#fff';" onmouseout="this.style.background='rgba(255,255,255,0.9)'; this.style.color='#333';">&times;</button>
        
        <div style="display: flex; flex-direction: row; flex-wrap: wrap; width: 100%;">
            
            <?php if (!empty($popup_image)): ?>
            <!-- Left Side Cover Image (Responsive) -->
            <div class="welcome-popup-img" style="width: 45%; min-width: 280px; background: url('images/<?php echo $popup_image; ?>') no-repeat center center / cover; min-height: 320px;"></div>
            <?php endif; ?>
            
            <!-- Right Side Text / Content -->
            <div class="welcome-popup-body" style="flex: 1; min-width: 280px; padding: 40px 30px; display: flex; flex-direction: column; justify-content: center; background: linear-gradient(135deg, #002147 0%, #001530 100%); color: #ffffff;">
                <div style="background: rgba(253, 200, 0, 0.15); width: 45px; height: 45px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                    <i class="fas fa-bullhorn" style="font-size: 18px; color: #fdc800;"></i>
                </div>
                <h3 style="color: #fdc800; font-size: 22px; margin: 0 0 12px 0; font-weight: 700; letter-spacing: 0.5px; line-height: 1.3; font-family: inherit;"><?php echo htmlspecialchars($popup_title); ?></h3>
                <p style="font-size: 14px; line-height: 1.6; color: #e0e0e0; margin: 0 0 25px 0; font-family: inherit; font-weight: 400;"><?php echo nl2br(htmlspecialchars($popup_desc)); ?></p>
                <div style="display: flex; gap: 12px; align-items: center;">
                    <a href="<?php echo htmlspecialchars($popup_btn_link); ?>" style="background: #fdc800; color: #002147; padding: 12px 28px; border-radius: 30px; font-weight: 700; text-decoration: none; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(253, 200, 0, 0.35); text-align: center;" onmouseover="this.style.background='#ffffff'; this.style.color='#002147'; this.style.transform='translateY(-2px)';" onmouseout="this.style.background='#fdc800'; this.style.color='#002147'; this.style.transform='translateY(0)';" onclick="closeFlashCard()"><?php echo htmlspecialchars($popup_btn_text); ?></a>
                    <button onclick="closeFlashCard()" style="background: transparent; color: rgba(255,255,255,0.75); border: 1px solid rgba(255,255,255,0.3); padding: 12px 24px; border-radius: 30px; font-weight: 600; font-size: 13px; cursor: pointer; transition: all 0.3s ease; text-transform: uppercase; letter-spacing: 0.5px;" onmouseover="this.style.background='rgba(255,255,255,0.08)'; this.style.color='#ffffff'; this.style.borderColor='rgba(255,255,255,0.5)';" onmouseout="this.style.background='transparent'; this.style.color='rgba(255,255,255,0.75)'; this.style.borderColor='rgba(255,255,255,0.3)';">Skip</button>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    if (!sessionStorage.getItem("flashCardSeen")) {
        setTimeout(function() {
            var overlay = document.getElementById("flash-card-overlay");
            var card = document.getElementById("flash-card");
            if (overlay && card) {
                overlay.style.display = "block";
                overlay.offsetWidth; // force layout reflow
                overlay.style.opacity = "1";
                card.style.opacity = "1";
                card.style.transform = "translate(-50%, -50%) scale(1)";
            }
        }, 2200); // Trigger after 2.2 seconds
    }
});

function closeFlashCard() {
    var overlay = document.getElementById("flash-card-overlay");
    var card = document.getElementById("flash-card");
    if (overlay && card) {
        overlay.style.opacity = "0";
        card.style.opacity = "0";
        card.style.transform = "translate(-50%, -40%) scale(0.9)";
        setTimeout(function() {
            overlay.style.display = "none";
        }, 400);
    }
    sessionStorage.setItem("flashCardSeen", "true");
}
</script>
<?php endif; ?>
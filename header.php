<?php require_once 'includes/visitor_tracker.php'; ?>
<header id="masthead" class="site-header">
    <div id="tophead">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 d-flex align-items-center">
                    <div class="tophead-contact">
                        <ul>
                            <li><i class="far fa-envelope"></i><a
                                    href="mailto:<?php echo $CONTACT_EMAIL; ?>"><?php echo $CONTACT_EMAIL; ?></a></li>
                            <li><i class="fas fa-phone-alt" aria-hidden="true"></i>
                                <ahref="tel:<?php echo $CONTACT_MOBILE; ?>"><?php echo $CONTACT_MOBILE; ?></a>
                            </li>
                        </ul>
                    </div>
                    <div class="tophead-right ml-auto">
                        <!--	<div class="login-btn">
                  <i class="fa fa-user"></i>&nbsp;&nbsp;<a href="<?php echo $SITE_URL; ?>/login">Login</a> / <a href="<?php echo $SITE_URL; ?>/register">Register</a>
                </div>-->

                        <div class="topbar-social">
                            <ul class="header-social">
                                <?php if (!empty($settings['facebook_url'])) { ?>
                                    <li><a target="_blank"
                                            href="<?php echo htmlspecialchars($settings['facebook_url']); ?>"><i
                                                class="fab fa-facebook-f"></i></a></li><?php } ?>
                                <?php if (!empty($settings['instagram_url'])) { ?>
                                    <li><a target="_blank"
                                            href="<?php echo htmlspecialchars($settings['instagram_url']); ?>"><i
                                                class="fab fa-instagram"></i></a></li><?php } ?>
                                <?php if (!empty($settings['twitter_url'])) { ?>
                                    <li><a target="_blank"
                                            href="<?php echo htmlspecialchars($settings['twitter_url']); ?>"><i
                                                class="fab fa-twitter"></i></a></li><?php } ?>
                                <?php if (!empty($settings['youtube_url'])) { ?>
                                    <li><a target="_blank"
                                            href="<?php echo htmlspecialchars($settings['youtube_url']); ?>"><i
                                                class="fab fa-youtube"></i></a></li><?php } ?>
                                <?php if (!empty($settings['linkedin_url'])) { ?>
                                    <li><a target="_blank"
                                            href="<?php echo htmlspecialchars($settings['linkedin_url']); ?>"><i
                                                class="fab fa-linkedin-in"></i></a></li><?php } ?>
                            </ul>
                        </div>

                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="container masthead-container">
        <div class="row menu-flex-wrapper">

            <!--site logo-->
            <div class="site-logo-section">
                <div class="site-branding">
                    <a class="dark-logo" href="<?php echo $SITE_URL; ?>/index">
                        <img src="images/logo.png" alt="<?php echo $SITE_TITLE; ?> Logo/" style="height:70px;">
                    </a>
                    <a class="light-logo" href="<?php echo $SITE_URL; ?>/index">
                        <img src="images/logo.png" alt="<?php echo $SITE_TITLE; ?> Logo/" style="height:70px;">
                    </a>
                </div>
                <style>
                    blink {
                        animation: 2s linear infinite condemned_blink_effect;
                    }

                    @keyframes condemned_blink_effect {
                        0% {
                            visibility: hidden;
                        }

                        50% {
                            visibility: hidden;
                        }

                        100% {
                            visibility: visible;
                        }
                    }
                </style>

                <!-- <div class="top-menu-category">
    <div id="site-navigation" class="main-navigation">
        <nav>
         <ul class="menu">
                <li class="menu-item menu-item-has-children">
                    <a href="#">
                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="4" height="4" fill="black"/>
                            <rect y="18" width="4" height="4" fill="black"/>
                            <rect y="9" width="4" height="4" fill="black"/>
                            <rect x="9" width="4" height="4" fill="black"/>
                            <rect x="9" y="18" width="4" height="4" fill="black"/>
                            <rect x="9" y="9" width="4" height="4" fill="black"/>
                            <rect x="18" width="4" height="4" fill="black"/>
                            <rect x="18" y="18" width="4" height="4" fill="black"/>
                            <rect x="18" y="9" width="4" height="4" fill="black"/>
                        </svg>
                        Category                    </a>

                    <ul class="sub-menu ">
                                                    <li>
                                <a href="#">Kids</a>
                            </li>
                                                    <li>
                                <a href="#">Web Development</a>
                            </li>
                                                    <li>
                                <a href="#">Programming</a>
                            </li>
                                                    <li>
                                <a href="#">Technology</a>
                            </li>
                        
                    </ul>

                </li>
            </ul>
        </nav>
    </div>
</div>-->

            </div>
            <!--end site logo-->

            <!--site menu -->
            <div class="site-menu-section">
                <div id="site-navigation" class="main-navigation">
                    <nav class="menu-main-menu-container">
                        <ul id="menu-main-menu" class="menu">
                            <li id="menu-item-57"
                                class="mega-menu hide-header menu-item menu-item-type-custom menu-item-object-custom current-menu-ancestor menu-item-57">
                                <a href="<?php echo $SITE_URL; ?>/index">Home</a>
                            </li>

                            <li id="menu-item-82"
                                class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-82">
                                <a href="#">Who We Are</a>
                                <ul class="sub-menu">
                                    <li id="menu-item-883"
                                        class="menu-item menu-item-type-post_type menu-item-object-lp_course menu-item-883">
                                        <a href="<?php echo $SITE_URL; ?>/about_us">About us</a>
                                    </li>
                                    <li id="menu-item-1690"
                                        class="menu-item menu-item-type-custom menu-item-object-custom menu-item-1690">
                                        <a href="<?php echo $SITE_URL; ?>/vision_mission">Vision & Mission</a>
                                    </li>
                                    <li id="menu-item-1691"
                                        class="menu-item menu-item-type-custom menu-item-object-custom menu-item-1691">
                                        <a href="<?php echo $SITE_URL; ?>/our_team">Our Team</a>
                                    </li>
                                    <li id="menu-item-1691"
                                        class="menu-item menu-item-type-custom menu-item-object-custom menu-item-1691">
                                        <a href="<?php echo $SITE_URL; ?>/annual_report">Annual Report</a>
                                    </li>
                                    <li id="menu-item-1691"
                                        class="menu-item menu-item-type-custom menu-item-object-custom menu-item-1691">
                                        <a href="<?php echo $SITE_URL; ?>/where_we_work">Where We Work</a>
                                    </li>
                                </ul>
                            </li>
                            <li id="menu-item-83"
                                class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-83">
                                <a href="#">Our Work</a>
                                <ul class="sub-menu">
                                    <li id="menu-item-957"
                                        class="menu-item menu-item-type-post_type menu-item-object-page menu-item-957">
                                        <a href="https://talk.lkvmbihar.in/">Talk Institute</a>
                                    </li>
                                    <li id="menu-item-956"
                                        class="menu-item menu-item-type-post_type menu-item-object-page menu-item-956">
                                        <a href="<?php echo $SITE_URL; ?>/skill_development">Skill Development</a>
                                    </li>
                                    <li id="menu-item-955"
                                        class="menu-item menu-item-type-post_type menu-item-object-page menu-item-955">
                                        <a href="<?php echo $SITE_URL; ?>/health">Health</a>
                                    </li>
                                    <li id="menu-item-955"
                                        class="menu-item menu-item-type-post_type menu-item-object-page menu-item-955">
                                        <a href="<?php echo $SITE_URL; ?>/livelihood">Livelihood</a>
                                    </li>
                                    <li id="menu-item-955"
                                        class="menu-item menu-item-type-post_type menu-item-object-page menu-item-955">
                                        <a href="<?php echo $SITE_URL; ?>/environment">Environment</a>
                                    </li>
                                </ul>
                            </li>
                            <li id="menu-item-84"
                                class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-84">
                                <a href="#">Achievement</a>
                                <ul class="sub-menu">
                                    <li id="menu-item-886"
                                        class="menu-item menu-item-type-post_type menu-item-object-page menu-item-886">
                                        <a href="<?php echo $SITE_URL; ?>/impact">Impact</a>
                                    </li>
                                    <li id="menu-item-886"
                                        class="menu-item menu-item-type-post_type menu-item-object-page menu-item-886">
                                        <a href="<?php echo $SITE_URL; ?>/innovations">Innovations</a>
                                    </li>
                                    <li id="menu-item-886"
                                        class="menu-item menu-item-type-post_type menu-item-object-page menu-item-886">
                                        <a href="<?php echo $SITE_URL; ?>/testimonial">Testimonial</a>
                                    </li>
                                    <li id="menu-item-886"
                                        class="menu-item menu-item-type-post_type menu-item-object-page menu-item-886">
                                        <a href="<?php echo $SITE_URL; ?>/award_recognition">Award & Recognition</a>
                                    </li>
                                </ul>
                            </li>

                            <li id="menu-item-84"
                                class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-84">
                                <a href="#">Partners</a>
                                <ul class="sub-menu">
                                    <li id="menu-item-886"
                                        class="menu-item menu-item-type-post_type menu-item-object-page menu-item-886">
                                        <a href="<?php echo $SITE_URL; ?>/government_partners">Government Partners</a>
                                    </li>
                                    <li id="menu-item-886"
                                        class="menu-item menu-item-type-post_type menu-item-object-page menu-item-886">
                                        <a href="<?php echo $SITE_URL; ?>/corporate_partners">Corporate Partners</a>
                                    </li>
                                    <li id="menu-item-886"
                                        class="menu-item menu-item-type-post_type menu-item-object-page menu-item-886">
                                        <a href="<?php echo $SITE_URL; ?>/institutions">Institution & Organization</a>
                                    </li>
                                </ul>
                            </li>

                            <li id="menu-item-84"
                                class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-84">
                                <a href="#">Get Involved</a>
                                <ul class="sub-menu">
                                    <li id="menu-item-86"
                                        class="menu-item menu-item-type-post_type menu-item-object-post menu-item-86"><a
                                            href="<?php echo $SITE_URL; ?>/Volunteer">Volunteer/in-team</a></li>
                                    <li id="menu-item-86"
                                        class="menu-item menu-item-type-post_type menu-item-object-post menu-item-86"><a
                                            href="<?php echo $SITE_URL; ?>/partner_us">Partner with us</a></li>
                                    <li id="menu-item-86"
                                        class="menu-item menu-item-type-post_type menu-item-object-post menu-item-86"><a
                                            href="<?php echo $SITE_URL; ?>/career">Career</a></li>
                                </ul>
                            </li>

                            <li id="menu-item-84"
                                class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-84">
                                <a href="#">Media</a>
                                <ul class="sub-menu">
                                    <li id="menu-item-86"
                                        class="menu-item menu-item-type-post_type menu-item-object-post menu-item-86"><a
                                            href="<?php echo $SITE_URL; ?>/media_room">Media Room</a></li>
                                    <li id="menu-item-86"
                                        class="menu-item menu-item-type-post_type menu-item-object-post menu-item-86"><a
                                            href="<?php echo $SITE_URL; ?>/photo_gallery">Photo Gallery</a></li>
                                    <li id="menu-item-86"
                                        class="menu-item menu-item-type-post_type menu-item-object-post menu-item-86"><a
                                            href="<?php echo $SITE_URL; ?>/video_gallery">Video Gallery</a></li>
                                    <li id="menu-item-86"
                                        class="menu-item menu-item-type-post_type menu-item-object-post menu-item-86"><a
                                            href="<?php echo $SITE_URL; ?>/news">News</a></li>
                                    <!--<li id="menu-item-86" class="menu-item menu-item-type-post_type menu-item-object-post menu-item-86"><a href="<?php echo $SITE_URL; ?>/publications">Publications</a></li>
<li id="menu-item-86" class="menu-item menu-item-type-post_type menu-item-object-post menu-item-86"><a href="<?php echo $SITE_URL; ?>/press_release">Press Release</a></li>-->
                                    <li id="menu-item-86"
                                        class="menu-item menu-item-type-post_type menu-item-object-post menu-item-86"><a
                                            href="<?php echo $SITE_URL; ?>/event">Event</a></li>
                                    <li id="menu-item-86"
                                        class="menu-item menu-item-type-post_type menu-item-object-post menu-item-86"><a
                                            href="<?php echo $SITE_URL; ?>/press_coverage">Press Coverage</a></li>
                                    <li id="menu-item-86"
                                        class="menu-item menu-item-type-post_type menu-item-object-post menu-item-86"><a
                                            href="<?php echo $SITE_URL; ?>/study_materials">Study Materials</a></li>
                                </ul>
                            </li>
                            <li id="menu-item-84"
                                class="menu-item menu-item-type-custom menu-item-object-custom menu-item-84"><a
                                    href="<?php echo $SITE_URL; ?>/contact_us">Contact us</a>
                            </li>
                            <li id="menu-item-84"
                                class="menu-item menu-item-type-custom menu-item-object-custom menu-item-84"><a
                                    href="<?php echo $SITE_URL; ?>/donate_us"
                                    style="background:#fdc800; padding:10px; border-radius:10px;">
                                    <blink>Donate us</blink>
                                </a>
                            </li>

                        </ul>
                    </nav>
                </div>
            </div>


            <!--end site menu -->

        </div>
    </div>
</header>

<div id="mobile-menu-sticky-placeholder"></div>

<div class="rt-header-menu mean-container mobile-offscreen-menu header-icon-round" id="meanmenu">
    <div class="mean-bar">
        <div class="mobile-logo">
            <div class="site-branding">
                <a class="dark-logo" href="<?php echo $SITE_URL; ?>"><img src="images/logo.png"
                        alt="<?php echo $SITE_TITLE; ?>"></a>
            </div>
        </div>

        <div class="header-icon-area">
            <ul class="header-btn">
                <li class="offcanvar_bar button" style="order: 99">
                    <span class="sidebarBtn ">
                        <span class="fa fa-bars">
                        </span>
                    </span>
                </li>
            </ul>
        </div>

    </div>

    <div class="rt-slide-nav">
        <div class="offscreen-navigation">

            <!--<div class="top-menu-category">
    <div id="site-navigation" class="main-navigation">
        <nav>
            <ul class="menu">
                <li class="menu-item menu-item-has-children">
                    <a href="#">
                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="4" height="4" fill="black"/>
                            <rect y="18" width="4" height="4" fill="black"/>
                            <rect y="9" width="4" height="4" fill="black"/>
                            <rect x="9" width="4" height="4" fill="black"/>
                            <rect x="9" y="18" width="4" height="4" fill="black"/>
                            <rect x="9" y="9" width="4" height="4" fill="black"/>
                            <rect x="18" width="4" height="4" fill="black"/>
                            <rect x="18" y="18" width="4" height="4" fill="black"/>
                            <rect x="18" y="9" width="4" height="4" fill="black"/>
                        </svg>
                        Category                    </a>

                    <ul class="sub-menu ">
                                                    <li>
                                <a href="#">Kids</a>
                            </li>
                                                    <li>
                                <a href="#">Web Development</a>
                            </li>
                                                    <li>
                                <a href="#">Programming</a>
                            </li>
                                                    <li>
                                <a href="#">Technology</a>
                            </li>
                        
                    </ul>

                </li>
            </ul>
        </nav>
    </div>
</div>-->
            <nav class="menu-main-menu-container">

                <ul id="menu-main-menu" class="menu">
                    <li id="menu-item-57"
                        class="mega-menu hide-header menu-item menu-item-type-custom menu-item-object-custom current-menu-ancestor menu-item-57">
                        <a href="<?php echo $SITE_URL; ?>/index">Home</a>
                    </li>

                    <li id="menu-item-82"
                        class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-82">
                        <a href="#">Who We Are</a>
                        <ul class="sub-menu">
                            <li id="menu-item-883"
                                class="menu-item menu-item-type-post_type menu-item-object-lp_course menu-item-883"><a
                                    href="<?php echo $SITE_URL; ?>/about_us">About us</a></li>
                            <li id="menu-item-1690"
                                class="menu-item menu-item-type-custom menu-item-object-custom menu-item-1690"><a
                                    href="<?php echo $SITE_URL; ?>/vision_mission">Vision & Mission</a></li>
                            <li id="menu-item-1691"
                                class="menu-item menu-item-type-custom menu-item-object-custom menu-item-1691"><a
                                    href="<?php echo $SITE_URL; ?>/our_team">Our Team</a></li>
                            <li id="menu-item-1691"
                                class="menu-item menu-item-type-custom menu-item-object-custom menu-item-1691"><a
                                    href="<?php echo $SITE_URL; ?>/annual_report">Annual Report</a></li>
                            <li id="menu-item-1691"
                                class="menu-item menu-item-type-custom menu-item-object-custom menu-item-1691"><a
                                    href="<?php echo $SITE_URL; ?>/where_we_work">Where We Work</a></li>
                        </ul>
                    </li>
                    <li id="menu-item-83"
                        class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-83">
                        <a href="#">Our Work</a>
                        <ul class="sub-menu">
                            <li id="menu-item-957"
                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-957"><a
                                    href="https://education.lkvmbihar.in/">Education</a></li>
                            <li id="menu-item-956"
                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-956"><a
                                    href="<?php echo $SITE_URL; ?>/skill_development">Skill Development</a></li>
                            <li id="menu-item-955"
                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-955"><a
                                    href="<?php echo $SITE_URL; ?>/health">Health</a></li>
                            <li id="menu-item-955"
                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-955"><a
                                    href="<?php echo $SITE_URL; ?>/livelihood">Livelihood</a></li>
                            <li id="menu-item-955"
                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-955"><a
                                    href="<?php echo $SITE_URL; ?>/environment">Environment</a></li>
                        </ul>
                    </li>
                    <li id="menu-item-84"
                        class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-84">
                        <a href="#">Achievement</a>
                        <ul class="sub-menu">
                            <li id="menu-item-886"
                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-886"><a
                                    href="<?php echo $SITE_URL; ?>/impact">Impact</a></li>
                            <li id="menu-item-886"
                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-886"><a
                                    href="<?php echo $SITE_URL; ?>/innovations">Innovations</a></li>
                            <li id="menu-item-886"
                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-886"><a
                                    href="<?php echo $SITE_URL; ?>/testimonial">Testimonial</a></li>
                            <li id="menu-item-886"
                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-886"><a
                                    href="<?php echo $SITE_URL; ?>/award_recognition">Award & Recognition</a></li>
                        </ul>
                    </li>

                    <li id="menu-item-84"
                        class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-84">
                        <a href="#">Partners</a>
                        <ul class="sub-menu">
                            <li id="menu-item-886"
                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-886"><a
                                    href="<?php echo $SITE_URL; ?>/government_partners">Government Partners</a></li>
                            <li id="menu-item-886"
                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-886"><a
                                    href="<?php echo $SITE_URL; ?>/corporate_partners">Corporate Partners</a></li>
                            <li id="menu-item-886"
                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-886"><a
                                    href="<?php echo $SITE_URL; ?>/institutions">Institution & Organization</a></li>
                        </ul>
                    </li>

                    <li id="menu-item-84"
                        class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-84">
                        <a href="#">Get Involved</a>
                        <ul class="sub-menu">
                            <li id="menu-item-86"
                                class="menu-item menu-item-type-post_type menu-item-object-post menu-item-86"><a
                                    href="<?php echo $SITE_URL; ?>/Volunteer">Volunteer/in-team</a></li>
                            <li id="menu-item-86"
                                class="menu-item menu-item-type-post_type menu-item-object-post menu-item-86"><a
                                    href="<?php echo $SITE_URL; ?>/partner_us">Partner with us</a></li>
                            <li id="menu-item-86"
                                class="menu-item menu-item-type-post_type menu-item-object-post menu-item-86"><a
                                    href="<?php echo $SITE_URL; ?>/career">Career</a></li>
                        </ul>
                    </li>

                    <li id="menu-item-84"
                        class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-84">
                        <a href="#">Media</a>
                        <ul class="sub-menu">
                            <li id="menu-item-86"
                                class="menu-item menu-item-type-post_type menu-item-object-post menu-item-86"><a
                                    href="<?php echo $SITE_URL; ?>/media_room">Media Room</a></li>
                            <li id="menu-item-86"
                                class="menu-item menu-item-type-post_type menu-item-object-post menu-item-86"><a
                                    href="<?php echo $SITE_URL; ?>/photo_gallery">Photo Gallery</a></li>
                            <li id="menu-item-86"
                                class="menu-item menu-item-type-post_type menu-item-object-post menu-item-86"><a
                                    href="<?php echo $SITE_URL; ?>/video_gallery">Video Gallery</a></li>
                            <li id="menu-item-86"
                                class="menu-item menu-item-type-post_type menu-item-object-post menu-item-86"><a
                                    href="<?php echo $SITE_URL; ?>/news">News</a></li>
                            <!--<li id="menu-item-86" class="menu-item menu-item-type-post_type menu-item-object-post menu-item-86"><a href="<?php echo $SITE_URL; ?>/publications">Publications</a></li>
<li id="menu-item-86" class="menu-item menu-item-type-post_type menu-item-object-post menu-item-86"><a href="<?php echo $SITE_URL; ?>/press_release">Press Release</a></li>-->
                            <li id="menu-item-86"
                                class="menu-item menu-item-type-post_type menu-item-object-post menu-item-86"><a
                                    href="<?php echo $SITE_URL; ?>/event">Event</a></li>
                            <li id="menu-item-86"
                                class="menu-item menu-item-type-post_type menu-item-object-post menu-item-86"><a
                                    href="<?php echo $SITE_URL; ?>/press_coverage">Press Coverage</a></li>
                            <li id="menu-item-86"
                                class="menu-item menu-item-type-post_type menu-item-object-post menu-item-86"><a
                                    href="<?php echo $SITE_URL; ?>/study_materials">Study Materials</a></li>
                        </ul>
                    </li>
                    <li id="menu-item-84" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-84">
                        <a href="<?php echo $SITE_URL; ?>/contact_us">Contact us</a>
                    </li>
                    <li id="menu-item-84" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-84">
                        <a href="<?php echo $SITE_URL; ?>/donate_us"
                            style="background:#fdc800; padding:10px; border-radius:10px;">
                            <blink>Donate us</blink>
                        </a>
                    </li>

                </ul>
            </nav>
        </div>
    </div>
</div>
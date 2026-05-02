<?php
// Enable output buffering with gzip compression for faster delivery
ob_start('ob_gzhandler');

// Set cache headers for static content (5 minutes)
header('Cache-Control: public, max-age=300');
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 300) . ' GMT');
?>
<!doctype html>
<html lang="en-US">

<head>
    <?php include('csslink.php'); ?>
</head>

<body
    class="home page-template-default page page-id-54 wp-embed-responsive theme-eikra woocommerce-no-js Eikra-version-4.4.6 header-style-9 footer-style-2 has-topbar topbar-style-7 no-sidebar rt-course-grid-view product-grid-view wpb-js-composer js-comp-ver-6.9.0 vc_responsive">
    <?//php include('loader.php'); ?>
    <div id="page" class="site">
        <?php include('header.php'); ?>
        <div id="content" class="site-content">
            <div id="primary" class="content-area">
                <?php include('slider.php'); ?>
                <?php include('section.php'); ?>
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12 col-12">
                            <main id="main" class="site-main">
                                <article id="post-54" class="post-54 page type-page status-publish hentry">
                                    <div class="entry-content">
                                        <?php include('section1.php'); ?>
                                        <?//php include('section2.php'); ?>
                                        <?php include('section_projects.php'); ?>
                                        <?php include('section3.php'); ?>
                                        <?php include('section4.php'); ?>
                                        <?php include('section5.php'); ?>

                                        <?php include('section7.php'); ?>
                                        <?//php include('section8.php'); ?>
                                        <?php include('section6.php'); ?>
                                        <?php include('section9.php'); ?>
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
<?php
// Flush output buffer
ob_end_flush();
?>
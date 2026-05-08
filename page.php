<?php
require_once 'connection.php';

// Get the slug from URL, default to 404 if not present
$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';

// Fetch the page content from the database using a prepared statement to prevent SQL injection
$stmt = $con->prepare("SELECT * FROM webpage WHERE slug = ? AND status = 1");
$stmt->bind_param("s", $slug);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $res = $result->fetch_assoc();
} else {
    // If no page found, we could redirect to a 404 page or show a generic message
    http_response_code(404);
    $res = null;
}
?>
<!doctype html>
<html lang="en-US">
<head>
    <?php include('csslink.php'); ?>
    
    <!-- Dynamic SEO Meta Tags -->
    <?php if ($res): ?>
        <title><?php echo htmlspecialchars($res['title'] ? $res['title'] : $res['name']); ?> - <?php echo $SITE_TITLE; ?></title>
        <meta name="description" content="<?php echo htmlspecialchars(strip_tags(substr($res['des'], 0, 160))); ?>">
    <?php else: ?>
        <title>Page Not Found - <?php echo $SITE_TITLE; ?></title>
    <?php endif; ?>

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
<body class="home page-template-default page wp-embed-responsive theme-eikra woocommerce-no-js Eikra-version-4.4.6 header-style-9 footer-style-2 has-topbar topbar-style-7 no-sidebar rt-course-grid-view product-grid-view wpb-js-composer js-comp-ver-6.9.0 vc_responsive">
    
    <div id="page" class="site">
        <?php include('header.php'); ?>

        <div id="content" class="site-content">
            <div class="entry-banner">
                <div class="container">
                    <div class="entry-banner-content">
                        <h1 class="entry-title"><?php echo $res ? $res['name'] : 'Page Not Found'; ?></h1>
                        <div class="breadcrumb-area">
                            <div class="entry-breadcrumb">
                                <span property="itemListElement" typeof="ListItem">
                                    <a property="item" typeof="WebPage" title="Home" href="<?php echo $SITE_URL; ?>" class="home">
                                        <span property="name">Home</span>
                                    </a>
                                    <meta property="position" content="1">
                                </span> &gt; 
                                <span property="itemListElement" typeof="ListItem">
                                    <span property="name" class="post post-page current-item"><?php echo $res ? $res['name'] : '404'; ?></span>
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
                                <article class="page type-page status-publish hentry">
                                    <div class="entry-content">
                                        <div class="vc_row wpb_row vc_row-fluid vc_custom_1510748400939">
                                            <div class="wpb_column vc_column_container vc_col-sm-12">
                                                <div class="vc_column-inner">
                                                    <div class="wpb_wrapper">
                                                        <div class="wpb_text_column wpb_content_element">
                                                            <div class="wpb_wrapper" style="min-height: 40vh;">
                                                                <?php if ($res): ?>
                                                                    <?php if (!empty($res['des'])): ?>
                                                                        <?php echo $res['des']; ?>
                                                                    <?php else: ?>
                                                                        <p>Content for this page is coming soon. Please check back later for updated information.</p>
                                                                    <?php endif; ?>
                                                                <?php else: ?>
                                                                    <div class="alert alert-warning text-center">
                                                                        <h4>404 - Page Not Found</h4>
                                                                        <p>The page you are looking for does not exist or has been moved.</p>
                                                                        <a href="<?php echo $SITE_URL; ?>" class="btn btn-primary mt-3">Return to Home</a>
                                                                    </div>
                                                                <?php endif; ?>
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
    </div>
</body>
</html>

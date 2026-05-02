<!doctype html>
<html lang="en-US">

<head>
    <?php 
    $page_title = "Latest News & Events";
    include('csslink.php'); 
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
            <h1 class="entry-title">News</h1>
            <div class="breadcrumb-area">
              <div class="entry-breadcrumb"><!-- Breadcrumb NavXT 7.2.0 -->
                <span property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage" title="Home"
                    href="<?php echo $SITE_URL; ?>" class="home"><span property="name">Home</span></a>
                  <meta property="position" content="1">
                </span> &gt; <span property="itemListElement" typeof="ListItem"><span property="name"
                    class="post post-page current-item">News</span>
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

                                <style>
                                  .collapsible {
                                    background-color: #005580;
                                    color: white;
                                    cursor: pointer;
                                    padding: 18px;
                                    width: 100%;
                                    border: none;
                                    text-align: left;
                                    outline: none;
                                    font-size: 22px;
                                    margin-top: 5px;
                                    margin-bottom: 5px;
                                  }

                                  .active,
                                  .collapsible:hover {
                                    background-color: #f58220;
                                  }

                                  .collapsible:after {
                                    content: '\002B';
                                    color: white;
                                    font-weight: bold;
                                    float: right;
                                    margin-left: 5px;
                                  }

                                  .active:after {
                                    content: "\2212";
                                  }

                                  .content {
                                    padding: 0 18px;
                                    max-height: 0;
                                    font-size: 20px;
                                    overflow: hidden;
                                    transition: max-height 0.2s ease-out;
                                    background-color: #f1f1f1;
                                  }

                                  .content p {
                                    font-size: 20px;
                                  }
                                </style>
                                <?php
                                $status = 1;
                                // Include connection if not already included (it seems header.php might not include it globally for this scope)
                                require_once 'connection.php';
                                // Use global constant or logic for specific news if needed, but here filtering by status
                                $type = 'news';
                                $sel = $con->prepare("select * from news where status=? AND (type=? OR type IS NULL OR type='') order by id DESC");
                                $sel->bind_param("is", $status, $type);
                                $sel->execute();
                                $result = $sel->get_result();
                                $i = 0;
                                while ($resm = $result->fetch_assoc()) {
                                  ?>
                                  <button class="collapsible"><?php echo htmlspecialchars($resm['name']); ?></button>
                                  <div class="content">
                                    <p><?php echo nl2br(htmlspecialchars($resm['des'])); ?></p>
                                  </div>
                                  <?php $i++;
                                } ?>

                                <script>
                                  var coll = document.getElementsByClassName("collapsible");
                                  var i;

                                  for (i = 0; i < coll.length; i++) {
                                    coll[i].addEventListener("click", function () {
                                      this.classList.toggle("active");
                                      var content = this.nextElementSibling;
                                      if (content.style.maxHeight) {
                                        content.style.maxHeight = null;
                                      } else {
                                        content.style.maxHeight = content.scrollHeight + "px";
                                      }
                                    });
                                  }
                                </script>

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
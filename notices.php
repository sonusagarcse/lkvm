<!doctype html>
<html lang="en-US">

<head>
  <?php 
    $page_title = "Official Notices";
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
  </style>
</head>

<body
  class="home page-template-default page page-id-54 wp-embed-responsive theme-eikra woocommerce-no-js Eikra-version-4.4.6 header-style-9 footer-style-2 has-topbar topbar-style-7 no-sidebar rt-course-grid-view product-grid-view wpb-js-composer js-comp-ver-6.9.0 vc_responsive">
  <div id="page" class="site">
    <?php include('header.php'); ?>

    <div id="content" class="site-content">
      <div class="entry-banner">
        <div class="container">
          <div class="entry-banner-content">
            <h1 class="entry-title">Notice Board</h1>
            <div class="breadcrumb-area">
              <div class="entry-breadcrumb">
                <span property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage" title="Home"
                    href="<?php echo $SITE_URL; ?>" class="home"><span property="name">Home</span></a>
                  <meta property="position" content="1">
                </span> &gt; <span property="itemListElement" typeof="ListItem"><span property="name"
                    class="post post-page current-item">Notice Board</span>
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
                <article class="post type-page status-publish hentry">
                  <div class="entry-content">
                    <div class="vc_row wpb_row vc_row-fluid">
                      <div class="wpb_column vc_column_container vc_col-sm-12">
                        <div class="vc_column-inner">
                          <div class="wpb_wrapper">
                            
                            <style>
                              .collapsible {
                                background-color: #cc2b5e;
                                color: white;
                                cursor: pointer;
                                padding: 18px;
                                width: 100%;
                                border: none;
                                text-align: left;
                                outline: none;
                                font-size: 20px;
                                margin-top: 10px;
                                border-radius: 8px;
                                font-weight: 600;
                                display: flex;
                                justify-content: space-between;
                                align-items: center;
                                transition: 0.3s;
                                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
                              }

                              .active,
                              .collapsible:hover {
                                background-color: #753a88;
                              }

                              .collapsible:after {
                                content: '\002B';
                                font-size: 24px;
                              }

                              .active:after {
                                content: "\2212";
                              }

                              .notice-content {
                                padding: 0 25px;
                                max-height: 0;
                                overflow: hidden;
                                transition: max-height 0.3s ease-out;
                                background-color: #fff;
                                border: 1px solid #eee;
                                border-top: 0;
                                border-bottom-left-radius: 8px;
                                border-bottom-right-radius: 8px;
                                margin-bottom: 10px;
                              }
                              .notice-content p {
                                padding: 20px 0;
                                margin-bottom: 0;
                                line-height: 1.6;
                                color: #444;
                              }
                              .notice-date {
                                font-size: 14px;
                                opacity: 0.8;
                                font-weight: 400;
                              }
                            </style>

                            <?php
                            require_once 'connection.php';
                            $status = 1;
                            $type = 'notice';
                            $sel = $con->prepare("SELECT * FROM news WHERE status=? AND type=? ORDER BY id DESC");
                            $sel->bind_param("is", $status, $type);
                            $sel->execute();
                            $result = $sel->get_result();
                            
                            if ($result->num_rows > 0) {
                                while ($resm = $result->fetch_assoc()) {
                                  ?>
                                  <button class="collapsible">
                                    <span><?php echo htmlspecialchars($resm['name']); ?></span>
                                    <span class="notice-date"><i class="far fa-calendar-alt"></i> <?php echo date('d M Y', strtotime($resm['date'] ?? 'now')); ?></span>
                                  </button>
                                  <div class="notice-content">
                                    <p><?php echo nl2br(htmlspecialchars($resm['des'])); ?></p>
                                  </div>
                                  <?php
                                }
                            } else {
                                echo '<div class="alert alert-info">No notices found at the moment.</div>';
                            }
                            ?>

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
                </article>
              </main>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php include('footer.php'); ?>
</body>
</html>

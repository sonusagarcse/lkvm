<?php
include('connection.php');
// Header included in body
// Frontend usually uses 'header.php' in root.
?>
<!doctype html>
<html lang="en-US">

<head>
    <?php include('csslink.php'); ?>
    <style>
        .entry-banner {
            background: url(images/banner.jpg) no-repeat scroll center center / cover;
        }

        .content-area {
            padding-top: 50px;
            padding-bottom: 50px;
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
                        <h1 class="entry-title">Study Materials</h1>
                        <div class="breadcrumb-area">
                            <div class="entry-breadcrumb">
                                <span property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage"
                                        title="Go to Home Page." href="<?php echo $SITE_URL; ?>" class="home"><span
                                            property="name">Home</span></a>
                                    <meta property="position" content="1">
                                </span> &gt; <span property="itemListElement" typeof="ListItem"><span property="name"
                                        class="post post-page current-item">Study Materials</span>
                                    <meta property="position" content="2">
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="primary" class="content-area">
                <div class="container">
                    <!-- Filter Form -->
                    <div class="row mb-5">
                        <div class="col-md-12">
                            <form method="GET" class="row g-3 p-4 border rounded shadow-sm">
                                <div class="col-md-5">
                                    <select name="course" class="form-control" onchange="this.form.submit()">
                                        <option value="">Select Course</option>
                                        <?php
                                        $c_q = mysqli_query($con, "SELECT * FROM courses WHERE status=1 ORDER BY name ASC");
                                        while ($c = mysqli_fetch_assoc($c_q)) {
                                            $selected = (isset($_GET['course']) && $_GET['course'] == $c['id']) ? 'selected' : '';
                                            echo "<option value='{$c['id']}' $selected>{$c['name']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <select name="subject" class="form-control" onchange="this.form.submit()">
                                        <option value="">Select Subject</option>
                                        <?php
                                        if (isset($_GET['course']) && !empty($_GET['course'])) {
                                            $cid = intval($_GET['course']);
                                            $s_q = mysqli_query($con, "SELECT * FROM subjects WHERE cid=$cid ORDER BY name ASC");
                                            while ($s = mysqli_fetch_assoc($s_q)) {
                                                $selected = (isset($_GET['subject']) && $_GET['subject'] == $s['id']) ? 'selected' : '';
                                                echo "<option value='{$s['id']}' $selected>{$s['name']}</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <a href="study_materials.php" class="btn btn-secondary w-100">Reset</a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Material List -->
                    <div class="row">
                        <?php
                        $where = "WHERE status=1";
                        if (isset($_GET['course']) && !empty($_GET['course'])) {
                            $cid = intval($_GET['course']);
                            $where .= " AND cid=$cid";
                        }
                        if (isset($_GET['subject']) && !empty($_GET['subject'])) {
                            $sid = intval($_GET['subject']);
                            $where .= " AND sid=$sid";
                        }

                        $query = "SELECT * FROM studymaterials $where ORDER BY id DESC";
                        $result = mysqli_query($con, $query);

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100 shadow-sm">
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
                                            <p class="card-text text-muted">
                                                Date: <?php echo $row['date']; ?>
                                            </p>
                                            <?php if (!empty($row['driveadd'])): ?>
                                                <div class="text-center mt-3">
                                                    <button type="button" class="btn btn-primary"
                                                        onclick="viewMaterial(<?php echo $row['id']; ?>)">
                                                        <i class="fas fa-eye"></i> View Material
                                                    </button>
                                                    <!-- Hidden content container -->
                                                    <div id="content-<?php echo $row['id']; ?>" style="display:none;">
                                                        <div class="ratio ratio-16x9">
                                                            <?php echo $row['driveadd']; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <div class="alert alert-warning">No content available.</div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        } else {
                            echo '<div class="col-12"><div class="alert alert-info text-center">No study materials found. Please select a course/subject.</div></div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="materialModal" tabindex="-1" role="dialog" aria-labelledby="materialModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="materialModalLabel">Study Material</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="modalBody">
                        <!-- Content will be loaded here -->
                    </div>
                </div>
            </div>
        </div>

        <script>
            function viewMaterial(id) {
                var content = document.getElementById('content-' + id).innerHTML;
                document.getElementById('modalBody').innerHTML = content;
                $('#materialModal').modal('show');
            }
            // Stop video/content when modal is closed
            $('#materialModal').on('hidden.bs.modal', function () {
                document.getElementById('modalBody').innerHTML = '';
            });
        </script>


        <?php include('footer.php'); ?>
    </div>
</body>

</html>
<?php
require_once dirname(__DIR__) . '/connection.php';
$slug = $_GET['slug'] ?? '';

// Fetch course details
$query = "SELECT c.*, cat.name as category_name 
          FROM lms_courses c 
          LEFT JOIN lms_categories cat ON c.category_id = cat.id 
          WHERE c.slug = ? AND c.status = 1 LIMIT 1";
$stmt = $con->prepare($query);
$stmt->bind_param("s", $slug);
$stmt->execute();
$course = $stmt->get_result()->fetch_assoc();

if (!$course) {
    header("Location: index.php");
    exit;
}

$page_title = $course['title'];
include 'includes/header.php';
?>

<div class="course-details-wrap py-5">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-8 order-2 order-lg-1">
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Courses</a></li>
                        <li class="breadcrumb-item active"><?php echo htmlspecialchars($course['category_name']); ?>
                        </li>
                    </ol>
                </nav>

                <h1 class="display-5 fw-bold mb-4"><?php echo htmlspecialchars($course['title']); ?></h1>
                <p class="lead text-muted mb-4"><?php echo htmlspecialchars($course['short_desc']); ?></p>

                <div class="d-flex flex-wrap gap-4 mb-5 pb-4 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-3">
                            <i class="fa fa-user-tie"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Instructor</small>
                            <span class="fw-semibold"><?php echo htmlspecialchars($course['instructor']); ?></span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-info bg-opacity-10 text-info p-2 rounded-3">
                            <i class="fa fa-signal"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Level</small>
                            <span class="fw-semibold"><?php echo htmlspecialchars($course['level']); ?></span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-success bg-opacity-10 text-success p-2 rounded-3">
                            <i class="fa fa-clock"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Duration</small>
                            <span class="fw-semibold"><?php echo $course['duration']; ?> Hours</span>
                        </div>
                    </div>
                </div>

                <div class="course-content-tabs mb-5">
                    <h3 class="fw-bold mb-4">Course Description</h3>
                    <div class="text-muted" style="line-height: 1.8;">
                        <?php echo !empty($course['long_desc']) ? nl2br(htmlspecialchars($course['long_desc'])) : 'No detailed description available yet.'; ?>
                    </div>
                </div>

                <div class="curriculum-preview mt-5">
                    <h3 class="fw-bold mb-4">Course Curriculum</h3>
                    <div class="curriculum-list border rounded-4 overflow-hidden bg-white shadow-sm">
                        <?php
                        $lessons_q = mysqli_query($con, "SELECT * FROM lms_lessons WHERE course_id = " . $course['id'] . " ORDER BY sort_order ASC, id ASC");
                        if (mysqli_num_rows($lessons_q) > 0):
                            while ($lesson = mysqli_fetch_assoc($lessons_q)):
                                $is_preview = $lesson['is_preview'];
                                ?>
                                <div
                                    class="d-flex justify-content-between align-items-center p-4 border-bottom lesson-preview-item">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-light p-2 rounded-circle text-muted">
                                            <i
                                                class="fa <?php echo $lesson['content_type'] == 'video' ? 'fa-play-circle' : 'fa-file-alt'; ?>"></i>
                                        </div>
                                        <span class="fw-semibold"><?php echo htmlspecialchars($lesson['title']); ?></span>
                                    </div>
                                    <div>
                                        <?php if ($is_preview): ?>
                                            <a href="learn.php?slug=<?php echo $course['slug']; ?>&lesson=<?php echo $lesson['id']; ?>"
                                                class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold">Free Preview</a>
                                        <?php else: ?>
                                            <i class="fa fa-lock text-muted opacity-50"></i>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php
                            endwhile;
                        else:
                            echo "<div class='p-5 text-center text-muted'>Curriculum details coming soon!</div>";
                        endif;
                        ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 order-1 order-lg-2">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden sticky-top" style="top: 100px;">
                    <div class="ratio ratio-16x9">
                        <?php if ($course['thumbnail']): ?>
                            <img src="<?php echo $course['thumbnail']; ?>"
                                alt="<?php echo htmlspecialchars($course['title']); ?>" class="object-fit-cover">
                        <?php else: ?>
                            <img src="https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&q=80&w=600"
                                alt="Preview" class="object-fit-cover">
                        <?php endif; ?>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-2 mb-4">
                            <h2 class="fw-bold mb-0">
                                <?php echo $course['price'] > 0 ? '₹' . number_format($course['price'], 0) : 'Free'; ?>
                            </h2>
                            <?php if ($course['discount_price'] > 0): ?>
                                <del class="text-muted">₹<?php echo number_format($course['discount_price'], 0); ?></del>
                            <?php endif; ?>
                        </div>

                        <a href="checkout.php?id=<?php echo $course['id']; ?>"
                            class="btn btn-primary w-100 py-3 rounded-3 fw-bold mb-3 shadow-sm"
                            style="background: var(--primary); border: none;">Enroll Now</a>
                        <p class="text-center text-muted small mb-0">30-Day Money-Back Guarantee</p>

                        <hr class="my-4">

                        <h6 class="fw-bold mb-3">This course includes:</h6>
                        <ul class="list-unstyled d-grid gap-2 mb-0">
                            <li><i class="fa-regular fa-circle-play text-primary me-2"></i> On-demand video content</li>
                            <li><i class="fa-regular fa-file-pdf text-primary me-2"></i> Downloadable resources</li>
                            <li><i class="fa-solid fa-mobile-screen-button text-primary me-2"></i> Access on mobile and
                                TV</li>
                            <li><i class="fa-solid fa-certificate text-primary me-2"></i> Certificate of completion</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
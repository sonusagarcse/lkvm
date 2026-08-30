<?php
$page_title = 'My Learning Dashboard';
include 'includes/header.php';

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['student_id']; // Using registration number as identifier

// Fetch enrolled courses
$query = "SELECT c.*, e.created_at as enrollment_date 
          FROM lms_enrollments e 
          JOIN lms_courses c ON e.course_id = c.id 
          WHERE e.user_id = ? AND e.status = 'completed'
          ORDER BY e.id DESC";
$stmt = $con->prepare($query);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="dashboard-wrap py-5 bg-white">
    <div class="container">
        <div class="row mb-5 align-items-center">
            <div class="col-md-6">
                <h1 class="fw-bold mb-0">My <span class="text-gradient">Learning</span></h1>
                <p class="text-muted mb-0">Track your progress and continue where you left off.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="d-inline-flex align-items-center p-2 bg-light rounded-4 border">
                    <span class="px-3 fw-semibold">Student ID: <?php echo $user_id; ?></span>
                </div>
            </div>
        </div>

        <?php if ($result->num_rows > 0): ?>
            <div class="row g-4">
                <?php while ($course = $result->fetch_assoc()): 
                    // Calculate progress
                    $total_lessons_q = mysqli_query($con, "SELECT COUNT(*) as total FROM lms_lessons WHERE course_id = " . $course['id']);
                    $total_lessons = mysqli_fetch_assoc($total_lessons_q)['total'];
                    
                    $completed_lessons_q = mysqli_query($con, "SELECT COUNT(*) as total FROM lms_progress p JOIN lms_lessons l ON p.lesson_id = l.id WHERE p.user_id = '$user_id' AND l.course_id = " . $course['id']);
                    $completed_lessons = mysqli_fetch_assoc($completed_lessons_q)['total'];
                    
                    $prog_pct = ($total_lessons > 0) ? ($completed_lessons / $total_lessons) * 100 : 0;
                ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="lms-card">
                            <div class="lms-card-img position-relative">
                                <?php if($course['thumbnail']): ?>
                                    <img src="<?php echo $course['thumbnail']; ?>" alt="<?php echo htmlspecialchars($course['title']); ?>" class="w-100 h-100 object-fit-cover">
                                <?php else: ?>
                                    <img src="https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&q=80&w=600" alt="Course" class="w-100 h-100 object-fit-cover">
                                <?php endif; ?>
                                <div class="progress" style="height: 6px; border-radius: 0; position: absolute; bottom: 0; width: 100%; background: rgba(255,255,255,0.3);">
                                    <div class="progress-bar bg-success" style="width: <?php echo $prog_pct; ?>%;"></div>
                                </div>
                            </div>
                            <div class="lms-card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="badge bg-soft-primary text-primary px-2" style="background: #eef2ff;">COURSE</span>
                                    <span class="small fw-bold text-muted"><?php echo round($prog_pct); ?>% COMPLETED</span>
                                </div>
                                <h3 class="lms-card-title h5 mb-4"><?php echo htmlspecialchars($course['title']); ?></h3>
                                <a href="learn.php?slug=<?php echo $course['slug']; ?>" class="btn btn-primary w-100 rounded-3 py-2 fw-bold" style="background: var(--primary); border: none;">
                                    <?php echo $prog_pct > 0 ? 'Continue Learning' : 'Start Learning'; ?>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <img src="https://illustrations.popsy.co/gray/creative-work.svg" alt="Empty" style="height: 250px;" class="mb-4">
                <h2 class="fw-bold">No courses yet!</h2>
                <p class="text-muted mb-4">You haven't enrolled in any courses yet. Explore our catalog and start learning today!</p>
                <a href="index.php" class="btn btn-signup btn-lg px-5">Explore Courses</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

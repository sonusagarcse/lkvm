<?php
$page_title = 'Admin Dashboard';
include 'includes/header.php';

// Fetch stats
$total_courses = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as total FROM lms_courses"))['total'];
$total_lessons = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as total FROM lms_lessons"))['total'];
$total_students = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as total FROM lms_students"))['total'];
$total_revenue = mysqli_fetch_assoc(mysqli_query($con, "SELECT SUM(amount) as total FROM lms_enrollments WHERE status = 'completed'"))['total'] ?? 0;
?>

<style>
    .stat-card-premium { border: none; border-radius: 24px; padding: 30px; position: relative; overflow: hidden; transition: 0.3s; background: #fff; box-shadow: 0 10px 30px rgba(0,0,0,0.02); }
    .stat-card-premium:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0,0,0,0.05); }
    .stat-icon-box { width: 60px; height: 60px; border-radius: 18px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 20px; }
    .bg-gradient-primary { background: linear-gradient(135deg, #6366f1 0%, #4338ca 100%); color: #fff; }
    .bg-gradient-success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #fff; }
    .bg-gradient-info { background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); color: #fff; }
    .bg-gradient-warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: #fff; }
    .table-premium thead th { background: #f8fafc; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 1px; font-weight: 800; color: #64748b; padding: 15px 20px; border: none; }
    .table-premium tbody td { padding: 20px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    .quick-action-btn { background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 15px 20px; display: flex; align-items: center; gap: 15px; text-decoration: none; color: var(--dark); transition: 0.2s; font-weight: 600; }
    .quick-action-btn:hover { background: #f8fafc; border-color: var(--primary); color: var(--primary); transform: translateX(5px); }
</style>

<div class="row g-4 mb-5">
    <div class="col-md-6 col-lg-3">
        <div class="stat-card-premium">
            <div class="stat-icon-box bg-gradient-primary">
                <i class="fa fa-graduation-cap"></i>
            </div>
            <p class="text-muted small fw-bold mb-1 text-uppercase">Total Courses</p>
            <h2 class="fw-800 mb-0"><?php echo $total_courses; ?></h2>
            <div class="mt-3 text-success small fw-bold"><i class="fa fa-arrow-up me-1"></i> 12% <span class="text-muted fw-normal">since last month</span></div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card-premium">
            <div class="stat-icon-box bg-gradient-success">
                <i class="fa fa-users"></i>
            </div>
            <p class="text-muted small fw-bold mb-1 text-uppercase">Active Students</p>
            <h2 class="fw-800 mb-0"><?php echo $total_students; ?></h2>
            <div class="mt-3 text-success small fw-bold"><i class="fa fa-arrow-up me-1"></i> 24% <span class="text-muted fw-normal">new joined</span></div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card-premium">
            <div class="stat-icon-box bg-gradient-info">
                <i class="fa fa-play-circle"></i>
            </div>
            <p class="text-muted small fw-bold mb-1 text-uppercase">Total Lessons</p>
            <h2 class="fw-800 mb-0"><?php echo $total_lessons; ?></h2>
            <div class="mt-3 text-primary small fw-bold"><i class="fa fa-clock me-1"></i> 150h <span class="text-muted fw-normal">total content</span></div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card-premium">
            <div class="stat-icon-box bg-gradient-warning">
                <i class="fa fa-wallet"></i>
            </div>
            <p class="text-muted small fw-bold mb-1 text-uppercase">Revenue</p>
            <h2 class="fw-800 mb-0">₹<?php echo number_format($total_revenue, 0); ?></h2>
            <div class="mt-3 text-danger small fw-bold"><i class="fa fa-chart-line me-1"></i> +₹5.2k <span class="text-muted fw-normal">this week</span></div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-8">
        <div class="admin-card p-4 overflow-hidden">
            <div class="d-flex justify-content-between align-items-center mb-4 px-2">
                <h5 class="fw-800 mb-0">Recent Enrollments</h5>
                <a href="enrollments.php" class="btn btn-sm btn-light rounded-pill px-3 fw-bold">View History</a>
            </div>
            <div class="table-responsive">
                <table class="table table-premium mb-0">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Course</th>
                            <th>Amount</th>
                            <th>Payment ID</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $recent_enrolls = mysqli_query($con, "SELECT e.*, c.title as course_title, s.name as student_name, s.email as student_email 
                                                            FROM lms_enrollments e 
                                                            JOIN lms_courses c ON e.course_id = c.id 
                                                            JOIN lms_students s ON e.user_id = s.id 
                                                            ORDER BY e.id DESC LIMIT 6");
                        if(mysqli_num_rows($recent_enrolls) > 0):
                            while($row = mysqli_fetch_assoc($recent_enrolls)):
                        ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($row['student_name']); ?>&background=random" class="rounded-circle" style="width: 38px;">
                                    <div>
                                        <div class="fw-bold small"><?php echo htmlspecialchars($row['student_name']); ?></div>
                                        <small class="text-muted" style="font-size: 0.7rem;"><?php echo $row['student_email']; ?></small>
                                    </div>
                                </div>
                            </td>
                            <td><div class="fw-semibold small"><?php echo $row['course_title']; ?></div></td>
                            <td><div class="fw-800 text-primary">₹<?php echo number_format($row['amount']); ?></div></td>
                            <td><small class="text-muted font-monospace"><?php echo $row['payment_id'] ?: 'N/A'; ?></small></td>
                            <td><span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 small">Completed</span></td>
                        </tr>
                        <?php 
                            endwhile;
                        else:
                            echo "<tr><td colspan='5' class='text-center py-5 text-muted'>No recent enrollments found.</td></tr>";
                        endif;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4">
        <div class="admin-card p-4">
            <h5 class="fw-800 mb-4">Quick Management</h5>
            <div class="d-grid gap-3">
                <a href="courses.php?action=add" class="quick-action-btn shadow-sm">
                    <div class="bg-primary bg-opacity-10 text-primary p-2 rounded-3"><i class="fa fa-plus"></i></div>
                    <span>Create New Course</span>
                </a>
                <a href="lessons.php" class="quick-action-btn shadow-sm">
                    <div class="bg-info bg-opacity-10 text-info p-2 rounded-3"><i class="fa fa-video"></i></div>
                    <span>Add Video Lesson</span>
                </a>
                <a href="students.php" class="quick-action-btn shadow-sm">
                    <div class="bg-success bg-opacity-10 text-success p-2 rounded-3"><i class="fa fa-user-plus"></i></div>
                    <span>Review Students</span>
                </a>
                <a href="categories.php" class="quick-action-btn shadow-sm">
                    <div class="bg-warning bg-opacity-10 text-warning p-2 rounded-3"><i class="fa fa-folder"></i></div>
                    <span>Organize Categories</span>
                </a>
            </div>

            <div class="mt-5 p-4 rounded-4 bg-primary bg-opacity-10 border border-primary border-opacity-10">
                <h6 class="fw-800 text-primary mb-2">Need Help?</h6>
                <p class="small text-muted mb-3">Check the documentation for advanced LMS configurations and setup.</p>
                <a href="#" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold shadow-sm">Documentation</a>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<?php
$page_title = 'Manage Enrollments';
include 'includes/header.php';
?>

<?php
$stats = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as count, SUM(amount) as revenue FROM lms_enrollments WHERE status='completed'"));
?>

<div class="row g-4 mb-4">
    <div class="col-md-6 col-xl-4">
        <div class="admin-card p-4 border-0 shadow-sm d-flex align-items-center gap-4">
            <div class="bg-primary bg-opacity-10 text-primary p-4 rounded-4">
                <i class="fa fa-shopping-cart fs-2"></i>
            </div>
            <div>
                <p class="text-muted small fw-bold mb-0 text-uppercase">Total Sales</p>
                <h3 class="fw-800 mb-0"><?php echo $stats['count']; ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-4">
        <div class="admin-card p-4 border-0 shadow-sm d-flex align-items-center gap-4">
            <div class="bg-success bg-opacity-10 text-success p-4 rounded-4">
                <i class="fa fa-wallet fs-2"></i>
            </div>
            <div>
                <p class="text-muted small fw-bold mb-0 text-uppercase">Total Revenue</p>
                <h3 class="fw-800 mb-0">₹<?php echo number_format($stats['revenue'], 0); ?></h3>
            </div>
        </div>
    </div>
</div>

<div class="admin-card p-0 border-0 shadow-sm overflow-hidden">
    <div class="p-4 border-bottom bg-white d-flex justify-content-between align-items-center">
        <h5 class="fw-800 mb-0">Enrollment History</h5>
        <div class="d-flex gap-2">
            <select class="form-select form-select-sm bg-light border-0 px-3 rounded-pill" style="width: 150px;">
                <option>All Status</option>
                <option>Completed</option>
                <option>Pending</option>
            </select>
            <button class="btn btn-primary btn-sm rounded-pill px-4 fw-bold shadow-sm" style="background: var(--primary); border: none;">
                <i class="fa fa-filter me-2"></i> Filter
            </button>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table table-premium mb-0">
            <thead>
                <tr>
                    <th class="ps-4">Order ID</th>
                    <th>Student Info</th>
                    <th>Enrolled Course</th>
                    <th>Payment</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th class="text-end pe-4">Receipt</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $enrolls = mysqli_query($con, "SELECT e.*, c.title as course_title, s.name as student_name, s.email as student_email 
                                            FROM lms_enrollments e 
                                            JOIN lms_courses c ON e.course_id = c.id 
                                            JOIN lms_students s ON e.user_id = s.id 
                                            ORDER BY e.id DESC");
                if (mysqli_num_rows($enrolls) > 0):
                    while($row = mysqli_fetch_assoc($enrolls)):
                ?>
                <tr>
                    <td class="ps-4">
                        <span class="fw-bold text-dark">#LMS-<?php echo str_pad($row['id'], 4, '0', STR_PAD_LEFT); ?></span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($row['student_name']); ?>&background=random" class="rounded-circle" style="width: 32px; height: 32px;">
                            <div>
                                <div class="fw-bold small text-dark"><?php echo htmlspecialchars($row['student_name']); ?></div>
                                <small class="text-muted" style="font-size: 0.7rem;"><?php echo htmlspecialchars($row['student_email']); ?></small>
                            </div>
                        </div>
                    </td>
                    <td><div class="small fw-semibold text-primary"><?php echo $row['course_title']; ?></div></td>
                    <td>
                        <div class="fw-800 text-dark">₹<?php echo number_format($row['amount'], 0); ?></div>
                        <div class="text-muted" style="font-size: 0.65rem;">ID: <?php echo $row['payment_id'] ?: 'N/A'; ?></div>
                    </td>
                    <td><div class="small text-muted fw-semibold"><?php echo date('M d, Y', strtotime($row['created_at'])); ?></div></td>
                    <td>
                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 small fw-bold">Success</span>
                    </td>
                    <td class="text-end pe-4">
                        <button class="btn btn-sm btn-light rounded-3 p-2 text-muted" title="View Details"><i class="fa fa-file-invoice"></i></button>
                    </td>
                </tr>
                <?php 
                    endwhile; 
                else:
                    echo "<tr><td colspan='7' class='text-center py-5 text-muted'><i class='fa fa-receipt fs-1 mb-3 d-block opacity-25'></i>No enrollments found.</td></tr>";
                endif;
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

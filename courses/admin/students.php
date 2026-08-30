<?php
$page_title = 'Manage Students';
include 'includes/header.php';

// Handle delete
if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($con, "DELETE FROM lms_students WHERE id = $id");
    echo "<script>window.location.href='students.php';</script>";
}
?>

<div class="admin-card p-0 border-0 shadow-sm overflow-hidden">
    <div class="p-4 border-bottom bg-white d-flex justify-content-between align-items-center">
        <div>
            <h5 class="fw-800 mb-1">Registered Students</h5>
            <p class="text-muted small mb-0">Manage and track your learners at a glance</p>
        </div>
        <div class="d-flex gap-2">
            <div class="input-group input-group-sm d-none d-md-flex" style="width: 250px;">
                <span class="input-group-text bg-light border-0"><i class="fa fa-search text-muted small"></i></span>
                <input type="text" class="form-control bg-light border-0" placeholder="Search students...">
            </div>
            <button class="btn btn-primary btn-sm rounded-pill px-4 fw-bold shadow-sm" style="background: var(--primary); border: none;">
                <i class="fa fa-download me-2"></i> Export
            </button>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table table-premium mb-0">
            <thead>
                <tr>
                    <th class="ps-4">Student Identity</th>
                    <th>Contact Info</th>
                    <th>Enrollments</th>
                    <th>Joined Date</th>
                    <th>Status</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $students = mysqli_query($con, "SELECT s.*, (SELECT COUNT(*) FROM lms_enrollments WHERE user_id = s.id) as enroll_count FROM lms_students s ORDER BY s.id DESC");
                if(mysqli_num_rows($students) > 0):
                    while($row = mysqli_fetch_assoc($students)):
                ?>
                <tr>
                    <td class="ps-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="position-relative">
                                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($row['name']); ?>&background=random" class="rounded-circle" style="width: 45px; height: 45px; object-fit: cover; border: 2px solid #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                                <span class="position-absolute bottom-0 end-0 bg-success border border-white border-2 rounded-circle" style="width: 12px; height: 12px;"></span>
                            </div>
                            <div>
                                <div class="fw-bold text-dark"><?php echo htmlspecialchars($row['name']); ?></div>
                                <div class="text-muted small">ID: #<?php echo $row['id']; ?></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="small fw-semibold text-dark"><i class="fa fa-envelope text-muted me-2 small"></i><?php echo htmlspecialchars($row['email']); ?></div>
                        <div class="small text-muted mt-1"><i class="fa fa-phone text-muted me-2 small"></i><?php echo htmlspecialchars($row['phone']); ?></div>
                    </td>
                    <td>
                        <span class="fw-bold text-primary bg-primary bg-opacity-10 px-3 py-1 rounded-pill small">
                            <?php echo $row['enroll_count']; ?> Courses
                        </span>
                    </td>
                    <td><div class="small text-muted fw-semibold"><?php echo date('M d, Y', strtotime($row['created_at'])); ?></div></td>
                    <td>
                        <?php if($row['status'] == 1): ?>
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 small fw-bold">Active Learner</span>
                        <?php else: ?>
                            <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2 small fw-bold">Deactivated</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-end pe-4">
                        <div class="d-flex gap-2 justify-content-end">
                            <button class="btn btn-sm btn-light rounded-3 p-2 text-primary" title="View Profile"><i class="fa fa-eye"></i></button>
                            <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-light rounded-3 p-2 text-danger" title="Remove" onclick="return confirm('Are you sure you want to delete this student?')"><i class="fa fa-trash-alt"></i></a>
                        </div>
                    </td>
                </tr>
                <?php 
                    endwhile;
                else:
                    echo "<tr><td colspan='6' class='text-center py-5 text-muted'><i class='fa fa-users fs-1 mb-3 d-block opacity-25'></i>No students registered yet.</td></tr>";
                endif;
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

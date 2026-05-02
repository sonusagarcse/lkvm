<?php
// admin/student_details.php
require_once '../connection.php';
include 'includes/header.php';

if (!isset($_GET['id'])) {
    echo "<script>window.location.href='students.php';</script>";
    exit();
}

$id = intval($_GET['id']);
$query = "SELECT r.*, c.name as course_name, b.bcode, b.bname, b.email as branch_email 
          FROM registration r 
          LEFT JOIN courses c ON r.course = c.id 
          LEFT JOIN branch b ON r.bid = b.id 
          WHERE r.id = $id";
$result = mysqli_query($con, $query);
$student = mysqli_fetch_assoc($result);

if (!$student) {
    echo "<div class='alert alert-danger'>Student not found.</div>";
    include 'includes/footer.php';
    exit();
}
?>

<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h2 class="h3 mb-0 text-gray-800">Student Details</h2>
        <a href="students.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to List</a>
    </div>
</div>

<div class="row">
    <!-- Student Profile Card -->
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm rounded-4 text-center p-4 h-100">
            <?php
            $img = !empty($student['img']) ? "../images/students/" . $student['img'] : 'assets/img/default-avatar.png'; // Path assumption
            // Note: user paths might differ, check previous analysis to be sure, or defaults.
            ?>
            <div class="mb-3">
                <i class="fas fa-user-circle fa-6x text-secondary"></i>
                <!-- <img src="<?php echo $img; ?>" class="rounded-circle img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;"> -->
            </div>
            <h4 class="fw-bold"><?php echo $student['name']; ?></h4>
            <p class="text-muted mb-1"><?php echo $student['regno']; ?></p>
            <span class="badge bg-primary mb-3"><?php echo $student['course_name']; ?></span>

            <div class="d-grid gap-2">
                <button class="btn btn-outline-primary"><i class="fas fa-print"></i> Print Details</button>
            </div>
        </div>
    </div>

    <!-- Info Tabs -->
    <div class="col-md-8 mb-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white">
                <h5 class="m-0 fw-bold text-primary">Personal & Academic Information</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="small text-muted fw-bold">Father's Name</label>
                        <p class="border-bottom pb-2"><?php echo $student['father']; ?></p>
                    </div>
                    <div class="col-md-6">
                        <label class="small text-muted fw-bold">Mother's Name</label>
                        <p class="border-bottom pb-2"><?php echo $student['mother']; ?></p>
                    </div>
                    <div class="col-md-6">
                        <label class="small text-muted fw-bold">Date of Birth</label>
                        <p class="border-bottom pb-2"><?php echo $student['dob']; ?></p>
                    </div>
                    <div class="col-md-6">
                        <label class="small text-muted fw-bold">Gender</label>
                        <p class="border-bottom pb-2"><?php echo $student['gender']; ?></p>
                    </div>
                    <div class="col-md-6">
                        <label class="small text-muted fw-bold">Mobile</label>
                        <p class="border-bottom pb-2"><?php echo $student['mob']; ?></p>
                    </div>
                    <div class="col-md-6">
                        <label class="small text-muted fw-bold">Email</label>
                        <p class="border-bottom pb-2"><?php echo $student['email']; ?></p>
                    </div>

                    <div class="col-12">
                        <label class="small text-muted fw-bold">Address</label>
                        <p class="border-bottom pb-2">
                            <?php echo $student['address']; ?>,
                            <?php echo $student['dis']; ?>, <?php echo $student['state']; ?> -
                            <?php echo $student['pincode']; ?>
                        </p>
                    </div>

                    <div class="col-md-6">
                        <label class="small text-muted fw-bold">Date of Joining</label>
                        <p class="border-bottom pb-2"><?php echo $student['doj']; ?></p>
                    </div>
                    <div class="col-md-6">
                        <label class="small text-muted fw-bold">Enrollment Date</label>
                        <p class="border-bottom pb-2"><?php echo $student['date']; ?></p>
                    </div>
                    <div class="col-md-6">
                        <label class="small text-muted fw-bold">Branch</label>
                        <p class="border-bottom pb-2"><?php echo $student['bname']; ?>
                            (<?php echo $student['bcode']; ?>)</p>
                    </div>
                    <div class="col-md-6">
                        <label class="small text-muted fw-bold">Account Status</label>
                        <p class="pb-2">
                            <?php echo $student['status'] == 1 ? '<span class="text-success fw-bold">Active</span>' : '<span class="text-danger fw-bold">Inactive</span>'; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
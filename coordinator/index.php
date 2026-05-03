<?php
// admin/index.php
require_once '../connection.php';
include 'includes/header.php';

// Fetch User Name for Greeting
$coordName = isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : 'Coordinator';

// Count Employees
$empQuery = mysqli_query($con, "SELECT COUNT(*) as total FROM employees");
$empCount = mysqli_fetch_assoc($empQuery)['total'];



// Count Attendance Log for Today
$today = date('Y-m-d');
$todayAttQuery = mysqli_query($con, "SELECT COUNT(DISTINCT emp_no) as total FROM emp_attendance_logs WHERE DATE(log_time) = '$today'");
$todayAttCount = mysqli_fetch_assoc($todayAttQuery)['total'];

?>

<div class="row mb-4">
    <div class="col-12">
        <h2 class="h3 mb-0 text-gray-800">Welcome, <?php echo htmlspecialchars($coordName); ?> 👋</h2>
        <p class="text-muted">Coordinator Panel - Manage your employee payroll and attendance.</p>
    </div>
</div>

<div class="row">

    <!-- Employees Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card card-stat h-100 py-2">
            <div class="card-body">
                <div class="text-box">
                    <h5>Total Employees</h5>
                    <h3><?php echo $empCount; ?></h3>
                </div>
                <div class="icon-box primary">
                    <i class="fas fa-users-cog"></i>
                </div>
            </div>
            <a href="emp_manage.php" class="stretched-link"></a>
        </div>
    </div>

    <!-- Attendance Today Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card card-stat h-100 py-2">
            <div class="card-body">
                <div class="text-box">
                    <h5>Present Today</h5>
                    <h3><?php echo $todayAttCount; ?></h3>
                </div>
                <div class="icon-box success">
                    <i class="fas fa-user-check"></i>
                </div>
            </div>
            <a href="emp_attendance_upload.php" class="stretched-link"></a>
        </div>
    </div>

</div>

<?php include 'includes/footer.php'; ?>
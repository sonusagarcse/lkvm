<?php
// admin/index.php
require_once '../connection.php';
include 'includes/header.php';

// Fetch User Name for Greeting
$adminName = isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : 'Admin';

// Count Branches
$branchQuery = mysqli_query($con, "SELECT COUNT(*) as total FROM branch");
$branchCount = mysqli_fetch_assoc($branchQuery)['total'];

// Count Students (Registration)
$studentQuery = mysqli_query($con, "SELECT COUNT(*) as total FROM registration WHERE status = 1");
$studentCount = mysqli_fetch_assoc($studentQuery)['total'];

// Count Courses
$courseQuery = mysqli_query($con, "SELECT COUNT(*) as total FROM courses WHERE status = 1");
$courseCount = mysqli_fetch_assoc($courseQuery)['total'];

// Count Slider Images
$sliderQuery = mysqli_query($con, "SELECT COUNT(*) as total FROM slider");
$sliderCount = mysqli_fetch_assoc($sliderQuery)['total'];

?>

<div class="row mb-4">
    <div class="col-12">
        <h2 class="h3 mb-0 text-gray-800">Welcome, <?php echo htmlspecialchars($adminName); ?> 👋</h2>
        <p class="text-muted">Here's what's happening today.</p>
    </div>
</div>

<div class="row">

    <!-- Branches Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card card-stat h-100 py-2">
            <div class="card-body">
                <div class="text-box">
                    <h5>Branches</h5>
                    <h3><?php echo $branchCount; ?></h3>
                </div>
                <div class="icon-box primary">
                    <i class="fas fa-code-branch"></i>
                </div>
            </div>
            <!-- Optional: Link overlay -->
            <a href="branches.php" class="stretched-link"></a>
        </div>
    </div>

    <!-- Students Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card card-stat h-100 py-2">
            <div class="card-body">
                <div class="text-box">
                    <h5>Students</h5>
                    <h3><?php echo $studentCount; ?></h3>
                </div>
                <div class="icon-box success">
                    <i class="fas fa-user-graduate"></i>
                </div>
            </div>
            <a href="students.php" class="stretched-link"></a>
        </div>
    </div>

    <!-- Courses Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card card-stat h-100 py-2">
            <div class="card-body">
                <div class="text-box">
                    <h5>Courses</h5>
                    <h3><?php echo $courseCount; ?></h3>
                </div>
                <div class="icon-box info">
                    <i class="fas fa-book"></i>
                </div>
            </div>
            <a href="courses.php" class="stretched-link"></a>
        </div>
    </div>

    <!-- Slider/Gallery Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card card-stat h-100 py-2">
            <div class="card-body">
                <div class="text-box">
                    <h5>Sliders</h5>
                    <h3><?php echo $sliderCount; ?></h3>
                </div>
                <div class="icon-box warning">
                    <i class="fas fa-images"></i>
                </div>
            </div>
            <a href="slider.php" class="stretched-link"></a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 font-weight-bold text-primary">Recent Registrations</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Reg No</th>
                                <th>Name</th>
                                <th>Father's Name</th>
                                <th>Course</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $recentQuery = "SELECT r.regno, r.name, r.father, c.name as course_name, r.date, r.status 
                                          FROM registration r 
                                          LEFT JOIN courses c ON r.course = c.id 
                                          ORDER BY r.id DESC LIMIT 5";
                            $recentResult = mysqli_query($con, $recentQuery);

                            if (mysqli_num_rows($recentResult) > 0) {
                                while ($row = mysqli_fetch_assoc($recentResult)) {
                                    $statusBadge = ($row['status'] == 1) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
                                    echo "<tr>
                                            <td>{$row['regno']}</td>
                                            <td><div class='fw-bold'>{$row['name']}</div></td>
                                            <td>{$row['father']}</td>
                                            <td>{$row['course_name']}</td>
                                            <td>{$row['date']}</td>
                                            <td>{$statusBadge}</td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center'>No recent registrations</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
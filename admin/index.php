<?php
// admin/index.php
require_once '../connection.php';
include 'includes/header.php';

// Fetch User Name for Greeting
$adminName = isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : 'Admin';

// Count Branches
$branchQuery = mysqli_query($con, "SELECT COUNT(*) as total FROM branch");
$branchCount = mysqli_fetch_assoc($branchQuery)['total'] ?? 0;

// Count Students (Registration) - Active
$studentQuery = mysqli_query($con, "SELECT COUNT(*) as total FROM registration WHERE status = 1");
$studentCount = mysqli_fetch_assoc($studentQuery)['total'] ?? 0;

// Count Students - Inactive
$inactiveStudentQuery = mysqli_query($con, "SELECT COUNT(*) as total FROM registration WHERE status = 0");
$inactiveStudentCount = mysqli_fetch_assoc($inactiveStudentQuery)['total'] ?? 0;

// Count Courses
$courseQuery = mysqli_query($con, "SELECT COUNT(*) as total FROM courses WHERE status = 1");
$courseCount = mysqli_fetch_assoc($courseQuery)['total'] ?? 0;

// Count Slider Images
$sliderQuery = mysqli_query($con, "SELECT COUNT(*) as total FROM slider");
$sliderCount = mysqli_fetch_assoc($sliderQuery)['total'] ?? 0;

// Count Coordinators
$coordQuery = mysqli_query($con, "SELECT COUNT(*) as total FROM admin_login WHERE utype = 2");
$coordCount = mysqli_fetch_assoc($coordQuery)['total'] ?? 0;

// Count Enquiries
$enquiryQuery = mysqli_query($con, "SELECT COUNT(*) as total FROM contact");
$enquiryCount = mysqli_fetch_assoc($enquiryQuery)['total'] ?? 0;

// Fetch Course Distribution Data for charts
$courseDistQuery = mysqli_query($con, "SELECT c.name as course_name, COUNT(r.id) as total FROM registration r LEFT JOIN courses c ON r.course = c.id GROUP BY r.course");
$courseLabels = [];
$courseTotals = [];
while ($row = mysqli_fetch_assoc($courseDistQuery)) {
    $courseLabels[] = $row['course_name'] ? $row['course_name'] : 'Unassigned';
    $courseTotals[] = intval($row['total']);
}

// Fetch Registration Trend (Last 7 active days)
$trendQuery = mysqli_query($con, "SELECT date, COUNT(*) as total FROM registration GROUP BY date ORDER BY MIN(id) DESC LIMIT 7");
$trendLabels = [];
$trendTotals = [];
while ($row = mysqli_fetch_assoc($trendQuery)) {
    $trendLabels[] = $row['date'];
    $trendTotals[] = intval($row['total']);
}
$trendLabels = array_reverse($trendLabels);
$trendTotals = array_reverse($trendTotals);

?>

<!-- Include Chart.js CDN for Analytics Charts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    .dashboard-header {
        background: linear-gradient(135deg, #012751 0%, #025191 100%);
        color: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 10px 30px rgba(1, 39, 81, 0.15);
        margin-bottom: 30px;
    }
    .stat-card {
        border: none;
        border-radius: 16px;
        transition: all 0.3s ease;
        overflow: hidden;
        position: relative;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    }
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }
    .card-primary { background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%); color: #0369a1; }
    .card-success { background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); color: #15803d; }
    .card-info { background: linear-gradient(135deg, #e0f7fa 0%, #b2ebf2 100%); color: #00838f; }
    .card-warning { background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); color: #b45309; }
    .card-danger { background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); color: #b91c1c; }
    .card-secondary { background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%); color: #4b5563; }
</style>

<div class="dashboard-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
    <div>
        <h2 class="h3 fw-bold mb-1 text-white">Welcome back, <?php echo htmlspecialchars($adminName); ?>! 👋</h2>
        <p class="mb-0 opacity-75">LKVM Bihar Foundation Central Control Station.</p>
    </div>
    <div class="bg-white bg-opacity-10 rounded-3 px-3 py-2 text-center text-md-end">
        <span class="small d-block opacity-75">System Time</span>
        <span class="fw-bold"><?php echo date('l, d M Y'); ?></span>
    </div>
</div>

<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-6 g-4 mb-4">
    <!-- Active Students Card -->
    <div class="col">
        <div class="card stat-card h-100">
            <div class="card-body d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted fw-bold small">Active Students</span>
                    <div class="stat-icon card-success"><i class="fas fa-user-graduate"></i></div>
                </div>
                <h3><?php echo $studentCount; ?></h3>
                <a href="students" class="stretched-link"></a>
            </div>
        </div>
    </div>
    
    <!-- Branches Card -->
    <div class="col">
        <div class="card stat-card h-100">
            <div class="card-body d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted fw-bold small">Branches</span>
                    <div class="stat-icon card-primary"><i class="fas fa-code-branch"></i></div>
                </div>
                <h3><?php echo $branchCount; ?></h3>
                <a href="branches" class="stretched-link"></a>
            </div>
        </div>
    </div>

    <!-- Courses Card -->
    <div class="col">
        <div class="card stat-card h-100">
            <div class="card-body d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted fw-bold small">Courses</span>
                    <div class="stat-icon card-info"><i class="fas fa-book"></i></div>
                </div>
                <h3><?php echo $courseCount; ?></h3>
                <a href="courses" class="stretched-link"></a>
            </div>
        </div>
    </div>

    <!-- Coordinators Card -->
    <div class="col">
        <div class="card stat-card h-100">
            <div class="card-body d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted fw-bold small">Coordinators</span>
                    <div class="stat-icon card-warning"><i class="fas fa-user-tie"></i></div>
                </div>
                <h3><?php echo $coordCount; ?></h3>
                <a href="coordinators" class="stretched-link"></a>
            </div>
        </div>
    </div>

    <!-- Enquiries Card -->
    <div class="col">
        <div class="card stat-card h-100">
            <div class="card-body d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted fw-bold small">Enquiries</span>
                    <div class="stat-icon card-danger"><i class="fas fa-comment-alt"></i></div>
                </div>
                <h3><?php echo $enquiryCount; ?></h3>
                <a href="enquiries" class="stretched-link"></a>
            </div>
        </div>
    </div>

    <!-- Inactive Students -->
    <div class="col">
        <div class="card stat-card h-100">
            <div class="card-body d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted fw-bold small">Pending Admits</span>
                    <div class="stat-icon card-secondary"><i class="fas fa-user-clock"></i></div>
                </div>
                <h3><?php echo $inactiveStudentCount; ?></h3>
                <a href="students" class="stretched-link"></a>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="row mb-4 g-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="m-0 fw-bold text-dark"><i class="fas fa-chart-line text-primary me-2"></i>Registration Trends</h6>
            </div>
            <div class="card-body p-4">
                <div style="position: relative; height: 300px;">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white py-3 border-0">
                <h6 class="m-0 fw-bold text-dark"><i class="fas fa-chart-pie text-success me-2"></i>Course Distribution</h6>
            </div>
            <div class="card-body p-4">
                <div style="position: relative; height: 300px;">
                    <canvas id="courseChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold text-dark"><i class="fas fa-list text-warning me-2"></i>Recent Registrations</h6>
                <a href="students" class="btn btn-sm btn-outline-primary rounded-3">View All Registrations</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Reg No</th>
                                <th>Name</th>
                                <th>Father's Name</th>
                                <th>Course</th>
                                <th>Date</th>
                                <th class="pe-4">Status</th>
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
                                            <td class='ps-4'>{$row['regno']}</td>
                                            <td><div class='fw-bold text-dark'>{$row['name']}</div></td>
                                            <td>{$row['father']}</td>
                                            <td>{$row['course_name']}</td>
                                            <td>{$row['date']}</td>
                                            <td class='pe-4'>{$statusBadge}</td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center py-4'>No recent registrations</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Trend Chart Configuration
    const trendCtx = document.getElementById('trendChart').getContext('2d');
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($trendLabels); ?>,
            datasets: [{
                label: 'New Registrations',
                data: <?php echo json_encode($trendTotals); ?>,
                borderColor: '#025191',
                backgroundColor: 'rgba(2, 81, 145, 0.05)',
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#025191',
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });

    // Course Distribution Chart Configuration
    const courseCtx = document.getElementById('courseChart').getContext('2d');
    new Chart(courseCtx, {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode($courseLabels); ?>,
            datasets: [{
                data: <?php echo json_encode($courseTotals); ?>,
                backgroundColor: [
                    '#012751', '#025191', '#14b8a6', '#f59e0b', '#ef4444', 
                    '#8b5cf6', '#ec4899', '#10b981', '#6b7280'
                ],
                borderWidth: 2,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { boxWidth: 12, padding: 15 }
                }
            }
        }
    });
</script>

<?php include 'includes/footer.php'; ?>
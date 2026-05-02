<?php
// admin/visitors.php
require_once '../connection.php';
include 'includes/header.php';

// --- Analytics Calculations ---

// 1. Active Now (Last 5 Minutes)
$activeQuery = mysqli_query($con, "SELECT COUNT(DISTINCT ip_address) as total FROM visitor_logs WHERE last_activity > NOW() - INTERVAL 5 MINUTE");
$activeNow = mysqli_fetch_assoc($activeQuery)['total'] ?? 0;

// 2. Today's Unique Visitors
$todayQuery = mysqli_query($con, "SELECT COUNT(DISTINCT ip_address) as total FROM visitor_logs WHERE visit_date = CURDATE()");
$todayVisitors = mysqli_fetch_assoc($todayQuery)['total'] ?? 0;

// 3. Yesterday's Unique Visitors
$yesterdayQuery = mysqli_query($con, "SELECT COUNT(DISTINCT ip_address) as total FROM visitor_logs WHERE visit_date = SUBDATE(CURDATE(), 1)");
$yesterdayVisitors = mysqli_fetch_assoc($yesterdayQuery)['total'] ?? 0;

// 4. This Month's Unique Visitors
$monthQuery = mysqli_query($con, "SELECT COUNT(DISTINCT ip_address) as total FROM visitor_logs WHERE MONTH(visit_date) = MONTH(CURDATE()) AND YEAR(visit_date) = YEAR(CURDATE())");
$monthVisitors = mysqli_fetch_assoc($monthQuery)['total'] ?? 0;

// 5. Total Unique Visitors (Lifetime)
$totalQuery = mysqli_query($con, "SELECT COUNT(DISTINCT ip_address) as total FROM visitor_logs");
$totalVisitors = mysqli_fetch_assoc($totalQuery)['total'] ?? 0;

// 6. Total Page Views (Hits)
$hitsQuery = mysqli_query($con, "SELECT COUNT(*) as total FROM visitor_logs");
$totalHits = mysqli_fetch_assoc($hitsQuery)['total'] ?? 0;
?>

<div class="row mb-4">
    <div class="col-12">
        <h2 class="h3 mb-0 text-gray-800">Visitor Analytics</h2>
        <p class="text-muted">Real-time traffic and historical data insights.</p>
    </div>
</div>

<div class="row">
    <!-- Active Now -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card card-stat h-100 py-2 border-left-danger" style="border-left: 4px solid #e74a3b !important;">
            <div class="card-body">
                <div class="text-box">
                    <h5 class="text-danger">Live Active</h5>
                    <h3><?php echo $activeNow; ?></h3>
                </div>
                <div class="icon-box danger" style="background: rgba(231, 74, 59, 0.1); color: #e74a3b;">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Today -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card card-stat h-100 py-2 border-left-primary" style="border-left: 4px solid #4e73df !important;">
            <div class="card-body">
                <div class="text-box">
                    <h5 class="text-primary">Today</h5>
                    <h3><?php echo $todayVisitors; ?></h3>
                </div>
                <div class="icon-box primary">
                    <i class="fas fa-calendar-day"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Yesterday -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card card-stat h-100 py-2 border-left-info" style="border-left: 4px solid #36b9cc !important;">
            <div class="card-body">
                <div class="text-box">
                    <h5 class="text-info">Yesterday</h5>
                    <h3><?php echo $yesterdayVisitors; ?></h3>
                </div>
                <div class="icon-box info">
                    <i class="fas fa-history"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Hits -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card card-stat h-100 py-2 border-left-warning" style="border-left: 4px solid #f6c23e !important;">
            <div class="card-body">
                <div class="text-box">
                    <h5 class="text-warning">Total Page Views</h5>
                    <h3><?php echo $totalHits; ?></h3>
                </div>
                <div class="icon-box warning" style="background: rgba(246, 194, 62, 0.1); color: #f6c23e;">
                    <i class="fas fa-eye"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- This Month -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card card-stat h-100 py-2 border-left-success" style="border-left: 4px solid #1cc88a !important;">
            <div class="card-body">
                <div class="text-box">
                    <h5 class="text-success">This Month</h5>
                    <h3><?php echo $monthVisitors; ?></h3>
                </div>
                <div class="icon-box success">
                    <i class="fas fa-calendar-alt"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Recent Visitors</h6>
                <span class="badge bg-light text-dark">Total Lifetime: <?php echo $totalVisitors; ?></span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>IP Address</th>
                                <th>Current/Last Page</th>
                                <th>Date</th>
                                <th>Last Activity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Show latest unique IPs to keep the list clean and follow "counted as 1"
                            $logQuery = "SELECT t1.* FROM visitor_logs t1 
                                        INNER JOIN (SELECT ip_address, MAX(last_activity) as max_la FROM visitor_logs GROUP BY ip_address) t2 
                                        ON t1.ip_address = t2.ip_address AND t1.last_activity = t2.max_la 
                                        ORDER BY t1.last_activity DESC LIMIT 15";
                            $logResult = mysqli_query($con, $logQuery);

                            if ($logResult && mysqli_num_rows($logResult) > 0) {
                                while ($row = mysqli_fetch_assoc($logResult)) {
                                    $timeDiff = time() - strtotime($row['last_activity']);
                                    $isOnline = ($timeDiff < 300) ? '<span class="badge bg-success">Online</span>' : '<span class="badge bg-secondary">Offline</span>';
                                    
                                    echo "<tr>
                                            <td><code>{$row['ip_address']}</code> {$isOnline}</td>
                                            <td style='max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;'>{$row['page_url']}</td>
                                            <td>" . date('d M Y', strtotime($row['visit_date'])) . "</td>
                                            <td>" . date('H:i:s', strtotime($row['last_activity'])) . "</td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4' class='text-center'>No visitor logs found yet.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 font-weight-bold text-primary">Top Visited Pages</h6>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <?php
                    $topPagesQuery = mysqli_query($con, "SELECT page_url, COUNT(*) as hits FROM visitor_logs GROUP BY page_url ORDER BY hits DESC LIMIT 10");
                    while ($pageRow = mysqli_fetch_assoc($topPagesQuery)) {
                        echo "<div class='list-group-item d-flex justify-content-between align-items-center px-0'>
                                <span class='text-truncate' style='max-width: 80%;'>{$pageRow['page_url']}</span>
                                <span class='badge bg-primary rounded-pill'>{$pageRow['hits']}</span>
                              </div>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

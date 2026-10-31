<?php
// admin/popup_analytics.php - Analytics Dashboard for Welcome Popup
require_once 'includes/auth_check.php';
require_once '../connection.php';

// Handle Clear Logs action
$message = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clear_analytics'])) {
    if (!verify_csrf_token()) {
        die("CSRF validation failed.");
    }
    if (mysqli_query($con, "TRUNCATE TABLE popup_analytics")) {
        $message = "Popup analytics data has been successfully reset.";
    } else {
        $error = "Failed to reset analytics data.";
    }
}

// Date Range Filter
$range = $_GET['range'] ?? '30d';
$date_condition = "1=1";
if ($range === 'today') {
    $date_condition = "DATE(created_at) = CURDATE()";
} elseif ($range === '7d') {
    $date_condition = "created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
} elseif ($range === '30d') {
    $date_condition = "created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
}

// 1. Overall Totals
$totals_query = mysqli_query($con, "
    SELECT 
        COUNT(CASE WHEN event_type = 'impression' THEN 1 END) AS total_impressions,
        COUNT(CASE WHEN event_type = 'click_primary' THEN 1 END) AS total_primary_clicks,
        COUNT(CASE WHEN event_type = 'click_secondary' THEN 1 END) AS total_secondary_clicks,
        COUNT(CASE WHEN event_type = 'close' THEN 1 END) AS total_closes,
        COUNT(DISTINCT ip_address) AS unique_visitors
    FROM popup_analytics
    WHERE $date_condition
");
$totals = mysqli_fetch_assoc($totals_query);

$total_views = intval($totals['total_impressions'] ?? 0);
$primary_clicks = intval($totals['total_primary_clicks'] ?? 0);
$secondary_clicks = intval($totals['total_secondary_clicks'] ?? 0);
$total_clicks = $primary_clicks + $secondary_clicks;
$total_closes = intval($totals['total_closes'] ?? 0);
$unique_visitors = intval($totals['unique_visitors'] ?? 0);

$ctr = $total_views > 0 ? round(($total_clicks / $total_views) * 100, 2) : 0;
$dismiss_rate = $total_views > 0 ? round(($total_closes / $total_views) * 100, 2) : 0;

// 2. Daily Trends (Last 14 days or filtered period)
$daily_query = mysqli_query($con, "
    SELECT 
        DATE(created_at) as log_date,
        COUNT(CASE WHEN event_type = 'impression' THEN 1 END) AS impressions,
        COUNT(CASE WHEN event_type IN ('click_primary', 'click_secondary') THEN 1 END) AS clicks
    FROM popup_analytics
    WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 14 DAY)
    GROUP BY DATE(created_at)
    ORDER BY log_date ASC
");
$daily_labels = [];
$daily_impressions = [];
$daily_clicks = [];
while ($row = mysqli_fetch_assoc($daily_query)) {
    $daily_labels[] = date('M d', strtotime($row['log_date']));
    $daily_impressions[] = intval($row['impressions']);
    $daily_clicks[] = intval($row['clicks']);
}

// 3. Device Breakdown
$device_query = mysqli_query($con, "
    SELECT device, COUNT(*) as cnt 
    FROM popup_analytics 
    WHERE $date_condition 
    GROUP BY device
");
$device_counts = ['Desktop' => 0, 'Mobile' => 0, 'Tablet' => 0];
while ($row = mysqli_fetch_assoc($device_query)) {
    $dev = $row['device'] ?: 'Desktop';
    $device_counts[$dev] = intval($row['cnt']);
}

// 4. Recent Activity Log (Top 30)
$logs_query = mysqli_query($con, "
    SELECT * 
    FROM popup_analytics 
    WHERE $date_condition 
    ORDER BY id DESC 
    LIMIT 30
");

// Fetch current popup title
$popup_info_q = mysqli_query($con, "SELECT welcome_title, welcome_status FROM global_setting WHERE id = 1");
$popup_info = mysqli_fetch_assoc($popup_info_q);

require_once 'includes/header.php';
?>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<div class="row mb-4 align-items-center">
    <div class="col-md-7">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="index">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="home_settings">Home Settings</a></li>
                <li class="breadcrumb-item active" aria-current="page">Welcome Popup Analytics</li>
            </ol>
        </nav>
        <h3 class="fw-bold mb-0 text-dark">
            <i class="fas fa-bullhorn text-warning me-2"></i>Welcome Popup Analytics
        </h3>
        <p class="text-muted small mb-0">
            Monitoring engagement, views, and click-through performance for popup: 
            <strong><?php echo htmlspecialchars($popup_info['welcome_title'] ?? 'Welcome to LKVM'); ?></strong>
            <?php if (($popup_info['welcome_status'] ?? 0) == 1): ?>
                <span class="badge bg-success ms-2"><i class="fas fa-circle fa-2xs me-1"></i>Active</span>
            <?php else: ?>
                <span class="badge bg-secondary ms-2"><i class="fas fa-pause fa-2xs me-1"></i>Disabled</span>
            <?php endif; ?>
        </p>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0 d-flex gap-2 justify-content-md-end align-items-center flex-wrap">
        <!-- Date filter dropdown -->
        <div class="btn-group shadow-sm">
            <a href="popup_analytics?range=today" class="btn btn-sm <?php echo $range === 'today' ? 'btn-primary' : 'btn-outline-secondary'; ?>">Today</a>
            <a href="popup_analytics?range=7d" class="btn btn-sm <?php echo $range === '7d' ? 'btn-primary' : 'btn-outline-secondary'; ?>">7 Days</a>
            <a href="popup_analytics?range=30d" class="btn btn-sm <?php echo $range === '30d' ? 'btn-primary' : 'btn-outline-secondary'; ?>">30 Days</a>
            <a href="popup_analytics?range=all" class="btn btn-sm <?php echo $range === 'all' ? 'btn-primary' : 'btn-outline-secondary'; ?>">All Time</a>
        </div>
        <a href="home_settings" class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm">
            <i class="fas fa-sliders-h me-1"></i> Edit Popup
        </a>
    </div>
</div>

<?php if ($message): ?>
    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
        <i class="fas fa-check-circle me-2"></i><?php echo $message; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i><?php echo $error; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- KPI Summary Cards -->
<div class="row g-3 mb-4">
    <!-- Total Impressions -->
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden" style="background: linear-gradient(135deg, #ffffff 0%, #f0f7ff 100%); border-left: 4px solid #0284c7 !important;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-uppercase fw-bold text-muted small mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Views (Impressions)</div>
                        <h2 class="fw-bold mb-0 text-dark"><?php echo number_format($total_views); ?></h2>
                        <small class="text-muted"><i class="fas fa-users me-1"></i><?php echo number_format($unique_visitors); ?> Unique Visitors</small>
                    </div>
                    <div class="rounded-3 p-3 text-white" style="background: #0284c7;">
                        <i class="fas fa-eye fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Clicks -->
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden" style="background: linear-gradient(135deg, #ffffff 0%, #f0fdf4 100%); border-left: 4px solid #10b981 !important;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-uppercase fw-bold text-muted small mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Button Clicks</div>
                        <h2 class="fw-bold mb-0 text-dark"><?php echo number_format($total_clicks); ?></h2>
                        <small class="text-muted">Primary: <?php echo number_format($primary_clicks); ?> | Sec: <?php echo number_format($secondary_clicks); ?></small>
                    </div>
                    <div class="rounded-3 p-3 text-white" style="background: #10b981;">
                        <i class="fas fa-mouse-pointer fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Click Through Rate (CTR) -->
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden" style="background: linear-gradient(135deg, #ffffff 0%, #fefce8 100%); border-left: 4px solid #f59e0b !important;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-uppercase fw-bold text-muted small mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Click-Through Rate (CTR)</div>
                        <h2 class="fw-bold mb-0 text-dark"><?php echo $ctr; ?>%</h2>
                        <small class="text-muted">Average engagement rate</small>
                    </div>
                    <div class="rounded-3 p-3 text-white" style="background: #f59e0b;">
                        <i class="fas fa-chart-line fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dismissed / Closed -->
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden" style="background: linear-gradient(135deg, #ffffff 0%, #fdf2f8 100%); border-left: 4px solid #ec4899 !important;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="text-uppercase fw-bold text-muted small mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Dismissed / Closed</div>
                        <h2 class="fw-bold mb-0 text-dark"><?php echo number_format($total_closes); ?></h2>
                        <small class="text-muted"><?php echo $dismiss_rate; ?>% dismissal rate</small>
                    </div>
                    <div class="rounded-3 p-3 text-white" style="background: #ec4899;">
                        <i class="fas fa-times-circle fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-4 mb-4">
    <!-- Daily Trend Line Chart -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-dark"><i class="fas fa-chart-area text-primary me-2"></i>Views & Clicks Trend (Last 14 Days)</h6>
                <span class="badge bg-light text-muted fw-normal">Daily Activity</span>
            </div>
            <div class="card-body p-4">
                <div style="height: 280px; position: relative;">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Device & Interaction Breakdown -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="fw-bold mb-0 text-dark"><i class="fas fa-mobile-alt text-success me-2"></i>Device Breakdown</h6>
            </div>
            <div class="card-body p-4 text-center">
                <div style="height: 220px; position: relative;" class="d-flex align-items-center justify-content-center">
                    <canvas id="deviceChart"></canvas>
                </div>
                <div class="row text-center mt-3 pt-3 border-top">
                    <div class="col-4">
                        <small class="text-muted d-block">Desktop</small>
                        <strong class="text-dark"><?php echo $device_counts['Desktop']; ?></strong>
                    </div>
                    <div class="col-4">
                        <small class="text-muted d-block">Mobile</small>
                        <strong class="text-dark"><?php echo $device_counts['Mobile']; ?></strong>
                    </div>
                    <div class="col-4">
                        <small class="text-muted d-block">Tablet</small>
                        <strong class="text-dark"><?php echo $device_counts['Tablet']; ?></strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Event Logs Table -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h6 class="fw-bold mb-0 text-dark"><i class="fas fa-history text-info me-2"></i>Live Activity Logs</h6>
            <small class="text-muted">Real-time interactions recorded from website visitors</small>
        </div>
        <div>
            <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#clearModal">
                <i class="fas fa-trash-alt me-1"></i> Reset Logs
            </button>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr class="small text-uppercase text-muted">
                    <th class="ps-4">Timestamp</th>
                    <th>Event Type</th>
                    <th>Button / Action</th>
                    <th>Target URL / Page</th>
                    <th>Device</th>
                    <th>Browser</th>
                    <th class="pe-4">Visitor IP</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($logs_query) > 0): ?>
                    <?php while ($log = mysqli_fetch_assoc($logs_query)): ?>
                        <tr>
                            <td class="ps-4 text-nowrap small text-muted">
                                <?php echo date('M d, Y h:i:s A', strtotime($log['created_at'])); ?>
                            </td>
                            <td>
                                <?php if ($log['event_type'] === 'impression'): ?>
                                    <span class="badge bg-info bg-opacity-10 text-info px-2 py-1 rounded-pill"><i class="fas fa-eye me-1"></i>Viewed</span>
                                <?php elseif ($log['event_type'] === 'click_primary'): ?>
                                    <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 rounded-pill"><i class="fas fa-check-circle me-1"></i>Primary Click</span>
                                <?php elseif ($log['event_type'] === 'click_secondary'): ?>
                                    <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1 rounded-pill"><i class="fas fa-mouse-pointer me-1"></i>Secondary Click</span>
                                <?php elseif ($log['event_type'] === 'close'): ?>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1 rounded-pill"><i class="fas fa-times me-1"></i>Dismissed</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong class="small text-dark"><?php echo htmlspecialchars($log['button_text'] ?: '-'); ?></strong>
                            </td>
                            <td class="small text-truncate" style="max-width: 200px;" title="<?php echo htmlspecialchars($log['target_url'] ?: $log['page_url'] ?: '-'); ?>">
                                <?php echo htmlspecialchars($log['target_url'] ?: $log['page_url'] ?: '-'); ?>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    <?php if ($log['device'] === 'Mobile'): ?>
                                        <i class="fas fa-mobile-alt me-1 text-primary"></i>
                                    <?php elseif ($log['device'] === 'Tablet'): ?>
                                        <i class="fas fa-tablet-alt me-1 text-info"></i>
                                    <?php else: ?>
                                        <i class="fas fa-desktop me-1 text-secondary"></i>
                                    <?php endif; ?>
                                    <?php echo htmlspecialchars($log['device'] ?: 'Desktop'); ?>
                                </span>
                            </td>
                            <td class="small text-muted">
                                <?php echo htmlspecialchars($log['browser'] ?: 'Unknown'); ?>
                            </td>
                            <td class="pe-4 small text-muted font-monospace">
                                <?php echo htmlspecialchars($log['ip_address'] ?: '-'); ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fas fa-chart-bar fa-3x mb-3 text-secondary opacity-50 d-block"></i>
                            No analytics data recorded yet for the selected period.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Clear Data Modal -->
<div class="modal fade" id="clearModal" tabindex="-1" aria-labelledby="clearModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Reset Popup Analytics</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <p class="mb-0 text-muted">Are you sure you want to completely erase all welcome popup view and click logs? This action cannot be undone.</p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="">
                    <?php echo csrf_field(); ?>
                    <button type="submit" name="clear_analytics" class="btn btn-danger rounded-pill px-4">
                        <i class="fas fa-trash me-1"></i> Yes, Reset Data
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1. Trend Chart
    const trendCtx = document.getElementById('trendChart');
    if (trendCtx) {
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(count($daily_labels) > 0 ? $daily_labels : [date('M d')]); ?>,
                datasets: [
                    {
                        label: 'Impressions (Views)',
                        data: <?php echo json_encode(count($daily_impressions) > 0 ? $daily_impressions : [0]); ?>,
                        borderColor: '#0284c7',
                        backgroundColor: 'rgba(2, 132, 199, 0.1)',
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: 'Button Clicks',
                        data: <?php echo json_encode(count($daily_clicks) > 0 ? $daily_clicks : [0]); ?>,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.3,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top' }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0 }
                    }
                }
            }
        });
    }

    // 2. Device Chart
    const deviceCtx = document.getElementById('deviceChart');
    if (deviceCtx) {
        new Chart(deviceCtx, {
            type: 'doughnut',
            data: {
                labels: ['Desktop', 'Mobile', 'Tablet'],
                datasets: [{
                    data: [
                        <?php echo $device_counts['Desktop']; ?>,
                        <?php echo $device_counts['Mobile']; ?>,
                        <?php echo $device_counts['Tablet']; ?>
                    ],
                    backgroundColor: ['#4361ee', '#10b981', '#f59e0b'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                },
                cutout: '65%'
            }
        });
    }
});
</script>

<?php require_once 'includes/footer.php'; ?>


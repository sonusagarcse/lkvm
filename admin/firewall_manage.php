<?php
// admin/firewall_manage.php - IP Firewall & Attack Analytics Dashboard
require_once 'includes/auth_check.php';
require_once '../connection.php';
require_once '../security/firewall.php';

$admin_id = $_SESSION['admin_id'] ?? 1;
$active_tab = $_GET['tab'] ?? 'analytics';
$flash_msg = '';
$flash_type = 'success';

// Handle Action Requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // Verify CSRF token
    if (function_exists('verify_csrf_token') && !verify_csrf_token()) {
        $_SESSION['firewall_flash'] = ['type' => 'danger', 'msg' => 'Security token invalid or expired. Please try again.'];
        header("Location: firewall_manage?tab=" . urlencode($active_tab));
        exit();
    }

    if ($action === 'manual_block') {
        $ip = trim($_POST['ip_address'] ?? '');
        $reason = trim($_POST['reason'] ?? 'Manual: Blocked by Administrator');
        $duration = (int)($_POST['duration_minutes'] ?? 5);

        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            if (firewall_admin_block_ip($con, $ip, $reason, $duration, $admin_id)) {
                $_SESSION['firewall_flash'] = ['type' => 'success', 'msg' => "IP $ip successfully blocked for $duration minutes."];
            } else {
                $_SESSION['firewall_flash'] = ['type' => 'danger', 'msg' => "Failed to block IP $ip."];
            }
        } else {
            $_SESSION['firewall_flash'] = ['type' => 'danger', 'msg' => "Invalid IP address provided."];
        }
        header("Location: firewall_manage?tab=blocked");
        exit();
    }

    if ($action === 'unblock') {
        $block_id = (int)($_POST['block_id'] ?? 0);
        if ($block_id > 0 && firewall_admin_unblock_ip($con, $block_id)) {
            $_SESSION['firewall_flash'] = ['type' => 'success', 'msg' => "IP successfully unblocked."];
        } else {
            $_SESSION['firewall_flash'] = ['type' => 'danger', 'msg' => "Failed to unblock IP."];
        }
        header("Location: firewall_manage?tab=blocked");
        exit();
    }

    if ($action === 'delete_block') {
        $block_id = (int)($_POST['block_id'] ?? 0);
        if ($block_id > 0 && firewall_admin_delete_block($con, $block_id)) {
            $_SESSION['firewall_flash'] = ['type' => 'success', 'msg' => "Block record permanently removed."];
        } else {
            $_SESSION['firewall_flash'] = ['type' => 'danger', 'msg' => "Failed to delete block record."];
        }
        header("Location: firewall_manage?tab=blocked");
        exit();
    }

    if ($action === 'add_whitelist') {
        $ip = trim($_POST['ip_address'] ?? '');
        $label = trim($_POST['label'] ?? 'Trusted Office / Admin IP');

        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            if (firewall_admin_add_whitelist($con, $ip, $label, $admin_id)) {
                $_SESSION['firewall_flash'] = ['type' => 'success', 'msg' => "IP $ip successfully added to Whitelist."];
            } else {
                $_SESSION['firewall_flash'] = ['type' => 'danger', 'msg' => "Failed to add IP to Whitelist."];
            }
        } else {
            $_SESSION['firewall_flash'] = ['type' => 'danger', 'msg' => "Invalid IP address provided."];
        }
        header("Location: firewall_manage?tab=whitelist");
        exit();
    }

    if ($action === 'remove_whitelist') {
        $whitelist_id = (int)($_POST['whitelist_id'] ?? 0);
        if ($whitelist_id > 0 && firewall_admin_remove_whitelist($con, $whitelist_id)) {
            $_SESSION['firewall_flash'] = ['type' => 'success', 'msg' => "IP removed from Whitelist."];
        } else {
            $_SESSION['firewall_flash'] = ['type' => 'danger', 'msg' => "Failed to remove IP from Whitelist."];
        }
        header("Location: firewall_manage?tab=whitelist");
        exit();
    }

    if ($action === 'purge_threats') {
        $hours = (int)($_POST['purge_hours'] ?? 48);
        $deleted = firewall_admin_purge_threat_logs($con, $hours);
        $_SESSION['firewall_flash'] = ['type' => 'info', 'msg' => "Purged $deleted old threat logs (older than $hours hours)."];
        header("Location: firewall_manage?tab=tools");
        exit();
    }

    if ($action === 'sync_expired') {
        $synced = firewall_admin_sync_expired_blocks($con);
        $_SESSION['firewall_flash'] = ['type' => 'info', 'msg' => "Synced $synced expired block records."];
        header("Location: firewall_manage?tab=blocked");
        exit();
    }
}

// Read Flash Messages
if (isset($_SESSION['firewall_flash'])) {
    $flash_msg = $_SESSION['firewall_flash']['msg'] ?? '';
    $flash_type = $_SESSION['firewall_flash']['type'] ?? 'success';
    unset($_SESSION['firewall_flash']);
}

// Fetch Analytics
$analytics = firewall_get_analytics($con);

// Fetch Blocked IPs Data
$filter_block_status = $_GET['status'] ?? 'all';
$search_ip = trim($_GET['search_ip'] ?? '');
$blocked_where = ["1=1"];

if ($filter_block_status === 'active') {
    $blocked_where[] = "b.is_active = 1 AND b.expires_at > NOW()";
} elseif ($filter_block_status === 'expired') {
    $blocked_where[] = "(b.is_active = 0 OR b.expires_at <= NOW())";
}

if (!empty($search_ip)) {
    $s_ip = mysqli_real_escape_string($con, $search_ip);
    $blocked_where[] = "(b.ip_address LIKE '%$s_ip%' OR b.reason LIKE '%$s_ip%')";
}
$blocked_where_sql = implode(' AND ', $blocked_where);

// Blocked IPs Pagination
$b_page = max(1, (int)($_GET['bp'] ?? 1));
$b_limit = 20;
$b_offset = ($b_page - 1) * $b_limit;

$b_total_res = mysqli_query($con, "SELECT COUNT(*) FROM ip_blocked b WHERE $blocked_where_sql");
$b_total = (int)($b_total_res ? mysqli_fetch_row($b_total_res)[0] : 0);
$b_pages = ceil($b_total / $b_limit);

$blocked_rows = [];
$b_query = mysqli_query($con, "
    SELECT b.* 
    FROM ip_blocked b 
    WHERE $blocked_where_sql 
    ORDER BY b.is_active DESC, b.blocked_at DESC 
    LIMIT $b_offset, $b_limit
");
if ($b_query) {
    while ($r = mysqli_fetch_assoc($b_query)) {
        $blocked_rows[] = $r;
    }
}

// Fetch Whitelist Data
$whitelist_rows = [];
$wl_query = mysqli_query($con, "SELECT * FROM ip_whitelist ORDER BY created_at DESC");
if ($wl_query) {
    while ($r = mysqli_fetch_assoc($wl_query)) {
        $whitelist_rows[] = $r;
    }
}

// Fetch Threat Log Data for Tab
$t_page = max(1, (int)($_GET['tp'] ?? 1));
$t_limit = 25;
$t_offset = ($t_page - 1) * $t_limit;
$t_search = trim($_GET['t_search'] ?? '');
$t_where = ["1=1"];

if (!empty($t_search)) {
    $s_t = mysqli_real_escape_string($con, $t_search);
    $t_where[] = "(t.ip_address LIKE '%$s_t%' OR t.request_uri LIKE '%$s_t%' OR t.user_agent LIKE '%$s_t%')";
}
$t_where_sql = implode(' AND ', $t_where);

$t_total_res = mysqli_query($con, "SELECT COUNT(*) FROM ip_threat_log t WHERE $t_where_sql");
$t_total = (int)($t_total_res ? mysqli_fetch_row($t_total_res)[0] : 0);
$t_pages = ceil($t_total / $t_limit);

$threat_rows = [];
$t_query = mysqli_query($con, "
    SELECT t.* 
    FROM ip_threat_log t 
    WHERE $t_where_sql 
    ORDER BY t.id DESC 
    LIMIT $t_offset, $t_limit
");
if ($t_query) {
    while ($r = mysqli_fetch_assoc($t_query)) {
        $threat_rows[] = $r;
    }
}

$current_client_ip = firewall_get_client_ip();

require_once 'includes/header.php';
?>

<div class="container-fluid px-0">
    <!-- Header Title Bar -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-7">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="index">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Firewall & Security</li>
                </ol>
            </nav>
            <h3 class="fw-bold mb-0 text-dark">
                <i class="fas fa-shield-halved text-primary me-2"></i>IP Firewall & Security Analytics
            </h3>
            <p class="text-muted small mb-0">Automated defense against rapid 404 URL scanners, request floods, and malicious crawlers</p>
        </div>
        <div class="col-md-5 text-md-end mt-3 mt-md-0 d-flex gap-2 justify-content-md-end flex-wrap">
            <button type="button" class="btn btn-outline-success rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalAddWhitelist">
                <i class="fas fa-plus-circle me-1"></i> Whitelist IP
            </button>
            <button type="button" class="btn btn-danger rounded-pill px-4 shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#modalManualBlock">
                <i class="fas fa-ban me-1"></i> Block IP Manually
            </button>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if ($flash_msg): ?>
        <div class="alert alert-<?php echo htmlspecialchars($flash_type); ?> alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
            <i class="fas fa-info-circle me-2"></i><?php echo htmlspecialchars($flash_msg); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- KPI Metric Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden" style="border-left: 5px solid #ef4444 !important;">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small fw-bold text-uppercase">Active Blocks</span>
                            <h2 class="fw-bold text-danger mb-0 mt-1"><?php echo $analytics['active_blocks']; ?></h2>
                            <small class="text-muted">Currently restricted</small>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-danger-subtle text-danger" style="width: 52px; height: 52px;">
                            <i class="fas fa-shield-virus fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden" style="border-left: 5px solid #f59e0b !important;">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small fw-bold text-uppercase">Threats (24h)</span>
                            <h2 class="fw-bold text-warning mb-0 mt-1"><?php echo $analytics['threats_24h']; ?></h2>
                            <small class="text-muted"><?php echo $analytics['threats_total']; ?> lifetime probes</small>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-warning-subtle text-warning" style="width: 52px; height: 52px;">
                            <i class="fas fa-radar fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden" style="border-left: 5px solid #3b82f6 !important;">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small fw-bold text-uppercase">Total Blocked IPs</span>
                            <h2 class="fw-bold text-primary mb-0 mt-1"><?php echo $analytics['total_blocks']; ?></h2>
                            <small class="text-muted"><?php echo $analytics['auto_blocks']; ?> auto / <?php echo $analytics['manual_blocks']; ?> admin</small>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary-subtle text-primary" style="width: 52px; height: 52px;">
                            <i class="fas fa-user-lock fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden" style="border-left: 5px solid #10b981 !important;">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small fw-bold text-uppercase">Whitelisted IPs</span>
                            <h2 class="fw-bold text-success mb-0 mt-1"><?php echo $analytics['whitelisted_total']; ?></h2>
                            <small class="text-muted">Bypasses all checks</small>
                        </div>
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-success-subtle text-success" style="width: 52px; height: 52px;">
                            <i class="fas fa-shield-heart fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-header bg-white border-bottom p-0">
            <ul class="nav nav-tabs border-0 px-3 pt-2" id="firewallTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link py-3 px-4 fw-bold <?php echo $active_tab === 'analytics' ? 'active text-primary border-primary border-bottom-2' : 'text-muted'; ?>" href="firewall_manage?tab=analytics">
                        <i class="fas fa-chart-pie me-2"></i>Attack Analytics
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link py-3 px-4 fw-bold <?php echo $active_tab === 'blocked' ? 'active text-primary border-primary border-bottom-2' : 'text-muted'; ?>" href="firewall_manage?tab=blocked">
                        <i class="fas fa-ban me-2"></i>Blocked IPs 
                        <span class="badge bg-danger ms-1 rounded-pill"><?php echo $analytics['active_blocks']; ?></span>
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link py-3 px-4 fw-bold <?php echo $active_tab === 'threats' ? 'active text-primary border-primary border-bottom-2' : 'text-muted'; ?>" href="firewall_manage?tab=threats">
                        <i class="fas fa-satellite-dish me-2"></i>Live Threat Log
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link py-3 px-4 fw-bold <?php echo $active_tab === 'whitelist' ? 'active text-primary border-primary border-bottom-2' : 'text-muted'; ?>" href="firewall_manage?tab=whitelist">
                        <i class="fas fa-check-shield me-2"></i>IP Whitelist
                    </a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link py-3 px-4 fw-bold <?php echo $active_tab === 'tools' ? 'active text-primary border-primary border-bottom-2' : 'text-muted'; ?>" href="firewall_manage?tab=tools">
                        <i class="fas fa-toolbox me-2"></i>Maintenance Tools
                    </a>
                </li>
            </ul>
        </div>

        <div class="card-body p-4">
            <!-- TAB 1: ATTACK ANALYTICS -->
            <?php if ($active_tab === 'analytics'): ?>
                <div class="row g-4">
                    <!-- Security Engine Live Status Cards -->
                    <div class="col-12">
                        <div class="alert alert-light border shadow-sm rounded-4 p-3 d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <span class="badge bg-success-subtle text-success p-2 rounded-circle">
                                    <i class="fas fa-check-double fa-lg"></i>
                                </span>
                                <div>
                                    <h6 class="mb-0 fw-bold text-dark">Application Firewall Active & Enforcing</h6>
                                    <small class="text-muted">Multi-Request Burst Rate Limiting (60 req/30s) + Rapid 404 Scanning Blocker (8 probes/60s) are armed.</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-primary rounded-pill px-3 py-2">Your IP: <?php echo htmlspecialchars($current_client_ip); ?></span>
                                <form method="POST" action="firewall_manage?tab=whitelist" class="d-inline">
                                    <?php echo function_exists('csrf_field') ? csrf_field() : ''; ?>
                                    <input type="hidden" name="action" value="add_whitelist">
                                    <input type="hidden" name="ip_address" value="<?php echo htmlspecialchars($current_client_ip); ?>">
                                    <input type="hidden" name="label" value="Current Admin Session">
                                    <button type="submit" class="btn btn-sm btn-outline-success rounded-pill px-3">
                                        <i class="fas fa-shield-alt me-1"></i> Whitelist My IP
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Top 10 Scanning IPs -->
                    <div class="col-lg-7">
                        <div class="card border rounded-4 shadow-sm h-100">
                            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                                <h6 class="fw-bold mb-0 text-dark">
                                    <i class="fas fa-crosshairs text-danger me-2"></i>Top 10 Attack & Scanning IPs
                                </h6>
                                <span class="badge bg-light text-muted border">Most Aggressive</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr class="small text-muted text-uppercase">
                                            <th>Attacking IP</th>
                                            <th>Total Hits</th>
                                            <th>Unique 404s</th>
                                            <th>Status</th>
                                            <th class="text-end">Quick Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($analytics['top_scanners'])): ?>
                                            <?php foreach ($analytics['top_scanners'] as $scanner): ?>
                                                <tr>
                                                    <td>
                                                        <span class="fw-bold font-monospace"><?php echo htmlspecialchars($scanner['ip_address']); ?></span>
                                                        <small class="text-muted d-block" style="font-size: 11px;">Last: <?php echo htmlspecialchars($scanner['last_seen']); ?></small>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-danger-subtle text-danger fw-bold px-2 py-1"><?php echo $scanner['total_probes']; ?></span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-warning-subtle text-warning fw-bold px-2 py-1"><?php echo $scanner['unique_probes']; ?></span>
                                                    </td>
                                                    <td>
                                                        <?php if ($scanner['is_whitelisted']): ?>
                                                            <span class="badge bg-success-subtle text-success">Whitelisted</span>
                                                        <?php elseif ($scanner['is_blocked']): ?>
                                                            <span class="badge bg-danger">Blocked</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-secondary-subtle text-secondary">Logged</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-end">
                                                        <?php if (!$scanner['is_blocked'] && !$scanner['is_whitelisted']): ?>
                                                            <form method="POST" action="firewall_manage?tab=blocked" class="d-inline">
                                                                <?php echo function_exists('csrf_field') ? csrf_field() : ''; ?>
                                                                <input type="hidden" name="action" value="manual_block">
                                                                <input type="hidden" name="ip_address" value="<?php echo htmlspecialchars($scanner['ip_address']); ?>">
                                                                <input type="hidden" name="duration_minutes" value="1440">
                                                                <input type="hidden" name="reason" value="Manual block: Aggressive scanner from analytics">
                                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-2 py-1" title="Block for 24h">
                                                                    <i class="fas fa-ban me-1"></i>Block
                                                                </button>
                                                            </form>
                                                        <?php else: ?>
                                                            <span class="text-muted small">-</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="5" class="text-center py-4 text-muted">
                                                    <i class="fas fa-check-circle text-success me-2"></i>No threat activity recorded yet.
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Top 10 Probed Non-Existent Routes -->
                    <div class="col-lg-5">
                        <div class="card border rounded-4 shadow-sm h-100">
                            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                                <h6 class="fw-bold mb-0 text-dark">
                                    <i class="fas fa-bullseye text-warning me-2"></i>Top Targeted 404 Targets
                                </h6>
                                <span class="badge bg-light text-muted border">Common Probes</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr class="small text-muted text-uppercase">
                                            <th>Probed URL Path</th>
                                            <th class="text-end">Probe Count</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($analytics['top_targeted_urls'])): ?>
                                            <?php foreach ($analytics['top_targeted_urls'] as $url): ?>
                                                <tr>
                                                    <td>
                                                        <code class="text-break text-dark fw-bold"><?php echo htmlspecialchars($url['request_uri']); ?></code>
                                                    </td>
                                                    <td class="text-end">
                                                        <span class="badge bg-secondary rounded-pill px-2 py-1"><?php echo $url['hit_count']; ?> hits</span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="2" class="text-center py-4 text-muted">
                                                    <i class="fas fa-check-circle text-success me-2"></i>No non-existent URL probes recorded yet.
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            <!-- TAB 2: BLOCKED IPS MANAGEMENT -->
            <?php elseif ($active_tab === 'blocked'): ?>
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                    <!-- Filters -->
                    <div class="d-flex gap-2 flex-wrap align-items-center">
                        <div class="btn-group rounded-pill border p-1 bg-light">
                            <a href="firewall_manage?tab=blocked&status=all&search_ip=<?php echo urlencode($search_ip); ?>" class="btn btn-sm rounded-pill <?php echo $filter_block_status === 'all' ? 'btn-primary' : 'btn-light'; ?>">All</a>
                            <a href="firewall_manage?tab=blocked&status=active&search_ip=<?php echo urlencode($search_ip); ?>" class="btn btn-sm rounded-pill <?php echo $filter_block_status === 'active' ? 'btn-danger' : 'btn-light'; ?>">Active Blocks</a>
                            <a href="firewall_manage?tab=blocked&status=expired&search_ip=<?php echo urlencode($search_ip); ?>" class="btn btn-sm rounded-pill <?php echo $filter_block_status === 'expired' ? 'btn-secondary' : 'btn-light'; ?>">Expired</a>
                        </div>

                        <form method="POST" action="firewall_manage?tab=blocked" class="d-inline">
                            <?php echo function_exists('csrf_field') ? csrf_field() : ''; ?>
                            <input type="hidden" name="action" value="sync_expired">
                            <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill" title="Mark expired blocks inactive">
                                <i class="fas fa-sync-alt me-1"></i> Sync Expiry
                            </button>
                        </form>
                    </div>

                    <!-- Search Box -->
                    <form method="GET" action="firewall_manage" class="d-flex gap-2">
                        <input type="hidden" name="tab" value="blocked">
                        <input type="hidden" name="status" value="<?php echo htmlspecialchars($filter_block_status); ?>">
                        <div class="input-group">
                            <input type="text" name="search_ip" class="form-control form-control-sm rounded-start-pill ps-3" placeholder="Search IP or Reason..." value="<?php echo htmlspecialchars($search_ip); ?>">
                            <button class="btn btn-sm btn-primary rounded-end-pill px-3" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <div class="table-responsive rounded-4 border">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr class="small text-muted text-uppercase">
                                <th>IP Address</th>
                                <th>Status</th>
                                <th>Reason</th>
                                <th>Blocked By</th>
                                <th>Probed URLs</th>
                                <th>Blocked At</th>
                                <th>Expires At</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($blocked_rows)): ?>
                                <?php foreach ($blocked_rows as $row): 
                                    $is_active = ($row['is_active'] == 1 && strtotime($row['expires_at']) > time());
                                    $time_left = max(0, strtotime($row['expires_at']) - time());
                                    $min_left = ceil($time_left / 60);
                                ?>
                                    <tr>
                                        <td>
                                            <span class="font-monospace fw-bold"><?php echo htmlspecialchars($row['ip_address']); ?></span>
                                        </td>
                                        <td>
                                            <?php if ($is_active): ?>
                                                <span class="badge bg-danger rounded-pill px-3 py-1">
                                                    <i class="fas fa-lock me-1"></i>Active (<?php echo $min_left; ?>m left)
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary rounded-pill px-3 py-1">
                                                    <i class="fas fa-lock-open me-1"></i>Expired
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <small class="text-muted d-block text-truncate" style="max-width: 250px;" title="<?php echo htmlspecialchars($row['reason']); ?>">
                                                <?php echo htmlspecialchars($row['reason']); ?>
                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border"><?php echo htmlspecialchars($row['blocked_by']); ?></span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info-subtle text-info fw-bold"><?php echo (int)$row['unique_urls_count']; ?></span>
                                        </td>
                                        <td><small class="text-muted"><?php echo htmlspecialchars($row['blocked_at']); ?></small></td>
                                        <td><small class="text-muted"><?php echo htmlspecialchars($row['expires_at']); ?></small></td>
                                        <td class="text-end">
                                            <div class="d-inline-flex gap-1">
                                                <?php if ($is_active): ?>
                                                    <!-- Unblock Action -->
                                                    <form method="POST" action="firewall_manage?tab=blocked" class="d-inline" onsubmit="return confirm('Are you sure you want to unblock this IP immediately?');">
                                                        <?php echo function_exists('csrf_field') ? csrf_field() : ''; ?>
                                                        <input type="hidden" name="action" value="unblock">
                                                        <input type="hidden" name="block_id" value="<?php echo $row['id']; ?>">
                                                        <button type="submit" class="btn btn-sm btn-outline-success rounded-pill px-2 py-1" title="Unblock Now">
                                                            <i class="fas fa-unlock me-1"></i>Unblock
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <!-- Re-block Action -->
                                                    <form method="POST" action="firewall_manage?tab=blocked" class="d-inline">
                                                        <?php echo function_exists('csrf_field') ? csrf_field() : ''; ?>
                                                        <input type="hidden" name="action" value="manual_block">
                                                        <input type="hidden" name="ip_address" value="<?php echo htmlspecialchars($row['ip_address']); ?>">
                                                        <input type="hidden" name="duration_minutes" value="60">
                                                        <input type="hidden" name="reason" value="Re-blocked by administrator">
                                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-2 py-1" title="Re-block for 1 hour">
                                                            <i class="fas fa-redo me-1"></i>Block 1h
                                                        </button>
                                                    </form>
                                                <?php endif; ?>

                                                <!-- Whitelist IP -->
                                                <form method="POST" action="firewall_manage?tab=whitelist" class="d-inline">
                                                    <?php echo function_exists('csrf_field') ? csrf_field() : ''; ?>
                                                    <input type="hidden" name="action" value="add_whitelist">
                                                    <input type="hidden" name="ip_address" value="<?php echo htmlspecialchars($row['ip_address']); ?>">
                                                    <input type="hidden" name="label" value="Whitelisted from Blocked List">
                                                    <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill px-2 py-1" title="Whitelist IP">
                                                        <i class="fas fa-shield-alt"></i>
                                                    </button>
                                                </form>

                                                <!-- Delete Block Entry -->
                                                <form method="POST" action="firewall_manage?tab=blocked" class="d-inline" onsubmit="return confirm('Delete this block record entirely?');">
                                                    <?php echo function_exists('csrf_field') ? csrf_field() : ''; ?>
                                                    <input type="hidden" name="action" value="delete_block">
                                                    <input type="hidden" name="block_id" value="<?php echo $row['id']; ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-1" title="Delete Record">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">
                                        <i class="fas fa-shield-alt text-primary fa-2x mb-2 d-block"></i>
                                        No blocked IP records found matching criteria.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($b_pages > 1): ?>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="small text-muted">Showing <?php echo min($b_total, $b_offset + 1); ?> - <?php echo min($b_total, $b_offset + $b_limit); ?> of <?php echo $b_total; ?></span>
                        <ul class="pagination pagination-sm mb-0">
                            <?php for ($p = 1; $p <= $b_pages; $p++): ?>
                                <li class="page-item <?php echo $b_page === $p ? 'active' : ''; ?>">
                                    <a class="page-link" href="firewall_manage?tab=blocked&bp=<?php echo $p; ?>&status=<?php echo urlencode($filter_block_status); ?>&search_ip=<?php echo urlencode($search_ip); ?>"><?php echo $p; ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </div>
                <?php endif; ?>

            <!-- TAB 3: LIVE THREAT LOGS -->
            <?php elseif ($active_tab === 'threats'): ?>
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                    <div>
                        <h6 class="fw-bold mb-0">Incoming Suspicious Probes & 404 Attacks</h6>
                        <small class="text-muted">Real-time log of non-existent URLs visited by external clients</small>
                    </div>
                    <form method="GET" action="firewall_manage" class="d-flex gap-2">
                        <input type="hidden" name="tab" value="threats">
                        <div class="input-group">
                            <input type="text" name="t_search" class="form-control form-control-sm rounded-start-pill ps-3" placeholder="Search IP, URI, User Agent..." value="<?php echo htmlspecialchars($t_search); ?>">
                            <button class="btn btn-sm btn-primary rounded-end-pill px-3" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <div class="table-responsive rounded-4 border">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr class="small text-muted text-uppercase">
                                <th>#</th>
                                <th>IP Address</th>
                                <th>Method</th>
                                <th>Probed Request URI</th>
                                <th>User Agent</th>
                                <th>Timestamp</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($threat_rows)): ?>
                                <?php foreach ($threat_rows as $t): ?>
                                    <tr>
                                        <td><small class="text-muted"><?php echo $t['id']; ?></small></td>
                                        <td>
                                            <span class="font-monospace fw-bold"><?php echo htmlspecialchars($t['ip_address']); ?></span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border"><?php echo htmlspecialchars($t['request_method']); ?></span>
                                        </td>
                                        <td>
                                            <code class="text-break text-danger fw-bold"><?php echo htmlspecialchars($t['request_uri']); ?></code>
                                        </td>
                                        <td>
                                            <small class="text-muted text-truncate d-inline-block" style="max-width: 200px;" title="<?php echo htmlspecialchars($t['user_agent']); ?>">
                                                <?php echo htmlspecialchars($t['user_agent']); ?>
                                            </small>
                                        </td>
                                        <td><small class="text-muted"><?php echo htmlspecialchars($t['created_at']); ?></small></td>
                                        <td class="text-end">
                                            <form method="POST" action="firewall_manage?tab=blocked" class="d-inline">
                                                <?php echo function_exists('csrf_field') ? csrf_field() : ''; ?>
                                                <input type="hidden" name="action" value="manual_block">
                                                <input type="hidden" name="ip_address" value="<?php echo htmlspecialchars($t['ip_address']); ?>">
                                                <input type="hidden" name="duration_minutes" value="1440">
                                                <input type="hidden" name="reason" value="Manual block: Suspicious probe detected in live log">
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-2 py-1" title="Block 24h">
                                                    <i class="fas fa-ban me-1"></i>Block
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        <i class="fas fa-radar fa-2x mb-2 text-primary d-block"></i>
                                        No threat logs recorded yet.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Threat Pagination -->
                <?php if ($t_pages > 1): ?>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="small text-muted">Showing <?php echo min($t_total, $t_offset + 1); ?> - <?php echo min($t_total, $t_offset + $t_limit); ?> of <?php echo $t_total; ?></span>
                        <ul class="pagination pagination-sm mb-0">
                            <?php for ($p = 1; $p <= $t_pages; $p++): ?>
                                <li class="page-item <?php echo $t_page === $p ? 'active' : ''; ?>">
                                    <a class="page-link" href="firewall_manage?tab=threats&tp=<?php echo $p; ?>&t_search=<?php echo urlencode($t_search); ?>"><?php echo $p; ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </div>
                <?php endif; ?>

            <!-- TAB 4: WHITELIST MANAGEMENT -->
            <?php elseif ($active_tab === 'whitelist'): ?>
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="card border rounded-4 shadow-sm">
                            <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                                <h6 class="fw-bold mb-0 text-dark">
                                    <i class="fas fa-shield-heart text-success me-2"></i>Trusted Whitelisted IPs
                                </h6>
                                <span class="badge bg-success-subtle text-success"><?php echo count($whitelist_rows); ?> Total</span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr class="small text-muted text-uppercase">
                                            <th>Whitelisted IP</th>
                                            <th>Label / Description</th>
                                            <th>Date Added</th>
                                            <th class="text-end">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($whitelist_rows)): ?>
                                            <?php foreach ($whitelist_rows as $wl): ?>
                                                <tr>
                                                    <td>
                                                        <span class="font-monospace fw-bold text-success"><?php echo htmlspecialchars($wl['ip_address']); ?></span>
                                                        <?php if ($wl['ip_address'] === $current_client_ip): ?>
                                                            <span class="badge bg-info text-white ms-1">You</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($wl['label']); ?></td>
                                                    <td><small class="text-muted"><?php echo htmlspecialchars($wl['created_at']); ?></small></td>
                                                    <td class="text-end">
                                                        <form method="POST" action="firewall_manage?tab=whitelist" class="d-inline" onsubmit="return confirm('Remove this IP from Whitelist?');">
                                                            <?php echo function_exists('csrf_field') ? csrf_field() : ''; ?>
                                                            <input type="hidden" name="action" value="remove_whitelist">
                                                            <input type="hidden" name="whitelist_id" value="<?php echo $wl['id']; ?>">
                                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-2 py-1">
                                                                <i class="fas fa-trash-alt me-1"></i>Remove
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="4" class="text-center py-4 text-muted">
                                                    No IPs whitelisted yet.
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card border rounded-4 shadow-sm">
                            <div class="card-header bg-white py-3 border-bottom">
                                <h6 class="fw-bold mb-0 text-dark">
                                    <i class="fas fa-plus-circle text-primary me-2"></i>Add Whitelist IP
                                </h6>
                            </div>
                            <div class="card-body p-3">
                                <form method="POST" action="firewall_manage?tab=whitelist">
                                    <?php echo function_exists('csrf_field') ? csrf_field() : ''; ?>
                                    <input type="hidden" name="action" value="add_whitelist">

                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">IP Address *</label>
                                        <input type="text" name="ip_address" class="form-control" required placeholder="e.g. 192.168.1.1">
                                        <small class="text-muted">IPv4 or IPv6 format</small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Label / Office Note</label>
                                        <input type="text" name="label" class="form-control" placeholder="e.g. Head Office Static IP">
                                    </div>

                                    <button type="submit" class="btn btn-success w-100 rounded-pill fw-bold">
                                        <i class="fas fa-save me-1"></i> Save to Whitelist
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            <!-- TAB 5: MAINTENANCE TOOLS -->
            <?php elseif ($active_tab === 'tools'): ?>
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card border rounded-4 shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <span class="rounded-circle bg-warning-subtle text-warning p-3">
                                        <i class="fas fa-broom fa-lg"></i>
                                    </span>
                                    <div>
                                        <h5 class="fw-bold mb-1">Purge Old Threat Logs</h5>
                                        <p class="text-muted small mb-0">Clean historical probe logs to keep database queries instantaneous</p>
                                    </div>
                                </div>

                                <form method="POST" action="firewall_manage?tab=tools" onsubmit="return confirm('Purge selected threat log history?');">
                                    <?php echo function_exists('csrf_field') ? csrf_field() : ''; ?>
                                    <input type="hidden" name="action" value="purge_threats">

                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Select Retention Period</label>
                                        <select name="purge_hours" class="form-select">
                                            <option value="24">Older than 24 Hours</option>
                                            <option value="48" selected>Older than 48 Hours (Recommended)</option>
                                            <option value="168">Older than 7 Days</option>
                                            <option value="720">Older than 30 Days</option>
                                        </select>
                                    </div>

                                    <button type="submit" class="btn btn-outline-warning w-100 rounded-pill fw-bold">
                                        <i class="fas fa-trash-can me-1"></i> Run Purge Now
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border rounded-4 shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <span class="rounded-circle bg-info-subtle text-info p-3">
                                        <i class="fas fa-clock-rotate-left fa-lg"></i>
                                    </span>
                                    <div>
                                        <h5 class="fw-bold mb-1">Sync Expired Blocks</h5>
                                        <p class="text-muted small mb-0">Automatically refresh active status of blocks whose 5-minute cooldown elapsed</p>
                                    </div>
                                </div>

                                <p class="small text-muted mb-4">
                                    The firewall naturally auto-expires blocks on the fly upon the visitor's next visit. You can also trigger a manual sweep right now.
                                </p>

                                <form method="POST" action="firewall_manage?tab=tools">
                                    <?php echo function_exists('csrf_field') ? csrf_field() : ''; ?>
                                    <input type="hidden" name="action" value="sync_expired">
                                    <button type="submit" class="btn btn-outline-info w-100 rounded-pill fw-bold">
                                        <i class="fas fa-rotate me-1"></i> Sync & Clean Expired Blocks
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- MODAL: MANUAL BLOCK IP -->
<div class="modal fade" id="modalManualBlock" tabindex="-1" aria-labelledby="modalManualBlockLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-danger text-white border-0">
                <h5 class="modal-title fw-bold" id="modalManualBlockLabel">
                    <i class="fas fa-ban me-2"></i>Block IP Address Manually
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="firewall_manage?tab=blocked">
                <div class="modal-body p-4">
                    <?php echo function_exists('csrf_field') ? csrf_field() : ''; ?>
                    <input type="hidden" name="action" value="manual_block">

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Target IP Address *</label>
                        <input type="text" name="ip_address" class="form-control" required placeholder="e.g. 198.51.100.4">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Block Duration *</label>
                        <select name="duration_minutes" class="form-select">
                            <option value="5" selected>5 Minutes (Standard cooldown)</option>
                            <option value="60">1 Hour</option>
                            <option value="1440">24 Hours (1 Day)</option>
                            <option value="10080">7 Days (1 Week)</option>
                            <option value="43200">30 Days (Extended)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Reason Note</label>
                        <input type="text" name="reason" class="form-control" value="Manual: Blocked by Administrator" placeholder="Reason for blocking">
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">Enforce Block</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL: ADD WHITELIST IP -->
<div class="modal fade" id="modalAddWhitelist" tabindex="-1" aria-labelledby="modalAddWhitelistLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-success text-white border-0">
                <h5 class="modal-title fw-bold" id="modalAddWhitelistLabel">
                    <i class="fas fa-shield-heart me-2"></i>Add IP to Whitelist
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="firewall_manage?tab=whitelist">
                <div class="modal-body p-4">
                    <?php echo function_exists('csrf_field') ? csrf_field() : ''; ?>
                    <input type="hidden" name="action" value="add_whitelist">

                    <div class="mb-3">
                        <label class="form-label small fw-bold">IP Address *</label>
                        <div class="input-group">
                            <input type="text" name="ip_address" id="whitelist_ip_input" class="form-control" required placeholder="e.g. 203.0.113.15">
                            <button type="button" class="btn btn-outline-secondary" onclick="document.getElementById('whitelist_ip_input').value='<?php echo htmlspecialchars($current_client_ip); ?>';">Use My IP</button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Description / Label</label>
                        <input type="text" name="label" class="form-control" value="Trusted Office / Admin IP" placeholder="e.g. Head Office Static Line">
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold">Add to Whitelist</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>


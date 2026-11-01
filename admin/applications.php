<?php
// admin/applications.php - Job Applications Management
require_once 'includes/auth_check.php';
require_once '../connection.php';

$message = '';
$error = '';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    // Fetch resume to unlink if needed
    $resQ = mysqli_query($con, "SELECT resume FROM career WHERE id = $id");
    if ($resQ && $r = mysqli_fetch_assoc($resQ)) {
        if (!empty($r['resume']) && file_exists('../' . $r['resume'])) {
            @unlink('../' . $r['resume']);
        }
    }

    $query = "DELETE FROM career WHERE id = $id";
    if (mysqli_query($con, $query)) {
        $message = "Application deleted successfully!";
    } else {
        $error = "Failed to delete application.";
    }
}

if (isset($_GET['msg']) && $_GET['msg'] === 'deleted') {
    $message = "Application deleted successfully!";
}

// Search & Filter
$search = trim($_GET['search'] ?? '');
$filter_pos = trim($_GET['position'] ?? '');

$where_clauses = ["1=1"];
if (!empty($search)) {
    $s_escaped = mysqli_real_escape_string($con, $search);
    $where_clauses[] = "(name LIKE '%$s_escaped%' OR email LIKE '%$s_escaped%' OR mob LIKE '%$s_escaped%' OR location LIKE '%$s_escaped%')";
}
if (!empty($filter_pos)) {
    $pos_escaped = mysqli_real_escape_string($con, $filter_pos);
    $where_clauses[] = "position = '$pos_escaped'";
}
$where_sql = implode(' AND ', $where_clauses);

// Pagination
$per_page = 20;
$page = max(1, intval($_GET['p'] ?? 1));
$offset = ($page - 1) * $per_page;

$total_res = mysqli_query($con, "SELECT COUNT(*) FROM career WHERE $where_sql");
$total_rows = (int)($total_res ? mysqli_fetch_row($total_res)[0] : 0);
$total_pages = ceil($total_rows / $per_page);

// Fetch Applications
$applicationsQ = mysqli_query($con, "
    SELECT * FROM career 
    WHERE $where_sql 
    ORDER BY id DESC 
    LIMIT $offset, $per_page
");

$applications = [];
if ($applicationsQ) {
    while ($appRow = mysqli_fetch_assoc($applicationsQ)) {
        $applications[] = $appRow;
    }
}

// KPI Stats
$stats = [
    'total' => (int)mysqli_fetch_row(mysqli_query($con, "SELECT COUNT(*) FROM career"))[0],
    'today' => (int)mysqli_fetch_row(mysqli_query($con, "SELECT COUNT(*) FROM career WHERE date = '" . date('d-m-Y') . "'"))[0],
    'positions' => (int)mysqli_fetch_row(mysqli_query($con, "SELECT COUNT(DISTINCT position) FROM career"))[0],
    'resumes' => (int)mysqli_fetch_row(mysqli_query($con, "SELECT COUNT(*) FROM career WHERE resume != '' AND resume IS NOT NULL"))[0]
];

// Fetch distinct positions for filter dropdown
$distinct_pos_q = mysqli_query($con, "SELECT DISTINCT position FROM career WHERE position != '' AND position IS NOT NULL ORDER BY position ASC");

include 'includes/header.php';
?>

<style>
/* Prevent table container and modal dialogs from card hover transforms and glitches */
.card.table-card:hover {
    transform: none !important;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04) !important;
}

.modal {
    z-index: 1060 !important;
}

.modal-dialog {
    transform: none !important;
}

.modal.fade .modal-dialog {
    transition: transform 0.25s ease-out, opacity 0.25s ease-out;
    transform: translateY(-20px) !important;
    opacity: 0;
}

.modal.show .modal-dialog {
    transform: translateY(0) !important;
    opacity: 1;
}

.modal-content {
    transform: none !important;
    transition: none !important;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15) !important;
}

.modal-content:hover {
    transform: none !important;
}
</style>

<div class="row mb-4 align-items-center">
    <div class="col-md-7">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="index">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Job Applications</li>
            </ol>
        </nav>
        <h3 class="fw-bold mb-0 text-dark">
            <i class="fas fa-user-tie text-primary me-2"></i>Job Applications & Candidate Resumes
        </h3>
        <p class="text-muted small mb-0">Review candidates applying from the careers portal and homepage</p>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0 d-flex gap-2 justify-content-md-end flex-wrap">
        <a href="../career" target="_blank" class="btn btn-outline-primary rounded-pill px-3 shadow-sm">
            <i class="fas fa-external-link-alt me-1"></i> View Career Page
        </a>
    </div>
</div>

<?php if ($message): ?>
    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
        <i class="fas fa-check-circle me-2"></i><?php echo htmlspecialchars($message); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i><?php echo htmlspecialchars($error); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- KPI Metric Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm rounded-4 h-100" style="border-left: 5px solid #002147 !important;">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase">Total Applications</span>
                        <h2 class="fw-bold text-dark mb-0 mt-1"><?php echo $stats['total']; ?></h2>
                        <small class="text-muted">Lifetime submissions</small>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary-subtle text-primary" style="width: 50px; height: 50px;">
                        <i class="fas fa-file-invoice fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm rounded-4 h-100" style="border-left: 5px solid #16a34a !important;">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase">Received Today</span>
                        <h2 class="fw-bold text-success mb-0 mt-1"><?php echo $stats['today']; ?></h2>
                        <small class="text-muted"><?php echo date('d M Y'); ?></small>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-success-subtle text-success" style="width: 50px; height: 50px;">
                        <i class="fas fa-calendar-check fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm rounded-4 h-100" style="border-left: 5px solid #f59e0b !important;">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase">Job Positions</span>
                        <h2 class="fw-bold text-warning mb-0 mt-1"><?php echo $stats['positions']; ?></h2>
                        <small class="text-muted">Distinct roles targeted</small>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-warning-subtle text-warning" style="width: 50px; height: 50px;">
                        <i class="fas fa-briefcase fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm rounded-4 h-100" style="border-left: 5px solid #0284c7 !important;">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase">With Resumes</span>
                        <h2 class="fw-bold text-info mb-0 mt-1"><?php echo $stats['resumes']; ?></h2>
                        <small class="text-muted">Uploaded CV attachments</small>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-info-subtle text-info" style="width: 50px; height: 50px;">
                        <i class="fas fa-paperclip fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Table Card (with table-card class to disable card hover transform) -->
<div class="card table-card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
    <div class="card-header bg-white py-3 border-bottom">
        <form method="GET" action="applications" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Search candidate name, email, phone, location..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
            </div>
            <div class="col-md-4">
                <select name="position" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All Applied Positions</option>
                    <?php if ($distinct_pos_q): ?>
                        <?php while ($pRow = mysqli_fetch_assoc($distinct_pos_q)): ?>
                            <option value="<?php echo htmlspecialchars($pRow['position']); ?>" <?php echo $filter_pos === $pRow['position'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($pRow['position']); ?>
                            </option>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3 w-100">Filter</button>
                <?php if (!empty($search) || !empty($filter_pos)): ?>
                    <a href="applications" class="btn btn-sm btn-outline-secondary rounded-pill px-3">Clear</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr class="small text-muted text-uppercase">
                    <th width="4%">#</th>
                    <th width="20%">Candidate</th>
                    <th width="20%">Applied Position</th>
                    <th width="20%">Contact Details</th>
                    <th width="14%">Qualification</th>
                    <th width="10%">Resume</th>
                    <th width="12%" class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($applications)): ?>
                    <?php foreach ($applications as $row): 
                        $hasResume = !empty($row['resume']) && file_exists('../' . $row['resume']);
                        $resumeLink = !empty($row['resume']) ? ('../' . htmlspecialchars($row['resume'])) : '';
                        $pos = !empty($row['position']) ? htmlspecialchars($row['position']) : 'General Application';
                    ?>
                        <tr>
                            <td><span class="text-muted fw-bold"><?php echo $row['id']; ?></span></td>
                            <td>
                                <strong class="text-dark d-block"><?php echo htmlspecialchars($row['name']); ?></strong>
                                <small class="text-muted"><i class="fas fa-map-marker-alt text-danger me-1"></i><?php echo !empty($row['location']) ? htmlspecialchars($row['location']) : 'Bihar'; ?></small>
                                <small class="text-muted d-block" style="font-size: 11px;"><?php echo htmlspecialchars($row['date']); ?></small>
                            </td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary fw-bold px-2 py-1 text-wrap" style="max-width: 220px;">
                                    <?php echo $pos; ?>
                                </span>
                                <?php if (!empty($row['experience'])): ?>
                                    <small class="text-muted d-block mt-1"><i class="fas fa-business-time me-1"></i>Exp: <?php echo htmlspecialchars($row['experience']); ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="mailto:<?php echo htmlspecialchars($row['email']); ?>" class="text-decoration-none d-block text-truncate" style="max-width: 200px;">
                                    <i class="fas fa-envelope text-muted me-1"></i><?php echo htmlspecialchars($row['email']); ?>
                                </a>
                                <a href="tel:<?php echo htmlspecialchars($row['mob']); ?>" class="text-decoration-none fw-bold text-dark d-block mt-1">
                                    <i class="fas fa-phone text-success me-1"></i><?php echo htmlspecialchars($row['mob']); ?>
                                </a>
                            </td>
                            <td>
                                <span class="small text-dark fw-medium"><?php echo !empty($row['qualification']) ? htmlspecialchars($row['qualification']) : 'Graduate'; ?></span>
                            </td>
                            <td>
                                <?php if (!empty($row['resume'])): ?>
                                    <a href="<?php echo $resumeLink; ?>" target="_blank" class="btn btn-sm btn-outline-success rounded-pill px-2 py-1" title="Download CV">
                                        <i class="fas fa-download me-1"></i>CV
                                    </a>
                                <?php else: ?>
                                    <span class="badge bg-light text-muted border">No CV</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    <!-- View Modal Trigger -->
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-circle" data-bs-toggle="modal" data-bs-target="#viewModal<?php echo $row['id']; ?>" title="View Application Details">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <!-- Delete Button -->
                                    <a href="applications?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger rounded-circle" onclick="return confirm('Delete this job application permanently?')" title="Delete Application">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fas fa-user-tie fa-3x mb-3 text-secondary d-block"></i>
                            No job applications found matching your criteria.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
        <div class="card-footer bg-white border-0 py-3 d-flex justify-content-between align-items-center">
            <span class="small text-muted">Showing <?php echo min($total_rows, $offset + 1); ?> - <?php echo min($total_rows, $offset + $per_page); ?> of <?php echo $total_rows; ?> applications</span>
            <ul class="pagination pagination-sm mb-0">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
                        <a class="page-link" href="?p=<?php echo $i; ?>&position=<?php echo urlencode($filter_pos); ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </div>
    <?php endif; ?>
</div>

<!-- APPLICATION DETAILS MODALS (Positioned outside the table card to eliminate hover jitter & stacking conflicts) -->
<?php if (!empty($applications)): ?>
    <?php foreach ($applications as $row): 
        $hasResume = !empty($row['resume']) && file_exists('../' . $row['resume']);
        $resumeLink = !empty($row['resume']) ? ('../' . htmlspecialchars($row['resume'])) : '';
        $pos = !empty($row['position']) ? htmlspecialchars($row['position']) : 'General Application';
    ?>
        <div class="modal fade text-start" id="viewModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="viewModalLabel<?php echo $row['id']; ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 border-0 shadow-lg">
                    <div class="modal-header bg-primary text-white border-0 py-3">
                        <h5 class="modal-title fw-bold" id="viewModalLabel<?php echo $row['id']; ?>">
                            <i class="fas fa-id-card me-2"></i>Candidate Profile (#<?php echo $row['id']; ?>)
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="text-center pb-3 border-bottom mb-3">
                            <div class="rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center mb-2 shadow-sm" style="width: 60px; height: 60px;">
                                <i class="fas fa-user fa-2x"></i>
                            </div>
                            <h5 class="fw-bold mb-1 text-dark"><?php echo htmlspecialchars($row['name']); ?></h5>
                            <span class="badge bg-primary rounded-pill px-3 py-1"><?php echo $pos; ?></span>
                        </div>

                        <div class="row g-2 mb-3 small">
                            <div class="col-6">
                                <span class="text-muted d-block">Phone:</span>
                                <strong><a href="tel:<?php echo htmlspecialchars($row['mob']); ?>" class="text-dark text-decoration-none"><i class="fas fa-phone-alt text-success me-1"></i><?php echo htmlspecialchars($row['mob']); ?></a></strong>
                            </div>
                            <div class="col-6">
                                <span class="text-muted d-block">Email:</span>
                                <strong><a href="mailto:<?php echo htmlspecialchars($row['email']); ?>" class="text-primary text-decoration-none text-break"><i class="fas fa-envelope text-muted me-1"></i><?php echo htmlspecialchars($row['email']); ?></a></strong>
                            </div>
                            <div class="col-6 mt-2">
                                <span class="text-muted d-block">Qualification:</span>
                                <strong><i class="fas fa-graduation-cap text-secondary me-1"></i><?php echo !empty($row['qualification']) ? htmlspecialchars($row['qualification']) : 'N/A'; ?></strong>
                            </div>
                            <div class="col-6 mt-2">
                                <span class="text-muted d-block">Experience:</span>
                                <strong><i class="fas fa-business-time text-secondary me-1"></i><?php echo !empty($row['experience']) ? htmlspecialchars($row['experience']) : 'N/A'; ?></strong>
                            </div>
                            <div class="col-6 mt-2">
                                <span class="text-muted d-block">Location:</span>
                                <strong><i class="fas fa-map-marker-alt text-danger me-1"></i><?php echo !empty($row['location']) ? htmlspecialchars($row['location']) : 'Bihar'; ?></strong>
                            </div>
                            <div class="col-6 mt-2">
                                <span class="text-muted d-block">Submitted:</span>
                                <strong><i class="fas fa-calendar-alt text-info me-1"></i><?php echo htmlspecialchars($row['date']); ?></strong>
                            </div>
                        </div>

                        <?php if (!empty($row['message'])): ?>
                            <div class="p-3 bg-light rounded-3 mb-3 border">
                                <span class="text-muted small fw-bold d-block mb-1"><i class="fas fa-comment-dots text-primary me-1"></i>Candidate Note / Message:</span>
                                <p class="mb-0 small text-dark" style="white-space: pre-wrap;"><?php echo htmlspecialchars($row['message']); ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($row['resume'])): ?>
                            <a href="<?php echo $resumeLink; ?>" target="_blank" class="btn btn-success w-100 rounded-pill fw-bold py-2 shadow-sm">
                                <i class="fas fa-file-pdf me-2"></i>Download Candidate Resume
                            </a>
                        <?php else: ?>
                            <div class="alert alert-secondary text-center small py-2 mb-0 rounded-pill">
                                <i class="fas fa-info-circle me-1"></i> No resume attached with this application
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>

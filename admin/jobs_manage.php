<?php
// admin/jobs_manage.php - Manage Job Openings & Vacancies
require_once 'includes/auth_check.php';
require_once '../connection.php';

$message = '';
$error = '';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $query = "DELETE FROM jobs WHERE id = $id";
    if (mysqli_query($con, $query)) {
        header("Location: jobs_manage?msg=deleted");
        exit();
    } else {
        $error = "Error deleting job role: " . mysqli_error($con);
    }
}

// Handle Status Toggle
if (isset($_GET['toggle_status']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $status = intval($_GET['toggle_status']) == 1 ? 1 : 0;
    mysqli_query($con, "UPDATE jobs SET status = $status WHERE id = $id");
    header("Location: jobs_manage?msg=status_updated");
    exit();
}

// Handle Add / Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_job'])) {
    $title = mysqli_real_escape_string($con, trim($_POST['title'] ?? ''));
    $department = mysqli_real_escape_string($con, trim($_POST['department'] ?? ''));
    $location = mysqli_real_escape_string($con, trim($_POST['location'] ?? ''));
    $type = mysqli_real_escape_string($con, trim($_POST['type'] ?? 'Full Time'));
    $experience = mysqli_real_escape_string($con, trim($_POST['experience'] ?? '1-2 Years'));
    $qualification = mysqli_real_escape_string($con, trim($_POST['qualification'] ?? 'Graduate'));
    $salary = mysqli_real_escape_string($con, trim($_POST['salary'] ?? 'Competitive'));
    $openings = max(1, intval($_POST['openings'] ?? 1));
    $description = mysqli_real_escape_string($con, trim($_POST['description'] ?? ''));
    $status = isset($_POST['status']) ? 1 : 0;

    if (!empty($_POST['id'])) {
        // Edit Existing Job
        $id = intval($_POST['id']);
        $sql = "UPDATE jobs SET 
                    title = '$title',
                    department = '$department',
                    location = '$location',
                    type = '$type',
                    experience = '$experience',
                    qualification = '$qualification',
                    salary = '$salary',
                    openings = $openings,
                    description = '$description',
                    status = $status
                WHERE id = $id";
        if (mysqli_query($con, $sql)) {
            header("Location: jobs_manage?msg=updated");
            exit();
        } else {
            $error = "Database error: " . mysqli_error($con);
        }
    } else {
        // Add New Job
        $sql = "INSERT INTO jobs (title, department, location, type, experience, qualification, salary, openings, description, status) 
                VALUES ('$title', '$department', '$location', '$type', '$experience', '$qualification', '$salary', $openings, '$description', $status)";
        if (mysqli_query($con, $sql)) {
            header("Location: jobs_manage?msg=added");
            exit();
        } else {
            $error = "Database error: " . mysqli_error($con);
        }
    }
}

// Flash messages
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'added') $message = "Job opening published successfully!";
    if ($_GET['msg'] === 'updated') $message = "Job opening updated successfully!";
    if ($_GET['msg'] === 'deleted') $message = "Job opening deleted successfully!";
    if ($_GET['msg'] === 'status_updated') $message = "Job status toggled successfully!";
}

// Fetch all jobs
$jobs_res = mysqli_query($con, "SELECT * FROM jobs ORDER BY status DESC, id DESC");

// KPI Stats
$stats = [
    'total' => (int)mysqli_fetch_row(mysqli_query($con, "SELECT COUNT(*) FROM jobs"))[0],
    'active' => (int)mysqli_fetch_row(mysqli_query($con, "SELECT COUNT(*) FROM jobs WHERE status = 1"))[0],
    'inactive' => (int)mysqli_fetch_row(mysqli_query($con, "SELECT COUNT(*) FROM jobs WHERE status = 0"))[0],
    'total_openings' => (int)mysqli_fetch_row(mysqli_query($con, "SELECT COALESCE(SUM(openings), 0) FROM jobs WHERE status = 1"))[0]
];

include 'includes/header.php';
?>

<div class="row mb-4 align-items-center">
    <div class="col-md-7">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="index">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Job Openings</li>
            </ol>
        </nav>
        <h3 class="fw-bold mb-0 text-dark">
            <i class="fas fa-briefcase text-primary me-2"></i>Manage Job Openings
        </h3>
        <p class="text-muted small mb-0">Create, edit, or deactivate career openings shown on the homepage and career portal</p>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0 d-flex gap-2 justify-content-md-end flex-wrap">
        <a href="../career" target="_blank" class="btn btn-outline-primary rounded-pill px-3 shadow-sm">
            <i class="fas fa-external-link-alt me-1"></i> Live Career Page
        </a>
        <button class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#jobModal" onclick="resetJobForm()">
            <i class="fas fa-plus me-1"></i> Post New Job
        </button>
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

<!-- KPI Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm rounded-4 h-100" style="border-left: 5px solid #002147 !important;">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase">Total Job Roles</span>
                        <h2 class="fw-bold text-dark mb-0 mt-1"><?php echo $stats['total']; ?></h2>
                        <small class="text-muted">Total positions listed</small>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary-subtle text-primary" style="width: 50px; height: 50px;">
                        <i class="fas fa-layer-group fa-lg"></i>
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
                        <span class="text-muted small fw-bold text-uppercase">Active Vacancies</span>
                        <h2 class="fw-bold text-success mb-0 mt-1"><?php echo $stats['active']; ?></h2>
                        <small class="text-muted">Visible on website</small>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-success-subtle text-success" style="width: 50px; height: 50px;">
                        <i class="fas fa-check-circle fa-lg"></i>
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
                        <span class="text-muted small fw-bold text-uppercase">Total Openings</span>
                        <h2 class="fw-bold text-warning mb-0 mt-1"><?php echo $stats['total_openings']; ?></h2>
                        <small class="text-muted">Aggregate open seats</small>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-warning-subtle text-warning" style="width: 50px; height: 50px;">
                        <i class="fas fa-users-viewfinder fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6">
        <div class="card border-0 shadow-sm rounded-4 h-100" style="border-left: 5px solid #94a3b8 !important;">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase">Closed / Inactive</span>
                        <h2 class="fw-bold text-secondary mb-0 mt-1"><?php echo $stats['inactive']; ?></h2>
                        <small class="text-muted">Hidden from portal</small>
                    </div>
                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-secondary-subtle text-secondary" style="width: 50px; height: 50px;">
                        <i class="fas fa-ban fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Jobs Table Card -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0 text-dark">
            <i class="fas fa-list text-primary me-2"></i>All Job Roles
        </h6>
        <span class="badge bg-light text-dark border"><?php echo count($stats) ? $stats['total'] : 0; ?> Total Roles</span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr class="small text-muted text-uppercase">
                    <th width="4%">#</th>
                    <th width="24%">Job Title & Department</th>
                    <th width="18%">Location & Type</th>
                    <th width="18%">Experience & Qualification</th>
                    <th width="14%">Salary</th>
                    <th width="8%">Status</th>
                    <th width="14%" class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($jobs_res && mysqli_num_rows($jobs_res) > 0): ?>
                    <?php while ($job = mysqli_fetch_assoc($jobs_res)): ?>
                        <tr>
                            <td><span class="text-muted fw-bold"><?php echo $job['id']; ?></span></td>
                            <td>
                                <strong class="text-dark d-block"><?php echo htmlspecialchars($job['title']); ?></strong>
                                <span class="badge bg-light text-dark border me-1"><?php echo htmlspecialchars($job['department']); ?></span>
                                <small class="text-muted"><?php echo $job['openings']; ?> Opening(s)</small>
                            </td>
                            <td>
                                <span class="d-block small"><i class="fas fa-map-marker-alt text-danger me-1"></i><?php echo htmlspecialchars($job['location']); ?></span>
                                <span class="badge bg-info-subtle text-info mt-1"><?php echo htmlspecialchars($job['type']); ?></span>
                            </td>
                            <td>
                                <span class="d-block small text-dark"><i class="fas fa-briefcase text-secondary me-1"></i><?php echo htmlspecialchars($job['experience']); ?></span>
                                <span class="d-block small text-muted"><i class="fas fa-graduation-cap text-secondary me-1"></i><?php echo htmlspecialchars($job['qualification']); ?></span>
                            </td>
                            <td>
                                <strong class="text-dark small"><?php echo htmlspecialchars($job['salary']); ?></strong>
                            </td>
                            <td>
                                <?php if ($job['status'] == 1): ?>
                                    <a href="jobs_manage?toggle_status=0&id=<?php echo $job['id']; ?>" class="badge bg-success rounded-pill text-decoration-none px-2 py-1" title="Click to Deactivate">
                                        <i class="fas fa-check-circle me-1"></i>Active
                                    </a>
                                <?php else: ?>
                                    <a href="jobs_manage?toggle_status=1&id=<?php echo $job['id']; ?>" class="badge bg-secondary rounded-pill text-decoration-none px-2 py-1" title="Click to Activate">
                                        <i class="fas fa-pause-circle me-1"></i>Inactive
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    <!-- Edit Button -->
                                    <button type="button" class="btn btn-sm btn-outline-primary rounded-circle" onclick='editJob(<?php echo json_encode($job, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>)' title="Edit Job">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <!-- Delete Button -->
                                    <a href="jobs_manage?delete=<?php echo $job['id']; ?>" class="btn btn-sm btn-outline-danger rounded-circle" onclick="return confirm('Are you sure you want to delete this job opening permanently?')" title="Delete Job">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fas fa-briefcase fa-3x mb-3 text-secondary d-block"></i>
                            No job openings created yet. Click "Post New Job" above to add your first opening!
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ADD / EDIT JOB MODAL -->
<div class="modal fade" id="jobModal" tabindex="-1" aria-labelledby="jobModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title fw-bold" id="jobModalLabel">
                    <i class="fas fa-briefcase me-2"></i><span id="modalTitleText">Post New Job Opening</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="jobs_manage">
                <input type="hidden" name="save_job" value="1">
                <input type="hidden" name="id" id="job_id" value="">

                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-bold">Job Title *</label>
                            <input type="text" name="title" id="job_title" class="form-control" required placeholder="e.g. Senior Computer Faculty & IT Trainer">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Department *</label>
                            <input type="text" name="department" id="job_department" class="form-control" required placeholder="e.g. IT & Computer Training">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Location *</label>
                            <input type="text" name="location" id="job_location" class="form-control" required placeholder="e.g. Patna / Muzaffarpur, Bihar">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Job Type *</label>
                            <select name="type" id="job_type" class="form-select" required>
                                <option value="Full Time">Full Time</option>
                                <option value="Part Time">Part Time</option>
                                <option value="Contract">Contract</option>
                                <option value="Internship">Internship</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Vacancies Count *</label>
                            <input type="number" name="openings" id="job_openings" class="form-control" required min="1" value="1">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Experience *</label>
                            <input type="text" name="experience" id="job_experience" class="form-control" required placeholder="e.g. 1 - 3 Years / Fresher">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Qualification *</label>
                            <input type="text" name="qualification" id="job_qualification" class="form-control" required placeholder="e.g. BCA / MCA / B.Tech">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Salary Package *</label>
                            <input type="text" name="salary" id="job_salary" class="form-control" required placeholder="e.g. ₹18,000 - ₹32,000 / month">
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold">Job Description & Responsibilities *</label>
                            <textarea name="description" id="job_description" class="form-control" rows="4" required placeholder="Briefly describe the candidate's core roles, practical training tasks, and expectations..."></textarea>
                        </div>

                        <div class="col-12">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="status" id="job_status" value="1" checked>
                                <label class="form-check-label fw-bold" for="job_status">Active (Display publicly on career portal)</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Save Job Opening</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function resetJobForm() {
    document.getElementById('modalTitleText').textContent = 'Post New Job Opening';
    document.getElementById('job_id').value = '';
    document.getElementById('job_title').value = '';
    document.getElementById('job_department').value = '';
    document.getElementById('job_location').value = 'Patna, Bihar';
    document.getElementById('job_type').value = 'Full Time';
    document.getElementById('job_openings').value = '1';
    document.getElementById('job_experience').value = '1 - 2 Years';
    document.getElementById('job_qualification').value = 'Graduate';
    document.getElementById('job_salary').value = '₹18,000 - ₹30,000 / month';
    document.getElementById('job_description').value = '';
    document.getElementById('job_status').checked = true;
}

function editJob(job) {
    document.getElementById('modalTitleText').textContent = 'Edit Job Opening #' + job.id;
    document.getElementById('job_id').value = job.id;
    document.getElementById('job_title').value = job.title;
    document.getElementById('job_department').value = job.department;
    document.getElementById('job_location').value = job.location;
    document.getElementById('job_type').value = job.type;
    document.getElementById('job_openings').value = job.openings;
    document.getElementById('job_experience').value = job.experience;
    document.getElementById('job_qualification').value = job.qualification;
    document.getElementById('job_salary').value = job.salary;
    document.getElementById('job_description').value = job.description;
    document.getElementById('job_status').checked = (job.status == 1);

    const modal = new bootstrap.Modal(document.getElementById('jobModal'));
    modal.show();
}
</script>

<?php include 'includes/footer.php'; ?>


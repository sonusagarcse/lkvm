<?php
// admin/students.php
require_once '../connection.php';
include 'includes/header.php';

// Pagination Setup
$limit = 20;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$start = ($page - 1) * $limit;

// Search & Filter
$search = isset($_GET['search']) ? mysqli_real_escape_string($con, $_GET['search']) : '';
$branch_filter = isset($_GET['branch']) ? intval($_GET['branch']) : 0;
$course_filter = isset($_GET['course']) ? intval($_GET['course']) : 0;

$where = "WHERE 1=1";
if ($search) {
    // Check if valid column for search; avoiding 'id' ambiguity if necessary
    $where .= " AND (r.name LIKE '%$search%' OR r.regno LIKE '%$search%' OR r.mob LIKE '%$search%')";
}
if ($branch_filter) {
    $where .= " AND r.bid = $branch_filter";
}
if ($course_filter) {
    $where .= " AND r.course = $course_filter";
}

// Count Total
$countQuery = "SELECT COUNT(*) as total FROM registration r $where";
$countResult = mysqli_query($con, $countQuery);
$totalRows = mysqli_fetch_assoc($countResult)['total'];
$totalPages = ceil($totalRows / $limit);

// Fetch Data
$query = "SELECT r.*, c.name as course_name, b.bcode, b.bname 
          FROM registration r 
          LEFT JOIN courses c ON r.course = c.id 
          LEFT JOIN branch b ON r.bid = b.id 
          $where 
          ORDER BY r.id DESC 
          LIMIT $start, $limit";
$result = mysqli_query($con, $query);

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $delQuery = "DELETE FROM registration WHERE id = $id";
    if (mysqli_query($con, $delQuery)) {
        echo "<script>window.location.href='students.php?msg=deleted';</script>";
    }
}
?>

<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h2 class="h3 mb-0 text-gray-800 d-flex align-items-center flex-wrap gap-2">
            <span>Student Management</span>
            <span class="badge bg-primary fs-6 shadow-sm fw-bold">
                <i class="fas fa-database me-1"></i> DB: <?php echo htmlspecialchars($dbName); ?>
            </span>
            <span class="badge bg-secondary fs-6 shadow-sm fw-bold">
                <i class="fas fa-table me-1"></i> Table: registration
            </span>
        </h2>
        <a href="add_student.php" class="btn btn-success rounded-pill px-4 shadow-sm fw-bold">
            <i class="fas fa-user-plus me-2"></i>Add Recent Joined Student
        </a>
    </div>
</div>

<!-- Filters -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search by Name, Reg No, Mobile"
                    value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <div class="col-md-3">
                <select name="branch" class="form-select">
                    <option value="0">All Branches</option>
                    <?php
                    $bQ = mysqli_query($con, "SELECT id, bname, bcode FROM branch");
                    while ($b = mysqli_fetch_assoc($bQ)) {
                        $selected = ($branch_filter == $b['id']) ? 'selected' : '';
                        echo "<option value='{$b['id']}' $selected>{$b['bcode']} - {$b['bname']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-3">
                <select name="course" class="form-select">
                    <option value="0">All Courses</option>
                    <?php
                    $cQ = mysqli_query($con, "SELECT id, name FROM courses");
                    while ($c = mysqli_fetch_assoc($cQ)) {
                        $selected = ($course_filter == $c['id']) ? 'selected' : '';
                        echo "<option value='{$c['id']}' $selected>{$c['name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i> Filter</button>
            </div>
        </form>
    </div>
</div>

<?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted')
    echo '<div class="alert alert-success">Student deleted successfully!</div>'; ?>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Reg & Roll No</th>
                        <th>Student Name & DB Login Credentials</th>
                        <th>Branch</th>
                        <th>Course</th>
                        <th>Reg Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $status = $row['status'] == 1 ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
                            $viewLink = "student_details.php?id={$row['id']}";

                            echo "<tr>
                                    <td>
                                        <div class='mb-1'><span class='badge bg-light text-dark border'>Reg: {$row['regno']}</span></div>
                                        <div><span class='badge bg-light text-dark border'>Roll: {$row['rollno']}</span></div>
                                    </td>
                                    <td>
                                        <div class='fw-bold text-dark fs-6'>{$row['name']}</div>
                                        <div class='mt-1 small'>
                                            <span class='me-2 badge bg-success-subtle text-success border border-success-subtle'><i class='fas fa-user-circle me-1'></i>DB Username: <strong>{$row['username']}</strong></span>
                                            <span class='badge bg-warning-subtle text-warning-emphasis border border-warning-subtle'><i class='fas fa-key me-1'></i>Pass: <strong>{$row['pass']}</strong></span>
                                        </div>
                                        <div class='small text-muted mt-1' style='font-size: 0.85rem;'>
                                            <span class='me-2'><strong>Father:</strong> " . (!empty($row['father']) ? htmlspecialchars($row['father']) : 'N/A') . "</span>
                                            <span><strong>Mother:</strong> " . (!empty($row['mother']) ? htmlspecialchars($row['mother']) : 'N/A') . "</span>
                                        </div>
                                    </td>
                                    <td><small>{$row['bcode']}</small></td>
                                    <td><span class='badge bg-info text-dark'>{$row['course_name']}</span></td>
                                    <td>{$row['date']}</td>
                                    <td>{$status}</td>
                                    <td>
                                        <a href='{$viewLink}' class='btn btn-sm btn-primary' title='View'><i class='fas fa-eye'></i></a>
                                        <a href='students.php?delete={$row['id']}' class='btn btn-sm btn-danger' title='Delete' onclick='return confirm(\"Are you sure?\")'><i class='fas fa-trash'></i></a>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center py-4'>No students found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <nav aria-label="Page navigation" class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php
                    // Preserve filters in pagination links
                    $params = $_GET;
                    unset($params['page']);
                    $qs = http_build_query($params);

                    for ($i = 1; $i <= $totalPages; $i++) {
                        $active = ($page == $i) ? 'active' : '';
                        echo "<li class='page-item $active'><a class='page-link' href='?$qs&page=$i'>$i</a></li>";
                    }
                    ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
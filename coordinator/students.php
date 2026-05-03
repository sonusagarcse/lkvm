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
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h2 class="h3 mb-0 text-gray-800">Student Management</h2>
        <!-- Add Student Button could go here if Admin manually adds students -->
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
                        <th>Reg No</th>
                        <th>Student Name</th>
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
                            // Link to student_details.php (to be created)
                            $viewLink = "student_details.php?id={$row['id']}";

                            echo "<tr>
                                    <td><span class='badge bg-light text-dark border'>{$row['regno']}</span></td>
                                    <td>
                                        <div class='fw-bold'>{$row['name']}</div>
                                        <small class='text-muted'>Any: {$row['father']}</small>
                                    </td>
                                    <td><small>{$row['bcode']}</small></td>
                                    <td><span class='badge bg-info text-dark'>{$row['course_name']}</span></td>
                                    <td>{$row['date']}</td>
                                    <td>{$status}</td>
                                    <td>
                                        <a href='{$viewLink}' class='btn btn-sm btn-primary'><i class='fas fa-eye'></i></a>
                                        <a href='students.php?delete={$row['id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure?\")'><i class='fas fa-trash'></i></a>
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
<?php
// admin/courses.php
require_once '../connection.php';
include 'includes/header.php';

$message = '';
$error = '';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $query = "DELETE FROM courses WHERE id = $id";
    if (mysqli_query($con, $query)) {
        echo "<script>window.location.href='courses.php?msg=deleted';</script>";
    } else {
        $error = "Error deleting record";
    }
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_course'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']); // Course Short Name e.g. DCA
    $title = mysqli_real_escape_string($con, $_POST['title']); // Full Title
    $duration = intval($_POST['duration']);
    $fees = intval($_POST['fees']);
    $des = mysqli_real_escape_string($con, $_POST['des']);

    // Default pid for now if not used in form
    $pid = 2; // Assuming category ID or parent ID default

    $status = isset($_POST['status']) ? 1 : 0;

    if (!empty($_POST['id'])) {
        // Edit
        $id = intval($_POST['id']);
        $query = "UPDATE courses SET name='$name', title='$title', duration='$duration', fees='$fees', des='$des', status='$status' WHERE id=$id";
    } else {
        // Add
        $query = "INSERT INTO courses (pid, name, title, duration, fees, des, status, date) VALUES ('$pid', '$name', '$title', '$duration', '$fees', '$des', '$status', NOW())";
    }

    if (mysqli_query($con, $query)) {
        echo "<script>window.location.href='courses.php?msg=saved';</script>";
    } else {
        $error = "Database Error: " . mysqli_error($con);
    }
}
?>

<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h2 class="h3 mb-0 text-gray-800">Course Management</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#courseModal" onclick="resetForm()">
            <i class="fas fa-plus"></i> Add New Course
        </button>
    </div>
</div>

<?php if (isset($_GET['msg']) && $_GET['msg'] == 'saved')
    echo '<div class="alert alert-success">Course saved successfully!</div>'; ?>
<?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted')
    echo '<div class="alert alert-success">Course deleted successfully!</div>'; ?>
<?php if ($error)
    echo '<div class="alert alert-danger">' . $error . '</div>'; ?>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Course Name</th>
                        <th>Duration</th>
                        <th>Fees</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT * FROM courses ORDER BY id DESC";
                    $result = mysqli_query($con, $query);
                    while ($row = mysqli_fetch_assoc($result)) {
                        $active = $row['status'] == 1 ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>';
                        $courseInfo = "<strong>{$row['name']}</strong><br><small class='text-muted'>{$row['title']}</small>";

                        echo "<tr>
                                <td>#{$row['id']}</td>
                                <td>{$courseInfo}</td>
                                <td>{$row['duration']} Months</td>
                                <td>₹{$row['fees']}</td>
                                <td>{$active}</td>
                                <td>
                                    <button class='btn btn-sm btn-info text-white me-1' onclick='editCourse(" . json_encode($row) . ")'><i class='fas fa-edit'></i></button>
                                    <a href='courses.php?delete={$row['id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure?\")'><i class='fas fa-trash'></i></a>
                                </td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div class="modal fade" id="courseModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add New Course</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="course_id">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Short Name (e.g. DCA)</label>
                            <input type="text" name="name" id="cname" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Full Title</label>
                            <input type="text" name="title" id="ctitle" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Duration (Months)</label>
                            <input type="number" name="duration" id="duration" class="form-control" require>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Fees (₹)</label>
                            <input type="number" name="fees" id="fees" class="form-control" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label>Description (HTML Allowed)</label>
                            <textarea name="des" id="des" class="form-control" rows="4"></textarea>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="status" id="status" checked>
                                <label class="form-check-label" for="status">Active Status</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="save_course" class="btn btn-primary">Save Course</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function resetForm() {
        document.getElementById('course_id').value = '';
        document.getElementById('cname').value = '';
        document.getElementById('ctitle').value = '';
        document.getElementById('duration').value = '';
        document.getElementById('fees').value = '';
        document.getElementById('des').value = '';
        document.getElementById('status').checked = true;
        document.getElementById('modalTitle').innerText = 'Add New Course';
    }

    function editCourse(data) {
        var modal = new bootstrap.Modal(document.getElementById('courseModal'));
        document.getElementById('course_id').value = data.id;
        document.getElementById('cname').value = data.name;
        document.getElementById('ctitle').value = data.title;
        document.getElementById('duration').value = data.duration;
        document.getElementById('fees').value = data.fees;
        document.getElementById('des').value = data.des;
        document.getElementById('status').checked = (data.status == 1);
        document.getElementById('modalTitle').innerText = 'Edit Course';
        modal.show();
    }
</script>

<?php include 'includes/footer.php'; ?>
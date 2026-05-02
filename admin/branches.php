<?php
// admin/branches.php
require_once '../connection.php';
include 'includes/header.php';

$message = '';
$error = '';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $query = "DELETE FROM branch WHERE id = $id";
    if (mysqli_query($con, $query)) {
        echo "<script>window.location.href='branches.php?msg=deleted';</script>";
    } else {
        $error = "Error deleting record";
    }
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_branch'])) {
    $bname = mysqli_real_escape_string($con, $_POST['bname']);
    $bcode = mysqli_real_escape_string($con, $_POST['bcode']);
    $name = mysqli_real_escape_string($con, $_POST['name']); // Head Name
    $bcontact = mysqli_real_escape_string($con, $_POST['bcontact']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $pass = !empty($_POST['pass']) ? mysqli_real_escape_string($con, $_POST['pass']) : '';

    // Status Logic (1=Active, 0=Inactive)
    $status = isset($_POST['status']) ? 1 : 0;

    if (!empty($_POST['id'])) {
        // Edit
        $id = intval($_POST['id']);
        $passClause = !empty($pass) ? ", pass='$pass'" : "";
        $query = "UPDATE branch SET bname='$bname', bcode='$bcode', name='$name', bcontact='$bcontact', email='$email', status='$status' $passClause WHERE id=$id";
    } else {
        // Add
        // Assuming 'username' requires a value, using bcode or auto-increment workaround if logic dictates.
        // Based on SQL, `username` is int(50). Let's use a random int or logic. Using time() for unique.
        $username = time();
        $query = "INSERT INTO branch (bname, bcode, name, bcontact, email, pass, status, username, date) VALUES ('$bname', '$bcode', '$name', '$bcontact', '$email', '$pass', '$status', '$username', NOW())";
    }

    if (mysqli_query($con, $query)) {
        echo "<script>window.location.href='branches.php?msg=saved';</script>";
    } else {
        $error = "Database Error: " . mysqli_error($con);
    }
}
?>

<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h2 class="h3 mb-0 text-gray-800">Branch Management</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#branchModal" onclick="resetForm()">
            <i class="fas fa-plus"></i> Add New Branch
        </button>
    </div>
</div>

<?php if (isset($_GET['msg']) && $_GET['msg'] == 'saved')
    echo '<div class="alert alert-success">Branch saved successfully!</div>'; ?>
<?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted')
    echo '<div class="alert alert-success">Branch deleted successfully!</div>'; ?>
<?php if ($error)
    echo '<div class="alert alert-danger">' . $error . '</div>'; ?>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Branch Info</th>
                        <th>Head Name</th>
                        <th>Contact</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT * FROM branch ORDER BY id DESC";
                    $result = mysqli_query($con, $query);
                    while ($row = mysqli_fetch_assoc($result)) {
                        $active = $row['status'] == 1 ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>';
                        $branchInfo = "<strong>{$row['bname']}</strong><br><small class='text-muted'>{$row['bcode']}</small>";
                        $contact = "{$row['bcontact']}<br><small>{$row['email']}</small>";

                        echo "<tr>
                                <td>#{$row['id']}</td>
                                <td>{$branchInfo}</td>
                                <td>{$row['name']}</td>
                                <td>{$contact}</td>
                                <td>{$active}</td>
                                <td>
                                    <button class='btn btn-sm btn-info text-white me-1' onclick='editBranch(" . json_encode($row) . ")'><i class='fas fa-edit'></i></button>
                                    <a href='branches.php?delete={$row['id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure?\")'><i class='fas fa-trash'></i></a>
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
<div class="modal fade" id="branchModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add New Branch</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="branch_id">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Branch Name</label>
                            <input type="text" name="bname" id="bname" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Branch Code</label>
                            <input type="text" name="bcode" id="bcode" class="form-control" require>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Head/Center Director Name</label>
                            <input type="text" name="name" id="head_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Contact Number</label>
                            <input type="text" name="bcontact" id="bcontact" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Email ID</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Password (Leave empty to keep current)</label>
                            <input type="password" name="pass" id="pass" class="form-control">
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
                    <button type="submit" name="save_branch" class="btn btn-primary">Save Branch</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function resetForm() {
        document.getElementById('branch_id').value = '';
        document.getElementById('bname').value = '';
        document.getElementById('bcode').value = '';
        document.getElementById('head_name').value = '';
        document.getElementById('bcontact').value = '';
        document.getElementById('email').value = '';
        document.getElementById('pass').value = '';
        document.getElementById('status').checked = true;
        document.getElementById('modalTitle').innerText = 'Add New Branch';
    }

    function editBranch(data) {
        var modal = new bootstrap.Modal(document.getElementById('branchModal'));
        document.getElementById('branch_id').value = data.id;
        document.getElementById('bname').value = data.bname;
        document.getElementById('bcode').value = data.bcode;
        document.getElementById('head_name').value = data.name;
        document.getElementById('bcontact').value = data.bcontact;
        document.getElementById('email').value = data.email;
        document.getElementById('pass').value = ''; // Don't show password
        document.getElementById('status').checked = (data.status == 1);
        document.getElementById('modalTitle').innerText = 'Edit Branch';
        modal.show();
    }
</script>

<?php include 'includes/footer.php'; ?>
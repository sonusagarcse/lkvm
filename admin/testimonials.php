<?php
// admin/testimonials.php
require_once '../connection.php';
include 'includes/header.php';

$message = '';
$error = '';
$cid = 10; // Assuming '10' is ID used for Testimonials in 'webpage' table based on section7.php

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $query = "DELETE FROM webpage WHERE id = $id";
    if (mysqli_query($con, $query)) {
        echo "<script>window.location.href='testimonials.php?msg=deleted';</script>";
        if (function_exists('cache_delete'))
            cache_delete('testimonials_10');
    }
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_testimonial'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']); // Name of visitor
    $title = mysqli_real_escape_string($con, $_POST['title']); // Designation
    $des = mysqli_real_escape_string($con, $_POST['des']);   // Message
    $status = isset($_POST['status']) ? 1 : 0;

    if (!empty($_POST['id'])) {
        $id = intval($_POST['id']);
        $query = "UPDATE webpage SET name='$name', title='$title', des='$des', status='$status' WHERE id=$id";
    } else {
        $date = date('d-m-Y');
        $query = "INSERT INTO webpage (cid, name, title, des, date, status) VALUES ('$cid', '$name', '$title', '$des', '$date', '$status')";
    }

    if (mysqli_query($con, $query)) {
        if (function_exists('cache_delete'))
            cache_delete('testimonials_10');
        echo "<script>window.location.href='testimonials.php?msg=saved';</script>";
    } else {
        $error = "Database Error: " . mysqli_error($con);
    }
}
?>

<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h2 class="h3 mb-0 text-gray-800">Testimonial Management</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#testModal" onclick="resetForm()">
            <i class="fas fa-plus"></i> Add Testimonial
        </button>
    </div>
</div>

<?php if (isset($_GET['msg']) && $_GET['msg'] == 'saved')
    echo '<div class="alert alert-success">Saved successfully!</div>'; ?>
<?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted')
    echo '<div class="alert alert-success">Deleted successfully!</div>'; ?>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%">id</th>
                        <th width="20%">Name</th>
                        <th width="15%">Designation</th>
                        <th width="40%">Message</th>
                        <th width="10%">Status</th>
                        <th width="10%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch webpage entries with cid=10
                    $query = "SELECT * FROM webpage WHERE cid = '$cid' ORDER BY id DESC";
                    $result = mysqli_query($con, $query);
                    while ($row = mysqli_fetch_assoc($result)) {
                        $active = $row['status'] == 1 ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>';
                        $desc = (strlen($row['des']) > 50) ? substr(strip_tags($row['des']), 0, 50) . '...' : $row['des'];

                        echo "<tr>
                                <td>#{$row['id']}</td>
                                <td><strong>{$row['name']}</strong></td>
                                <td>{$row['title']}</td>
                                <td>{$desc}</td>
                                <td>{$active}</td>
                                <td>
                                    <button class='btn btn-sm btn-info text-white me-1' onclick='editTest(" . json_encode($row) . ")'><i class='fas fa-edit'></i></button>
                                    <a href='testimonials.php?delete={$row['id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure?\")'><i class='fas fa-trash'></i></a>
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
<div class="modal fade" id="testModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add Testimonial</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="t_id">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Name</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Designation (Title)</label>
                            <input type="text" name="title" id="title" class="form-control">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label>Message</label>
                            <textarea name="des" id="des" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="status" id="status" checked>
                                <label class="form-check-label" for="status">Active</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="save_testimonial" class="btn btn-primary">Save Testimonial</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function resetForm() {
        document.getElementById('t_id').value = '';
        document.getElementById('name').value = '';
        document.getElementById('title').value = '';
        document.getElementById('des').value = '';
        document.getElementById('status').checked = true;
        document.getElementById('modalTitle').innerText = 'Add Testimonial';
    }

    function editTest(data) {
        var modal = new bootstrap.Modal(document.getElementById('testModal'));
        document.getElementById('t_id').value = data.id;
        document.getElementById('name').value = data.name;
        document.getElementById('title').value = data.title;
        document.getElementById('des').value = data.des;
        document.getElementById('status').checked = (data.status == 1);
        document.getElementById('modalTitle').innerText = 'Edit Testimonial';
        modal.show();
    }
</script>

<?php include 'includes/footer.php'; ?>
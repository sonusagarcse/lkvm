<?php
// admin/manage_pages.php
require_once '../connection.php';
include 'includes/header.php';

$message = '';
$error = '';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $query = "DELETE FROM webpage WHERE id = $id";
    if (mysqli_query($con, $query)) {
        echo "<script>window.location.href='manage_pages.php?msg=deleted';</script>";
    } else {
        $error = "Error deleting record";
    }
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_page'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']); // Page Title
    $slug = mysqli_real_escape_string($con, strtolower(trim($_POST['slug']))); // Slug
    $cid = intval($_POST['cid']); // Category ID
    $des = mysqli_real_escape_string($con, $_POST['des']); // Description

    $status = isset($_POST['status']) ? 1 : 0;
    $date = date('d-m-Y');

    // Ensure slug is unique
    $id_check = !empty($_POST['id']) ? intval($_POST['id']) : 0;
    $check_query = "SELECT id FROM webpage WHERE slug='$slug' AND id != $id_check";
    $check_res = mysqli_query($con, $check_query);
    if (mysqli_num_rows($check_res) > 0) {
        $error = "Slug already exists. Please choose another slug.";
    } else {
        if (!empty($_POST['id'])) {
            // Edit
            $id = intval($_POST['id']);
            $query = "UPDATE webpage SET cid='$cid', name='$name', slug='$slug', des='$des', status='$status' WHERE id=$id";
        } else {
            // Add
            $query = "INSERT INTO webpage (cid, name, slug, title, img, vdo, des, date, status) VALUES ('$cid', '$name', '$slug', '$name', '', '', '$des', '$date', '$status')";
        }

        if (mysqli_query($con, $query)) {
            echo "<script>window.location.href='manage_pages.php?msg=saved';</script>";
        } else {
            $error = "Database Error: " . mysqli_error($con);
        }
    }
}
?>

<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h2 class="h3 mb-0 text-gray-800">Page Management</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#pageModal" onclick="resetForm()">
            <i class="fas fa-plus"></i> Add New Page
        </button>
    </div>
</div>

<?php if (isset($_GET['msg']) && $_GET['msg'] == 'saved')
    echo '<div class="alert alert-success">Page saved successfully!</div>'; ?>
<?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted')
    echo '<div class="alert alert-success">Page deleted successfully!</div>'; ?>
<?php if ($error)
    echo '<div class="alert alert-danger">' . $error . '</div>'; ?>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Page Title</th>
                        <th>Slug</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT w.*, c.name as category_name FROM webpage w LEFT JOIN webpage_category c ON w.cid = c.id ORDER BY w.id DESC";
                    $result = mysqli_query($con, $query);
                    while ($row = mysqli_fetch_assoc($result)) {
                        $active = $row['status'] == 1 ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>';
                        $catName = $row['category_name'] ? $row['category_name'] : 'None';

                        echo "<tr>
                                <td>#{$row['id']}</td>
                                <td><strong>{$row['name']}</strong></td>
                                <td><code>{$row['slug']}</code></td>
                                <td>{$catName}</td>
                                <td>{$active}</td>
                                <td>
                                    <button class='btn btn-sm btn-info text-white me-1' onclick='editPage(" . htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8') . ")'><i class='fas fa-edit'></i></button>
                                    <a href='manage_pages.php?delete={$row['id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to delete this page?\")'><i class='fas fa-trash'></i></a>
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
<div class="modal fade" id="pageModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form method="POST" action="">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add New Page</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="page_id">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Page Title</label>
                            <input type="text" name="name" id="pname" class="form-control" onkeyup="generateSlug(this.value)" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Slug (URL path)</label>
                            <input type="text" name="slug" id="pslug" class="form-control" required>
                            <small class="text-muted">e.g., about-us</small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Navbar Category</label>
                            <select name="cid" id="pcid" class="form-control" required>
                                <option value="0">Select Category</option>
                                <?php
                                $cat_query = "SELECT * FROM webpage_category ORDER BY name ASC";
                                $cat_res = mysqli_query($con, $cat_query);
                                while($cat = mysqli_fetch_assoc($cat_res)) {
                                    echo "<option value='{$cat['id']}'>{$cat['name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label>Page Content</label>
                            <textarea name="des" id="pdes" class="form-control" rows="10"></textarea>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="status" id="pstatus" checked value="1">
                                <label class="form-check-label" for="pstatus">Active Status</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="save_page" class="btn btn-primary">Save Page</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Include CKEditor -->
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('pdes', {
        versionCheck: false
    });

    function generateSlug(text) {
        if(document.getElementById('page_id').value == '') {
            let slug = text.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');
            document.getElementById('pslug').value = slug;
        }
    }

    function resetForm() {
        document.getElementById('page_id').value = '';
        document.getElementById('pname').value = '';
        document.getElementById('pslug').value = '';
        document.getElementById('pcid').value = '0';
        CKEDITOR.instances.pdes.setData('');
        document.getElementById('pstatus').checked = true;
        document.getElementById('modalTitle').innerText = 'Add New Page';
    }

    function editPage(data) {
        var modal = new bootstrap.Modal(document.getElementById('pageModal'));
        document.getElementById('page_id').value = data.id;
        document.getElementById('pname').value = data.name;
        document.getElementById('pslug').value = data.slug;
        document.getElementById('pcid').value = data.cid;
        CKEDITOR.instances.pdes.setData(data.des);
        document.getElementById('pstatus').checked = (data.status == 1);
        document.getElementById('modalTitle').innerText = 'Edit Page';
        modal.show();
    }
</script>

<?php include 'includes/footer.php'; ?>

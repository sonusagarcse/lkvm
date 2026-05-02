<?php
// admin/news.php
require_once '../connection.php';
include 'includes/header.php';

$message = '';
$error = '';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $query = "DELETE FROM news WHERE id = $id";
    if (mysqli_query($con, $query)) {
        if (function_exists('cache_delete')) {
            cache_delete('latest_news_5');
            cache_delete('latest_notices_5');
        }
        echo "<script>window.location.href='news.php?msg=deleted';</script>";
    }
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_news'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']); // Title/Headline
    $des = mysqli_real_escape_string($con, $_POST['des']);   // Description
    $type = mysqli_real_escape_string($con, $_POST['type']);
    $status = isset($_POST['status']) ? 1 : 0;

    if (!empty($_POST['id'])) {
        $id = intval($_POST['id']);
        $query = "UPDATE news SET name='$name', des='$des', type='$type', status='$status' WHERE id=$id";
    } else {
        $date = date('d-m-Y');
        $query = "INSERT INTO news (name, title, des, date, status, type) VALUES ('$name', '$name', '$des', '$date', '$status', '$type')";
    }

    if (mysqli_query($con, $query)) {
        // Clear cache so changes appear immediately
        if (function_exists('cache_delete')) {
            cache_delete('latest_news_5');
            cache_delete('latest_notices_5');
        }
        echo "<script>window.location.href='news.php?msg=saved';</script>";
    } else {
        $error = "Database Error: " . mysqli_error($con);
    }
}
?>

<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h2 class="h3 mb-0 text-gray-800">News & Notice Management</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newsModal" onclick="resetForm()">
            <i class="fas fa-plus"></i> Add News/Notice
        </button>
    </div>
</div>

<?php if (isset($_GET['msg']) && $_GET['msg'] == 'saved')
    echo '<div class="alert alert-success">Saved successfully!</div>'; ?>
<?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted')
    echo '<div class="alert alert-success">Deleted successfully!</div>'; ?>
<?php if ($error)
    echo '<div class="alert alert-danger">' . $error . '</div>'; ?>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%">id</th>
                        <th width="10%">Type</th>
                        <th width="20%">Headline</th>
                        <th width="35%">Description</th>
                        <th width="10%">Date</th>
                        <th width="10%">Status</th>
                        <th width="10%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT * FROM news ORDER BY id DESC";
                    $result = mysqli_query($con, $query);
                    while ($row = mysqli_fetch_assoc($result)) {
                        $active = $row['status'] == 1 ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>';
                        $dataType = ($row['type'] == 'notice') ? '<span class="badge bg-warning text-dark">Notice</span>' : '<span class="badge bg-info">News</span>';
                        $desc = (strlen($row['des']) > 50) ? substr(strip_tags($row['des']), 0, 50) . '...' : $row['des'];

                        echo "<tr>
                                <td>#{$row['id']}</td>
                                <td>{$dataType}</td>
                                <td><strong>{$row['name']}</strong></td>
                                <td>{$desc}</td>
                                <td>{$row['date']}</td>
                                <td>{$active}</td>
                                <td>
                                    <button class='btn btn-sm btn-info text-white me-1' onclick='editNews(" . json_encode($row) . ")'><i class='fas fa-edit'></i></button>
                                    <a href='news.php?delete={$row['id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure?\")'><i class='fas fa-trash'></i></a>
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
<div class="modal fade" id="newsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add News / Notice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="news_id">

                    <div class="mb-3">
                        <label>Type</label>
                        <select name="type" id="type" class="form-select">
                            <option value="news">News & Events</option>
                            <option value="notice">Notice Board</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Headline (Title)</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Details / Description</label>
                        <textarea name="des" id="des" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="status" id="status" checked>
                        <label class="form-check-label" for="status">Display on Website</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="save_news" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function resetForm() {
        document.getElementById('news_id').value = '';
        document.getElementById('name').value = '';
        document.getElementById('des').value = '';
        document.getElementById('type').value = 'news';
        document.getElementById('status').checked = true;
        document.getElementById('modalTitle').innerText = 'Add News / Notice';
    }

    function editNews(data) {
        var modal = new bootstrap.Modal(document.getElementById('newsModal'));
        document.getElementById('news_id').value = data.id;
        document.getElementById('name').value = data.name;
        document.getElementById('des').value = data.des;
        // Handle type if key exists, otherwise default
        document.getElementById('type').value = data.type || 'news';
        document.getElementById('status').checked = (data.status == 1);
        document.getElementById('modalTitle').innerText = 'Edit News / Notice';
        modal.show();
    }
</script>

<?php include 'includes/footer.php'; ?>
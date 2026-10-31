<?php
// admin/projects.php
require_once '../connection.php';
include 'includes/header.php';

$message = '';
$error = '';
$uploadDir = '../images/projects/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    // Fetch image path to delete file
    $q = mysqli_query($con, "SELECT image FROM projects WHERE id = $id");
    if ($r = mysqli_fetch_assoc($q)) {
        if (!empty($r['image']) && file_exists($uploadDir . $r['image'])) {
            unlink($uploadDir . $r['image']);
        }
    }
    mysqli_query($con, "DELETE FROM projects WHERE id = $id");
    echo "<script>window.location.href='projects?msg=deleted';</script>";
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_project'])) {
    if (!verify_csrf_token()) {
        die("CSRF validation failed.");
    }

    $title = mysqli_real_escape_string($con, $_POST['title']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $link = mysqli_real_escape_string($con, $_POST['link']);
    $status = mysqli_real_escape_string($con, $_POST['status']);
    $id = !empty($_POST['id']) ? intval($_POST['id']) : 0;

    $imageName = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $allowed_mimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        
        $upload_result = secure_upload_file($_FILES['image'], $uploadDir, $allowed_exts, $allowed_mimes, 3145728); // max 3MB
        
        if ($upload_result['success']) {
            $imageName = $upload_result['filename'];
            
            // Delete old image if editing
            if ($id > 0) {
                $stmt = $con->prepare("SELECT image FROM projects WHERE id = ?");
                if ($stmt) {
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $res = $stmt->get_result();
                    if ($r = $res->fetch_assoc()) {
                        if (!empty($r['image']) && file_exists($uploadDir . $r['image'])) {
                            @unlink($uploadDir . $r['image']);
                        }
                    }
                    $stmt->close();
                }
            }
        } else {
            die("Upload failed: " . e($upload_result['error']));
        }
    }

    if ($id > 0) {
        if (!empty($imageName)) {
            $stmt = $con->prepare("UPDATE projects SET title=?, description=?, link=?, status=?, image=? WHERE id=?");
            if ($stmt) {
                $stmt->bind_param("sssssi", $title, $description, $link, $status, $imageName, $id);
                $stmt->execute();
                $stmt->close();
            }
        } else {
            $stmt = $con->prepare("UPDATE projects SET title=?, description=?, link=?, status=? WHERE id=?");
            if ($stmt) {
                $stmt->bind_param("ssssi", $title, $description, $link, $status, $id);
                $stmt->execute();
                $stmt->close();
            }
        }
    } else {
        $stmt = $con->prepare("INSERT INTO projects (title, description, image, link, status) VALUES (?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sssss", $title, $description, $imageName, $link, $status);
            $stmt->execute();
            $stmt->close();
        }
    }

    echo "<script>window.location.href='projects?msg=saved';</script>";
}
?>

<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h2 class="h3 mb-0 text-gray-800">Our Running Projects</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#projectModal" onclick="resetForm()">
            <i class="fas fa-plus"></i> Add Project
        </button>
    </div>
</div>

<?php if (isset($_GET['msg']) && $_GET['msg'] == 'saved') echo '<div class="alert alert-success">Project saved successfully!</div>'; ?>
<?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted') echo '<div class="alert alert-success">Project deleted successfully!</div>'; ?>
<?php if ($error) echo '<div class="alert alert-danger">' . $error . '</div>'; ?>

<div class="row">
    <?php
    $result = mysqli_query($con, "SELECT * FROM projects ORDER BY id DESC");
    while ($row = mysqli_fetch_assoc($result)) {
        $img = !empty($row['image']) && file_exists($uploadDir . $row['image']) ? $uploadDir . $row['image'] : 'https://placehold.co/600x400?text=No+Image';
        $statusBadge = $row['status'] == 'active' ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>';
    ?>
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
            <img src="<?php echo $img; ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h5 class="card-title fw-bold mb-0"><?php echo htmlspecialchars($row['title']); ?></h5>
                    <?php echo $statusBadge; ?>
                </div>
                <p class="card-text text-muted small"><?php echo (strlen($row['description']) > 100) ? substr(strip_tags($row['description']), 0, 100) . '...' : $row['description']; ?></p>
                <?php if(!empty($row['link'])): ?>
                    <div class="text-truncate small text-primary mb-3"><i class="fas fa-link me-1"></i><?php echo htmlspecialchars($row['link']); ?></div>
                <?php endif; ?>
                <div class="d-flex gap-2">
                    <button class="btn btn-info btn-sm text-white flex-grow-1" onclick='editProject(<?php echo json_encode($row); ?>)'>
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <a href="projects?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                        <i class="fas fa-trash"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
</div>

<!-- Modal -->
<div class="modal fade" id="projectModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add New Project</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="project_id">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Project Title</label>
                        <input type="text" name="title" id="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <div class="form-text">Leave blank to keep existing image when editing. Recommended size: 800x600px</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="4"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label fw-bold">Link (Optional)</label>
                            <input type="url" name="link" id="link" class="form-control" placeholder="https://example.com/project">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="save_project" class="btn btn-primary">Save Project</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function resetForm() {
    document.getElementById('project_id').value = '';
    document.getElementById('title').value = '';
    document.getElementById('description').value = '';
    document.getElementById('link').value = '';
    document.getElementById('status').value = 'active';
    document.getElementById('modalTitle').innerText = 'Add New Project';
}

function editProject(data) {
    var modal = new bootstrap.Modal(document.getElementById('projectModal'));
    document.getElementById('project_id').value = data.id;
    document.getElementById('title').value = data.title;
    document.getElementById('description').value = data.description;
    document.getElementById('link').value = data.link;
    document.getElementById('status').value = data.status;
    document.getElementById('modalTitle').innerText = 'Edit Project';
    modal.show();
}
</script>

<?php include 'includes/footer.php'; ?>

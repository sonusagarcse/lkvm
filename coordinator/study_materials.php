<?php
// admin/study_materials.php
require_once '../connection.php';
include 'includes/header.php';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $query = "DELETE FROM studymaterials WHERE id = $id";
    if (mysqli_query($con, $query)) {
        echo "<script>window.location.href='study_materials.php?msg=deleted';</script>";
    }
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_material'])) {
    $cid = intval($_POST['cid']);
    $sid = intval($_POST['sid']);
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $driveadd = mysqli_real_escape_string($con, $_POST['driveadd']);

    // Default values
    $pid = 2; // Default Program ID
    $title = ''; // Not used in form
    $img = '';
    $vdo = '';
    $des = '';
    $bid = 0;

    $date = date('d-m-Y');
    $status = 1;

    if (!empty($_POST['id'])) {
        $id = intval($_POST['id']);
        $query = "UPDATE studymaterials SET cid='$cid', sid='$sid', name='$name', driveadd='$driveadd' WHERE id=$id";
    } else {
        $query = "INSERT INTO studymaterials (pid, cid, sid, name, title, img, vdo, driveadd, des, date, status, bid) 
                  VALUES ('$pid', '$cid', '$sid', '$name', '$title', '$img', '$vdo', '$driveadd', '$des', '$date', '$status', '$bid')";
    }

    if (mysqli_query($con, $query)) {
        echo "<script>window.location.href='study_materials.php?msg=saved';</script>";
    } else {
        $error = "Database Error: " . mysqli_error($con);
    }
}

// Fetch Courses for Dropdown
$courses = [];
$c_query = "SELECT id, name FROM courses WHERE status=1 ORDER BY name ASC";
$c_res = mysqli_query($con, $c_query);
while ($row = mysqli_fetch_assoc($c_res)) {
    $courses[] = $row;
}

// Fetch Subjects for JS Array
$subjects = [];
$s_query = "SELECT id, cid, name FROM subjects ORDER BY name ASC";
$s_res = mysqli_query($con, $s_query);
while ($row = mysqli_fetch_assoc($s_res)) {
    $subjects[] = $row;
}
?>

<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h2 class="h3 mb-0 text-gray-800">Study Materials</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#materialModal" onclick="resetForm()">
            <i class="fas fa-plus"></i> Add New Material
        </button>
    </div>
</div>

<?php if (isset($_GET['msg']) && $_GET['msg'] == 'saved')
    echo '<div class="alert alert-success">Material saved successfully!</div>'; ?>
<?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted')
    echo '<div class="alert alert-success">Material deleted successfully!</div>'; ?>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Material Name</th>
                        <th>Course</th>
                        <th>Subject</th>
                        <th>Link/Content</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT sm.*, c.name as course_name, s.name as subject_name 
                              FROM studymaterials sm 
                              LEFT JOIN courses c ON sm.cid = c.id 
                              LEFT JOIN subjects s ON sm.sid = s.id 
                              ORDER BY sm.id DESC";
                    $result = mysqli_query($con, $query);
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $linkPreview = !empty($row['driveadd']) ? '<span class="badge bg-info text-dark">Has Embed Code</span>' : '<span class="badge bg-secondary">No Content</span>';
                            echo "<tr>
                                    <td>{$row['id']}</td>
                                    <td><strong>" . htmlspecialchars($row['name']) . "</strong></td>
                                    <td>" . htmlspecialchars($row['course_name']) . "</td>
                                    <td>" . htmlspecialchars($row['subject_name']) . "</td>
                                    <td>{$linkPreview}</td>
                                    <td>{$row['date']}</td>
                                    <td>
                                        <button class='btn btn-sm btn-info text-white me-1' onclick='editMaterial(" . json_encode($row) . ")'><i class='fas fa-edit'></i></button>
                                        <a href='study_materials.php?delete={$row['id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure?\")'><i class='fas fa-trash'></i></a>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center'>No study materials found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div class="modal fade" id="materialModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add Study Material</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="material_id">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Course</label>
                            <select name="cid" id="cid" class="form-select" required onchange="filterSubjects()">
                                <option value="">Select Course</option>
                                <?php foreach ($courses as $c) {
                                    echo "<option value='{$c['id']}'>{$c['name']}</option>";
                                } ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Subject</label>
                            <select name="sid" id="sid" class="form-select" required>
                                <option value="">Select Subject</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label>Material Name / Title</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label>Google Drive Embed Code / Link</label>
                            <textarea name="driveadd" id="driveadd" class="form-control" rows="4"
                                placeholder="<iframe src='...'></iframe>"></textarea>
                            <small class="text-muted">Paste the Google Drive Embed Code here.</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="save_material" class="btn btn-primary">Save Material</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Subjects Data
    const allSubjects = <?php echo json_encode($subjects); ?>;

    function filterSubjects(selectedSid = null) {
        const cid = document.getElementById('cid').value;
        const sidSelect = document.getElementById('sid');
        sidSelect.innerHTML = '<option value="">Select Subject</option>';

        const filtered = allSubjects.filter(s => s.cid == cid);

        filtered.forEach(s => {
            const option = document.createElement('option');
            option.value = s.id;
            option.textContent = s.name;
            if (selectedSid && selectedSid == s.id) option.selected = true;
            sidSelect.appendChild(option);
        });
    }

    function resetForm() {
        document.getElementById('material_id').value = '';
        document.getElementById('cid').value = '';
        document.getElementById('sid').innerHTML = '<option value="">Select Subject</option>';
        document.getElementById('name').value = '';
        document.getElementById('driveadd').value = '';
        document.getElementById('modalTitle').innerText = 'Add Study Material';
    }

    function editMaterial(data) {
        var modal = new bootstrap.Modal(document.getElementById('materialModal'));
        document.getElementById('material_id').value = data.id;
        document.getElementById('cid').value = data.cid;
        document.getElementById('name').value = data.name;
        document.getElementById('driveadd').value = data.driveadd;
        document.getElementById('modalTitle').innerText = 'Edit Study Material';

        // Trigger subject filter and set selected subject
        filterSubjects(data.sid);

        modal.show();
    }
</script>

<?php include 'includes/footer.php'; ?>
<?php
// Handle actions
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$success_msg = '';
$error_msg = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_admit_card']) || isset($_POST['edit_admit_card'])) {
        $rollno = $_POST['rollno'];
        $exam = $_POST['exam'];
        $exam_date = $_POST['exam_date'];
        $exam_time = $_POST['exam_time'];
        $exam_session = $_POST['exam_session'];
        $bid = $_POST['bid'];
        $status = $_POST['status'];
        $description = $_POST['description'];

        // Handle image upload
        $img = '';
        if (isset($_FILES['img']) && $_FILES['img']['size'] > 0) {
            $target_dir = "../uploads/admit_cards/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $ext = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
            $img = time() . '_' . uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['img']['tmp_name'], $target_dir . $img);
        }

        if (isset($_POST['add_admit_card'])) {
            $stmt = $con->prepare("INSERT INTO admit_card (rollno, exam, exam_date, exam_time, exam_session, bid, status, des, img, date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
            $stmt->bind_param("sssssssss", $rollno, $exam, $exam_date, $exam_time, $exam_session, $bid, $status, $description, $img);
            if ($stmt->execute()) {
                $success_msg = "Admit card generated successfully!";
            } else {
                $error_msg = "Error generating admit card: " . $con->error;
            }
        } else {
            $id = $_POST['admit_card_id'];
            if ($img) {
                $stmt = $con->prepare("UPDATE admit_card SET rollno=?, exam=?, exam_date=?, exam_time=?, exam_session=?, bid=?, status=?, des=?, img=? WHERE id=?");
                $stmt->bind_param("sssssssssi", $rollno, $exam, $exam_date, $exam_time, $exam_session, $bid, $status, $description, $img, $id);
            } else {
                $stmt = $con->prepare("UPDATE admit_card SET rollno=?, exam=?, exam_date=?, exam_time=?, exam_session=?, bid=?, status=?, des=? WHERE id=?");
                $stmt->bind_param("ssssssssi", $rollno, $exam, $exam_date, $exam_time, $exam_session, $bid, $status, $description, $id);
            }
            if ($stmt->execute()) {
                $success_msg = "Admit card updated successfully!";
            } else {
                $error_msg = "Error updating admit card: " . $con->error;
            }
        }
    } elseif (isset($_POST['delete_admit_card'])) {
        $id = $_POST['admit_card_id'];
        $stmt = $con->prepare("DELETE FROM admit_card WHERE id=?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $success_msg = "Admit card deleted successfully!";
        } else {
            $error_msg = "Error deleting admit card: " . $con->error;
        }
    }
}

// Get branches for dropdown
$branches_result = $con->query("SELECT id, bname FROM branch WHERE status = 1");
$branches = [];
while ($row = $branches_result->fetch_assoc()) {
    $branches[] = $row;
}

// Get admit card data if editing
$admit_card = null;
if ($action === 'edit' && isset($_GET['id'])) {
    $stmt = $con->prepare("SELECT * FROM admit_card WHERE id = ?");
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    $admit_card = $stmt->get_result()->fetch_assoc();
}
?>

<!-- Success/Error Messages -->
<?php if ($success_msg): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <?php echo $success_msg; ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<?php if ($error_msg): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?php echo $error_msg; ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<?php if ($action === 'add' || $action === 'edit'): ?>
<!-- Add/Edit Admit Card Form -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><?php echo $action === 'add' ? 'Generate New Admit Card' : 'Edit Admit Card'; ?></h5>
    </div>
    <div class="card-body">
        <form method="POST" action="" enctype="multipart/form-data">
            <?php if ($action === 'edit'): ?>
                <input type="hidden" name="admit_card_id" value="<?php echo $admit_card['id']; ?>">
            <?php endif; ?>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Roll Number</label>
                        <input type="text" name="rollno" class="form-control" required 
                               value="<?php echo $admit_card ? $admit_card['rollno'] : ''; ?>">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Study Center</label>
                        <select name="bid" class="form-control" required>
                            <option value="">Select Study Center</option>
                            <?php foreach ($branches as $branch): ?>
                                <option value="<?php echo $branch['id']; ?>" 
                                        <?php echo ($admit_card && $admit_card['bid'] == $branch['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($branch['bname']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Exam Name/Type</label>
                        <input type="text" name="exam" class="form-control" required 
                               value="<?php echo $admit_card ? $admit_card['exam'] : ''; ?>">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Exam Session</label>
                        <input type="number" name="exam_session" class="form-control" required 
                               value="<?php echo $admit_card ? $admit_card['exam_session'] : date('Y'); ?>">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Exam Date</label>
                        <input type="date" name="exam_date" class="form-control" required 
                               value="<?php echo $admit_card ? $admit_card['exam_date'] : ''; ?>">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Exam Time</label>
                        <input type="text" name="exam_time" class="form-control" required 
                               value="<?php echo $admit_card ? $admit_card['exam_time'] : ''; ?>"
                               placeholder="e.g. 10:00 AM">
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Student Photo</label>
                <input type="file" name="img" class="form-control" <?php echo $action === 'add' ? 'required' : ''; ?>>
                <?php if ($admit_card && $admit_card['img']): ?>
                    <div class="mt-2">
                        <img src="../uploads/admit_cards/<?php echo $admit_card['img']; ?>" 
                             alt="Student Photo" style="max-height: 100px;">
                    </div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Additional Instructions</label>
                <textarea name="description" class="form-control" rows="4"><?php echo $admit_card ? $admit_card['des'] : ''; ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-control" required>
                    <option value="1" <?php echo ($admit_card && $admit_card['status'] == 1) ? 'selected' : ''; ?>>Active</option>
                    <option value="0" <?php echo ($admit_card && $admit_card['status'] == 0) ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>
            
            <div class="mb-3">
                <button type="submit" name="<?php echo $action === 'add' ? 'add_admit_card' : 'edit_admit_card'; ?>" 
                        class="btn btn-primary">
                    <?php echo $action === 'add' ? 'Generate Admit Card' : 'Update Admit Card'; ?>
                </button>
                <a href="?page=admit_cards" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php else: ?>
<!-- List Admit Cards -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Admit Cards</h5>
        <a href="?page=admit_cards&action=add" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Generate New Admit Card
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Roll No</th>
                        <th>Study Center</th>
                        <th>Exam</th>
                        <th>Date & Time</th>
                        <th>Session</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $con->query("SELECT ac.*, b.bname 
                                         FROM admit_card ac 
                                         LEFT JOIN branch b ON ac.bid = b.id 
                                         ORDER BY ac.id DESC");
                    while ($row = $result->fetch_assoc()):
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['rollno']); ?></td>
                        <td><?php echo htmlspecialchars($row['bname']); ?></td>
                        <td><?php echo htmlspecialchars($row['exam']); ?></td>
                        <td>
                            <?php echo date('d M Y', strtotime($row['exam_date'])); ?><br>
                            <small><?php echo $row['exam_time']; ?></small>
                        </td>
                        <td><?php echo $row['exam_session']; ?></td>
                        <td>
                            <span class="badge bg-<?php echo $row['status'] ? 'success' : 'danger'; ?>">
                                <?php echo $row['status'] ? 'Active' : 'Inactive'; ?>
                            </span>
                        </td>
                        <td>
                            <a href="?page=admit_cards&action=edit&id=<?php echo $row['id']; ?>" 
                               class="btn btn-sm btn-info">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="" class="d-inline" 
                                  onsubmit="return confirm('Are you sure you want to delete this admit card?');">
                                <input type="hidden" name="admit_card_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="delete_admit_card" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?> 
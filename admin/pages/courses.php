<?php
// Handle actions
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$success_msg = '';
$error_msg = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_course']) || isset($_POST['edit_course'])) {
        $title = $_POST['title'];
        $category = $_POST['category'];
        $duration = $_POST['duration'];
        $fees = $_POST['fees'];
        $description = $_POST['description'];
        $status = $_POST['status'];

        if (isset($_POST['add_course'])) {
            $stmt = $con->prepare("INSERT INTO courses (title, category_id, duration, fees, description, status) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("siidsi", $title, $category, $duration, $fees, $description, $status);
            if ($stmt->execute()) {
                $success_msg = "Course added successfully!";
            } else {
                $error_msg = "Error adding course: " . $con->error;
            }
        } else {
            $id = $_POST['course_id'];
            $stmt = $con->prepare("UPDATE courses SET title=?, category_id=?, duration=?, fees=?, description=?, status=? WHERE id=?");
            $stmt->bind_param("siidsii", $title, $category, $duration, $fees, $description, $status, $id);
            if ($stmt->execute()) {
                $success_msg = "Course updated successfully!";
            } else {
                $error_msg = "Error updating course: " . $con->error;
            }
        }
    } elseif (isset($_POST['delete_course'])) {
        $id = $_POST['course_id'];
        $stmt = $con->prepare("DELETE FROM courses WHERE id=?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $success_msg = "Course deleted successfully!";
        } else {
            $error_msg = "Error deleting course: " . $con->error;
        }
    }
}

// Get categories for dropdown
$categories_result = $con->query("SELECT * FROM course_category WHERE status = 1");
$categories = [];
while ($row = $categories_result->fetch_assoc()) {
    $categories[] = $row;
}

// Get course data if editing
$course = null;
if ($action === 'edit' && isset($_GET['id'])) {
    $stmt = $con->prepare("SELECT * FROM courses WHERE id = ?");
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    $course = $stmt->get_result()->fetch_assoc();
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
<!-- Add/Edit Course Form -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><?php echo $action === 'add' ? 'Add New Course' : 'Edit Course'; ?></h5>
    </div>
    <div class="card-body">
        <form method="POST" action="">
            <?php if ($action === 'edit'): ?>
                <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
            <?php endif; ?>
            
            <div class="mb-3">
                <label class="form-label">Course Title</label>
                <input type="text" name="title" class="form-control" required 
                       value="<?php echo $course ? $course['title'] : ''; ?>">
            </div>
            
            <div class="mb-3">
                <label class="form-label">Category</label>
                <select name="category" class="form-control" required>
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>" 
                                <?php echo ($course && $course['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Duration (months)</label>
                <input type="number" name="duration" class="form-control" required 
                       value="<?php echo $course ? $course['duration'] : ''; ?>">
            </div>
            
            <div class="mb-3">
                <label class="form-label">Fees</label>
                <input type="number" name="fees" class="form-control" required 
                       value="<?php echo $course ? $course['fees'] : ''; ?>">
            </div>
            
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4"><?php echo $course ? $course['description'] : ''; ?></textarea>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-control" required>
                    <option value="1" <?php echo ($course && $course['status'] == 1) ? 'selected' : ''; ?>>Active</option>
                    <option value="0" <?php echo ($course && $course['status'] == 0) ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>
            
            <div class="mb-3">
                <button type="submit" name="<?php echo $action === 'add' ? 'add_course' : 'edit_course'; ?>" 
                        class="btn btn-primary">
                    <?php echo $action === 'add' ? 'Add Course' : 'Update Course'; ?>
                </button>
                <a href="?page=courses" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php else: ?>
<!-- List Courses -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Course List</h5>
        <a href="?page=courses&action=add" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Add New Course
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Duration</th>
                        <th>Fees</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $con->query("SELECT c.*, cc.name as category_name 
                                         FROM courses c 
                                         LEFT JOIN course_category cc ON c.category_id = cc.id 
                                         ORDER BY c.id DESC");
                    while ($row = $result->fetch_assoc()):
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                        <td><?php echo $row['duration']; ?> months</td>
                        <td>₹<?php echo number_format($row['fees'], 2); ?></td>
                        <td>
                            <span class="badge bg-<?php echo $row['status'] ? 'success' : 'danger'; ?>">
                                <?php echo $row['status'] ? 'Active' : 'Inactive'; ?>
                            </span>
                        </td>
                        <td>
                            <a href="?page=courses&action=edit&id=<?php echo $row['id']; ?>" 
                               class="btn btn-sm btn-info">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="" class="d-inline" 
                                  onsubmit="return confirm('Are you sure you want to delete this course?');">
                                <input type="hidden" name="course_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="delete_course" class="btn btn-sm btn-danger">
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
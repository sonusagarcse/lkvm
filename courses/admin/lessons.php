<?php
$page_title = 'Manage Lessons';
include 'includes/header.php';

$course_id = $_GET['course_id'] ?? 0;

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($con, "DELETE FROM lms_lessons WHERE id = $id");
    echo "<script>window.location.href='lessons.php?course_id=$course_id';</script>";
}

// Handle Add
if (isset($_POST['save_lesson'])) {
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $desc = mysqli_real_escape_string($con, $_POST['description']);
    $type = $_POST['content_type'];
    $url = $_POST['content_url'];
    $cid = $_POST['course_id'];
    $is_demo = isset($_POST['is_preview']) ? 1 : 0;
    
    $sql = "INSERT INTO lms_lessons (course_id, title, description, content_type, content_url, is_preview) 
            VALUES ('$cid', '$title', '$desc', '$type', '$url', '$is_demo')";
    mysqli_query($con, $sql);
    echo "<script>window.location.href='lessons.php?course_id=$cid';</script>";
}
?>

<div class="row g-4">
    <div class="col-xl-4">
        <div class="admin-card p-4 border-0 shadow-sm">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-4">
                    <i class="fa fa-plus-circle fs-4"></i>
                </div>
                <h5 class="fw-800 mb-0">Add New Lesson</h5>
            </div>
            <form method="POST">
                <div class="mb-4">
                    <label class="form-label small fw-bold text-muted text-uppercase">Select Course</label>
                    <select name="course_id" class="form-select border-0 bg-light p-3 rounded-3" required>
                        <?php
                        $courses = mysqli_query($con, "SELECT id, title FROM lms_courses");
                        while($c = mysqli_fetch_assoc($courses)) {
                            $sel = ($c['id'] == $course_id) ? 'selected' : '';
                            echo "<option value='{$c['id']}' $sel>{$c['title']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="form-label small fw-bold text-muted text-uppercase">Lesson Title</label>
                    <input type="text" name="title" class="form-control border-0 bg-light p-3 rounded-3" required placeholder="Introduction to UI">
                </div>
                <div class="mb-4">
                    <div class="form-check form-switch bg-light p-3 rounded-3 border-0">
                        <input class="form-check-input ms-0 me-3" type="checkbox" name="is_preview" id="is_preview">
                        <label class="form-check-label fw-bold text-primary" for="is_preview">Free Preview / Demo</label>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label small fw-bold text-muted text-uppercase">Content Type</label>
                    <select name="content_type" class="form-select border-0 bg-light p-3 rounded-3">
                        <option value="video">YouTube Video</option>
                        <option value="pdf">PDF Resource</option>
                        <option value="text">Article / Text</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="form-label small fw-bold text-muted text-uppercase">URL / Link</label>
                    <input type="text" name="content_url" class="form-control border-0 bg-light p-3 rounded-3" placeholder="https://youtube.com/...">
                </div>
                <div class="mb-4">
                    <label class="form-label small fw-bold text-muted text-uppercase">Short Description</label>
                    <textarea name="description" class="form-control border-0 bg-light p-3 rounded-3" rows="4"></textarea>
                </div>
                <button type="submit" name="save_lesson" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-lg" style="background: var(--primary); border: none;">Save Lesson</button>
            </form>
        </div>
    </div>
    
    <div class="col-xl-8">
        <div class="admin-card p-0 border-0 shadow-sm overflow-hidden">
            <div class="p-4 border-bottom bg-white d-flex justify-content-between align-items-center">
                <h5 class="fw-800 mb-0">Lesson List</h5>
                <?php if($course_id > 0): ?>
                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3">Filtered by Course</span>
                <?php endif; ?>
            </div>
            <div class="table-responsive">
                <table class="table table-premium mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Title & Course</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $filter = ($course_id > 0) ? "WHERE course_id = $course_id" : "";
                        $lessons = mysqli_query($con, "SELECT l.*, c.title as course_title FROM lms_lessons l JOIN lms_courses c ON l.course_id = c.id $filter ORDER BY l.id DESC");
                        if(mysqli_num_rows($lessons) > 0):
                            while($row = mysqli_fetch_assoc($lessons)):
                        ?>
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark mb-1"><?php echo htmlspecialchars($row['title']); ?></div>
                                <div class="text-muted small"><i class="fa fa-graduation-cap me-1"></i> <?php echo $row['course_title']; ?></div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark rounded-pill px-3 py-2 border small text-capitalize">
                                    <i class="fa <?php echo $row['content_type'] == 'video' ? 'fa-play-circle text-danger' : 'fa-file-alt text-primary'; ?> me-1"></i>
                                    <?php echo $row['content_type']; ?>
                                </span>
                            </td>
                            <td>
                                <?php if($row['is_preview']): ?>
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 small fw-bold">FREE DEMO</span>
                                <?php else: ?>
                                    <span class="badge bg-light text-muted rounded-pill px-3 py-2 small">Premium</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="?delete=<?php echo $row['id']; ?>&course_id=<?php echo $course_id; ?>" class="btn btn-sm btn-light text-danger rounded-3 p-2" onclick="return confirm('Delete this lesson?')" title="Delete"><i class="fa fa-trash-alt"></i></a>
                                </div>
                            </td>
                        </tr>
                        <?php 
                            endwhile;
                        else:
                            echo "<tr><td colspan='4' class='text-center py-5 text-muted'>No lessons found. Add your first lesson!</td></tr>";
                        endif;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

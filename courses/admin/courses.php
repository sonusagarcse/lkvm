<?php
$page_title = 'Manage Courses';
include 'includes/header.php';

$action = $_GET['action'] ?? 'list';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $con->prepare("DELETE FROM lms_courses WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
    echo "<script>window.location.href='courses.php';</script>";
}

// Handle Add/Edit
if (isset($_POST['save_course'])) {
    // CSRF Check
    if (!verify_csrf_token()) {
        die("CSRF validation failed.");
    }

    $title = mysqli_real_escape_string($con, $_POST['title']);
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    $short_desc = mysqli_real_escape_string($con, $_POST['short_desc']);
    $price = floatval($_POST['price'] ?? 0);
    $is_free = isset($_POST['is_free']) ? intval($_POST['is_free']) : 0;
    if ($is_free == 1) $price = 0;
    
    $duration = mysqli_real_escape_string($con, $_POST['duration']);
    $level = mysqli_real_escape_string($con, $_POST['level']);
    $instructor = mysqli_real_escape_string($con, $_POST['instructor']);
    $category_id = intval($_POST['category_id']);
    $cid = isset($_POST['course_id']) ? intval($_POST['course_id']) : 0;
    
    // Handle Image Upload
    $thumbnail = $_POST['old_thumb'] ?? '';
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] !== UPLOAD_ERR_NO_FILE) {
        $target_dir = "../uploads/courses/";
        $allowed_exts = ['jpg', 'jpeg', 'png', 'webp'];
        $allowed_mimes = ['image/jpeg', 'image/png', 'image/webp'];
        
        $upload_result = secure_upload_file($_FILES['thumbnail'], $target_dir, $allowed_exts, $allowed_mimes, 2097152); // max 2MB
        
        if ($upload_result['success']) {
            $thumbnail = 'uploads/courses/' . $upload_result['filename'];
        } else {
            die("Thumbnail upload failed: " . e($upload_result['error']));
        }
    }
    
    if ($cid > 0) {
        $stmt = $con->prepare("UPDATE lms_courses SET title=?, slug=?, thumbnail=?, short_desc=?, price=?, is_free=?, duration=?, level=?, instructor=?, category_id=? WHERE id=?");
        if ($stmt) {
            $stmt->bind_param("ssssdiisssi", $title, $slug, $thumbnail, $short_desc, $price, $is_free, $duration, $level, $instructor, $category_id, $cid);
            $stmt->execute();
            $stmt->close();
        }
    } else {
        $stmt = $con->prepare("INSERT INTO lms_courses (title, slug, thumbnail, short_desc, price, is_free, duration, level, instructor, category_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("ssssdiisss", $title, $slug, $thumbnail, $short_desc, $price, $is_free, $duration, $level, $instructor, $category_id);
            $stmt->execute();
            $stmt->close();
        }
    }
    echo "<script>window.location.href='courses.php';</script>";
}

// Fetch Course Data for Edit
$course_data = ['title' => '', 'short_desc' => '', 'thumbnail' => '', 'price' => '0', 'is_free' => 0, 'duration' => '', 'level' => 'Beginner', 'instructor' => '', 'category_id' => '', 'id' => 0];
if ($action == 'edit' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $con->prepare("SELECT * FROM lms_courses WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows > 0) {
            $course_data = $res->fetch_assoc();
        }
        $stmt->close();
    }
}
?>

<?php if($action == 'list'): ?>
    <div class="d-flex justify-content-end mb-4">
        <a href="?action=add" class="btn btn-primary px-4 rounded-pill shadow-sm"><i class="fa fa-plus me-2"></i> Add New Course</a>
    </div>
<?php else: ?>
    <div class="d-flex justify-content-end mb-4">
        <a href="courses.php" class="btn btn-light px-4 rounded-pill shadow-sm"><i class="fa fa-arrow-left me-2"></i> Back to List</a>
    </div>
<?php endif; ?>

<?php if($action == 'list'): ?>
    <div class="admin-card p-0 border-0 shadow-sm overflow-hidden">
        <div class="p-4 border-bottom bg-white d-flex justify-content-between align-items-center">
            <h5 class="fw-800 mb-0">Course Catalog</h5>
            <span class="badge bg-light text-dark rounded-pill px-3">Manage curriculum</span>
        </div>
        <div class="table-responsive">
            <table class="table table-premium mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Course Details</th>
                        <th>Category</th>
                        <th>Pricing</th>
                        <th>Lessons</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $courses = mysqli_query($con, "SELECT c.*, cat.name as category_name, (SELECT COUNT(*) FROM lms_lessons WHERE course_id = c.id) as lesson_count FROM lms_courses c LEFT JOIN lms_categories cat ON c.category_id = cat.id ORDER BY c.id DESC");
                    while($row = mysqli_fetch_assoc($courses)):
                    ?>
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                <?php if($row['thumbnail']): ?>
                                    <img src="../<?php echo $row['thumbnail']; ?>" class="rounded-3" style="width: 50px; height: 35px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="bg-light rounded-3" style="width: 50px; height: 35px;"></div>
                                <?php endif; ?>
                                <div>
                                    <div class="fw-bold text-dark mb-0 small"><?php echo htmlspecialchars($row['title']); ?></div>
                                    <div class="text-muted" style="font-size: 0.7rem;"><i class="fa fa-user-tie me-1"></i> <?php echo $row['instructor']; ?></div>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3"><?php echo $row['category_name']; ?></span></td>
                        <td>
                            <?php if($row['is_free']): ?>
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 fw-bold small">FREE</span>
                            <?php else: ?>
                                <div class="fw-800 text-dark small">₹<?php echo number_format($row['price']); ?></div>
                            <?php endif; ?>
                        </td>
                        <td><div class="small text-muted fw-bold"><i class="fa fa-book-open me-2"></i><?php echo $row['lesson_count']; ?></div></td>
                        <td class="text-end pe-4">
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="lessons.php?course_id=<?php echo $row['id']; ?>" class="btn btn-sm btn-light text-primary rounded-3 p-2" title="Manage Lessons"><i class="fa fa-layer-group"></i></a>
                                <a href="?action=edit&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-light text-warning rounded-3 p-2" title="Edit Course"><i class="fa fa-edit"></i></a>
                                <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-light text-danger rounded-3 p-2" title="Delete" onclick="return confirm('Delete course and all its lessons?')"><i class="fa fa-trash-alt"></i></a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php elseif($action == 'add' || $action == 'edit'): ?>
    <div class="mx-auto" style="max-width: 1000px;">
        <div class="admin-card p-0 border-0 shadow-sm overflow-hidden mb-5">
            <div class="p-4 border-bottom bg-white d-flex align-items-center gap-3">
                <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-4">
                    <i class="fa <?php echo $action == 'add' ? 'fa-plus' : 'fa-edit'; ?> fs-4"></i>
                </div>
                <div>
                    <h5 class="fw-800 mb-0"><?php echo $action == 'add' ? 'Create New Course' : 'Update Course'; ?></h5>
                    <p class="text-muted small mb-0">Fill in the details to publish your course content.</p>
                </div>
            </div>
            
            <form method="POST" class="p-5" enctype="multipart/form-data">
                <input type="hidden" name="course_id" value="<?php echo $course_data['id']; ?>">
                <input type="hidden" name="old_thumb" value="<?php echo $course_data['thumbnail']; ?>">
                
                <div class="row g-4">
                    <div class="col-12">
                        <div class="p-4 rounded-4 bg-light bg-opacity-50 border border-dashed mb-2">
                            <h6 class="fw-800 text-muted text-uppercase small mb-4">1. Primary Details</h6>
                            <div class="row g-4">
                                <div class="col-md-8">
                                    <div class="mb-4">
                                        <label class="form-label fw-bold small">COURSE TITLE</label>
                                        <input type="text" name="title" class="form-control border-0 p-3 rounded-3 shadow-sm" required placeholder="e.g. Master React.js" value="<?php echo htmlspecialchars($course_data['title']); ?>">
                                    </div>
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small">CATEGORY</label>
                                            <select name="category_id" class="form-select border-0 p-3 rounded-3 shadow-sm">
                                                <?php
                                                $cats = mysqli_query($con, "SELECT * FROM lms_categories");
                                                while($c = mysqli_fetch_assoc($cats)) {
                                                    $sel = ($c['id'] == $course_data['category_id']) ? 'selected' : '';
                                                    echo "<option value='{$c['id']}' $sel>{$c['name']}</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small">INSTRUCTOR</label>
                                            <input type="text" name="instructor" class="form-control border-0 p-3 rounded-3 shadow-sm" required value="<?php echo htmlspecialchars($course_data['instructor'] ?: 'Admin'); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold small">COURSE THUMBNAIL</label>
                                    <div class="thumbnail-upload border rounded-4 d-flex flex-column align-items-center justify-content-center bg-white shadow-sm overflow-hidden position-relative" style="height: 160px; cursor: pointer;" onclick="document.getElementById('thumbInput').click()">
                                        <?php if($course_data['thumbnail']): ?>
                                            <img src="../<?php echo $course_data['thumbnail']; ?>" id="thumbPreview" class="w-100 h-100 object-fit-cover">
                                        <?php else: ?>
                                            <div id="uploadPlaceholder" class="text-center">
                                                <i class="fa fa-cloud-upload-alt fs-2 text-primary mb-2"></i>
                                                <div class="small fw-bold text-muted">Upload Image</div>
                                            </div>
                                            <img src="" id="thumbPreview" class="w-100 h-100 object-fit-cover d-none">
                                        <?php endif; ?>
                                        <input type="file" name="thumbnail" id="thumbInput" class="d-none" accept="image/*" onchange="previewImage(this)">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="p-4 rounded-4 bg-primary bg-opacity-10 border border-primary border-opacity-10 mb-2">
                            <h6 class="fw-800 text-primary text-uppercase small mb-4">2. Pricing Strategy</h6>
                            <div class="row g-4 align-items-end">
                                <div class="col-md-5">
                                    <label class="form-label fw-bold small text-dark">ENROLLMENT TYPE</label>
                                    <div class="d-flex gap-4 mt-2">
                                        <div class="form-check custom-radio">
                                            <input class="form-check-input" type="radio" name="is_free" id="paidRadio" value="0" <?php echo !$course_data['is_free'] ? 'checked' : ''; ?>>
                                            <label class="form-check-label fw-bold text-dark" for="paidRadio">Paid Course</label>
                                        </div>
                                        <div class="form-check custom-radio">
                                            <input class="form-check-input" type="radio" name="is_free" id="freeRadio" value="1" <?php echo $course_data['is_free'] ? 'checked' : ''; ?>>
                                            <label class="form-check-label fw-bold text-success" for="freeRadio">Free Course</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <label class="form-label fw-bold small text-dark" id="priceLabel">COURSE PRICE (₹)</label>
                                    <div class="input-group">
                                        <span class="input-group-text border-0 bg-white shadow-sm">₹</span>
                                        <input type="number" name="price" id="priceInput" class="form-control border-0 p-3 shadow-sm" required value="<?php echo $course_data['price']; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="p-4 rounded-4 bg-light bg-opacity-50 border border-dashed">
                            <h6 class="fw-800 text-muted text-uppercase small mb-4">3. Extra Information</h6>
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">DURATION (HOURS)</label>
                                    <input type="text" name="duration" class="form-control border-0 p-3 rounded-3 shadow-sm" placeholder="e.g. 15" value="<?php echo htmlspecialchars($course_data['duration']); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">LEVEL</label>
                                    <select name="level" class="form-select border-0 p-3 rounded-3 shadow-sm">
                                        <option <?php echo $course_data['level'] == 'Beginner' ? 'selected' : ''; ?>>Beginner</option>
                                        <option <?php echo $course_data['level'] == 'Intermediate' ? 'selected' : ''; ?>>Intermediate</option>
                                        <option <?php echo $course_data['level'] == 'Advanced' ? 'selected' : ''; ?>>Advanced</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold small">DESCRIPTION</label>
                                    <textarea name="short_desc" class="form-control border-0 p-4 rounded-4 shadow-sm" rows="4" placeholder="Course highlights..."><?php echo htmlspecialchars($course_data['short_desc']); ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 pt-4">
                        <button type="submit" name="save_course" class="btn btn-primary btn-lg px-5 rounded-pill shadow-lg" style="background: var(--primary); border: none;">
                            <i class="fa fa-check-circle me-2"></i> Save Course
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        const pRadio = document.getElementById('paidRadio');
        const fRadio = document.getElementById('freeRadio');
        const pInput = document.getElementById('priceInput');
        const pLabel = document.getElementById('priceLabel');

        function updatePriceState() {
            if (fRadio.checked) {
                pInput.value = 0;
                pInput.disabled = true;
                pInput.classList.add('bg-light');
                pLabel.classList.add('opacity-50');
            } else {
                pInput.disabled = false;
                pInput.classList.remove('bg-light');
                pLabel.classList.remove('opacity-50');
            }
        }

        function previewImage(input) {
            const preview = document.getElementById('thumbPreview');
            const placeholder = document.getElementById('uploadPlaceholder');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                    if(placeholder) placeholder.classList.add('d-none');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        pRadio.addEventListener('change', updatePriceState);
        fRadio.addEventListener('change', updatePriceState);
        updatePriceState();
    </script>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
 ?>

<?php
// admin/home_settings.php
require_once '../connection.php';
include 'includes/header.php';

$message = '';
$error = '';
$uploadDir = '../images/';

// Run self-healing database check to verify popup columns exist in global_setting table
$cols_to_check = [
    'welcome_title' => "VARCHAR(255) DEFAULT 'Welcome to LKVM'",
    'welcome_desc' => "TEXT",
    'welcome_image' => "VARCHAR(255) DEFAULT ''",
    'welcome_btn_text' => "VARCHAR(100) DEFAULT 'Read More'",
    'welcome_btn_link' => "VARCHAR(255) DEFAULT '/about_us'",
    'welcome_status' => "TINYINT(1) DEFAULT 1"
];

foreach ($cols_to_check as $col => $definition) {
    $check = mysqli_query($con, "SHOW COLUMNS FROM `global_setting` LIKE '$col'");
    if (mysqli_num_rows($check) == 0) {
        mysqli_query($con, "ALTER TABLE `global_setting` ADD `$col` $definition");
    }
}

// Fetch current settings
$setQ = mysqli_query($con, "SELECT * FROM global_setting WHERE id = 1");
$currentSettings = mysqli_fetch_assoc($setQ);

// Handle Delete (for home_sections and testimonials)
if (isset($_GET['delete_section'])) {
    $id = intval($_GET['delete_section']);
    if (mysqli_query($con, "DELETE FROM home_sections WHERE id = $id")) {
        $message = "Item deleted successfully!";
        if (function_exists('cache_delete')) { 
            cache_delete('home_top_features');
            cache_delete('home_website_links');
            cache_delete('home_counters');
        }
    }
}

if (isset($_GET['delete_testimonial'])) {
    $id = intval($_GET['delete_testimonial']);
    if (mysqli_query($con, "DELETE FROM webpage WHERE id = $id AND cid = 10")) {
        $message = "Testimonial deleted successfully!";
        if (function_exists('cache_delete')) { cache_delete('testimonials_10'); }
    }
}

// Handle Welcome Pop-up Update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_popup'])) {
    if (!verify_csrf_token()) {
        die("CSRF validation failed.");
    }
    $welcome_title = mysqli_real_escape_string($con, $_POST['welcome_title']);
    $welcome_desc = mysqli_real_escape_string($con, $_POST['welcome_desc']);
    $welcome_btn_text = mysqli_real_escape_string($con, $_POST['welcome_btn_text']);
    $welcome_btn_link = mysqli_real_escape_string($con, $_POST['welcome_btn_link']);
    $welcome_status = isset($_POST['welcome_status']) ? 1 : 0;

    $welcome_image = $currentSettings['welcome_image'] ?? '';

    // Handle Image Upload
    if (isset($_FILES['welcome_image_file']) && $_FILES['welcome_image_file']['error'] !== UPLOAD_ERR_NO_FILE) {
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $allowed_mimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $upload_result = secure_upload_file($_FILES['welcome_image_file'], $uploadDir, $allowed_exts, $allowed_mimes, 2097152);
        
        if ($upload_result['success']) {
            if ($welcome_image && file_exists($uploadDir . $welcome_image)) {
                @unlink($uploadDir . $welcome_image);
            }
            $welcome_image = $upload_result['filename'];
        } else {
            die("Upload failed: " . e($upload_result['error']));
        }
    }

    // Handle Image Removal
    if (isset($_POST['remove_welcome_image']) && $_POST['remove_welcome_image'] == '1') {
        if ($welcome_image && file_exists($uploadDir . $welcome_image)) {
            @unlink($uploadDir . $welcome_image);
        }
        $welcome_image = '';
    }

    $stmt = $con->prepare("UPDATE global_setting SET welcome_title=?, welcome_desc=?, welcome_btn_text=?, welcome_btn_link=?, welcome_status=?, welcome_image=? WHERE id=1");
    if ($stmt) {
        $stmt->bind_param("ssssis", $welcome_title, $welcome_desc, $welcome_btn_text, $welcome_btn_link, $welcome_status, $welcome_image);
        if ($stmt->execute()) {
            $message = "Welcome Pop-up Settings saved successfully!";
            if (function_exists('cache_delete')) { cache_delete('global_settings'); }
            // Refresh values
            $setQ = mysqli_query($con, "SELECT * FROM global_setting WHERE id = 1");
            $currentSettings = mysqli_fetch_assoc($setQ);
        } else {
            $error = "Error updating popup settings.";
        }
        $stmt->close();
    }
}

// Handle KYP Update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_kyp'])) {
    if (!verify_csrf_token()) {
        die("CSRF validation failed.");
    }
    $kyp_id = 12;
    $name = mysqli_real_escape_string($con, $_POST['kyp_title']);
    $des = mysqli_real_escape_string($con, $_POST['kyp_des']);
    $vdo = mysqli_real_escape_string($con, $_POST['kyp_link']);
    
    // Fetch current img
    $stmt = $con->prepare("SELECT img FROM webpage WHERE id = ? LIMIT 1");
    $img = '';
    if ($stmt) {
        $stmt->bind_param("i", $kyp_id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            $img = $row['img'];
        }
        $stmt->close();
    }

    if (isset($_FILES['kyp_img']) && $_FILES['kyp_img']['error'] !== UPLOAD_ERR_NO_FILE) {
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $allowed_mimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $upload_result = secure_upload_file($_FILES['kyp_img'], $uploadDir, $allowed_exts, $allowed_mimes, 2097152);
        
        if ($upload_result['success']) {
            $img = $upload_result['filename'];
        } else {
            die("Upload failed: " . e($upload_result['error']));
        }
    }

    $stmt = $con->prepare("UPDATE webpage SET name=?, des=?, vdo=?, img=? WHERE id=?");
    if ($stmt) {
        $stmt->bind_param("ssssi", $name, $des, $vdo, $img, $kyp_id);
        if ($stmt->execute()) {
            $message = "KYP Section updated!";
            if (function_exists('cache_delete')) { cache_delete('webpage_12'); }
        }
        $stmt->close();
    }
}

// Handle Testimonial Add/Update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_testimonial'])) {
    if (!verify_csrf_token()) {
        die("CSRF validation failed.");
    }
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $des = mysqli_real_escape_string($con, $_POST['des']);
    $cid = 10;
    $status = 1;
    $date = date('d-m-Y');

    // Fetch current testimonial image
    $img = '';
    if ($id > 0) {
        $stmt = $con->prepare("SELECT img FROM webpage WHERE id = ? LIMIT 1");
        if ($stmt) {
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($row = $res->fetch_assoc()) {
                $img = $row['img'];
            }
            $stmt->close();
        }
    }

    if (isset($_FILES['t_img']) && $_FILES['t_img']['error'] !== UPLOAD_ERR_NO_FILE) {
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $allowed_mimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $upload_result = secure_upload_file($_FILES['t_img'], $uploadDir, $allowed_exts, $allowed_mimes, 2097152);
        
        if ($upload_result['success']) {
            $img = $upload_result['filename'];
        } else {
            die("Upload failed: " . e($upload_result['error']));
        }
    }

    if ($id > 0) {
        $stmt = $con->prepare("UPDATE webpage SET name=?, title=?, des=?, img=? WHERE id=? AND cid=10");
        if ($stmt) {
            $stmt->bind_param("ssssi", $name, $title, $des, $img, $id);
            $stmt->execute();
            $stmt->close();
        }
    } else {
        $stmt = $con->prepare("INSERT INTO webpage (name, title, des, img, cid, status, date) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("ssssiis", $name, $title, $des, $img, $cid, $status, $date);
            $stmt->execute();
            $stmt->close();
        }
    }

    $message = "Testimonial saved!";
    if (function_exists('cache_delete')) { cache_delete('testimonials_10'); }
}

// Handle Home Section Add/Update (Phase 2 logic)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_item'])) {
    if (!verify_csrf_token()) {
        die("CSRF validation failed.");
    }
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $section_type = mysqli_real_escape_string($con, $_POST['section_type']);
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $icon = mysqli_real_escape_string($con, $_POST['icon']);
    $link = mysqli_real_escape_string($con, $_POST['link']);
    $value = mysqli_real_escape_string($con, $_POST['value']);
    $sort_order = intval($_POST['sort_order']);
    $date = date('d-m-Y');

    if (isset($_FILES['icon_file']) && $_FILES['icon_file']['error'] !== UPLOAD_ERR_NO_FILE) {
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $allowed_mimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $upload_result = secure_upload_file($_FILES['icon_file'], $uploadDir, $allowed_exts, $allowed_mimes, 2097152);
        
        if ($upload_result['success']) {
            $icon = $upload_result['filename'];
        } else {
            die("Upload failed: " . e($upload_result['error']));
        }
    }

    if ($id > 0) {
        $stmt = $con->prepare("UPDATE home_sections SET section_type=?, title=?, icon=?, link=?, value=?, sort_order=? WHERE id=?");
        if ($stmt) {
            $stmt->bind_param("sssssii", $section_type, $title, $icon, $link, $value, $sort_order, $id);
            $stmt->execute();
            $stmt->close();
        }
    } else {
        $stmt = $con->prepare("INSERT INTO home_sections (section_type, title, icon, link, value, sort_order, date) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sssssis", $section_type, $title, $icon, $link, $value, $sort_order, $date);
            $stmt->execute();
            $stmt->close();
        }
    }

    $message = "Section item saved!";
    if (function_exists('cache_delete')) { 
        cache_delete('home_top_features');
        cache_delete('home_website_links');
        cache_delete('home_counters');
    }
}

// Fetch Data
$items = [];
$res = mysqli_query($con, "SELECT * FROM home_sections ORDER BY section_type, sort_order ASC");
while ($row = mysqli_fetch_assoc($res)) { $items[$row['section_type']][] = $row; }

$kyp = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM webpage WHERE id = 12"));

$testimonials = [];
$res = mysqli_query($con, "SELECT * FROM webpage WHERE cid = 10 ORDER BY id DESC");
while ($row = mysqli_fetch_assoc($res)) { $testimonials[] = $row; }
?>

<div class="row mb-4">
    <div class="col-12">
        <h2 class="h3 mb-0 text-gray-800">Unified Home Page Settings</h2>
    </div>
</div>

<?php if ($message) echo '<div class="alert alert-success">' . $message . '</div>'; ?>
<?php if ($error) echo '<div class="alert alert-danger">' . $error . '</div>'; ?>

<ul class="nav nav-pills mb-4 bg-white p-2 rounded-4 shadow-sm" id="pills-tab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active rounded-3" id="pills-general-tab" data-bs-toggle="pill" data-bs-target="#pills-general" type="button">General Sections</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link rounded-3" id="pills-popup-tab" data-bs-toggle="pill" data-bs-target="#pills-popup" type="button">Welcome Pop-up</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link rounded-3" id="pills-kyp-tab" data-bs-toggle="pill" data-bs-target="#pills-kyp" type="button">Welcome to KYP</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link rounded-3" id="pills-testimonials-tab" data-bs-toggle="pill" data-bs-target="#pills-testimonials" type="button">Testimonials</button>
    </li>
</ul>

<div class="tab-content" id="pills-tabContent">
    <!-- Home Sections Tab -->
    <div class="tab-pane fade show active" id="pills-general" role="tabpanel">
        <div class="d-flex justify-content-between mb-3">
            <h5 class="fw-bold">Icon Boxes & Stats</h5>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#itemModal" onclick="setItem(null)">
                <i class="fas fa-plus"></i> Add New
            </button>
        </div>
        <?php foreach (['visit_website' => 'Website Links', 'top_feature' => 'Top Features', 'counter' => 'Counters'] as $type => $label): ?>
            <div class="card mb-3 border-0 shadow-sm">
                <div class="card-header bg-light border-0"><small class="fw-bold text-uppercase"><?php echo $label; ?></small></div>
                <div class="card-body p-0">
                    <table class="table table-sm table-hover mb-0 align-middle">
                        <thead><tr class="bg-light"><th width="50">Sort</th><th>Title</th><th>Icon / Image</th><th>Misc</th><th class="text-end">Action</th></tr></thead>
                        <tbody>
                            <?php if (isset($items[$type])): foreach ($items[$type] as $item): ?>
                            <tr>
                                <td class="text-center"><small class="text-muted"><?php echo $item['sort_order']; ?></small></td>
                                <td><strong><?php echo htmlspecialchars($item['title']); ?></strong></td>
                                <td>
                                    <?php if (strpos($item['icon'], 'fa') === 0): ?>
                                        <i class="<?php echo $item['icon']; ?>"></i> <small>(<?php echo $item['icon']; ?>)</small>
                                    <?php elseif ($item['icon']): ?>
                                        <img src="../images/<?php echo $item['icon']; ?>" style="height:30px; border-radius:3px;">
                                    <?php endif; ?>
                                </td>
                                <td><small><?php echo ($type == 'counter' ? 'Value: ' : 'Link: ') . htmlspecialchars($type == 'counter' ? $item['value'] : $item['link']); ?></small></td>
                                <td class="text-end px-3">
                                    <button class="btn btn-link btn-sm p-0 me-2" data-bs-toggle="modal" data-bs-target="#itemModal" onclick='setItem(<?php echo json_encode($item); ?>)'><i class="fas fa-edit"></i></button>
                                    <a href="?delete_section=<?php echo $item['id']; ?>" class="btn btn-link btn-sm p-0 text-danger" onclick="return confirm('Delete?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Welcome Pop-up Settings Tab -->
    <div class="tab-pane fade" id="pills-popup" role="tabpanel">
        <div class="row">
            <!-- Form Column -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4" style="color:#002147;"><i class="fas fa-bullhorn text-warning me-2"></i>Welcome Pop-up Notice Settings</h5>
                        <form method="POST" action="" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Pop-up Title</label>
                                    <input type="text" name="welcome_title" id="input_welcome_title" class="form-control" value="<?php echo htmlspecialchars($currentSettings['welcome_title'] ?? 'Welcome to LKVM'); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Pop-up Status</label>
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input" type="checkbox" name="welcome_status" id="welcome_status" style="width: 50px; height: 25px;" <?php echo ($currentSettings['welcome_status'] ?? 1) == 1 ? 'checked' : ''; ?>>
                                        <label class="form-check-label fw-medium ms-2" for="welcome_status">Display Welcome Pop-up Site-wide</label>
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label fw-bold">Message Content</label>
                                    <textarea name="welcome_desc" id="input_welcome_desc" class="form-control" rows="4" required><?php echo htmlspecialchars($currentSettings['welcome_desc'] ?? ''); ?></textarea>
                                    <div class="form-text">Briefly explain announcements, notices, or welcome greetings to first-time visitors.</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Action Button Text</label>
                                    <input type="text" name="welcome_btn_text" id="input_welcome_btn_text" class="form-control" value="<?php echo htmlspecialchars($currentSettings['welcome_btn_text'] ?? 'Read More'); ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Action Button Link</label>
                                    <input type="text" name="welcome_btn_link" class="form-control" value="<?php echo htmlspecialchars($currentSettings['welcome_btn_link'] ?? '/about_us'); ?>">
                                    <div class="form-text">Redirect page path e.g. <code>about_us</code></div>
                                </div>
                                <div class="col-md-12 mb-4">
                                    <label class="form-label fw-bold">Side Image (Optional)</label>
                                    <div class="p-3 border rounded-3 bg-light d-flex align-items-center gap-3">
                                        <div class="flex-grow-1">
                                            <input type="file" name="welcome_image_file" id="input_welcome_image" class="form-control" accept="image/*">
                                            <div class="form-text">Adds a modern, professional split side-image layout! Recommended: Portrait orientation.</div>
                                        </div>
                                        <?php if (!empty($currentSettings['welcome_image'])): ?>
                                            <div class="position-relative text-center">
                                                <img src="../images/<?php echo $currentSettings['welcome_image']; ?>" id="preview_img_source" style="height: 100px; width: 80px; object-fit: cover; border-radius: 8px; border: 2px solid #ddd;">
                                                <div class="form-check mt-1">
                                                    <input class="form-check-input" type="checkbox" name="remove_welcome_image" value="1" id="removeImg">
                                                    <label class="form-check-label text-danger small fw-bold" for="removeImg">Remove Image</label>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" name="update_popup" class="btn btn-primary rounded-pill px-4 shadow-sm"><i class="fas fa-save me-2"></i>Save Pop-up Settings</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Preview Column -->
            <div class="col-lg-5 mt-4 mt-lg-0">
                <div class="sticky-top" style="top: 20px; z-index: 5;">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="card-header bg-dark text-white border-0 py-3">
                            <h6 class="mb-0 small text-uppercase fw-bold"><i class="fas fa-eye me-2"></i>Live Popup Preview</h6>
                        </div>
                        <div class="card-body bg-light p-4">
                            <!-- Simulated Popup Content -->
                            <div id="live_popup_preview" style="background: #fff; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); overflow: hidden; display: flex; flex-direction: column; width: 100%;">
                                <div style="display: flex; flex-wrap: wrap;">
                                    <div id="preview_image_container" style="width: 40%; min-height: 250px; background: url('../images/<?php echo $currentSettings['welcome_image'] ?? ''; ?>') no-repeat center center / cover; display: <?php echo !empty($currentSettings['welcome_image']) ? 'block' : 'none'; ?>;"></div>
                                    <div style="flex: 1; padding: 30px; background: #ffffff; color: #002147;">
                                        <div style="background: #fdf2d0; width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                                            <i class="fas fa-bullhorn" style="font-size: 16px; color: #fdc800;"></i>
                                        </div>
                                        <h4 id="preview_title" style="color: #002147; font-size: 22px; font-weight: 800; margin-bottom: 15px; letter-spacing: -0.5px;"><?php echo htmlspecialchars($currentSettings['welcome_title'] ?? 'Welcome to LKVM'); ?></h4>
                                        <p id="preview_desc" style="font-size: 14px; line-height: 1.7; color: #4b5563; margin-bottom: 25px; white-space: pre-wrap;"><?php echo nl2br(htmlspecialchars($currentSettings['welcome_desc'] ?? '')); ?></p>
                                        <a href="javascript:void(0)" id="preview_btn" style="background: #002147; color: #ffffff; padding: 10px 24px; border-radius: 50px; font-weight: 700; text-decoration: none; font-size: 13px; display: inline-block; box-shadow: 0 4px 10px rgba(0,33,71,0.15);"><?php echo htmlspecialchars($currentSettings['welcome_btn_text'] ?? 'Read More'); ?></a>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3 text-center small text-muted">
                                <i class="fas fa-info-circle me-1"></i> This is a scaled-down preview of how the popup appears to users.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputTitle = document.getElementById('input_welcome_title');
        const inputDesc = document.getElementById('input_welcome_desc');
        const inputBtnText = document.getElementById('input_welcome_btn_text');
        const inputImage = document.getElementById('input_welcome_image');
        const removeImgCheck = document.getElementById('removeImg');

        const previewTitle = document.getElementById('preview_title');
        const previewDesc = document.getElementById('preview_desc');
        const previewBtn = document.getElementById('preview_btn');
        const previewImageContainer = document.getElementById('preview_image_container');

        const updatePreview = () => {
            previewTitle.innerText = inputTitle.value || 'Welcome to LKVM';
            previewDesc.innerText = inputDesc.value || '';
            previewBtn.innerText = inputBtnText.value || 'Read More';
            
            const bg = previewImageContainer.style.backgroundImage;
            const hasActualImage = bg && bg !== 'none' && bg !== 'url("")' && !bg.match(/\/images\/?['"]?\)$/);

            if (hasActualImage && !(removeImgCheck && removeImgCheck.checked)) {
                previewImageContainer.style.display = 'block';
            } else {
                previewImageContainer.style.display = 'none';
            }
        };

        inputTitle.addEventListener('input', updatePreview);
        inputDesc.addEventListener('input', updatePreview);
        inputBtnText.addEventListener('input', updatePreview);
        if (removeImgCheck) removeImgCheck.addEventListener('change', updatePreview);

        inputImage.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImageContainer.style.backgroundImage = `url('${e.target.result}')`;
                    previewImageContainer.style.display = 'block';
                    if (removeImgCheck) removeImgCheck.checked = false;
                }
                reader.readAsDataURL(file);
            }
        });
    });
    </script>


    <!-- KYP Tab -->
    <div class="tab-pane fade" id="pills-kyp" role="tabpanel">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body">
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="fw-bold">Title</label>
                        <input type="text" name="kyp_title" class="form-control" value="<?php echo htmlspecialchars($kyp['name']); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Description</label>
                        <textarea name="kyp_des" class="form-control" rows="5"><?php echo htmlspecialchars($kyp['des']); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Read More Link</label>
                        <input type="text" name="kyp_link" class="form-control" value="<?php echo htmlspecialchars($kyp['vdo']); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Background Image</label>
                        <input type="file" name="kyp_img" class="form-control">
                        <?php if($kyp['img']): ?>
                            <img src="../images/<?php echo $kyp['img']; ?>" class="mt-2" style="height:80px; border-radius:5px;">
                        <?php endif; ?>
                    </div>
                    <button type="submit" name="update_kyp" class="btn btn-primary">Save KYP Section</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Testimonials Tab -->
    <div class="tab-pane fade" id="pills-testimonials" role="tabpanel">
        <div class="d-flex justify-content-between mb-3">
            <h5 class="fw-bold">Visitor Testimonials</h5>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#testimonialModal" onclick="setTestimonial(null)">
                <i class="fas fa-plus"></i> Add New
            </button>
        </div>
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <table class="table table-hover mb-0 align-middle">
                    <thead><tr class="bg-light"><th>Photo</th><th>Name</th><th>Designation</th><th>Content</th><th>Action</th></tr></thead>
                    <tbody>
                        <?php foreach ($testimonials as $t): ?>
                        <tr>
                            <td>
                                <?php if($t['img']): ?>
                                    <img src="../images/<?php echo $t['img']; ?>" style="height:40px; width:40px; object-fit:cover; border-radius:50%;">
                                <?php else: ?>
                                    <div style="height:40px; width:40px; background:#eee; border-radius:50%;" class="d-flex align-items-center justify-content-center"><i class="fas fa-user text-muted"></i></div>
                                <?php endif; ?>
                            </td>
                            <td><strong><?php echo htmlspecialchars($t['name']); ?></strong></td>
                            <td><small><?php echo htmlspecialchars($t['title']); ?></small></td>
                            <td><small><?php echo substr(strip_tags($t['des']), 0, 50); ?>...</small></td>
                            <td>
                                <button class="btn btn-link btn-sm p-0 me-2" data-bs-toggle="modal" data-bs-target="#testimonialModal" onclick='setTestimonial(<?php echo json_encode($t); ?>)'><i class="fas fa-edit"></i></button>
                                <a href="?delete_testimonial=<?php echo $t['id']; ?>" class="btn btn-link btn-sm p-0 text-danger" onclick="return confirm('Delete?')"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
<div class="modal fade" id="itemModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="" enctype="multipart/form-data">
                <input type="hidden" name="id" id="item_id">
                <div class="modal-header"><h5>Item Settings</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label>Type</label><select name="section_type" id="item_type" class="form-select"><option value="visit_website">Visit Our Website</option><option value="top_feature">Top Feature</option><option value="counter">Counter Stat</option></select></div>
                    <div class="mb-3"><label>Title</label><input type="text" name="title" id="item_title" class="form-control" required></div>
                    <div class="mb-3"><label>Icon Class / Upload</label><input type="text" name="icon" id="item_icon" class="form-control mb-2" placeholder="fas fa-star"><input type="file" name="icon_file" class="form-control"></div>
                    <div class="mb-3"><label>Link</label><input type="text" name="link" id="item_link" class="form-control"></div>
                    <div class="mb-3"><label>Value / Subtitle</label><input type="text" name="value" id="item_value" class="form-control"></div>
                    <div class="mb-3"><label>Sort Order</label><input type="number" name="sort_order" id="item_sort" class="form-control"></div>
                </div>
                <div class="modal-footer"><button type="submit" name="save_item" class="btn btn-primary w-100">Save Changes</button></div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="testimonialModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="" enctype="multipart/form-data">
                <input type="hidden" name="id" id="t_id">
                <div class="modal-header"><h5>Testimonial Settings</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label>Visitor Name</label><input type="text" name="name" id="t_name" class="form-control" required></div>
                    <div class="mb-3"><label>Designation</label><input type="text" name="title" id="t_title" class="form-control"></div>
                    <div class="mb-3"><label>Review Content</label><textarea name="des" id="t_des" class="form-control" rows="4"></textarea></div>
                    <div class="mb-3">
                        <label>Visitor Photo</label>
                        <input type="file" name="t_img" class="form-control" accept="image/*">
                        <small class="text-muted">Current photo will be replaced if you upload a new one.</small>
                    </div>
                </div>
                <div class="modal-footer"><button type="submit" name="save_testimonial" class="btn btn-primary w-100">Save Testimonial</button></div>
            </form>
        </div>
    </div>
</div>

<script>
function setItem(item) {
    if (item) {
        document.getElementById('item_id').value = item.id;
        document.getElementById('item_type').value = item.section_type;
        document.getElementById('item_title').value = item.title;
        document.getElementById('item_icon').value = item.icon;
        document.getElementById('item_link').value = item.link;
        document.getElementById('item_value').value = item.value;
        document.getElementById('item_sort').value = item.sort_order;
    } else {
        document.getElementById('item_id').value = '';
        document.getElementById('item_title').value = '';
        document.getElementById('item_icon').value = '';
        document.getElementById('item_link').value = '';
        document.getElementById('item_value').value = '';
        document.getElementById('item_sort').value = '0';
    }
}
function setTestimonial(item) {
    if (item) {
        document.getElementById('t_id').value = item.id;
        document.getElementById('t_name').value = item.name;
        document.getElementById('t_title').value = item.title;
        document.getElementById('t_des').value = item.des;
    } else {
        document.getElementById('t_id').value = '';
        document.getElementById('t_name').value = '';
        document.getElementById('t_title').value = '';
        document.getElementById('t_des').value = '';
    }
}
</script>

<?php include 'includes/footer.php'; ?>

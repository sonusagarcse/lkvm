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
    $welcome_title = mysqli_real_escape_string($con, $_POST['welcome_title']);
    $welcome_desc = mysqli_real_escape_string($con, $_POST['welcome_desc']);
    $welcome_btn_text = mysqli_real_escape_string($con, $_POST['welcome_btn_text']);
    $welcome_btn_link = mysqli_real_escape_string($con, $_POST['welcome_btn_link']);
    $welcome_status = isset($_POST['welcome_status']) ? 1 : 0;

    $welcome_image = $currentSettings['welcome_image'] ?? '';

    // Handle Image Upload
    if (isset($_FILES['welcome_image_file']) && $_FILES['welcome_image_file']['error'] == 0) {
        $filename = 'popup_' . time() . '_' . $_FILES['welcome_image_file']['name'];
        if (move_uploaded_file($_FILES['welcome_image_file']['tmp_name'], $uploadDir . $filename)) {
            // Remove old image if any
            if ($welcome_image && file_exists($uploadDir . $welcome_image)) {
                @unlink($uploadDir . $welcome_image);
            }
            $welcome_image = $filename;
        }
    }

    // Handle Image Removal
    if (isset($_POST['remove_welcome_image']) && $_POST['remove_welcome_image'] == '1') {
        if ($welcome_image && file_exists($uploadDir . $welcome_image)) {
            @unlink($uploadDir . $welcome_image);
        }
        $welcome_image = '';
    }

    $sql = "UPDATE global_setting SET 
            welcome_title='$welcome_title', 
            welcome_desc='$welcome_desc', 
            welcome_btn_text='$welcome_btn_text', 
            welcome_btn_link='$welcome_btn_link', 
            welcome_status=$welcome_status,
            welcome_image='$welcome_image' 
            WHERE id=1";

    if (mysqli_query($con, $sql)) {
        $message = "Welcome Pop-up Settings saved successfully!";
        if (function_exists('cache_delete')) { cache_delete('global_settings'); }
        // Refresh values
        $setQ = mysqli_query($con, "SELECT * FROM global_setting WHERE id = 1");
        $currentSettings = mysqli_fetch_assoc($setQ);
    } else {
        $error = "Error updating popup settings: " . mysqli_error($con);
    }
}

// Handle KYP Update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_kyp'])) {
    $kyp_id = 12;
    $name = mysqli_real_escape_string($con, $_POST['kyp_title']);
    $des = mysqli_real_escape_string($con, $_POST['kyp_des']);
    $vdo = mysqli_real_escape_string($con, $_POST['kyp_link']);
    
    // Fetch current img
    $res = mysqli_query($con, "SELECT img FROM webpage WHERE id = $kyp_id");
    $row = mysqli_fetch_assoc($res);
    $img = $row['img'];

    if (isset($_FILES['kyp_img']) && $_FILES['kyp_img']['error'] == 0) {
        $filename = 'kyp_bg_' . time() . '_' . $_FILES['kyp_img']['name'];
        if (move_uploaded_file($_FILES['kyp_img']['tmp_name'], $uploadDir . $filename)) {
            $img = $filename;
        }
    }

    $sql = "UPDATE webpage SET name='$name', des='$des', vdo='$vdo', img='$img' WHERE id=$kyp_id";
    if (mysqli_query($con, $sql)) {
        $message = "KYP Section updated!";
        if (function_exists('cache_delete')) { cache_delete('webpage_12'); }
    }
}

// Handle Testimonial Add/Update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_testimonial'])) {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $des = mysqli_real_escape_string($con, $_POST['des']);
    $cid = 10;
    $status = 1;
    $date = date('d-m-Y');

    // Handle testimonial image
    $res = mysqli_query($con, "SELECT img FROM webpage WHERE id = $id");
    $row = mysqli_fetch_assoc($res);
    $img = $row ? $row['img'] : '';

    if (isset($_FILES['t_img']) && $_FILES['t_img']['error'] == 0) {
        $filename = 't_' . time() . '_' . $_FILES['t_img']['name'];
        if (move_uploaded_file($_FILES['t_img']['tmp_name'], $uploadDir . $filename)) {
            $img = $filename;
        }
    }

    if ($id > 0) {
        $sql = "UPDATE webpage SET name='$name', title='$title', des='$des', img='$img' WHERE id=$id AND cid=10";
    } else {
        $sql = "INSERT INTO webpage (name, title, des, img, cid, status, date) VALUES ('$name', '$title', '$des', '$img', $cid, $status, '$date')";
    }

    if (mysqli_query($con, $sql)) {
        $message = "Testimonial saved!";
        if (function_exists('cache_delete')) { cache_delete('testimonials_10'); }
    }
}

// Handle Home Section Add/Update (Phase 2 logic)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_item'])) {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $section_type = mysqli_real_escape_string($con, $_POST['section_type']);
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $icon = mysqli_real_escape_string($con, $_POST['icon']);
    $link = mysqli_real_escape_string($con, $_POST['link']);
    $value = mysqli_real_escape_string($con, $_POST['value']);
    $sort_order = intval($_POST['sort_order']);
    $date = date('d-m-Y');

    if (isset($_FILES['icon_file']) && $_FILES['icon_file']['error'] == 0) {
        $filename = 'icon_' . time() . '_' . $_FILES['icon_file']['name'];
        if (move_uploaded_file($_FILES['icon_file']['tmp_name'], $uploadDir . $filename)) {
            $icon = $filename;
        }
    }

    if ($id > 0) {
        $query = "UPDATE home_sections SET section_type='$section_type', title='$title', icon='$icon', link='$link', value='$value', sort_order=$sort_order WHERE id=$id";
    } else {
        $query = "INSERT INTO home_sections (section_type, title, icon, link, value, sort_order, date) VALUES ('$section_type', '$title', '$icon', '$link', '$value', $sort_order, '$date')";
    }

    if (mysqli_query($con, $query)) {
        $message = "Section item saved!";
        if (function_exists('cache_delete')) { 
            cache_delete('home_top_features');
            cache_delete('home_website_links');
            cache_delete('home_counters');
        }
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
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4" style="color:#002147;"><i class="fas fa-bullhorn text-warning me-2"></i>Welcome Pop-up Notice Settings</h5>
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Pop-up Title</label>
                            <input type="text" name="welcome_title" class="form-control" value="<?php echo htmlspecialchars($currentSettings['welcome_title'] ?? 'Welcome to LKVM'); ?>" required>
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
                            <textarea name="welcome_desc" class="form-control" rows="4" required><?php echo htmlspecialchars($currentSettings['welcome_desc'] ?? ''); ?></textarea>
                            <div class="form-text">Briefly explain announcements, notices, or welcome greetings to first-time visitors.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Action Button Text</label>
                            <input type="text" name="welcome_btn_text" class="form-control" value="<?php echo htmlspecialchars($currentSettings['welcome_btn_text'] ?? 'Read More'); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Action Button Link</label>
                            <input type="text" name="welcome_btn_link" class="form-control" value="<?php echo htmlspecialchars($currentSettings['welcome_btn_link'] ?? '/about_us'); ?>">
                            <div class="form-text">Redirect page path e.g. <code>about_us</code> or <code>https://example.com</code></div>
                        </div>
                        <div class="col-md-12 mb-4">
                            <label class="form-label fw-bold">Side Image (Optional)</label>
                            <div class="p-3 border rounded-3 bg-light d-flex align-items-center gap-3">
                                <div class="flex-grow-1">
                                    <input type="file" name="welcome_image_file" class="form-control" accept="image/*">
                                    <div class="form-text">Adds a modern, professional split side-image layout on desktop screens! Recommended: Portrait orientation.</div>
                                </div>
                                <?php if (!empty($currentSettings['welcome_image'])): ?>
                                    <div class="position-relative text-center">
                                        <img src="../images/<?php echo $currentSettings['welcome_image']; ?>" style="height: 100px; width: 80px; object-fit: cover; border-radius: 8px; border: 2px solid #ddd;">
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

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
    'welcome_btn_text' => "VARCHAR(100) DEFAULT 'Explore More'",
    'welcome_btn_link' => "VARCHAR(255) DEFAULT 'about_us'",
    'welcome_status' => "TINYINT(1) DEFAULT 1",
    'welcome_layout' => "VARCHAR(50) DEFAULT 'card_cover'",
    'welcome_badge' => "VARCHAR(150) DEFAULT 'Special Announcement'",
    'welcome_btn_secondary_text' => "VARCHAR(100) DEFAULT 'Close'",
    'welcome_btn_secondary_link' => "VARCHAR(255) DEFAULT '#close'",
    'welcome_btn_color' => "VARCHAR(30) DEFAULT '#002147'",
    'welcome_delay' => "INT(11) DEFAULT 2",
    'welcome_frequency' => "VARCHAR(20) DEFAULT 'session'"
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

// Query Quick Analytics for Welcome Popup
$popup_stats = ['views' => 0, 'clicks' => 0, 'ctr' => 0];
$stat_q = mysqli_query($con, "
    SELECT 
        COUNT(CASE WHEN event_type = 'impression' THEN 1 END) AS total_views,
        COUNT(CASE WHEN event_type IN ('click_primary', 'click_secondary') THEN 1 END) AS total_clicks
    FROM popup_analytics
");
if ($stat_q) {
    $row_stat = mysqli_fetch_assoc($stat_q);
    $popup_stats['views'] = intval($row_stat['total_views'] ?? 0);
    $popup_stats['clicks'] = intval($row_stat['total_clicks'] ?? 0);
    $popup_stats['ctr'] = $popup_stats['views'] > 0 ? round(($popup_stats['clicks'] / $popup_stats['views']) * 100, 1) : 0;
}

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
    $welcome_title = trim($_POST['welcome_title'] ?? 'Welcome to LKVM');
    $welcome_desc = trim($_POST['welcome_desc'] ?? '');
    $welcome_btn_text = trim($_POST['welcome_btn_text'] ?? 'Explore More');
    $welcome_btn_link = trim($_POST['welcome_btn_link'] ?? 'about_us');
    $welcome_status = isset($_POST['welcome_status']) ? 1 : 0;
    $welcome_layout = in_array($_POST['welcome_layout'] ?? '', ['card_cover', 'card_split', 'card_minimal']) ? $_POST['welcome_layout'] : 'card_cover';
    $welcome_badge = trim($_POST['welcome_badge'] ?? '');
    $welcome_btn_secondary_text = trim($_POST['welcome_btn_secondary_text'] ?? '');
    $welcome_btn_secondary_link = trim($_POST['welcome_btn_secondary_link'] ?? '#close');
    $welcome_btn_color = trim($_POST['welcome_btn_color'] ?? '#002147');
    $welcome_delay = max(1, intval($_POST['welcome_delay'] ?? 2));
    $welcome_frequency = in_array($_POST['welcome_frequency'] ?? '', ['always', 'session', 'daily']) ? $_POST['welcome_frequency'] : 'session';

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

    $stmt = $con->prepare("UPDATE global_setting SET welcome_title=?, welcome_desc=?, welcome_btn_text=?, welcome_btn_link=?, welcome_status=?, welcome_image=?, welcome_layout=?, welcome_badge=?, welcome_btn_secondary_text=?, welcome_btn_secondary_link=?, welcome_btn_color=?, welcome_delay=?, welcome_frequency=? WHERE id=1");
    if ($stmt) {
        $stmt->bind_param("ssssissssssis", $welcome_title, $welcome_desc, $welcome_btn_text, $welcome_btn_link, $welcome_status, $welcome_image, $welcome_layout, $welcome_badge, $welcome_btn_secondary_text, $welcome_btn_secondary_link, $welcome_btn_color, $welcome_delay, $welcome_frequency);
        if ($stmt->execute()) {
            $message = "Welcome Pop-up Settings saved successfully!";
            if (function_exists('cache_delete')) { cache_delete('global_settings'); }
            // Refresh values
            $setQ = mysqli_query($con, "SELECT * FROM global_setting WHERE id = 1");
            $currentSettings = mysqli_fetch_assoc($setQ);
        } else {
            $error = "Error updating popup settings: " . $stmt->error;
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

<ul class="nav nav-pills mb-4 bg-white p-2 rounded-4 shadow-sm align-items-center" id="pills-tab" role="tablist">
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
    <li class="nav-item ms-auto d-none d-md-block">
        <a href="popup_analytics" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold">
            <i class="fas fa-chart-line me-1"></i> Track Popup Analytics
        </a>
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
        
        <!-- Quick Analytics Header Bar -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 text-white overflow-hidden" style="background: linear-gradient(135deg, #002147 0%, #1e3a8a 100%);">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-lg-6 mb-3 mb-lg-0">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-3 p-3 text-white" style="background: rgba(255,255,255,0.15); backdrop-filter: blur(4px);">
                                <i class="fas fa-bullhorn fa-2x text-warning"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1">Welcome Popup Card & Analytics</h5>
                                <p class="mb-0 small text-white-50">
                                    Status: 
                                    <?php if (($currentSettings['welcome_status'] ?? 1) == 1): ?>
                                        <span class="badge bg-success ms-1"><i class="fas fa-check-circle me-1"></i>Active Site-wide</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary ms-1"><i class="fas fa-pause-circle me-1"></i>Currently Disabled</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="d-flex justify-content-lg-end align-items-center gap-3 flex-wrap">
                            <div class="text-center px-3 py-1 rounded-3" style="background: rgba(255,255,255,0.1);">
                                <span class="d-block small text-white-50">Total Views</span>
                                <strong class="fs-5"><?php echo number_format($popup_stats['views']); ?></strong>
                            </div>
                            <div class="text-center px-3 py-1 rounded-3" style="background: rgba(255,255,255,0.1);">
                                <span class="d-block small text-white-50">Clicks</span>
                                <strong class="fs-5"><?php echo number_format($popup_stats['clicks']); ?></strong>
                            </div>
                            <div class="text-center px-3 py-1 rounded-3" style="background: rgba(255,255,255,0.1);">
                                <span class="d-block small text-white-50">CTR</span>
                                <strong class="fs-5"><?php echo $popup_stats['ctr']; ?>%</strong>
                            </div>
                            <a href="popup_analytics" class="btn btn-warning rounded-pill px-4 fw-bold shadow-sm" style="color: #002147;">
                                <i class="fas fa-chart-line me-1"></i> Full Analytics
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Form Column -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <form method="POST" action="" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>

                            <!-- Section: Card Layout Selection -->
                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark d-block">
                                    <i class="fas fa-layer-group text-primary me-2"></i>Select Card Layout Style
                                </label>
                                <?php $selected_layout = $currentSettings['welcome_layout'] ?? 'card_cover'; ?>
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <label class="card h-100 p-3 border text-center cursor-pointer layout-option-card <?php echo $selected_layout === 'card_cover' ? 'border-primary bg-primary bg-opacity-10' : ''; ?>" style="cursor: pointer; border-radius: 12px;">
                                            <input type="radio" name="welcome_layout" value="card_cover" class="d-none" <?php echo $selected_layout === 'card_cover' ? 'checked' : ''; ?>>
                                            <div class="mb-2"><i class="fas fa-id-card fa-2x text-primary"></i></div>
                                            <strong class="d-block small text-dark">Top Cover Card</strong>
                                            <span class="text-muted text-xs" style="font-size: 0.72rem;">Hero image banner on top</span>
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="card h-100 p-3 border text-center cursor-pointer layout-option-card <?php echo $selected_layout === 'card_split' ? 'border-primary bg-primary bg-opacity-10' : ''; ?>" style="cursor: pointer; border-radius: 12px;">
                                            <input type="radio" name="welcome_layout" value="card_split" class="d-none" <?php echo $selected_layout === 'card_split' ? 'checked' : ''; ?>>
                                            <div class="mb-2"><i class="fas fa-columns fa-2x text-success"></i></div>
                                            <strong class="d-block small text-dark">Split Side Card</strong>
                                            <span class="text-muted text-xs" style="font-size: 0.72rem;">Side image & content</span>
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="card h-100 p-3 border text-center cursor-pointer layout-option-card <?php echo $selected_layout === 'card_minimal' ? 'border-primary bg-primary bg-opacity-10' : ''; ?>" style="cursor: pointer; border-radius: 12px;">
                                            <input type="radio" name="welcome_layout" value="card_minimal" class="d-none" <?php echo $selected_layout === 'card_minimal' ? 'checked' : ''; ?>>
                                            <div class="mb-2"><i class="fas fa-square-envelope fa-2x text-warning"></i></div>
                                            <strong class="d-block small text-dark">Minimal Card</strong>
                                            <span class="text-muted text-xs" style="font-size: 0.72rem;">Clean text announcement</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Section: Cover Image -->
                            <div class="mb-4 p-3 bg-light rounded-3 border">
                                <label class="form-label fw-bold text-dark">
                                    <i class="fas fa-image text-info me-2"></i>Cover Image
                                </label>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="flex-grow-1">
                                        <input type="file" name="welcome_image_file" id="input_welcome_image" class="form-control" accept="image/*">
                                        <div class="form-text"><strong>Recommended Size: 1280 &times; 720 Px (16:9 Aspect Ratio)</strong> for Top Cover layout, or Portrait for Split layout (Max 2MB).</div>
                                    </div>
                                    <?php if (!empty($currentSettings['welcome_image'])): ?>
                                        <div class="text-center position-relative">
                                            <img src="../images/<?php echo htmlspecialchars($currentSettings['welcome_image']); ?>" id="current_image_thumb" style="height: 60px; width: 60px; object-fit: cover; border-radius: 8px; border: 2px solid #cbd5e1;">
                                            <div class="form-check mt-1">
                                                <input class="form-check-input" type="checkbox" name="remove_welcome_image" value="1" id="removeImg">
                                                <label class="form-check-label text-danger small fw-bold" for="removeImg" style="font-size: 0.72rem;">Remove</label>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Section: Content Details -->
                            <div class="row g-3 mb-3">
                                <div class="col-md-5">
                                    <label class="form-label fw-bold">Badge / Tagline</label>
                                    <input type="text" name="welcome_badge" id="input_welcome_badge" class="form-control" placeholder="e.g. Special Announcement" value="<?php echo htmlspecialchars($currentSettings['welcome_badge'] ?? 'Special Announcement'); ?>">
                                    <div class="form-text">Small pill tag above headline.</div>
                                </div>
                                <div class="col-md-7">
                                    <label class="form-label fw-bold">Card Title / Headline <span class="text-danger">*</span></label>
                                    <input type="text" name="welcome_title" id="input_welcome_title" class="form-control" value="<?php echo htmlspecialchars($currentSettings['welcome_title'] ?? 'Welcome to LKVM'); ?>" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold">Details / Announcement Message <span class="text-danger">*</span></label>
                                    <textarea name="welcome_desc" id="input_welcome_desc" class="form-control" rows="4" required><?php echo htmlspecialchars($currentSettings['welcome_desc'] ?? ''); ?></textarea>
                                    <div class="form-text">Full details of your notice, program, admission or greeting. Line breaks are preserved.</div>
                                </div>
                            </div>

                            <!-- Section: Action Buttons -->
                            <div class="p-3 bg-light rounded-3 border mb-4">
                                <h6 class="fw-bold mb-3 text-dark"><i class="fas fa-hand-pointer text-primary me-2"></i>Card Action Buttons</h6>
                                <div class="row g-3">
                                    <!-- Primary Button -->
                                    <div class="col-md-5">
                                        <label class="form-label fw-bold small">Primary Button Text</label>
                                        <input type="text" name="welcome_btn_text" id="input_welcome_btn_text" class="form-control" value="<?php echo htmlspecialchars($currentSettings['welcome_btn_text'] ?? 'Explore More'); ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold small">Primary Button Link</label>
                                        <input type="text" name="welcome_btn_link" class="form-control" value="<?php echo htmlspecialchars($currentSettings['welcome_btn_link'] ?? 'about_us'); ?>" placeholder="about_us or https://">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold small">Button Color</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <input type="color" name="welcome_btn_color" id="input_welcome_btn_color" class="form-control form-control-color" value="<?php echo htmlspecialchars($currentSettings['welcome_btn_color'] ?? '#002147'); ?>" title="Choose button color">
                                            <span class="small font-monospace" id="color_hex_label"><?php echo htmlspecialchars($currentSettings['welcome_btn_color'] ?? '#002147'); ?></span>
                                        </div>
                                    </div>

                                    <!-- Secondary Button -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small">Secondary Button Text (Optional)</label>
                                        <input type="text" name="welcome_btn_secondary_text" id="input_welcome_btn_secondary_text" class="form-control" placeholder="e.g. Later / Close or Contact Us" value="<?php echo htmlspecialchars($currentSettings['welcome_btn_secondary_text'] ?? 'Close'); ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small">Secondary Button Link</label>
                                        <input type="text" name="welcome_btn_secondary_link" class="form-control" placeholder="#close or page path" value="<?php echo htmlspecialchars($currentSettings['welcome_btn_secondary_link'] ?? '#close'); ?>">
                                        <div class="form-text">Use <code>#close</code> to dismiss popup on click.</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section: Timing & Behavior -->
                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Pop-up Status</label>
                                    <div class="form-check form-switch mt-1">
                                        <input class="form-check-input" type="checkbox" name="welcome_status" id="welcome_status" style="width: 48px; height: 24px;" <?php echo ($currentSettings['welcome_status'] ?? 1) == 1 ? 'checked' : ''; ?>>
                                        <label class="form-check-label fw-medium ms-2" for="welcome_status">Enable Pop-up</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Display Delay</label>
                                    <select name="welcome_delay" class="form-select">
                                        <?php $del = intval($currentSettings['welcome_delay'] ?? 2); ?>
                                        <option value="1" <?php echo $del == 1 ? 'selected' : ''; ?>>1 Second</option>
                                        <option value="2" <?php echo $del == 2 ? 'selected' : ''; ?>>2 Seconds (Recommended)</option>
                                        <option value="3" <?php echo $del == 3 ? 'selected' : ''; ?>>3 Seconds</option>
                                        <option value="5" <?php echo $del == 5 ? 'selected' : ''; ?>>5 Seconds</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Frequency</label>
                                    <select name="welcome_frequency" class="form-select">
                                        <?php $freq = $currentSettings['welcome_frequency'] ?? 'session'; ?>
                                        <option value="session" <?php echo $freq === 'session' ? 'selected' : ''; ?>>Once per Session (Recommended)</option>
                                        <option value="daily" <?php echo $freq === 'daily' ? 'selected' : ''; ?>>Once Every 24 Hours</option>
                                        <option value="always" <?php echo $freq === 'always' ? 'selected' : ''; ?>>Every Page Load (Testing)</option>
                                    </select>
                                </div>
                            </div>

                            <button type="submit" name="update_popup" class="btn btn-primary rounded-pill px-5 shadow-sm fw-bold">
                                <i class="fas fa-save me-2"></i>Save Pop-up Settings
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Preview Column -->
            <div class="col-lg-5">
                <div class="sticky-top" style="top: 20px; z-index: 5;">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="card-header bg-dark text-white border-0 py-3 d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 small text-uppercase fw-bold"><i class="fas fa-eye me-2"></i>Live Popup Preview</h6>
                            <span class="badge bg-primary" id="preview_layout_badge">Top Cover Card</span>
                        </div>
                        <div class="card-body bg-light p-3 p-md-4">
                            <!-- Real-time Interactive Card Preview -->
                            <div id="live_preview_card" class="bg-white rounded-4 shadow-sm overflow-hidden" style="border: 1px solid rgba(0,0,0,0.08); transition: all 0.3s ease;">
                                <!-- Top Cover Image Banner (For card_cover mode - 1280x720 Px 16:9 ratio) -->
                                <div id="preview_top_cover" style="width: 100%; aspect-ratio: 1280 / 720; background-size: cover; background-position: center; position: relative; background-image: url('../images/<?php echo htmlspecialchars($currentSettings['welcome_image'] ?? ''); ?>'); display: block;">
                                    <div id="preview_badge_cover" style="position: absolute; bottom: 12px; left: 15px; background: rgba(255,255,255,0.95); font-size: 10px; font-weight: 800; letter-spacing: 0.5px; text-transform: uppercase; padding: 4px 10px; border-radius: 50px; color: <?php echo htmlspecialchars($currentSettings['welcome_btn_color'] ?? '#002147'); ?>; box-shadow: 0 2px 6px rgba(0,0,0,0.15);">
                                        <?php echo htmlspecialchars($currentSettings['welcome_badge'] ?? 'Special Announcement'); ?>
                                    </div>
                                </div>

                                <!-- Split Container (For card_split mode) -->
                                <div id="preview_split_container" style="display: none; width: 100%;">
                                    <div id="preview_split_image" style="width: 40%; min-height: 220px; background-size: cover; background-position: center; background-image: url('../images/<?php echo htmlspecialchars($currentSettings['welcome_image'] ?? ''); ?>');"></div>
                                    <div style="flex: 1; padding: 20px;">
                                        <div id="preview_badge_split" class="mb-2" style="display: inline-block; background: #e0f2fe; color: #0369a1; font-size: 10px; font-weight: 800; text-transform: uppercase; padding: 3px 10px; border-radius: 50px;">
                                            <?php echo htmlspecialchars($currentSettings['welcome_badge'] ?? 'Special Announcement'); ?>
                                        </div>
                                        <h5 id="preview_title_split" style="font-size: 16px; font-weight: 800; color: #0f172a; margin-bottom: 8px;">
                                            <?php echo htmlspecialchars($currentSettings['welcome_title'] ?? 'Welcome to LKVM'); ?>
                                        </h5>
                                        <p id="preview_desc_split" style="font-size: 12px; color: #64748b; line-height: 1.6; margin-bottom: 15px; white-space: pre-line;">
                                            <?php echo htmlspecialchars($currentSettings['welcome_desc'] ?? ''); ?>
                                        </p>
                                        <div class="d-flex gap-2 align-items-center flex-wrap">
                                            <a href="javascript:void(0)" id="preview_btn_split" class="btn btn-sm text-white rounded-pill px-3" style="font-size: 11px; font-weight: 700; background: <?php echo htmlspecialchars($currentSettings['welcome_btn_color'] ?? '#002147'); ?>;">
                                                <?php echo htmlspecialchars($currentSettings['welcome_btn_text'] ?? 'Explore More'); ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card Body (For card_cover and card_minimal modes) -->
                                <div id="preview_main_body" class="p-4">
                                    <!-- Minimal Header Accent (Only in minimal mode) -->
                                    <div id="preview_minimal_icon" class="text-center mb-3" style="display: none;">
                                        <div class="d-inline-flex align-items-center justify-content-center rounded-3 p-3 text-white" id="preview_icon_bg" style="background: <?php echo htmlspecialchars($currentSettings['welcome_btn_color'] ?? '#002147'); ?>; width: 50px; height: 50px;">
                                            <i class="fas fa-bullhorn fa-lg"></i>
                                        </div>
                                    </div>

                                    <div id="preview_badge_body" class="mb-2" style="display: none;">
                                        <span class="badge bg-light text-dark border px-2 py-1 rounded-pill" style="font-size: 10px;">
                                            <?php echo htmlspecialchars($currentSettings['welcome_badge'] ?? 'Special Announcement'); ?>
                                        </span>
                                    </div>

                                    <h5 id="preview_title" class="fw-bold text-dark mb-2" style="font-size: 18px; line-height: 1.3;">
                                        <?php echo htmlspecialchars($currentSettings['welcome_title'] ?? 'Welcome to LKVM'); ?>
                                    </h5>

                                    <div id="preview_desc" class="text-muted small mb-4" style="line-height: 1.6; white-space: pre-line; max-height: 160px; overflow-y: auto;">
                                        <?php echo htmlspecialchars($currentSettings['welcome_desc'] ?? ''); ?>
                                    </div>

                                    <div class="d-flex gap-2 align-items-center flex-wrap" id="preview_actions_row">
                                        <a href="javascript:void(0)" id="preview_btn_primary" class="btn text-white rounded-pill px-4 fw-bold shadow-sm" style="font-size: 13px; background: <?php echo htmlspecialchars($currentSettings['welcome_btn_color'] ?? '#002147'); ?>;">
                                            <span><?php echo htmlspecialchars($currentSettings['welcome_btn_text'] ?? 'Explore More'); ?></span>
                                            <i class="fas fa-arrow-right fa-xs ms-1"></i>
                                        </a>

                                        <a href="javascript:void(0)" id="preview_btn_secondary" class="btn btn-outline-secondary rounded-pill px-3" style="font-size: 13px; <?php echo empty($currentSettings['welcome_btn_secondary_text']) ? 'display: none;' : ''; ?>">
                                            <?php echo htmlspecialchars($currentSettings['welcome_btn_secondary_text'] ?? 'Close'); ?>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3 text-center small text-muted">
                                <i class="fas fa-info-circle me-1"></i> Interactive preview updates in real-time as you type or change options.
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
        const inputBadge = document.getElementById('input_welcome_badge');
        const inputBtnText = document.getElementById('input_welcome_btn_text');
        const inputBtnSec = document.getElementById('input_welcome_btn_secondary_text');
        const inputColor = document.getElementById('input_welcome_btn_color');
        const colorLabel = document.getElementById('color_hex_label');
        const inputImage = document.getElementById('input_welcome_image');
        const removeImgCheck = document.getElementById('removeImg');
        const layoutRadios = document.querySelectorAll('input[name="welcome_layout"]');
        const layoutCards = document.querySelectorAll('.layout-option-card');

        // Preview Elements
        const previewTopCover = document.getElementById('preview_top_cover');
        const previewSplitContainer = document.getElementById('preview_split_container');
        const previewSplitImg = document.getElementById('preview_split_image');
        const previewMainBody = document.getElementById('preview_main_body');
        const previewMinimalIcon = document.getElementById('preview_minimal_icon');
        const previewIconBg = document.getElementById('preview_icon_bg');
        const previewBadgeCover = document.getElementById('preview_badge_cover');
        const previewBadgeSplit = document.getElementById('preview_badge_split');
        const previewBadgeBody = document.getElementById('preview_badge_body');
        const previewLayoutBadge = document.getElementById('preview_layout_badge');

        const previewTitle = document.getElementById('preview_title');
        const previewDesc = document.getElementById('preview_desc');
        const previewTitleSplit = document.getElementById('preview_title_split');
        const previewDescSplit = document.getElementById('preview_desc_split');

        const previewBtnPrimary = document.getElementById('preview_btn_primary');
        const previewBtnSecondary = document.getElementById('preview_btn_secondary');
        const previewBtnSplit = document.getElementById('preview_btn_split');

        let currentImageSrc = "<?php echo !empty($currentSettings['welcome_image']) ? '../images/' . htmlspecialchars($currentSettings['welcome_image']) : ''; ?>";

        function getActiveLayout() {
            let active = 'card_cover';
            layoutRadios.forEach(r => {
                if (r.checked) active = r.value;
            });
            return active;
        }

        function updatePreview() {
            const layout = getActiveLayout();
            const titleVal = inputTitle.value || 'Welcome to LKVM';
            const descVal = inputDesc.value || '';
            const badgeVal = inputBadge.value || 'Special Announcement';
            const btnVal = inputBtnText.value || 'Explore More';
            const secVal = inputBtnSec.value || '';
            const colorVal = inputColor.value || '#002147';
            const isImgRemoved = removeImgCheck && removeImgCheck.checked;
            const hasImage = currentImageSrc && !isImgRemoved;

            if (colorLabel) colorLabel.innerText = colorVal;

            // Update text everywhere
            previewTitle.innerText = titleVal;
            previewDesc.innerText = descVal;
            previewTitleSplit.innerText = titleVal;
            previewDescSplit.innerText = descVal;

            previewBadgeCover.innerText = badgeVal;
            previewBadgeSplit.innerText = badgeVal;
            previewBadgeCover.style.color = colorVal;

            // Buttons
            previewBtnPrimary.querySelector('span').innerText = btnVal;
            previewBtnPrimary.style.background = colorVal;
            previewBtnSplit.innerText = btnVal;
            previewBtnSplit.style.background = colorVal;
            if (previewIconBg) previewIconBg.style.background = colorVal;

            if (secVal) {
                previewBtnSecondary.innerText = secVal;
                previewBtnSecondary.style.display = 'inline-block';
            } else {
                previewBtnSecondary.style.display = 'none';
            }

            // Layout Switching
            if (layout === 'card_cover') {
                previewLayoutBadge.innerText = 'Top Cover Card';
                previewSplitContainer.style.display = 'none';
                previewMainBody.style.display = 'block';
                previewMinimalIcon.style.display = 'none';

                if (hasImage) {
                    previewTopCover.style.display = 'block';
                    previewTopCover.style.backgroundImage = `url('${currentImageSrc}')`;
                    previewTopCover.style.aspectRatio = '1280 / 720';
                    previewTopCover.style.height = 'auto';
                    previewBadgeCover.style.display = badgeVal ? 'block' : 'none';
                    previewBadgeBody.style.display = 'none';
                } else {
                    previewTopCover.style.display = 'block';
                    previewTopCover.style.aspectRatio = '1280 / 720';
                    previewTopCover.style.height = 'auto';
                    previewTopCover.style.backgroundImage = `linear-gradient(135deg, ${colorVal}, #0284c7)`;
                    previewBadgeCover.style.display = badgeVal ? 'block' : 'none';
                    previewBadgeBody.style.display = 'none';
                }
            } else if (layout === 'card_split') {
                previewLayoutBadge.innerText = 'Split Side Card';
                previewTopCover.style.display = 'none';
                previewMainBody.style.display = 'none';
                previewSplitContainer.style.display = 'flex';

                if (hasImage) {
                    previewSplitImg.style.display = 'block';
                    previewSplitImg.style.backgroundImage = `url('${currentImageSrc}')`;
                } else {
                    previewSplitImg.style.display = 'none';
                }
            } else {
                // Minimal
                previewLayoutBadge.innerText = 'Minimal Card';
                previewTopCover.style.display = 'none';
                previewSplitContainer.style.display = 'none';
                previewMainBody.style.display = 'block';
                previewMinimalIcon.style.display = 'block';
                previewBadgeBody.style.display = badgeVal ? 'block' : 'none';
                previewBadgeBody.querySelector('span').innerText = badgeVal;
            }
        }

        // Event listeners
        inputTitle.addEventListener('input', updatePreview);
        inputDesc.addEventListener('input', updatePreview);
        inputBadge.addEventListener('input', updatePreview);
        inputBtnText.addEventListener('input', updatePreview);
        inputBtnSec.addEventListener('input', updatePreview);
        inputColor.addEventListener('input', updatePreview);

        layoutCards.forEach(card => {
            card.addEventListener('click', function() {
                layoutCards.forEach(c => c.classList.remove('border-primary', 'bg-primary', 'bg-opacity-10'));
                this.classList.add('border-primary', 'bg-primary', 'bg-opacity-10');
                const radio = this.querySelector('input[type="radio"]');
                if (radio) {
                    radio.checked = true;
                    updatePreview();
                }
            });
        });

        if (removeImgCheck) {
            removeImgCheck.addEventListener('change', updatePreview);
        }

        inputImage.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    currentImageSrc = e.target.result;
                    if (removeImgCheck) removeImgCheck.checked = false;
                    updatePreview();
                };
                reader.readAsDataURL(file);
            }
        });

        updatePreview();
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

<?php
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_settings'])) {
    $site_url = $_POST['site_url'];
    $site_name = $_POST['site_name'];
    $site_short_name = $_POST['site_short_name'];
    $site_admin_title = $_POST['site_admin_title'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $mobile = $_POST['mobile'];
    $mobile1 = $_POST['mobile1'];

    // Update settings in database
    $stmt = $con->prepare("UPDATE global_setting SET 
        site_url = ?,
        site_name = ?,
        site_short_name = ?,
        site_admin_title = ?,
        email = ?,
        phone = ?,
        address = ?,
        mobile = ?,
        mobile1 = ?
        WHERE id = 1");
    
    $stmt->bind_param("sssssssss", 
        $site_url,
        $site_name,
        $site_short_name,
        $site_admin_title,
        $email,
        $phone,
        $address,
        $mobile,
        $mobile1
    );

    if ($stmt->execute()) {
        $success_msg = "Settings updated successfully!";
    } else {
        $error_msg = "Error updating settings: " . $con->error;
    }
}

// Get current settings
$settings = $con->query("SELECT * FROM global_setting WHERE id = 1")->fetch_assoc();
?>

<!-- Success/Error Messages -->
<?php if (isset($success_msg)): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <?php echo $success_msg; ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<?php if (isset($error_msg)): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?php echo $error_msg; ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<!-- Settings Form -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Website Settings</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Site URL</label>
                        <input type="url" name="site_url" class="form-control" required 
                               value="<?php echo htmlspecialchars($settings['site_url']); ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Site Name</label>
                        <input type="text" name="site_name" class="form-control" required 
                               value="<?php echo htmlspecialchars($settings['site_name']); ?>">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Site Short Name</label>
                        <input type="text" name="site_short_name" class="form-control" 
                               value="<?php echo htmlspecialchars($settings['site_short_name']); ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Admin Panel Title</label>
                        <input type="text" name="site_admin_title" class="form-control" required 
                               value="<?php echo htmlspecialchars($settings['site_admin_title']); ?>">
                    </div>
                </div>

                <!-- Social Media Links -->
                <div class="col-12">
                    <h6 class="mb-3 mt-4">Social Media Links</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Facebook URL</label>
                                <input type="url" name="facebook" class="form-control" value="<?php echo htmlspecialchars($settings['facebook'] ?? ''); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Twitter URL</label>
                                <input type="url" name="twitter" class="form-control" value="<?php echo htmlspecialchars($settings['twitter'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Instagram URL</label>
                                <input type="url" name="instagram" class="form-control" value="<?php echo htmlspecialchars($settings['instagram'] ?? ''); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">YouTube URL</label>
                                <input type="url" name="youtube" class="form-control" value="<?php echo htmlspecialchars($settings['youtube'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <button type="submit" name="save_settings" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Settings
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Change Password Section -->
<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">Change Admin Password</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="" class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Current Password</label>
                    <input type="password" name="current_password" class="form-control" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <input type="password" name="new_password" class="form-control" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" name="confirm_password" class="form-control" required>
                </div>
            </div>
            <div class="col-12">
                <button type="submit" name="change_password" class="btn btn-warning">
                    <i class="fas fa-key"></i> Change Password
                </button>
            </div>
        </form>
    </div>
</div> 
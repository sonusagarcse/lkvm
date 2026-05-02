<?php
// admin/settings.php
require_once '../connection.php';
include 'includes/header.php';

$message = '';
$error = '';


// Fetch current settings
$setQ = mysqli_query($con, "SELECT * FROM global_setting WHERE id = 1");
$currentSettings = mysqli_fetch_assoc($setQ);

if (!$currentSettings) {
    // Attempt to insert if empty (rare)
    mysqli_query($con, "INSERT INTO global_setting (id) VALUES (1)");
    $currentSettings = [];
}

// Handle Razorpay Settings Update
$rzp_msg = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_razorpay'])) {
    $rzp_key_id     = mysqli_real_escape_string($con, $_POST['razorpay_key_id'] ?? '');
    $rzp_key_secret = mysqli_real_escape_string($con, $_POST['razorpay_key_secret'] ?? '');
    $rzp_mode       = in_array($_POST['razorpay_mode'] ?? 'test', ['test', 'live']) ? $_POST['razorpay_mode'] : 'test';

    $sql = "UPDATE global_setting SET razorpay_key_id='$rzp_key_id', razorpay_key_secret='$rzp_key_secret', razorpay_mode='$rzp_mode' WHERE id=1";
    if (mysqli_query($con, $sql)) {
        if (function_exists('cache_delete')) cache_delete('global_settings');
        $rzp_msg = 'success: Razorpay settings saved successfully!';
        $setQ = mysqli_query($con, "SELECT * FROM global_setting WHERE id = 1");
        $currentSettings = mysqli_fetch_assoc($setQ);
    } else {
        $rzp_msg = 'error: ' . mysqli_error($con);
    }
}

// Handle SMTP Settings Update
$smtp_msg = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_smtp'])) {
    $smtp_host = mysqli_real_escape_string($con, $_POST['smtp_host'] ?? '');
    $smtp_port = (int)($_POST['smtp_port'] ?? 587);
    $smtp_user = mysqli_real_escape_string($con, $_POST['smtp_user'] ?? '');
    $smtp_pass = mysqli_real_escape_string($con, $_POST['smtp_pass'] ?? '');
    $smtp_from_name = mysqli_real_escape_string($con, $_POST['smtp_from_name'] ?? '');
    $smtp_secure = mysqli_real_escape_string($con, $_POST['smtp_secure'] ?? 'tls');

    $sql = "UPDATE global_setting SET 
            smtp_host='$smtp_host', 
            smtp_port=$smtp_port, 
            smtp_user='$smtp_user', 
            smtp_pass='$smtp_pass', 
            smtp_from_name='$smtp_from_name', 
            smtp_secure='$smtp_secure' 
            WHERE id=1";
            
    if (mysqli_query($con, $sql)) {
        if (function_exists('cache_delete')) cache_delete('global_settings');
        $smtp_msg = 'success: SMTP settings saved successfully!';
        $setQ = mysqli_query($con, "SELECT * FROM global_setting WHERE id = 1");
        $currentSettings = mysqli_fetch_assoc($setQ);
    } else {
        $smtp_msg = 'error: ' . mysqli_error($con);
    }
}

// Process POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_settings'])) {
    // Dynamic Update Construction
    $updates = [];
    foreach ($_POST as $key => $value) {
        if ($key != 'update_settings' && array_key_exists($key, $currentSettings)) { // Only update if column exists in table
            $val = mysqli_real_escape_string($con, $value);
            $updates[] = "`$key` = '$val'";
        }
    }

    if (!empty($updates)) {
        $updateSQL = "UPDATE global_setting SET " . implode(', ', $updates) . " WHERE id = 1";
        if (mysqli_query($con, $updateSQL)) {
            $message = "Settings updated successfully!";
            // Cache clearing would be good here:
            if (function_exists('cache_delete')) {
                cache_delete('global_settings');
            }
            // Refresh
            $setQ = mysqli_query($con, "SELECT * FROM global_setting WHERE id = 1");
            $currentSettings = mysqli_fetch_assoc($setQ);
        } else {
            $error = "Update Failed: " . mysqli_error($con);
        }
    }
}
?>

<div class="row mb-4">
    <div class="col-12">
        <h2 class="h3 mb-0 text-gray-800">Global Settings</h2>
    </div>
</div>

<?php if ($message)
    echo '<div class="alert alert-success">' . $message . '</div>'; ?>
<?php if ($error)
    echo '<div class="alert alert-danger">' . $error . '</div>'; ?>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white">
        <h5 class="m-0 fw-bold text-primary">Website Configuration</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="">
            <div class="row">
                <?php
                // Explicitly define fields to ensure order and better UX
                $fields = [
                    'site_name' => 'Site Name',
                    'site_title' => 'Site Title',
                    'email' => 'Email Address',
                    'mobile' => 'Mobile Number',
                    'address' => 'Address',
                    'facebook_url' => 'Facebook URL',
                    'twitter_url' => 'Twitter URL',
                    'instagram_url' => 'Instagram URL',
                    'youtube_url' => 'YouTube URL',
                    'linkedin_url' => 'LinkedIn URL',
                    'description' => 'Footer Description',
                    'project_badge' => 'Project Section Badge',
                    'project_title' => 'Project Section Title',
                    'project_subtitle' => 'Project Section Subtitle',
                    'map' => 'Google Map Embed Code',
                    'ticker_speed' => 'Ticker Scroll Speed (Seconds - Higher is Slower)'
                ];

                foreach ($fields as $key => $label) {
                    // Skip if field doesn't exist in DB (just in case)
                    if (!array_key_exists($key, $currentSettings) && !in_array($key, ['facebook_url', 'twitter_url', 'instagram_url', 'youtube_url', 'linkedin_url', 'description'])) {
                        continue;
                    }

                    // Value
                    $val = isset($currentSettings[$key]) ? $currentSettings[$key] : '';

                    echo '<div class="col-md-6 mb-3">';
                    echo '<label class="form-label fw-bold">' . $label . '</label>';

                    if (in_array($key, ['address', 'description', 'map', 'project_subtitle'])) {
                        echo '<textarea name="' . $key . '" class="form-control" rows="3">' . htmlspecialchars($val) . '</textarea>';
                    } else {
                        echo '<input type="text" name="' . $key . '" class="form-control" value="' . htmlspecialchars($val) . '">';
                    }
                    echo '</div>';
                }
                ?>
            </div>
            <hr>
            <button type="submit" name="update_settings" class="btn btn-primary"><i class="fas fa-save"></i> Save
                Settings</button>
        </form>
    </div>
</div>

<!-- Razorpay Gateway Settings -->
<div class="card border-0 shadow-sm rounded-4 mt-4">
    <div class="card-header bg-white d-flex align-items-center gap-2">
        <i class="fas fa-credit-card text-danger"></i>
        <h5 class="m-0 fw-bold" style="color:#012751;">Razorpay Payment Gateway</h5>
    </div>
    <div class="card-body">
        <?php if (!empty($rzp_msg)): ?>
            <?php $rzp_type = strpos($rzp_msg,'success:') === 0 ? 'success' : 'danger'; ?>
            <div class="alert alert-<?php echo $rzp_type; ?>">
                <?php echo htmlspecialchars(substr($rzp_msg, strpos($rzp_msg,':')+2)); ?>
            </div>
        <?php endif; ?>
        <p class="text-muted small mb-3">
            <i class="fas fa-info-circle"></i>
            Get API Keys from your <a href="https://dashboard.razorpay.com/app/keys" target="_blank">Razorpay Dashboard</a>.
            Use <strong>Test</strong> mode during development.
        </p>
        <form method="POST" action="">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Key ID <span class="text-danger">*</span></label>
                    <input type="text" name="razorpay_key_id" class="form-control"
                           value="<?php echo htmlspecialchars($currentSettings['razorpay_key_id'] ?? ''); ?>"
                           placeholder="rzp_test_xxxx or rzp_live_xxxx">
                    <div class="form-text">Starts with <code>rzp_test_</code> (test) or <code>rzp_live_</code> (live)</div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Key Secret <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="password" name="razorpay_key_secret" id="rzp_secret_field" class="form-control"
                               value="<?php echo htmlspecialchars($currentSettings['razorpay_key_secret'] ?? ''); ?>"
                               placeholder="Your Razorpay Key Secret">
                        <button class="btn btn-outline-secondary" type="button"
                                onclick="var f=document.getElementById('rzp_secret_field');f.type=(f.type==='password'?'text':'password')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Mode</label>
                    <select name="razorpay_mode" class="form-select">
                        <option value="test" <?php echo ($currentSettings['razorpay_mode'] ?? 'test') === 'test' ? 'selected' : ''; ?>>🧪 Test Mode</option>
                        <option value="live" <?php echo ($currentSettings['razorpay_mode'] ?? '') === 'live'  ? 'selected' : ''; ?>>🚀 Live Mode</option>
                    </select>
                </div>
            </div>
            <hr>
            <button type="submit" name="update_razorpay" class="btn btn-success">
                <i class="fas fa-save"></i> Save Razorpay Settings
            </button>
            <?php if (!empty($currentSettings['razorpay_key_id'])): ?>
                <span class="badge bg-success ms-2"><i class="fas fa-check-circle"></i> Gateway Configured</span>
            <?php else: ?>
                <span class="badge bg-warning text-dark ms-2"><i class="fas fa-exclamation-circle"></i> Not Configured</span>
            <?php endif; ?>
        </form>
    </div>
</div>

<!-- SMTP Email Settings -->
<div class="card border-0 shadow-sm rounded-4 mt-4">
    <div class="card-header bg-white d-flex align-items-center gap-2">
        <i class="fas fa-envelope text-info"></i>
        <h5 class="m-0 fw-bold" style="color:#012751;">SMTP Email Configuration</h5>
    </div>
    <div class="card-body">
        <?php if (!empty($smtp_msg)): ?>
            <?php $smtp_type = strpos($smtp_msg,'success:') === 0 ? 'success' : 'danger'; ?>
            <div class="alert alert-<?php echo $smtp_type; ?>">
                <?php echo htmlspecialchars(substr($smtp_msg, strpos($smtp_msg,':')+2)); ?>
            </div>
        <?php endif; ?>
        <p class="text-muted small mb-4">
            <i class="fas fa-info-circle"></i> Use these settings to connect your website to an email server for sending donation receipts and notifications.
        </p>
        <form method="POST" action="">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">SMTP Host</label>
                    <input type="text" name="smtp_host" class="form-control" 
                           value="<?php echo htmlspecialchars($currentSettings['smtp_host'] ?? ''); ?>" placeholder="e.g., smtp.gmail.com">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">SMTP Port</label>
                    <input type="number" name="smtp_port" class="form-control" 
                           value="<?php echo htmlspecialchars($currentSettings['smtp_port'] ?? '587'); ?>" placeholder="587">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Encryption</label>
                    <select name="smtp_secure" class="form-select">
                        <option value="tls" <?php echo ($currentSettings['smtp_secure'] ?? 'tls') === 'tls' ? 'selected' : ''; ?>>TLS</option>
                        <option value="ssl" <?php echo ($currentSettings['smtp_secure'] ?? '') === 'ssl' ? 'selected' : ''; ?>>SSL</option>
                        <option value="" <?php echo ($currentSettings['smtp_secure'] ?? '') === '' ? 'selected' : ''; ?>>None</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">SMTP Username / Email</label>
                    <input type="text" name="smtp_user" class="form-control" 
                           value="<?php echo htmlspecialchars($currentSettings['smtp_user'] ?? ''); ?>" placeholder="Your email address">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">SMTP Password</label>
                    <div class="input-group">
                        <input type="password" name="smtp_pass" id="smtp_pass_field" class="form-control" 
                               value="<?php echo htmlspecialchars($currentSettings['smtp_pass'] ?? ''); ?>" placeholder="Your app password">
                        <button class="btn btn-outline-secondary" type="button" 
                                onclick="var f=document.getElementById('smtp_pass_field');f.type=(f.type==='password'?'text':'password')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">From Name</label>
                    <input type="text" name="smtp_from_name" class="form-control" 
                           value="<?php echo htmlspecialchars($currentSettings['smtp_from_name'] ?? ($currentSettings['site_name'] ?? '')); ?>" placeholder="Display Name">
                </div>
            </div>
            <hr>
            <button type="submit" name="update_smtp" class="btn btn-info text-white">
                <i class="fas fa-save"></i> Save SMTP Settings
            </button>
            <?php if (!empty($currentSettings['smtp_user'])): ?>
                <span class="badge bg-success ms-2"><i class="fas fa-check-circle"></i> SMTP Connected</span>
            <?php else: ?>
                <span class="badge bg-warning text-dark ms-2"><i class="fas fa-exclamation-circle"></i> Service Inactive</span>
            <?php endif; ?>
        </form>
    </div>
</div>

<!-- SEO & Social Media Settings -->
<div class="card border-0 shadow-sm rounded-4 mt-4">
    <div class="card-header bg-white d-flex align-items-center gap-2">
        <i class="fas fa-search text-success"></i>
        <h5 class="m-0 fw-bold" style="color:#012751;">SEO & Social Media Optimization</h5>
    </div>
    <div class="card-body">
        <p class="text-muted small mb-4">
            <i class="fas fa-info-circle"></i> Configure how your website appears on Google, Facebook, and WhatsApp.
        </p>
        <form method="POST" action="">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold">Meta Keywords</label>
                    <textarea name="meta_keywords" class="form-control" rows="2" placeholder="computer classes, KYP, coding, etc."><?php echo htmlspecialchars($currentSettings['meta_keywords'] ?? ''); ?></textarea>
                    <div class="form-text">Separate keywords with commas.</div>
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold">Meta Description</label>
                    <textarea name="meta_description" class="form-control" rows="3" placeholder="Describe your foundation and services..."><?php echo htmlspecialchars($currentSettings['meta_description'] ?? ''); ?></textarea>
                    <div class="form-text">Maintain under 160 characters for best Google results.</div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Favicon URL</label>
                    <input type="text" name="favicon" class="form-control" value="<?php echo htmlspecialchars($currentSettings['favicon'] ?? ''); ?>" placeholder="URL to your .ico or .png icon">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Social Sharing Image (OG Image)</label>
                    <input type="text" name="og_image" class="form-control" value="<?php echo htmlspecialchars($currentSettings['og_image'] ?? ''); ?>" placeholder="URL to image shown when sharing on WhatsApp/FB">
                </div>
            </div>
            <hr>
            <button type="submit" name="update_settings" class="btn btn-success">
                <i class="fas fa-save"></i> Save SEO Settings
            </button>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
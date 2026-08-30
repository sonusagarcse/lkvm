<?php
// coordinator/settings.php
require_once '../connection.php';
include 'includes/header.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_password'])) {
    if (!verify_csrf_token()) {
        die("CSRF validation failed.");
    }
    
    $current_pass = $_POST['current_password'] ?? '';
    $new_pass = $_POST['new_password'] ?? '';
    $confirm_pass = $_POST['confirm_password'] ?? '';
    $coord_id = $_SESSION['coord_id'];

    // Verify current password with prepared statement
    $stmt = $con->prepare("SELECT pass FROM admin_login WHERE id = ? LIMIT 1");
    if ($stmt) {
        $stmt->bind_param("i", $coord_id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            $is_valid = false;
            // Verify against password_hash or legacy plaintext password
            if (password_verify($current_pass, $row['pass'])) {
                $is_valid = true;
            } elseif ($current_pass === $row['pass']) {
                $is_valid = true;
            }

            if (!$is_valid) {
                $error = "Current password is incorrect.";
            } else if ($new_pass !== $confirm_pass) {
                $error = "New password and confirmation do not match.";
            } else if (strlen($new_pass) < 6) {
                $error = "Password must be at least 6 characters long.";
            } else {
                $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);
                $update_stmt = $con->prepare("UPDATE admin_login SET pass = ? WHERE id = ?");
                if ($update_stmt) {
                    $update_stmt->bind_param("si", $hashed_pass, $coord_id);
                    if ($update_stmt->execute()) {
                        $message = "Password updated successfully!";
                        security_log('COORD_PASSWORD_CHANGED', "Coordinator User ID: $coord_id successfully changed password");
                    } else {
                        $error = "Error updating password.";
                    }
                    $update_stmt->close();
                }
            }
        } else {
            $error = "User not found.";
        }
        $stmt->close();
    } else {
        $error = "Database Error.";
    }
}
?>

<div class="row mb-4">
    <div class="col-12">
        <h2 class="h3 mb-0 text-gray-800">Account Settings</h2>
        <p class="text-muted">Manage your security and profile details.</p>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 font-weight-bold text-primary">Change Password</h6>
            </div>
            <div class="card-body">
                <?php if ($message): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i> <?php echo $message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i> <?php echo $error; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Current Password</label>
                        <input type="password" name="current_password" class="form-control" required placeholder="Enter current password">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">New Password</label>
                        <input type="password" name="new_password" class="form-control" required placeholder="Enter new password">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Confirm New Password</label>
                        <input type="password" name="confirm_password" class="form-control" required placeholder="Repeat new password">
                    </div>
                    <hr>
                    <button type="submit" name="update_password" class="btn btn-primary">
                        <i class="fas fa-key me-2"></i> Update Password
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white py-3">
                <h6 class="m-0 font-weight-bold text-primary">Profile Information</h6>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['admin_name']); ?>&background=4361ee&color=fff&size=100&bold=true" class="rounded-4 shadow-sm me-4" alt="Profile">
                    <div>
                        <h4 class="mb-1"><?php echo htmlspecialchars($_SESSION['admin_name']); ?></h4>
                        <span class="badge bg-primary">Coordinator</span>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="text-muted small d-block">Account Level</label>
                    <span class="fw-bold">Level 2 (Coordinator)</span>
                </div>
                
                <div class="alert alert-info py-2 small border-0 bg-info bg-opacity-10 text-info">
                    <i class="fas fa-info-circle me-2"></i>
                    You have access to the Employee Payroll & Attendance system. For higher privileges, contact the Super Admin.
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<!-- admin/includes/header.php -->
<?php require_once 'auth_check.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - LKVM</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" type="image/png" href="../images/favicon.png">
    <!-- jQuery (Useful via CDN) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main-content" id="main-content">
        <!-- Topbar -->
        <header class="topbar" id="topbar">
            <div class="d-flex align-items-center">
                <div class="toggle-sidebar me-3" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </div>
                <h5 class="m-0 h5 fw-bold text-dark d-none d-sm-block">Coordinator Dashboard</h5>
            </div>

            <div class="dropdown">
                <a class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle profile-toggle" href="#"
                    role="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 5px; border-radius: 12px; transition: var(--transition);">
                    <div class="me-3 text-end d-none d-md-block">
                        <span class="d-block fw-bold" style="font-size: 0.9rem; line-height: 1.2;"><?php echo isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : 'Coordinator'; ?></span>
                        <span class="d-block text-primary fw-medium" style="font-size: 0.75rem;">Coordinator</span>
                    </div>
                    <div class="profile-img-wrapper" style="position: relative;">
                        <img src="https://ui-avatars.com/api/?name=<?php echo urlencode(isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : 'Coordinator'); ?>&background=4361ee&color=fff&bold=true"
                            alt="Profile" class="rounded-circle border border-2 border-white shadow-sm" width="42" height="42">
                        <span class="status-indicator" style="position: absolute; bottom: 2px; right: 2px; width: 12px; height: 12px; background: var(--success-color); border: 2px solid #fff; border-radius: 50%;"></span>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-2 mt-2" aria-labelledby="userDropdown" style="border-radius: 15px; min-width: 200px;">
                    <li><h6 class="dropdown-header text-xs text-uppercase fw-bold text-muted pb-2">Coordinator Menu</h6></li>
                    <li><a class="dropdown-item rounded-3 py-2" href="settings.php"><i class="fas fa-user-cog me-2 text-primary"></i> Profile Settings</a></li>
                    <li><a class="dropdown-item rounded-3 py-2" href="settings.php"><i class="fas fa-shield-alt me-2 text-primary"></i> Change Password</a></li>
                    <li><hr class="dropdown-divider opacity-50"></li>
                    <li><a class="dropdown-item text-danger rounded-3 py-2" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout System</a></li>
                </ul>
            </div>
        </header>

        <!-- Content Body (Starts here) -->
        <div class="container-fluid p-3 p-md-4">
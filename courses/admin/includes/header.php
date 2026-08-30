<?php
require_once dirname(__DIR__) . '/../../connection.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Enable Admin Auth Check (type 1 is Super Admin)
if (!isset($_SESSION['admin_id']) || $_SESSION['admin_type'] != 1) {
    header("Location: ../../admin/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Admin Dashboard'; ?> - Academy LMS</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --primary: #6366f1; --dark: #0f172a; --bg: #f8fafc; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: var(--bg); min-height: 100vh; overflow-x: hidden; }
        
        .sidebar { width: 280px; background: #fff; border-right: 1px solid #e2e8f0; height: 100vh; position: fixed; top: 0; left: 0; z-index: 1000; transition: 0.3s; }
        .main-content { margin-left: 280px; padding: 30px; transition: 0.3s; }
        
        .nav-link { padding: 12px 20px; border-radius: 12px; margin: 4px 15px; color: #64748b; font-weight: 600; display: flex; align-items: center; gap: 12px; transition: 0.2s; text-decoration: none; }
        .nav-link:hover { background: #f8fafc; color: var(--primary); }
        .nav-link.active { background: #eef2ff; color: var(--primary); }
        .nav-link i { width: 20px; font-size: 1.1rem; }
        
    .admin-navbar { background: #fff; border-bottom: 1px solid #e2e8f0; padding: 15px 20px; position: sticky; top: 0; z-index: 999; margin: -30px -30px 30px -30px; }
    
    @media (max-width: 992px) {
        .admin-navbar { margin: -20px -20px 20px -20px; padding: 15px; }
        .sidebar { left: -100%; }
        .sidebar.active { left: 0; box-shadow: 20px 0 50px rgba(0,0,0,0.1); }
        .main-content { margin-left: 0; padding: 20px; }
    }
</style>
</head>
<body>

<div class="sidebar" id="adminSidebar">
    <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
        <a href="index.php" class="fw-bold fs-4 text-decoration-none text-dark">
            <span class="text-primary">LKVM</span> Admin
        </a>
        <button class="btn d-lg-none p-0 border-0" id="closeSidebar"><i class="fa fa-times fs-4 text-muted"></i></button>
    </div>
    
    <nav class="mt-4">
        <div class="px-4 mb-2 text-uppercase small fw-bold text-muted" style="letter-spacing: 1px;">General</div>
        <a href="index.php" class="nav-link <?php echo $page_title == 'Admin Dashboard' ? 'active' : ''; ?>"><i class="fa fa-th-large"></i> Dashboard</a>
        
        <div class="px-4 mt-4 mb-2 text-uppercase small fw-bold text-muted" style="letter-spacing: 1px;">Content</div>
        <a href="categories.php" class="nav-link <?php echo $page_title == 'Manage Categories' ? 'active' : ''; ?>"><i class="fa fa-folder"></i> Categories</a>
        <a href="courses.php" class="nav-link <?php echo $page_title == 'Manage Courses' ? 'active' : ''; ?>"><i class="fa fa-graduation-cap"></i> Courses</a>
        <a href="lessons.php" class="nav-link <?php echo $page_title == 'Manage Lessons' ? 'active' : ''; ?>"><i class="fa fa-play-circle"></i> Lessons</a>
        
        <div class="px-4 mt-4 mb-2 text-uppercase small fw-bold text-muted" style="letter-spacing: 1px;">Users & Sales</div>
        <a href="students.php" class="nav-link <?php echo $page_title == 'Manage Students' ? 'active' : ''; ?>"><i class="fa fa-users"></i> Students</a>
        <a href="enrollments.php" class="nav-link <?php echo $page_title == 'Manage Enrollments' ? 'active' : ''; ?>"><i class="fa fa-shopping-cart"></i> Enrollments</a>
        
        <div class="mt-auto pt-5">
            <a href="../../index.php" class="nav-link text-muted"><i class="fa fa-globe"></i> View Website</a>
            <a href="../../logout.php" class="nav-link text-danger"><i class="fa fa-sign-out-alt"></i> Logout</a>
        </div>
    </nav>
</div>

<div class="main-content">
    <div class="admin-navbar d-flex justify-content-between align-items-center d-lg-none shadow-sm">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-white border shadow-sm rounded-3" id="openSidebar">
                <i class="fa fa-bars text-primary"></i>
            </button>
            <span class="fw-bold h6 mb-0">Dashboard</span>
        </div>
        <img src="https://ui-avatars.com/api/?name=Admin&background=6366f1&color=fff" class="rounded-circle" style="width: 35px;">
    </div>
    
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h3 class="fw-800 mb-0"><?php echo $page_title; ?></h3>
        <div class="admin-profile d-none d-md-flex align-items-center gap-3 bg-white p-2 pe-4 rounded-pill border shadow-sm">
            <img src="https://ui-avatars.com/api/?name=Admin&background=6366f1&color=fff" class="rounded-circle" style="width: 40px;">
            <div>
                <div class="fw-bold small">Administrator</div>
                <div class="text-success small fw-semibold"><i class="fa fa-circle fs-xs me-1"></i> Online</div>
            </div>
        </div>
    </div>

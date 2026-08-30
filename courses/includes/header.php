<?php
require_once dirname(dirname(__DIR__)) . '/connection.php';
@session_start();

$is_logged_in = isset($_SESSION['student_id']);
$student_name = $_SESSION['student_name'] ?? 'Student';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Academy'; ?> - LKVM Learning</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg lms-navbar sticky-top bg-white shadow-sm">
    <div class="container py-2">
        <a class="navbar-brand fw-800 fs-4" href="index.php">
            <span class="text-primary">LKVM</span> Academy
        </a>
        
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#lmsNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="lmsNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link px-3 fw-semibold" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link px-3 fw-semibold" href="categories.php">Categories</a></li>
                <li class="nav-item"><a class="nav-link px-3 fw-semibold" href="dashboard.php">My Learning</a></li>
            </ul>
            
            <div class="d-flex align-items-center gap-2">
                <?php if($is_logged_in): ?>
                    <div class="dropdown">
                        <button class="btn btn-light rounded-pill px-4 fw-bold dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($student_name); ?>&background=6366f1&color=fff" class="rounded-circle" style="width: 25px;">
                            <?php echo htmlspecialchars($student_name); ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 mt-3 p-2">
                            <li><a class="dropdown-item rounded-3 py-2" href="dashboard.php"><i class="fa fa-th-large me-2"></i> Dashboard</a></li>
                            <li><a class="dropdown-item rounded-3 py-2" href="#"><i class="fa fa-user me-2"></i> Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item rounded-3 py-2 text-danger" href="logout.php"><i class="fa fa-sign-out-alt me-2"></i> Logout</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="btn btn-link text-decoration-none text-dark fw-bold">Login</a>
                    <a href="signup.php" class="btn btn-primary rounded-pill px-4 fw-bold">Sign Up</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

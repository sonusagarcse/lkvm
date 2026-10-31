<?php
// admin/includes/auth_check.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id']) || ($_SESSION['admin_type'] ?? null) != 1) {
    header("Location: login");
    exit();
}
?>
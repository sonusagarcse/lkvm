<?php
// admin/includes/auth_check.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id']) || $_SESSION['admin_type'] != 2) {
    header("Location: login.php");
    exit();
}
?>
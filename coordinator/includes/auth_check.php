<?php
// admin/includes/auth_check.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['coord_id']) || $_SESSION['coord_type'] != 2) {
    header("Location: login.php");
    exit();
}
?>
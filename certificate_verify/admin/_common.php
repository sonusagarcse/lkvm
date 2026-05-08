<?php
require_once __DIR__ . '/../config.php';
if (isset($_GET['logout'])) { unset($_SESSION['admin_id']); header('Location: ../index.php'); exit; }
?>

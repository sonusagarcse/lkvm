<?php
session_start();
unset($_SESSION['coord_id']);
unset($_SESSION['coord_name']);
unset($_SESSION['coord_type']);
header("Location: login.php");
exit();
?>
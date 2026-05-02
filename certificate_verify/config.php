<?php
error_reporting(0);

// Database credentials
$dbHost = "localhost";
$dbUser = "ouvcxwtd_lkvm";
$dbPass = "ouvcxwtd_lkvm";
$dbName = "ouvcxwtd_lkvm";

// Use persistent connection for better performance
$con = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($con, "utf8");

// Error reporting (comment these lines in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
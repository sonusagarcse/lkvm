<?php
error_reporting(0);

// Load environment variables from .env file in the parent directory
$envPath = dirname(__DIR__) . '/.env';
if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0)
            continue;
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);
            if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
                putenv(sprintf('%s=%s', $name, $value));
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }
}

// Database credentials
$dbHost = getenv('DB_HOST') !== false ? getenv('DB_HOST') : "localhost";
$dbUser = getenv('DB_USER') !== false ? getenv('DB_USER') : "root";
$dbPass = getenv('DB_PASS') !== false ? getenv('DB_PASS') : "";
$dbName = getenv('DB_NAME') !== false ? getenv('DB_NAME') : "lkvm";

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
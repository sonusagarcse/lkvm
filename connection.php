<?php
// Include security core first
require_once __DIR__ . '/includes/security.php';

// Emit secure headers
emit_security_headers();

// Include cache helper
require_once __DIR__ . '/cache_helper.php';

// Load environment variables from .env file
if (file_exists(__DIR__ . '/.env')) {
    $lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
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

// Connect with error suppression for users, log details internally
try {
    $con = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName);
} catch (mysqli_sql_exception $e) {
    if (function_exists('security_log')) {
        security_log('DATABASE_ERROR', $e->getMessage());
    }
    die("<div style='font-family:sans-serif; text-align:center; padding:50px;'>
            <h2>Database Connection Error</h2>
            <p>Could not connect to the database server. Please try again later.</p>
         </div>");
}

if (!$con) {
    if (function_exists('security_log')) {
        security_log('DATABASE_ERROR', mysqli_connect_error());
    }
    die("Connection failed. Please try again later.");
}

mysqli_set_charset($con, "utf8");

// Fetch Global Settings (Cached)
$settings = cache_remember('global_settings', function () use ($con) {
    if ($con) {
        $q = mysqli_query($con, "SELECT * FROM global_setting WHERE id = 1");
        return $q ? mysqli_fetch_assoc($q) : [];
    }
    return [];
}, 3600);

// Map Settings to Global Variables used in templates
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
$host = $_SERVER['HTTP_HOST'];
// Calculate relative path from Document Root to this file's directory
$script_path = str_replace('\\', '/', __DIR__);
$doc_root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
$base_path = str_replace($doc_root, '', $script_path);

// Ensure no double slashes if base_path is empty or just /
$base_path = trim($base_path, '/');
$SITE_URL = $protocol . "://" . $host . ($base_path ? "/" . $base_path : "");

$SITE_NAME = $settings['site_name'] ?? 'LKVM';
$SITE_TITLE = $settings['site_name'] ?? 'LKVM';
$CONTACT_EMAIL = $settings['email'] ?? '';
$CONTACT_MOBILE = $settings['mobile'] ?? '';
$CONTACT_MOBILE1 = $settings['mobile1'] ?? '';
$ADDRESS = $settings['address'] ?? '';
$RAZORPAY_KEY_ID = $settings['razorpay_key_id'] ?? '';
$RAZORPAY_KEY_SECRET = $settings['razorpay_key_secret'] ?? '';

// Production Configuration - suppress screen error display
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
?>
<?php
error_reporting(0);

// Include cache helper
require_once __DIR__ . '/cache_helper.php';

// Use persistent connection for better performance
$con = mysqli_connect("localhost", "root", "", "lkvm");

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($con, "utf8");

// Fetch Global Settings (Cached)
// Fetch Global Settings (Cached)
$settings = cache_remember('global_settings', function () use ($con) {
    if ($con) {
        $q = mysqli_query($con, "SELECT * FROM global_setting WHERE id = 1");
        return $q ? mysqli_fetch_assoc($q) : [];
    }
    return [];
}, 3600);

// Map Settings to Global Variables used in templates
// Map Settings to Global Variables used in templates
// Dynamic SITE_URL Detection
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
$host = $_SERVER['HTTP_HOST'];
// Calculate relative path from Document Root to this file's directory
$script_path = str_replace('\\', '/', __DIR__);
$doc_root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
$base_path = str_replace($doc_root, '', $script_path);

// Ensure no double slashes if base_path is empty or just /
$base_path = trim($base_path, '/');
$SITE_URL = $protocol . "://" . $host . ($base_path ? "/" . $base_path : "");

// Allow database override if strictly needed (optional, keeping priority on dynamic for now as requested)
if (!empty($settings['site_url']) && $settings['site_url'] !== 'http://localhost/lkvm') {
    // Uncomment the line below if you prefer the DB setting to take precedence over dynamic detection
    // $SITE_URL = $settings['site_url'];
}
$SITE_NAME = $settings['site_name'] ?? 'LKVM';
$SITE_TITLE = $settings['site_name'] ?? 'LKVM'; // Use site_name as title default
$CONTACT_EMAIL = $settings['email'] ?? '';
$CONTACT_MOBILE = $settings['mobile'] ?? '';
$CONTACT_MOBILE1 = $settings['mobile1'] ?? '';
$ADDRESS = $settings['address'] ?? '';

// Error reporting (comment these lines in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
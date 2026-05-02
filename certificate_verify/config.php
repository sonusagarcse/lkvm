<?php
/*
 * Certificate Verify Pro v2
 * - Uses DB-backed admin user with password hash
 * - Add/Edit certificate supports PDF upload
 * - Download allowed toggle
 */
define('DB_HOST', 'localhost');
define('DB_NAME', 'ouvcxwtd_certificate_verify');
define('DB_USER', 'ouvcxwtd_certificate_verify');
define('DB_PASS', 'certificate_verify');


// Toggle development error display (set false in production)
define('DEV_MODE', true);
if (defined('DEV_MODE') && DEV_MODE) { error_reporting(E_ALL); ini_set('display_errors', '1'); }
// Upload directories
define('UPLOAD_DIR', __DIR__ . '/admin/uploads'); // absolute
define('UPLOAD_WEB', 'admin/uploads');            // relative web path

function db(): mysqli {
    $m = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($m->connect_errno) { http_response_code(500); die('DB connection failed: ' . $m->connect_error); }
    $m->set_charset('utf8mb4');
    return $m;
}
function e(string $s): string { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

// Load institute settings (id=1)
function get_settings(): array {
    $m = db();
    $q = $m->query("SELECT * FROM institute_settings WHERE id=1 LIMIT 1");
    $row = $q ? $q->fetch_assoc() : null;
    $m->close();
    return $row ?: ['id'=>1,'name'=>'Your Institute','logo'=>null,'email'=>'','phone'=>'','address'=>''];
}

// Authentication helpers
session_start();
function is_logged_in(): bool { return !empty($_SESSION['admin_id']); }
function require_login() { if (!is_logged_in()) { header('Location: /admin/login.php'); exit; } }
function current_admin(): ?array {
    if (!is_logged_in()) return null;
    $m = db();
    $stmt = $m->prepare("SELECT id, username FROM admin_users WHERE id=?");
    $stmt->bind_param('i', $_SESSION['admin_id']);
    $stmt->execute();
    $r = $stmt->get_result()->fetch_assoc();
    $stmt->close(); $m->close();
    return $r ?: null;
}
?>

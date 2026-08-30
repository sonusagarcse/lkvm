<?php
/**
 * LKVM Application Security Core
 * Contains helper functions for CSRF, secure uploads, session hardening, SQLi defense,
 * rate limiting, and generic output escaping.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start_secure();
}

/**
 * Starts a secure session with appropriate security headers and flags
 */
function session_start_secure() {
    if (session_status() !== PHP_SESSION_NONE) {
        return;
    }
    
    // Set secure session cookie parameters
    $cookieParams = session_get_cookie_params();
    $secure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
    
    session_set_cookie_params([
        'lifetime' => 0, // session cookie
        'path' => $cookieParams['path'],
        'domain' => $cookieParams['domain'],
        'secure' => $secure,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    
    session_start();
    
    // Prevent session fixation
    if (!isset($_SESSION['created_time'])) {
        $_SESSION['created_time'] = time();
    } elseif (time() - $_SESSION['created_time'] > 1800) { // regenerate every 30 mins
        session_regenerate_id(true);
        $_SESSION['created_time'] = time();
    }
}

/**
 * Emits standard security headers
 */
function emit_security_headers() {
    if (!headers_sent()) {
        header("X-Frame-Options: SAMEORIGIN");
        header("X-Content-Type-Options: nosniff");
        header("Referrer-Policy: strict-origin-when-cross-origin");
        header("Permissions-Policy: geolocation=(), microphone=(), camera=()");
        // If HTTPS is active, force HSTS
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");
        }
    }
}

/**
 * Escapes output for HTML context
 */
if (!function_exists('e')) {
    function e($string) {
        return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
    }
}

/**
 * CSRF Token generation
 */
function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Output HTML input element for CSRF
 */
function csrf_field() {
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

/**
 * Validate CSRF Token
 */
function verify_csrf_token() {
    // If not POST/PUT/PATCH/DELETE, don't require token
    if (!in_array($_SERVER['REQUEST_METHOD'], ['POST', 'PUT', 'PATCH', 'DELETE'])) {
        return true;
    }
    
    $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    
    if (empty($_SESSION['csrf_token']) || empty($token)) {
        security_log('CSRF_FAILURE', 'Missing CSRF token');
        return false;
    }
    
    $valid = hash_equals($_SESSION['csrf_token'], $token);
    if (!$valid) {
        security_log('CSRF_FAILURE', 'Invalid CSRF token match');
    }
    return $valid;
}

/**
 * Validate and process secure file upload
 *
 * @param array $file The $_FILES['name'] subarray
 * @param string $target_dir Target directory to write to
 * @param array $allowed_exts Allowed file extensions (lowercase)
 * @param array $allowed_mimes Allowed MIME types
 * @param int $max_bytes Max file size in bytes
 * @return array [success => bool, filename => string, error => string]
 */
function secure_upload_file($file, $target_dir, $allowed_exts, $allowed_mimes, $max_bytes = 5242880) {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        $upload_errors = [
            UPLOAD_ERR_INI_SIZE   => 'The uploaded file exceeds the upload_max_filesize directive in php.ini.',
            UPLOAD_ERR_FORM_SIZE  => 'The uploaded file exceeds the MAX_FILE_SIZE directive in the HTML form.',
            UPLOAD_ERR_PARTIAL    => 'The uploaded file was only partially uploaded.',
            UPLOAD_ERR_NO_FILE    => 'No file was uploaded.',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder.',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
            UPLOAD_ERR_EXTENSION  => 'A PHP extension stopped the file upload.'
        ];
        $err = $upload_errors[$file['error']] ?? 'Unknown upload error.';
        return ['success' => false, 'filename' => '', 'error' => $err];
    }

    // Check size
    if ($file['size'] > $max_bytes) {
        return ['success' => false, 'filename' => '', 'error' => 'File size exceeds limit. Maximum is ' . ($max_bytes / 1024 / 1024) . 'MB.'];
    }

    // Check extension
    $orig_name = basename($file['name']);
    $ext = strtolower(pathinfo($orig_name, PATHINFO_EXTENSION));
    
    if (!in_array($ext, $allowed_exts)) {
        security_log('SUSPICIOUS_UPLOAD', "Disallowed extension: .$ext (Original name: $orig_name)");
        return ['success' => false, 'filename' => '', 'error' => 'Disallowed file extension.'];
    }

    // Double extension protection
    if (preg_match('/\.(php|phtml|php3|php4|php5|phar|inc|cgi|pl|py|sh|asp|aspx|jsp|exe|dll)\./i', $orig_name)) {
        security_log('SUSPICIOUS_UPLOAD', "Double extension attack detected: $orig_name");
        return ['success' => false, 'filename' => '', 'error' => 'Invalid file name.'];
    }

    // Validate MIME type using finfo_file
    $tmp_path = $file['tmp_name'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $tmp_path);
    finfo_close($finfo);

    if (!in_array($mime, $allowed_mimes)) {
        security_log('SUSPICIOUS_UPLOAD', "MIME spoofing detected: MIME was $mime, expected one of " . implode(', ', $allowed_mimes));
        return ['success' => false, 'filename' => '', 'error' => 'Invalid file type (MIME check failed).'];
    }

    // Reject SVG if not explicitly needed, or sanitize it to prevent SVG XSS
    if ($mime === 'image/svg+xml') {
        $svg_content = file_get_contents($tmp_path);
        if (preg_match('/<script|onload|onmouseover|onclick|javascript:/i', $svg_content)) {
            security_log('SUSPICIOUS_UPLOAD', "SVG XSS payload detected in $orig_name");
            return ['success' => false, 'filename' => '', 'error' => 'SVG file contains insecure scripts.'];
        }
    }

    // If it's an image, validate dimensions to block image polyglots / corrupt files
    $image_mimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (in_array($mime, $image_mimes)) {
        $img_info = @getimagesize($tmp_path);
        if ($img_info === false) {
            security_log('SUSPICIOUS_UPLOAD', "Invalid or corrupt image content: $orig_name");
            return ['success' => false, 'filename' => '', 'error' => 'Invalid image structure.'];
        }
    }

    // Ensure the target directory exists and resolve real path
    if (!is_dir($target_dir)) {
        @mkdir($target_dir, 0755, true);
    }
    
    $real_target_dir = realpath($target_dir);
    if ($real_target_dir === false) {
        return ['success' => false, 'filename' => '', 'error' => 'Target directory does not exist.'];
    }

    // Generate random safe filename
    $sanitized_base = preg_replace('/[^a-zA-Z0-9_-]/', '', pathinfo($orig_name, PATHINFO_FILENAME));
    if (empty($sanitized_base)) {
        $sanitized_base = 'file';
    }
    $new_filename = $sanitized_base . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
    
    // Ensure final path resolves strictly inside intended target directory (Path Traversal protection)
    $final_path = $real_target_dir . DIRECTORY_SEPARATOR . $new_filename;
    
    if (strpos($final_path, $real_target_dir) !== 0) {
        security_log('SUSPICIOUS_UPLOAD', "Path traversal attempt in upload: $final_path");
        return ['success' => false, 'filename' => '', 'error' => 'Invalid destination path.'];
    }

    if (move_uploaded_file($tmp_path, $final_path)) {
        security_log('FILE_UPLOAD', "Successfully uploaded: $new_filename as type $mime");
        return ['success' => true, 'filename' => $new_filename, 'error' => ''];
    } else {
        return ['success' => false, 'filename' => '', 'error' => 'Failed to move uploaded file. Check server permissions.'];
    }
}

/**
 * Simple rate limiting logic to mitigate brute force attacks
 *
 * @param string $key Unique key for current endpoint + identifier (IP / Email)
 * @param int $max_attempts Maximum allowed attempts
 * @param int $decay_seconds Time window in seconds
 * @return bool True if within limits, False if rate limited
 */
function check_rate_limit($key, $max_attempts, $decay_seconds) {
    $now = time();
    $cache_dir = __DIR__ . '/../cache/rate_limit';
    if (!is_dir($cache_dir)) {
        @mkdir($cache_dir, 0700, true);
    }
    
    $hash = md5($key);
    $limit_file = $cache_dir . '/' . $hash . '.json';
    
    $attempts = [];
    if (file_exists($limit_file)) {
        $content = @file_get_contents($limit_file);
        if ($content !== false) {
            $attempts = json_decode($content, true) ?: [];
        }
    }
    
    // Clean expired attempts
    $attempts = array_filter($attempts, function ($timestamp) use ($now, $decay_seconds) {
        return ($now - $timestamp) < $decay_seconds;
    });
    
    if (count($attempts) >= $max_attempts) {
        security_log('RATE_LIMIT_EXCEEDED', "Key: $key exceeded $max_attempts attempts");
        return false;
    }
    
    $attempts[] = $now;
    @file_put_contents($limit_file, json_encode($attempts), LOCK_EX);
    return true;
}

/**
 * Log security related events to file (excluding sensitive info)
 *
 * @param string $event Event type (e.g. CSRF_FAILURE, AUTH_SUCCESS, RATE_LIMIT)
 * @param string $details Detailed message
 */
function security_log($event, $details) {
    $log_dir = __DIR__ . '/../cache/logs';
    if (!is_dir($log_dir)) {
        @mkdir($log_dir, 0700, true);
    }
    
    $log_file = $log_dir . '/security.log';
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN_IP';
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? 'UNKNOWN_UA';
    $timestamp = date('Y-m-d H:i:s');
    
    // Format: [Timestamp] [IP] [Event] Details [UA]
    $log_entry = sprintf("[%s] [%s] [%s] %s [UA: %s]\n", $timestamp, $ip, $event, $details, $ua);
    @file_put_contents($log_file, $log_entry, FILE_APPEND | LOCK_EX);
}

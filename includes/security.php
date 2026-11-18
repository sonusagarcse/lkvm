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
 * @param string|null $custom_token Optional token to validate (e.g. from GET requests)
 */
function verify_csrf_token($custom_token = null) {
    if ($custom_token !== null) {
        if (empty($_SESSION['csrf_token']) || empty($custom_token)) {
            security_log('CSRF_FAILURE', 'Missing CSRF token for custom validation');
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $custom_token);
    }

    // If not POST/PUT/PATCH/DELETE, don't require token
    if (!in_array($_SERVER['REQUEST_METHOD'], ['POST', 'PUT', 'PATCH', 'DELETE'])) {
        return true;
    }
    
    $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    
    if (empty($_SESSION['csrf_token']) || empty($token)) {
        security_log('CSRF_FAILURE', 'Missing CSRF token in POST request');
        return false;
    }
    
    $valid = hash_equals($_SESSION['csrf_token'], $token);
    if (!$valid) {
        security_log('CSRF_FAILURE', 'Invalid CSRF token match');
    }
    return $valid;
}

/**
 * Compresses and resizes an image file to reduce disk space and optimize web loading.
 * Supports JPEG, PNG, WebP, and GIF.
 * Automatically corrects mobile EXIF orientation and preserves alpha transparency.
 *
 * @param string $source_path Path to the source image file
 * @param string $destination_path Path where the compressed image will be written
 * @param int $max_width Maximum width in pixels (proportional resize)
 * @param int $max_height Maximum height in pixels (proportional resize)
 * @param int $quality Compression quality (1-100, recommended 78-85)
 * @param string $output_format Desired output format: 'webp', 'jpg', 'png', or 'auto'
 * @return array ['success' => bool, 'error' => string, 'width' => int, 'height' => int, 'size' => int]
 */
function compress_and_resize_image($source_path, $destination_path, $max_width = 1280, $max_height = 1280, $quality = 80, $output_format = 'webp') {
    if (!file_exists($source_path) || !is_readable($source_path)) {
        return ['success' => false, 'error' => 'Source image file does not exist or is unreadable.'];
    }

    $info = @getimagesize($source_path);
    if ($info === false) {
        return ['success' => false, 'error' => 'Invalid image structure.'];
    }

    $orig_w = $info[0];
    $orig_h = $info[1];
    $mime = $info['mime'];

    // Load source image using GD
    $src_img = null;
    switch ($mime) {
        case 'image/jpeg':
        case 'image/jpg':
            $src_img = @imagecreatefromjpeg($source_path);
            break;
        case 'image/png':
            $src_img = @imagecreatefrompng($source_path);
            break;
        case 'image/webp':
            if (function_exists('imagecreatefromwebp')) {
                $src_img = @imagecreatefromwebp($source_path);
            }
            break;
        case 'image/gif':
            $src_img = @imagecreatefromgif($source_path);
            break;
    }

    if (!$src_img) {
        return ['success' => false, 'error' => 'Failed to initialize GD image resource.'];
    }

    // Correct EXIF orientation for JPEG from mobile cameras
    if (function_exists('exif_read_data') && ($mime === 'image/jpeg' || $mime === 'image/jpg')) {
        $exif = @exif_read_data($source_path);
        if (!empty($exif['Orientation'])) {
            switch ($exif['Orientation']) {
                case 3:
                    $rotated = @imagerotate($src_img, 180, 0);
                    if ($rotated) { imagedestroy($src_img); $src_img = $rotated; }
                    break;
                case 6:
                    $rotated = @imagerotate($src_img, -90, 0);
                    if ($rotated) {
                        imagedestroy($src_img);
                        $src_img = $rotated;
                        $tmp = $orig_w; $orig_w = $orig_h; $orig_h = $tmp;
                    }
                    break;
                case 8:
                    $rotated = @imagerotate($src_img, 90, 0);
                    if ($rotated) {
                        imagedestroy($src_img);
                        $src_img = $rotated;
                        $tmp = $orig_w; $orig_w = $orig_h; $orig_h = $tmp;
                    }
                    break;
            }
        }
    }

    // Calculate new dimensions preserving aspect ratio
    $new_w = $orig_w;
    $new_h = $orig_h;
    if ($orig_w > $max_width || $orig_h > $max_height) {
        $ratio = min($max_width / $orig_w, $max_height / $orig_h);
        $new_w = max(1, (int)round($orig_w * $ratio));
        $new_h = max(1, (int)round($orig_h * $ratio));
    }

    // Create target canvas
    $dst_img = imagecreatetruecolor($new_w, $new_h);
    if (!$dst_img) {
        imagedestroy($src_img);
        return ['success' => false, 'error' => 'Failed to allocate image canvas.'];
    }

    // Preserve alpha transparency for PNG, WebP, GIF
    imagealphablending($dst_img, false);
    imagesavealpha($dst_img, true);
    $transparent = imagecolorallocatealpha($dst_img, 255, 255, 255, 127);
    imagefilledrectangle($dst_img, 0, 0, $new_w, $new_h, $transparent);

    // High quality resample
    imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $new_w, $new_h, $orig_w, $orig_h);

    // Determine target format
    $save_format = $output_format;
    if ($save_format === 'auto') {
        $save_format = function_exists('imagewebp') ? 'webp' : 'jpg';
    } elseif ($save_format === 'webp' && !function_exists('imagewebp')) {
        $save_format = 'jpg';
    }

    // Write compressed image
    $saved = false;
    switch ($save_format) {
        case 'webp':
            $saved = @imagewebp($dst_img, $destination_path, $quality);
            break;
        case 'png':
            $png_quality = (int)round(9 - (($quality / 100) * 9));
            $saved = @imagepng($dst_img, $destination_path, max(0, min(9, $png_quality)));
            break;
        case 'jpg':
        case 'jpeg':
        default:
            $opaque_canvas = imagecreatetruecolor($new_w, $new_h);
            $white = imagecolorallocate($opaque_canvas, 255, 255, 255);
            imagefill($opaque_canvas, 0, 0, $white);
            imagecopy($opaque_canvas, $dst_img, 0, 0, 0, 0, $new_w, $new_h);
            $saved = @imagejpeg($opaque_canvas, $destination_path, $quality);
            imagedestroy($opaque_canvas);
            break;
    }

    // Free GD memory
    imagedestroy($src_img);
    imagedestroy($dst_img);

    if ($saved && file_exists($destination_path)) {
        return [
            'success' => true,
            'error' => '',
            'width' => $new_w,
            'height' => $new_h,
            'size' => filesize($destination_path)
        ];
    } else {
        return ['success' => false, 'error' => 'Failed to write compressed image to destination.'];
    }
}

/**
 * Scans HTML content for embedded base64 data URI images, compresses them to WebP,
 * writes them to the target upload directory, and replaces the massive base64 strings
 * with lightweight relative/absolute image URLs.
 *
 * @param string $html HTML content
 * @param string $target_dir Target upload folder (e.g. '../uploads/')
 * @param string $base_url Base URL for the site (e.g. 'http://localhost/lkvm')
 * @param string $prefix Prefix for generated image files
 * @return string Processed HTML with compressed image URLs
 */
function extract_and_compress_base64_images($html, $target_dir, $base_url, $prefix = 'blog_inline_') {
    if (empty($html) || strpos($html, 'data:image/') === false) {
        return $html;
    }

    if (!is_dir($target_dir)) {
        @mkdir($target_dir, 0755, true);
    }
    $real_target_dir = realpath($target_dir);
    if (!$real_target_dir) {
        return $html;
    }

    $pattern = '/data:image\/(jpeg|jpg|png|webp|gif);base64,([A-Za-z0-9+\/=\s]+)/i';

    return preg_replace_callback($pattern, function($matches) use ($real_target_dir, $base_url, $prefix) {
        $raw_base64 = preg_replace('/\s+/', '', $matches[2]);
        $image_data = base64_decode($raw_base64);

        if (!$image_data) {
            return $matches[0];
        }

        $tmp_file = tempnam(sys_get_temp_dir(), 'b64img_');
        file_put_contents($tmp_file, $image_data);

        $target_ext = function_exists('imagewebp') ? 'webp' : 'jpg';
        $new_filename = $prefix . bin2hex(random_bytes(8)) . '.' . $target_ext;
        $final_path = $real_target_dir . DIRECTORY_SEPARATOR . $new_filename;

        $comp = compress_and_resize_image($tmp_file, $final_path, 1200, 1200, 80, $target_ext);
        @unlink($tmp_file);

        if ($comp['success']) {
            return rtrim($base_url, '/') . '/uploads/' . $new_filename;
        }

        return $matches[0];
    }, $html);
}

/**
 * Validate and process secure file upload with optional compression & resizing
 *
 * @param array $file The $_FILES['name'] subarray
 * @param string $target_dir Target directory to write to
 * @param array $allowed_exts Allowed file extensions (lowercase)
 * @param array $allowed_mimes Allowed MIME types
 * @param int $max_bytes Max file size in bytes
 * @param bool $optimize Whether to compress and resize image files
 * @param int $max_width Maximum width for image resizing (default 1280)
 * @param int $max_height Maximum height for image resizing (default 1280)
 * @param int $quality Compression quality (default 80)
 * @param string $custom_prefix Optional prefix for filename
 * @return array [success => bool, filename => string, error => string, original_size => int, reduced_size => int]
 */
function secure_upload_file($file, $target_dir, $allowed_exts, $allowed_mimes, $max_bytes = 5242880, $optimize = false, $max_width = 1280, $max_height = 1280, $quality = 80, $custom_prefix = '') {
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

    // Generate random safe base name
    $sanitized_base = preg_replace('/[^a-zA-Z0-9_-]/', '', pathinfo($orig_name, PATHINFO_FILENAME));
    if (empty($sanitized_base)) {
        $sanitized_base = 'file';
    }
    $file_prefix = !empty($custom_prefix) ? $custom_prefix : ($sanitized_base . '_');

    // If image compression & optimization is requested and GD is available
    if ($optimize && in_array($mime, $image_mimes) && extension_loaded('gd')) {
        // Output WebP for modern browsers, maximum space reduction (75-90% smaller)
        $target_ext = function_exists('imagewebp') ? 'webp' : ($ext === 'png' ? 'png' : 'jpg');
        $new_filename = $file_prefix . bin2hex(random_bytes(8)) . '.' . $target_ext;
        $final_path = $real_target_dir . DIRECTORY_SEPARATOR . $new_filename;

        if (strpos($final_path, $real_target_dir) !== 0) {
            security_log('SUSPICIOUS_UPLOAD', "Path traversal attempt in upload: $final_path");
            return ['success' => false, 'filename' => '', 'error' => 'Invalid destination path.'];
        }

        $comp_res = compress_and_resize_image($tmp_path, $final_path, $max_width, $max_height, $quality, $target_ext);
        if ($comp_res['success']) {
            security_log('FILE_UPLOAD', "Successfully uploaded & optimized: $new_filename (Reduced from " . $file['size'] . " to " . $comp_res['size'] . " bytes)");
            return [
                'success' => true,
                'filename' => $new_filename,
                'error' => '',
                'original_size' => $file['size'],
                'reduced_size' => $comp_res['size']
            ];
        }
        // Fallback to standard move_uploaded_file if compression had any issue
    }

    $new_filename = $file_prefix . bin2hex(random_bytes(8)) . '.' . $ext;
    
    // Ensure final path resolves strictly inside intended target directory (Path Traversal protection)
    $final_path = $real_target_dir . DIRECTORY_SEPARATOR . $new_filename;
    
    if (strpos($final_path, $real_target_dir) !== 0) {
        security_log('SUSPICIOUS_UPLOAD', "Path traversal attempt in upload: $final_path");
        return ['success' => false, 'filename' => '', 'error' => 'Invalid destination path.'];
    }

    if (move_uploaded_file($tmp_path, $final_path)) {
        security_log('FILE_UPLOAD', "Successfully uploaded: $new_filename as type $mime");
        return [
            'success' => true,
            'filename' => $new_filename,
            'error' => '',
            'original_size' => $file['size'],
            'reduced_size' => filesize($final_path)
        ];
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

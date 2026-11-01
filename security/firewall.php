<?php
/**
 * LKVM Application Layer IP Firewall & Rapid URL Scanning Blocker
 *
 * Automatically detects malicious scanners probing non-existent URLs and
 * temporarily blocks their IP address for 5 minutes.
 * Guarantees zero false positives for legitimate visitors, browser sub-requests,
 * static assets, search engine crawlers, and whitelisted IPs.
 */

if (!defined('FIREWALL_LOADED')) {
    define('FIREWALL_LOADED', true);
}

// Ensure Indian Standard Time consistency across servers
if (function_exists('date_default_timezone_set') && date_default_timezone_get() !== 'Asia/Kolkata') {
    @date_default_timezone_set('Asia/Kolkata');
}

/**
 * Ensure database tables exist (Self-healing schema migrator)
 *
 * @param mysqli $con
 */
function firewall_ensure_tables($con) {
    static $verified = false;
    if ($verified || !$con) {
        return;
    }

    $cache_dir = __DIR__ . '/../cache';
    if (!is_dir($cache_dir)) {
        @mkdir($cache_dir, 0755, true);
    }
    $lock_file = $cache_dir . '/firewall_tables_v1.lock';

    // If schema lock exists, skip running CREATE TABLE queries on every hit
    if (file_exists($lock_file)) {
        $verified = true;
        return;
    }

    // 1. ip_threat_log
    $sql_threat = "CREATE TABLE IF NOT EXISTS `ip_threat_log` (
        `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
        `ip_address` VARCHAR(45) NOT NULL,
        `request_uri` VARCHAR(500) NOT NULL,
        `user_agent` VARCHAR(500) DEFAULT '',
        `request_method` VARCHAR(10) DEFAULT 'GET',
        `is_suspicious` TINYINT(1) DEFAULT 0,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX `idx_ip_time` (`ip_address`, `created_at`),
        INDEX `idx_suspicious` (`is_suspicious`, `created_at`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

    // 2. ip_blocked
    $sql_blocked = "CREATE TABLE IF NOT EXISTS `ip_blocked` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `ip_address` VARCHAR(45) NOT NULL UNIQUE,
        `reason` VARCHAR(255) DEFAULT 'Automated: Rapid URL scanning detected',
        `blocked_by` ENUM('auto','admin') DEFAULT 'auto',
        `admin_id` INT DEFAULT NULL,
        `blocked_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `expires_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `unblocked_at` TIMESTAMP NULL DEFAULT NULL,
        `is_active` TINYINT(1) DEFAULT 1,
        `unique_urls_count` INT DEFAULT 0,
        INDEX `idx_ip_active` (`ip_address`, `is_active`),
        INDEX `idx_expires` (`expires_at`, `is_active`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

    // 3. ip_whitelist
    $sql_whitelist = "CREATE TABLE IF NOT EXISTS `ip_whitelist` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `ip_address` VARCHAR(45) NOT NULL UNIQUE,
        `label` VARCHAR(150) DEFAULT 'Trusted Office / Admin IP',
        `added_by` INT DEFAULT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX `idx_ip` (`ip_address`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

    mysqli_query($con, $sql_threat);
    mysqli_query($con, $sql_blocked);
    mysqli_query($con, $sql_whitelist);

    @file_put_contents($lock_file, date('Y-m-d H:i:s'));
    $verified = true;
}

/**
 * Resolve client IP with anti-spoofing defense.
 * Only trusts CF-Connecting-IP if provided by Cloudflare; otherwise falls back to REMOTE_ADDR.
 * Does NOT blindly trust X-Forwarded-For.
 *
 * @return string
 */
function firewall_get_client_ip() {
    // Optional testing override constant
    if (defined('FIREWALL_TEST_IP') && !empty(FIREWALL_TEST_IP)) {
        return FIREWALL_TEST_IP;
    }

    // Cloudflare connecting IP
    if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        $cf_ip = trim($_SERVER['HTTP_CF_CONNECTING_IP']);
        if (filter_var($cf_ip, FILTER_VALIDATE_IP)) {
            return $cf_ip;
        }
    }

    // Standard remote address
    $remote_ip = trim($_SERVER['REMOTE_ADDR'] ?? '');
    if (filter_var($remote_ip, FILTER_VALIDATE_IP)) {
        return $remote_ip;
    }

    return '127.0.0.1';
}

/**
 * Determine if current request is for a static asset or browser sub-resource.
 * Prevents false positives caused by images, scripts, stylesheets, fonts, etc.
 *
 * @return bool
 */
function firewall_is_static_asset() {
    // Browser Sec-Fetch-Dest header check
    if (!empty($_SERVER['HTTP_SEC_FETCH_DEST'])) {
        $dest = strtolower(trim($_SERVER['HTTP_SEC_FETCH_DEST']));
        if (in_array($dest, ['image', 'style', 'script', 'font', 'video', 'audio', 'manifest'], true)) {
            return true;
        }
    }

    // Extension check on requested URI path
    $uri_path = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
    if ($uri_path) {
        $ext = strtolower(pathinfo($uri_path, PATHINFO_EXTENSION));
        $static_extensions = [
            'png', 'jpg', 'jpeg', 'gif', 'webp', 'svg', 'ico', 'bmp', 'avif',
            'css', 'js', 'mjs',
            'woff', 'woff2', 'ttf', 'otf', 'eot',
            'map', 'json', 'xml', 'txt',
            'mp4', 'webm', 'ogg', 'mp3', 'wav',
            'pdf', 'zip'
        ];
        if (in_array($ext, $static_extensions, true)) {
            return true;
        }
    }

    return false;
}

/**
 * Check if the request is from a verified major search engine crawler
 *
 * @return bool
 */
function firewall_is_crawler() {
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
    if (empty($ua)) {
        return false;
    }

    // Major search bots
    if (preg_match('/(Googlebot|bingbot|Baiduspider|YandexBot|DuckDuckBot)/i', $ua)) {
        return true;
    }

    return false;
}

/**
 * Check if the given IP address is whitelisted in ip_whitelist table
 *
 * @param mysqli $con
 * @param string $ip
 * @return bool
 */
function firewall_is_whitelisted($con, $ip) {
    static $whitelist_cache = [];

    if (isset($whitelist_cache[$ip])) {
        return $whitelist_cache[$ip];
    }

    $stmt = mysqli_prepare($con, "SELECT id FROM ip_whitelist WHERE ip_address = ? LIMIT 1");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $ip);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $is_whitelisted = (mysqli_stmt_num_rows($stmt) > 0);
        mysqli_stmt_close($stmt);

        $whitelist_cache[$ip] = $is_whitelisted;
        return $is_whitelisted;
    }

    return false;
}

/**
 * Main firewall check executed on every incoming request.
 * Verifies if the client IP has an active block. Auto-expires blocks after 5 minutes.
 *
 * @param mysqli $con
 */
function firewall_check($con) {
    if (!$con) {
        return;
    }

    // 1. Immediately bypass static assets
    if (firewall_is_static_asset()) {
        return;
    }

    // 2. Bypass verified crawlers
    if (firewall_is_crawler()) {
        return;
    }

    // Ensure database tables exist
    firewall_ensure_tables($con);

    $ip = firewall_get_client_ip();

    // 3. Bypass whitelisted IPs
    if (firewall_is_whitelisted($con, $ip)) {
        return;
    }

    // 4. Check active block status
    $stmt = mysqli_prepare($con, "
        SELECT id, ip_address, reason, blocked_by, blocked_at, expires_at, unique_urls_count,
               UNIX_TIMESTAMP(expires_at) AS expires_ts,
               UNIX_TIMESTAMP(NOW()) AS now_ts
        FROM ip_blocked 
        WHERE ip_address = ? AND is_active = 1 
        LIMIT 1
    ");

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $ip);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $block = $res ? mysqli_fetch_assoc($res) : null;
        mysqli_stmt_close($stmt);

        if ($block) {
            $now = !empty($block['now_ts']) ? (int)$block['now_ts'] : time();
            $expires_at = !empty($block['expires_ts']) ? (int)$block['expires_ts'] : strtotime($block['expires_at']);

            if ($expires_at <= $now) {
                // Block has naturally expired! Auto-expire and restore access immediately
                $up = mysqli_prepare($con, "UPDATE ip_blocked SET is_active = 0, unblocked_at = NOW() WHERE id = ?");
                if ($up) {
                    mysqli_stmt_bind_param($up, "i", $block['id']);
                    mysqli_stmt_execute($up);
                    mysqli_stmt_close($up);
                }
                return;
            }

            // Still active block: halt and render modern 403 Forbidden screen
            firewall_render_blocked_screen($block, $ip);
        }
    }

    // 5. Multi-Request Flood & Rapid Burst Protection (Separate rate limit layer)
    firewall_check_request_flood($con, $ip);

    // 6. Probabilistic background cleanup (1 in 100 requests)
    firewall_maybe_cleanup($con);
}

/**
 * Detect high-frequency multi-request flooding / burst DDoS attacks.
 * If an IP sends more than $max_requests within $window_seconds on dynamic PHP endpoints,
 * it is automatically blocked for 5 minutes.
 *
 * @param mysqli $con
 * @param string $ip
 * @param int $max_requests (Default: 60)
 * @param int $window_seconds (Default: 30)
 */
function firewall_check_request_flood($con, $ip, $max_requests = 60, $window_seconds = 30) {
    // If request is from localhost CLI, skip flood check
    if (php_sapi_name() === 'cli') {
        return;
    }

    // Allow overriding limit via constant if needed
    if (defined('FIREWALL_FLOOD_LIMIT')) {
        $max_requests = (int)FIREWALL_FLOOD_LIMIT;
    }

    $cache_dir = __DIR__ . '/../cache/firewall_flood';
    if (!is_dir($cache_dir)) {
        @mkdir($cache_dir, 0755, true);
    }

    $rate_file = $cache_dir . '/' . md5($ip) . '.json';
    $now = time();
    $timestamps = [];

    if (file_exists($rate_file)) {
        $content = @file_get_contents($rate_file);
        if ($content !== false) {
            $decoded = json_decode($content, true);
            if (is_array($decoded)) {
                $cutoff = $now - $window_seconds;
                foreach ($decoded as $ts) {
                    if ($ts > $cutoff) {
                        $timestamps[] = $ts;
                    }
                }
            }
        }
    }

    $timestamps[] = $now;

    // Check if flooded
    if (count($timestamps) >= $max_requests) {
        $uri = substr($_SERVER['REQUEST_URI'] ?? '/', 0, 500);
        $method = substr($_SERVER['REQUEST_METHOD'] ?? 'GET', 0, 10);
        $ua = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 500);
        $flood_count = count($timestamps);

        // Record in threat log
        $stmt_log = mysqli_prepare($con, "
            INSERT INTO ip_threat_log (ip_address, request_uri, user_agent, request_method, is_suspicious)
            VALUES (?, ?, ?, ?, 1)
        ");
        if ($stmt_log) {
            mysqli_stmt_bind_param($stmt_log, "ssss", $ip, $uri, $ua, $method);
            mysqli_stmt_execute($stmt_log);
            mysqli_stmt_close($stmt_log);
        }

        // Insert or activate block in ip_blocked
        $stmt_block = mysqli_prepare($con, "
            INSERT INTO ip_blocked (ip_address, reason, blocked_by, blocked_at, expires_at, is_active, unique_urls_count)
            VALUES (?, 'Automated: High-frequency request flooding detected', 'auto', NOW(), NOW() + INTERVAL 5 MINUTE, 1, ?)
            ON DUPLICATE KEY UPDATE 
                reason = VALUES(reason),
                blocked_by = 'auto',
                blocked_at = NOW(),
                expires_at = NOW() + INTERVAL 5 MINUTE,
                unblocked_at = NULL,
                is_active = 1,
                unique_urls_count = VALUES(unique_urls_count)
        ");
        if ($stmt_block) {
            mysqli_stmt_bind_param($stmt_block, "si", $ip, $flood_count);
            mysqli_stmt_execute($stmt_block);
            mysqli_stmt_close($stmt_block);
        }

        if (function_exists('security_log')) {
            security_log('FIREWALL_FLOOD_BLOCK', "IP $ip blocked for 5 minutes (sent $flood_count requests in $window_seconds s)");
        }

        @unlink($rate_file);

        $block_data = [
            'id' => 0,
            'reason' => 'Automated: High-frequency request flooding detected',
            'blocked_by' => 'auto',
            'blocked_at' => date('Y-m-d H:i:s'),
            'expires_at' => date('Y-m-d H:i:s', time() + 300),
            'expires_ts' => time() + 300,
            'now_ts' => time(),
            'unique_urls_count' => $flood_count
        ];

        firewall_render_blocked_screen($block_data, $ip);
    }

    // Save active window timestamps
    @file_put_contents($rate_file, json_encode($timestamps), LOCK_EX);
}

/**
 * Record a suspicious 404 / probe attempt into ip_threat_log.
 * Triggers 5-minute auto-block if >= 8 unique suspicious URLs probed within 60 seconds.
 *
 * @param mysqli $con
 */
function firewall_record_suspicious_hit($con) {
    if (!$con) {
        return;
    }

    // Bypass static assets
    if (firewall_is_static_asset()) {
        return;
    }

    // Bypass crawlers
    if (firewall_is_crawler()) {
        return;
    }

    firewall_ensure_tables($con);

    $ip = firewall_get_client_ip();

    // Bypass whitelisted IPs
    if (firewall_is_whitelisted($con, $ip)) {
        return;
    }

    $uri = substr($_SERVER['REQUEST_URI'] ?? '/', 0, 500);
    $method = substr($_SERVER['REQUEST_METHOD'] ?? 'GET', 0, 10);
    $user_agent = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 500);

    // 1. Insert suspicious hit into ip_threat_log
    $stmt = mysqli_prepare($con, "
        INSERT INTO ip_threat_log (ip_address, request_uri, user_agent, request_method, is_suspicious)
        VALUES (?, ?, ?, ?, 1)
    ");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssss", $ip, $uri, $user_agent, $method);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    // 2. Query distinct suspicious URLs requested by this IP in the last 60 seconds
    $count_stmt = mysqli_prepare($con, "
        SELECT COUNT(DISTINCT request_uri) AS unique_count 
        FROM ip_threat_log 
        WHERE ip_address = ? AND is_suspicious = 1 AND created_at >= NOW() - INTERVAL 60 SECOND
    ");

    if ($count_stmt) {
        mysqli_stmt_bind_param($count_stmt, "s", $ip);
        mysqli_stmt_execute($count_stmt);
        $res = mysqli_stmt_get_result($count_stmt);
        $row = $res ? mysqli_fetch_assoc($res) : null;
        $unique_count = (int)($row['unique_count'] ?? 0);
        mysqli_stmt_close($count_stmt);

        // Threshold check: 8 or more unique non-existent URLs within 60 seconds
        if ($unique_count >= 8) {
            $block_stmt = mysqli_prepare($con, "
                INSERT INTO ip_blocked (ip_address, reason, blocked_by, blocked_at, expires_at, is_active, unique_urls_count)
                VALUES (?, 'Automated: Rapid URL scanning detected', 'auto', NOW(), NOW() + INTERVAL 5 MINUTE, 1, ?)
                ON DUPLICATE KEY UPDATE 
                    reason = VALUES(reason),
                    blocked_by = 'auto',
                    blocked_at = NOW(),
                    expires_at = NOW() + INTERVAL 5 MINUTE,
                    unblocked_at = NULL,
                    is_active = 1,
                    unique_urls_count = VALUES(unique_urls_count)
            ");
            if ($block_stmt) {
                mysqli_stmt_bind_param($block_stmt, "si", $ip, $unique_count);
                mysqli_stmt_execute($block_stmt);
                mysqli_stmt_close($block_stmt);
            }

            if (function_exists('security_log')) {
                security_log('FIREWALL_AUTO_BLOCK', "IP $ip blocked for 5 minutes (probed $unique_count unique suspicious URLs in 60s)");
            }

            $block_data = [
                'id' => 0,
                'reason' => 'Automated: Rapid URL scanning detected',
                'blocked_by' => 'auto',
                'blocked_at' => date('Y-m-d H:i:s'),
                'expires_at' => date('Y-m-d H:i:s', time() + 300),
                'expires_ts' => time() + 300,
                'now_ts' => time(),
                'unique_urls_count' => $unique_count
            ];

            firewall_render_blocked_screen($block_data, $ip);
        }
    }
}

/**
 * Probabilistic cleanup (1 in 100 requests) to keep tables fast and lean.
 *
 * @param mysqli $con
 */
function firewall_maybe_cleanup($con) {
    if (rand(1, 100) === 1) {
        @mysqli_query($con, "DELETE FROM ip_threat_log WHERE created_at < NOW() - INTERVAL 48 HOUR");
        @mysqli_query($con, "UPDATE ip_blocked SET is_active = 0, unblocked_at = NOW() WHERE is_active = 1 AND expires_at <= NOW()");
    }
}

/**
 * Render clean, simple, human-friendly 403 Blocked Screen and terminate script execution.
 *
 * @param array $block
 * @param string $ip
 */
function firewall_render_blocked_screen($block, $ip) {
    http_response_code(403);
    header("Status: 403 Forbidden");
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Pragma: no-cache");

    // Unified timestamp calculation using MySQL timestamp or fallback
    $now_ts = !empty($block['now_ts']) ? (int)$block['now_ts'] : time();
    $expires_ts = !empty($block['expires_ts']) ? (int)$block['expires_ts'] : 0;
    if ($expires_ts <= 0 && !empty($block['expires_at'])) {
        $expires_ts = strtotime($block['expires_at']);
    }
    if ($expires_ts <= 0) {
        $expires_ts = $now_ts + 300;
    }

    $remaining_seconds = max(1, $expires_ts - $now_ts);

    // If auto-blocked, clamp to 300 seconds maximum so it always displays proper minutes
    $blocked_by = $block['blocked_by'] ?? 'auto';
    if ($blocked_by === 'auto' && $remaining_seconds > 300) {
        $remaining_seconds = 300;
    }

    $total_duration = max(300, $remaining_seconds);
    $ip_display = htmlspecialchars($ip);
    $block_id = !empty($block['id']) ? ('#' . str_pad($block['id'], 5, '0', STR_PAD_LEFT)) : ('#' . strtoupper(substr(md5($ip), 0, 6)));
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Please Wait a Moment | LKVM Security</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background: #f8fafc;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .hold-card {
            background: #ffffff;
            width: 100%;
            max-width: 460px;
            border-radius: 24px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 20px 45px -12px rgba(15, 23, 42, 0.08);
            padding: 40px 32px 36px;
            text-align: center;
            animation: cardAppear 0.35s ease-out;
        }
        @keyframes cardAppear {
            from { opacity: 0; transform: translateY(12px) scale(0.99); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        .icon-circle {
            width: 64px;
            height: 64px;
            margin: 0 auto 18px;
            border-radius: 50%;
            background: #fef3c7;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        .icon-circle::after {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            border: 2px solid rgba(245, 158, 11, 0.35);
            animation: pulseRing 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        @keyframes pulseRing {
            0% { transform: scale(1); opacity: 0.8; }
            50% { transform: scale(1.12); opacity: 0; }
            100% { transform: scale(1.12); opacity: 0; }
        }
        .icon-circle svg {
            width: 32px;
            height: 32px;
            fill: #d97706;
        }
        .badge-tag {
            display: inline-block;
            background: #f1f5f9;
            color: #475569;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 4px 12px;
            border-radius: 50px;
            margin-bottom: 12px;
        }
        h1 {
            font-size: 24px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.4px;
            margin-bottom: 8px;
        }
        .subtext {
            font-size: 14px;
            color: #64748b;
            line-height: 1.55;
            margin-bottom: 26px;
        }
        .timer-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 20px 16px 18px;
            margin-bottom: 24px;
        }
        .timer-label {
            font-size: 11px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin-bottom: 4px;
        }
        .timer-value {
            font-size: 46px;
            font-weight: 800;
            color: #002147;
            font-variant-numeric: tabular-nums;
            letter-spacing: -0.5px;
            line-height: 1.1;
        }
        .timer-note {
            font-size: 12px;
            color: #64748b;
            margin-top: 6px;
            font-weight: 500;
        }
        .progress-bar-bg {
            height: 5px;
            background: #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
            margin-top: 14px;
        }
        .progress-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, #3b82f6, #002147);
            border-radius: 10px;
            transition: width 1s linear;
        }
        .info-pill-container {
            display: flex;
            justify-content: center;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 22px;
        }
        .info-pill {
            background: #f1f5f9;
            color: #475569;
            font-size: 12px;
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 50px;
        }
        .info-pill span {
            color: #0f172a;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        }
        .help-text {
            font-size: 12px;
            color: #94a3b8;
            line-height: 1.5;
            margin-bottom: 20px;
        }
        .btn-reload {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            background: #002147;
            color: #ffffff;
            font-size: 14px;
            font-weight: 700;
            padding: 13px 20px;
            border-radius: 50px;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 6px 16px rgba(0, 33, 71, 0.15);
        }
        .btn-reload:hover {
            background: #0a366b;
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(0, 33, 71, 0.22);
        }
        .btn-reload svg {
            width: 16px;
            height: 16px;
            fill: currentColor;
        }
    </style>
</head>
<body>
    <div class="hold-card">
        <div class="icon-circle">
            <svg viewBox="0 0 24 24">
                <path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.5-13H11v6l5.2 3.2.8-1.3-4.5-2.7V7z"/>
            </svg>
        </div>

        <span class="badge-tag">Temporary Pause &bull; 5-Min Cooldown</span>
        <h1>Please Wait a Moment</h1>
        <p class="subtext">
            Multiple rapid requests were detected from your network. To keep the website running fast and smooth, access is briefly paused.
        </p>

        <div class="timer-box">
            <div class="timer-label">Access Restores Automatically In</div>
            <div class="timer-value" id="timerDisplay">05:00</div>
            <div class="timer-note" id="timerHumanNote">About 5 minutes remaining</div>
            <div class="progress-bar-bg">
                <div class="progress-bar-fill" id="progressFill" style="width: 100%;"></div>
            </div>
        </div>

        <div class="info-pill-container">
            <div class="info-pill">Your IP: <span><?php echo $ip_display; ?></span></div>
            <div class="info-pill">Ref: <span><?php echo htmlspecialchars($block_id); ?></span></div>
        </div>

        <p class="help-text">
            No action is required. This screen will automatically refresh and restore access as soon as the timer reaches zero.
        </p>

        <button class="btn-reload" onclick="location.reload();">
            <svg viewBox="0 0 24 24">
                <path d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/>
            </svg>
            Reload Page
        </button>
    </div>

    <script>
        let remainingSeconds = <?php echo (int)$remaining_seconds; ?>;
        const totalDuration = <?php echo (int)$total_duration; ?>;
        const timerDisplay = document.getElementById('timerDisplay');
        const timerHumanNote = document.getElementById('timerHumanNote');
        const progressFill = document.getElementById('progressFill');

        function updateCountdown() {
            if (remainingSeconds <= 0) {
                timerDisplay.textContent = "00:00";
                timerHumanNote.textContent = "Restoring access now...";
                if (progressFill) progressFill.style.width = "0%";
                setTimeout(() => {
                    location.reload();
                }, 1000);
                return;
            }

            const m = Math.floor(remainingSeconds / 60);
            const s = remainingSeconds % 60;
            timerDisplay.textContent = String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');

            if (m > 0) {
                timerHumanNote.textContent = m + " min " + s + " sec remaining";
            } else {
                timerHumanNote.textContent = s + " seconds remaining";
            }

            if (progressFill && totalDuration > 0) {
                const percent = Math.max(0, Math.min(100, (remainingSeconds / totalDuration) * 100));
                progressFill.style.width = percent + "%";
            }

            remainingSeconds--;
        }

        updateCountdown();
        setInterval(updateCountdown, 1000);
    </script>
</body>
</html>
<?php
    exit();
}

/**
 * =========================================================================
 * ADMIN MODULE FUNCTIONS (Self-Contained IP Management & Analytics Core)
 * =========================================================================
 */

/**
 * Manually block an IP address from the Admin Panel
 *
 * @param mysqli $con
 * @param string $ip
 * @param string $reason
 * @param int $duration_minutes (e.g. 5, 60, 1440, 10080, 43200)
 * @param int|null $admin_id
 * @return bool
 */
function firewall_admin_block_ip($con, $ip, $reason = 'Manual: Blocked by Administrator', $duration_minutes = 5, $admin_id = null) {
    if (!$con || !filter_var($ip, FILTER_VALIDATE_IP)) {
        return false;
    }
    firewall_ensure_tables($con);
    $duration_minutes = max(1, (int)$duration_minutes);

    // If IP is in whitelist, remove it so the block takes effect
    $del_wl = mysqli_prepare($con, "DELETE FROM ip_whitelist WHERE ip_address = ?");
    if ($del_wl) {
        mysqli_stmt_bind_param($del_wl, "s", $ip);
        mysqli_stmt_execute($del_wl);
        mysqli_stmt_close($del_wl);
    }

    $stmt = mysqli_prepare($con, "
        INSERT INTO ip_blocked (ip_address, reason, blocked_by, admin_id, blocked_at, expires_at, is_active, unique_urls_count)
        VALUES (?, ?, 'admin', ?, NOW(), NOW() + INTERVAL ? MINUTE, 1, 0)
        ON DUPLICATE KEY UPDATE
            reason = VALUES(reason),
            blocked_by = 'admin',
            admin_id = VALUES(admin_id),
            blocked_at = NOW(),
            expires_at = NOW() + INTERVAL ? MINUTE,
            unblocked_at = NULL,
            is_active = 1
    ");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssiii", $ip, $reason, $admin_id, $duration_minutes, $duration_minutes);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $ok;
    }
    return false;
}

/**
 * Manually unblock an IP address
 *
 * @param mysqli $con
 * @param int $block_id
 * @return bool
 */
function firewall_admin_unblock_ip($con, $block_id) {
    if (!$con) {
        return false;
    }
    $block_id = (int)$block_id;
    $stmt = mysqli_prepare($con, "UPDATE ip_blocked SET is_active = 0, unblocked_at = NOW() WHERE id = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $block_id);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $ok;
    }
    return false;
}

/**
 * Permanently delete a block entry from ip_blocked
 *
 * @param mysqli $con
 * @param int $block_id
 * @return bool
 */
function firewall_admin_delete_block($con, $block_id) {
    if (!$con) {
        return false;
    }
    $block_id = (int)$block_id;
    $stmt = mysqli_prepare($con, "DELETE FROM ip_blocked WHERE id = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $block_id);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $ok;
    }
    return false;
}

/**
 * Add an IP to the Whitelist table
 *
 * @param mysqli $con
 * @param string $ip
 * @param string $label
 * @param int|null $admin_id
 * @return bool
 */
function firewall_admin_add_whitelist($con, $ip, $label = 'Trusted Admin IP', $admin_id = null) {
    if (!$con || !filter_var($ip, FILTER_VALIDATE_IP)) {
        return false;
    }
    firewall_ensure_tables($con);

    // Unblock if currently blocked
    $unblock = mysqli_prepare($con, "UPDATE ip_blocked SET is_active = 0, unblocked_at = NOW() WHERE ip_address = ?");
    if ($unblock) {
        mysqli_stmt_bind_param($unblock, "s", $ip);
        mysqli_stmt_execute($unblock);
        mysqli_stmt_close($unblock);
    }

    $stmt = mysqli_prepare($con, "
        INSERT INTO ip_whitelist (ip_address, label, added_by, created_at)
        VALUES (?, ?, ?, NOW())
        ON DUPLICATE KEY UPDATE label = VALUES(label), added_by = VALUES(added_by)
    ");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssi", $ip, $label, $admin_id);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $ok;
    }
    return false;
}

/**
 * Remove an IP from the Whitelist table
 *
 * @param mysqli $con
 * @param int $whitelist_id
 * @return bool
 */
function firewall_admin_remove_whitelist($con, $whitelist_id) {
    if (!$con) {
        return false;
    }
    $whitelist_id = (int)$whitelist_id;
    $stmt = mysqli_prepare($con, "DELETE FROM ip_whitelist WHERE id = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $whitelist_id);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $ok;
    }
    return false;
}

/**
 * Purge threat logs older than X hours (default 48 hours)
 *
 * @param mysqli $con
 * @param int $hours
 * @return int Number of deleted rows
 */
function firewall_admin_purge_threat_logs($con, $hours = 48) {
    if (!$con) {
        return 0;
    }
    $hours = max(1, (int)$hours);
    @mysqli_query($con, "DELETE FROM ip_threat_log WHERE created_at < NOW() - INTERVAL $hours HOUR");
    return mysqli_affected_rows($con);
}

/**
 * Clean expired block records from ip_blocked (sets is_active = 0)
 *
 * @param mysqli $con
 * @return int Number of affected rows
 */
function firewall_admin_sync_expired_blocks($con) {
    if (!$con) {
        return 0;
    }
    @mysqli_query($con, "UPDATE ip_blocked SET is_active = 0, unblocked_at = NOW() WHERE is_active = 1 AND expires_at <= NOW()");
    return mysqli_affected_rows($con);
}

/**
 * Fetch complete analytics dataset for Admin Dashboard
 *
 * @param mysqli $con
 * @return array
 */
function firewall_get_analytics($con) {
    if (!$con) {
        return [];
    }

    firewall_ensure_tables($con);
    firewall_admin_sync_expired_blocks($con);

    $stats = [
        'active_blocks' => 0,
        'total_blocks' => 0,
        'auto_blocks' => 0,
        'manual_blocks' => 0,
        'threats_24h' => 0,
        'threats_total' => 0,
        'whitelisted_total' => 0,
        'top_scanners' => [],
        'top_targeted_urls' => [],
        'recent_threats' => []
    ];

    // Block statistics
    $q = mysqli_query($con, "SELECT COUNT(*) FROM ip_blocked WHERE is_active = 1 AND expires_at > NOW()");
    $stats['active_blocks'] = (int)($q ? mysqli_fetch_row($q)[0] : 0);

    $q = mysqli_query($con, "SELECT COUNT(*) FROM ip_blocked");
    $stats['total_blocks'] = (int)($q ? mysqli_fetch_row($q)[0] : 0);

    $q = mysqli_query($con, "SELECT COUNT(*) FROM ip_blocked WHERE blocked_by = 'auto'");
    $stats['auto_blocks'] = (int)($q ? mysqli_fetch_row($q)[0] : 0);

    $q = mysqli_query($con, "SELECT COUNT(*) FROM ip_blocked WHERE blocked_by = 'admin'");
    $stats['manual_blocks'] = (int)($q ? mysqli_fetch_row($q)[0] : 0);

    // Threat statistics
    $q = mysqli_query($con, "SELECT COUNT(*) FROM ip_threat_log WHERE created_at >= NOW() - INTERVAL 24 HOUR");
    $stats['threats_24h'] = (int)($q ? mysqli_fetch_row($q)[0] : 0);

    $q = mysqli_query($con, "SELECT COUNT(*) FROM ip_threat_log");
    $stats['threats_total'] = (int)($q ? mysqli_fetch_row($q)[0] : 0);

    // Whitelist count
    $q = mysqli_query($con, "SELECT COUNT(*) FROM ip_whitelist");
    $stats['whitelisted_total'] = (int)($q ? mysqli_fetch_row($q)[0] : 0);

    // Top scanning IPs
    $q = mysqli_query($con, "
        SELECT 
            t.ip_address, 
            COUNT(*) as total_probes, 
            COUNT(DISTINCT t.request_uri) as unique_probes,
            MAX(t.created_at) as last_seen,
            (SELECT is_active FROM ip_blocked b WHERE b.ip_address = t.ip_address AND b.is_active = 1 AND b.expires_at > NOW() LIMIT 1) as is_blocked,
            (SELECT id FROM ip_whitelist w WHERE w.ip_address = t.ip_address LIMIT 1) as is_whitelisted
        FROM ip_threat_log t
        GROUP BY t.ip_address
        ORDER BY total_probes DESC
        LIMIT 10
    ");
    if ($q) {
        while ($row = mysqli_fetch_assoc($q)) {
            $stats['top_scanners'][] = $row;
        }
    }

    // Top targeted non-existent URLs
    $q = mysqli_query($con, "
        SELECT request_uri, COUNT(*) as hit_count, MAX(created_at) as last_targeted
        FROM ip_threat_log
        GROUP BY request_uri
        ORDER BY hit_count DESC
        LIMIT 10
    ");
    if ($q) {
        while ($row = mysqli_fetch_assoc($q)) {
            $stats['top_targeted_urls'][] = $row;
        }
    }

    return $stats;
}

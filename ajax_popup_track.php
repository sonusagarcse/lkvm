<?php
// ajax_popup_track.php - Tracks Welcome Popup Impressions and Interactions
header('Content-Type: application/json; charset=UTF-8');
require_once 'connection.php';

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

// Get input data (JSON or Form POST)
$raw = file_get_contents('php://input');
$data = json_decode($raw, true);
if (!is_array($data)) {
    $data = $_POST;
}

$event_type = trim($data['event_type'] ?? '');
$valid_events = ['impression', 'click_primary', 'click_secondary', 'close'];

if (!in_array($event_type, $valid_events, true)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid event type']);
    exit();
}

$button_text = substr(trim($data['button_text'] ?? ''), 0, 100);
$target_url  = substr(trim($data['target_url'] ?? ''), 0, 255);
$page_url    = substr(trim($data['page_url'] ?? ''), 0, 255);
$user_agent  = substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255);
$ip_address  = $_SERVER['HTTP_CF_CONNECTING_IP'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
// Extract first IP if multiple are listed
if (strpos($ip_address, ',') !== false) {
    $parts = explode(',', $ip_address);
    $ip_address = trim($parts[0]);
}
$ip_address = substr($ip_address, 0, 45);

// Detect Device
$device = 'Desktop';
$ua = strtolower($user_agent);
if (preg_match('/(tablet|ipad|playbook|silk)|(android(?!.*mobi))/i', $ua)) {
    $device = 'Tablet';
} elseif (preg_match('/(mobile|ipod|iphone|android|blackberry|iemobile|opera mini)/i', $ua)) {
    $device = 'Mobile';
}

// Detect Browser
$browser = 'Unknown';
if (preg_match('/edg/i', $ua)) {
    $browser = 'Edge';
} elseif (preg_match('/chrome/i', $ua)) {
    $browser = 'Chrome';
} elseif (preg_match('/safari/i', $ua)) {
    $browser = 'Safari';
} elseif (preg_match('/firefox/i', $ua)) {
    $browser = 'Firefox';
} elseif (preg_match('/opera|opr/i', $ua)) {
    $browser = 'Opera';
}

if ($con) {
    $stmt = $con->prepare("INSERT INTO popup_analytics (event_type, button_text, target_url, page_url, ip_address, user_agent, device, browser, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    if ($stmt) {
        $stmt->bind_param("ssssssss", $event_type, $button_text, $target_url, $page_url, $ip_address, $user_agent, $device, $browser);
        $stmt->execute();
        $stmt->close();
        echo json_encode(['success' => true]);
        exit();
    }
}

echo json_encode(['success' => false, 'error' => 'Database error']);
exit();


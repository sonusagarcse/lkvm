<?php
/**
 * visitor_tracker.php — Tracks unique visits and active users.
 */
require_once __DIR__ . '/../connection.php';

function track_visitor($con) {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $page = $_SERVER['REQUEST_URI'] ?? '/';
    $agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $date = date('Y-m-d');
    $time = date('H:i:s');

    // Check if this IP has visited THIS SPECIFIC PAGE today
    $stmt = $con->prepare("SELECT id FROM visitor_logs WHERE ip_address = ? AND page_url = ? AND visit_date = ? LIMIT 1");
    $stmt->bind_param("sss", $ip, $page, $date);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Already visited this page today, just update last activity
        $row = $result->fetch_assoc();
        $id = $row['id'];
        $upd = $con->prepare("UPDATE visitor_logs SET last_activity = CURRENT_TIMESTAMP WHERE id = ?");
        $upd->bind_param("i", $id);
        $upd->execute();
    } else {
        // New unique page visit for this IP today
        $ins = $con->prepare("INSERT INTO visitor_logs (ip_address, page_url, user_agent, visit_date, visit_time) VALUES (?, ?, ?, ?, ?)");
        $ins->bind_param("sssss", $ip, $page, $agent, $date, $time);
        $ins->execute();
    }
}

if (isset($con)) {
    track_visitor($con);
}
?>

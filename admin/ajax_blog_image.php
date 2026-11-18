<?php
// admin/ajax_blog_image.php - Secure Image Upload Handler for Summernote Editor
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../connection.php';

header('Content-Type: application/json; charset=utf-8');

// Ensure only authenticated admins can upload
if (!isset($_SESSION['admin_id']) || ($_SESSION['admin_type'] ?? null) != 1) {
    echo json_encode(['success' => false, 'error' => 'Session expired. Please log in again to upload images.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method. Only POST is allowed.']);
    exit();
}

if (!verify_csrf_token()) {
    echo json_encode(['success' => false, 'error' => 'CSRF validation failed. Please refresh the page.']);
    exit();
}

if (!isset($_FILES['image']) || $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
    echo json_encode(['success' => false, 'error' => 'No image file was received.']);
    exit();
}

$uploadDir = '../uploads/';
$allowed_exts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
$allowed_mimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
$max_bytes = 10485760; // 10MB

// Enable automatic image resizing (max 1200x1200) and WebP compression (80% quality)
$res = secure_upload_file($_FILES['image'], $uploadDir, $allowed_exts, $allowed_mimes, $max_bytes, true, 1200, 1200, 80, 'blog_editor_');

if ($res['success']) {
    $imageUrl = $SITE_URL . '/uploads/' . $res['filename'];
    echo json_encode([
        'success' => true,
        'url' => $imageUrl,
        'filename' => $res['filename'],
        'original_size' => $res['original_size'] ?? 0,
        'reduced_size' => $res['reduced_size'] ?? 0
    ]);
} else {
    echo json_encode([
        'success' => false,
        'error' => $res['error'] ?? 'Upload failed.'
    ]);
}
exit();

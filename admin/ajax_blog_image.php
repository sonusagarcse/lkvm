<?php
// admin/ajax_blog_image.php - Secure Image Upload Handler for Summernote Editor
require_once 'includes/auth_check.php';
require_once '../connection.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
    exit();
}

if (!verify_csrf_token()) {
    echo json_encode(['success' => false, 'error' => 'CSRF validation failed.']);
    exit();
}

if (!isset($_FILES['image']) || $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
    echo json_encode(['success' => false, 'error' => 'No image uploaded.']);
    exit();
}

$uploadDir = '../uploads/';
$allowed_exts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
$allowed_mimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
$max_bytes = 10485760; // 10MB (will be compressed down to ~80-150KB)

// Enable automatic image resizing and WebP compression
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

<?php
require_once dirname(__DIR__) . '/connection.php';
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['student_id'];
$course_id = $_GET['course_id'] ?? 0;
$payment_id = $_GET['payment_id'] ?? '';

if ($course_id && $payment_id) {
    // Fetch course price for record
    $course_q = mysqli_query($con, "SELECT price FROM lms_courses WHERE id = $course_id");
    $course = mysqli_fetch_assoc($course_q);
    $amount = $course['price'] ?? 0;

    // Check if already enrolled
    $check = mysqli_query($con, "SELECT id FROM lms_enrollments WHERE user_id = '$user_id' AND course_id = $course_id");
    if (mysqli_num_rows($check) == 0) {
        // Insert enrollment
        $sql = "INSERT INTO lms_enrollments (user_id, course_id, amount, payment_id, status) 
                VALUES ('$user_id', $course_id, $amount, '$payment_id', 'completed')";
        if (mysqli_query($con, $sql)) {
            header("Location: dashboard.php?payment_success=1");
            exit;
        }
    } else {
        header("Location: dashboard.php");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
?>

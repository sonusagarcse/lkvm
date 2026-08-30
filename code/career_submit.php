<?php
// code/career_submit.php
include '../connection.php';

if (isset($_POST['submit'])) {
    // CSRF Protection Check
    if (!verify_csrf_token()) {
        die("CSRF validation failed.");
    }

    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $mob = mysqli_real_escape_string($con, $_POST['mob']);
    $date = date('d-m-Y');

    $resumePath = '';
    // Secure File Upload (Resume)
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] !== UPLOAD_ERR_NO_FILE) {
        $target_dir = "../uploads/resumes/";
        $allowed_exts = ['pdf', 'doc', 'docx'];
        $allowed_mimes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];
        
        $upload_result = secure_upload_file($_FILES['resume'], $target_dir, $allowed_exts, $allowed_mimes, 5242880); // max 5MB
        
        if ($upload_result['success']) {
            $resumePath = "uploads/resumes/" . $upload_result['filename'];
        } else {
            die("File upload failed: " . e($upload_result['error']));
        }
    }

    // Use prepared statement to insert
    $stmt = $con->prepare("INSERT INTO career (name, email, mob, resume, date) VALUES (?, ?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("sssss", $name, $email, $mob, $resumePath, $date);
        if ($stmt->execute()) {
            header("Location: ../career.php?msg=applied");
        } else {
            security_log('DB_ERROR', 'Insert career record failed: ' . $stmt->error);
            die("An error occurred. Please try again later.");
        }
        $stmt->close();
    } else {
        die("Database error. Please try again later.");
    }
} else {
    header("Location: ../career.php");
}
?>
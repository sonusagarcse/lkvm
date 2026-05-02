<?php
// code/career_submit.php
include '../connection.php';

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $mob = mysqli_real_escape_string($con, $_POST['mob']);
    $date = date('d-m-Y');

    $resumePath = '';
    // File Upload (Resume)
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] == 0) {
        $target_dir = "../uploads/resumes/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $file_name = time() . '_' . basename($_FILES["resume"]["name"]);
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["resume"]["tmp_name"], $target_file)) {
            $resumePath = "uploads/resumes/" . $file_name;
        }
    }

    $query = "INSERT INTO career (name, email, mob, resume, date) VALUES ('$name', '$email', '$mob', '$resumePath', '$date')";

    if (mysqli_query($con, $query)) {
        header("Location: ../career.php?msg=applied");
    } else {
        echo "Error: " . mysqli_error($con);
    }
} else {
    header("Location: ../career.php");
}
?>
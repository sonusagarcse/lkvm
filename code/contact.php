<?php
// code/contact.php
include '../connection.php';

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $sub = mysqli_real_escape_string($con, $_POST['sub']);
    $mob = mysqli_real_escape_string($con, $_POST['mob']);
    $gender = mysqli_real_escape_string($con, $_POST['gender']);
    $loc = mysqli_real_escape_string($con, $_POST['loc']);
    $msg = mysqli_real_escape_string($con, $_POST['msg']);

    // Check if fields are empty
    $gender = !empty($gender) ? $gender : 'Not Specified';
    $loc = !empty($loc) ? $loc : 'Not Specified';

    $date = date('d-m-Y');

    $query = "INSERT INTO contact (name, email, mob, sub, gender, loc, msg, date) VALUES ('$name', '$email', '$mob', '$sub', '$gender', '$loc', '$msg', '$date')";

    if (mysqli_query($con, $query)) {
        // Send Email Notifications
        require_once '../includes/smtp_helper.php';
        
        // 1. Notify User
        $tplUserQ = mysqli_query($con, "SELECT * FROM email_templates WHERE template_key='contact_user'");
        $tplUser = mysqli_fetch_assoc($tplUserQ);
        if ($tplUser && !empty($settings['smtp_user'])) {
            $uSub = str_replace(['{{org_name}}', '{{user_name}}', '{{subject}}'], [$settings['site_name'], $name, $sub], $tplUser['subject']);
            $uBody = str_replace(['{{org_name}}', '{{user_name}}', '{{subject}}'], [$settings['site_name'], $name, $sub], $tplUser['body']);
            send_smtp_email($email, $uSub, $uBody, $settings);
        }

        // 2. Notify Admin
        $tplAdminQ = mysqli_query($con, "SELECT * FROM email_templates WHERE template_key='contact_admin'");
        $tplAdmin = mysqli_fetch_assoc($tplAdminQ);
        $adminEmail = 'info@lkvmbihar.in';
        if ($tplAdmin && !empty($settings['smtp_user'])) {
            $aSub = str_replace(['{{org_name}}', '{{user_name}}', '{{user_email}}', '{{user_mobile}}', '{{subject}}', '{{location}}', '{{message}}'], [$settings['site_name'], $name, $email, $mob, $sub, $loc, $msg], $tplAdmin['subject']);
            $aBody = str_replace(['{{org_name}}', '{{user_name}}', '{{user_email}}', '{{user_mobile}}', '{{subject}}', '{{location}}', '{{message}}'], [$settings['site_name'], $name, $email, $mob, $sub, $loc, $msg], $tplAdmin['body']);
            send_smtp_email($adminEmail, $aSub, nl2br($aBody), $settings);
        }

        header("Location: ../contact_us.php?msg=send");
    } else {
        echo "Error: " . mysqli_error($con);
    }
} else {
    header("Location: ../contact_us.php");
}
?>
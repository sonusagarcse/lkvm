<?php
// code/contact.php - Hardened Contact Message Processor
require_once '../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    
    // 1. Anti-Bot Honeypot
    if (!empty($_POST['contact_hp_field'])) {
        if (function_exists('security_log')) {
            security_log('BOT_HONEYPOT_TRIGGERED', 'Spam bot caught in contact form honeypot.');
        }
        header("Location: ../contact_us.php?msg=send");
        exit();
    }

    // 2. CSRF Verification
    if (function_exists('verify_csrf_token') && !verify_csrf_token()) {
        if (function_exists('security_log')) {
            security_log('CSRF_ATTACK_BLOCKED', 'Contact form failed CSRF verification.');
        }
        die("<div style='font-family: sans-serif; text-align: center; padding: 50px;'>
                <h2>Security Verification Failed</h2>
                <p>CSRF token verification failed. Please refresh the page and try again.</p>
                <a href='../contact_us.php'>Back to Contact Us</a>
             </div>");
    }

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $sub = trim($_POST['sub'] ?? '');
    $mob = trim($_POST['mob'] ?? '');
    $gender = trim($_POST['gender'] ?? 'Not Specified');
    $loc = trim($_POST['loc'] ?? 'Not Specified');
    $msg = trim($_POST['msg'] ?? '');

    // 3. Spam / Link Detection in Name & Subject
    $spam_regex = '/(http:\/\/|https:\/\/|www\.|\.ru|\.xyz|\.top|promocode|promo\b|\$|\btake\s*->|telegram|whatsapp|bonus|casino|crypto|<script)/i';
    if (preg_match($spam_regex, $name) || preg_match($spam_regex, $sub)) {
        if (function_exists('security_log')) {
            security_log('SPAM_PAYLOAD_BLOCKED', "Spam promo detected in contact form from IP " . ($_SERVER['REMOTE_ADDR'] ?? '') . ": Name='$name'");
        }
        header("Location: ../contact_us.php?msg=invalid_input");
        exit();
    }

    // 4. Basic Name Validation
    if (!preg_match("/^[a-zA-Z\s\.\'-]{2,70}$/", $name)) {
        header("Location: ../contact_us.php?msg=invalid_name");
        exit();
    }

    $date = date('d-m-Y');

    // 5. Prepared INSERT
    $stmt = $con->prepare("INSERT INTO contact (name, email, mob, sub, gender, loc, msg, date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param("ssssssss", $name, $email, $mob, $sub, $gender, $loc, $msg, $date);
        
        if ($stmt->execute()) {
            $stmt->close();
            
            // Fast response to user before sending emails
            if (session_status() === PHP_SESSION_ACTIVE) {
                session_write_close();
            }
            if (function_exists('fastcgi_finish_request')) {
                header("Location: ../contact_us.php?msg=send");
                fastcgi_finish_request();
            } else {
                ignore_user_abort(true);
                while (ob_get_level() > 0) {
                    ob_end_clean();
                }
                header("Location: ../contact_us.php?msg=send");
                header("Connection: close");
                header("Content-Length: 0");
                ob_start();
                ob_end_flush();
                @flush();
            }

            // Send Email Notifications in background
            require_once '../includes/smtp_helper.php';
            try {
                // 1. Notify User
                $tplUserQ = mysqli_query($con, "SELECT * FROM email_templates WHERE template_key='contact_user'");
                $tplUser = $tplUserQ ? mysqli_fetch_assoc($tplUserQ) : null;
                if ($tplUser && !empty($settings['smtp_user'])) {
                    $uSub = str_replace(['{{org_name}}', '{{user_name}}', '{{subject}}'], [$settings['site_name'] ?? 'LKVM', $name, $sub], $tplUser['subject']);
                    $uBody = str_replace(['{{org_name}}', '{{user_name}}', '{{subject}}'], [$settings['site_name'] ?? 'LKVM', $name, $sub], $tplUser['body']);
                    @send_smtp_email($email, $uSub, $uBody, $settings);
                }

                // 2. Notify Admin
                $tplAdminQ = mysqli_query($con, "SELECT * FROM email_templates WHERE template_key='contact_admin'");
                $tplAdmin = $tplAdminQ ? mysqli_fetch_assoc($tplAdminQ) : null;
                $adminEmail = !empty($CONTACT_EMAIL) ? $CONTACT_EMAIL : 'info@lkvmbihar.in';
                if ($tplAdmin && !empty($settings['smtp_user'])) {
                    $aSub = str_replace(['{{org_name}}', '{{user_name}}', '{{user_email}}', '{{user_mobile}}', '{{subject}}', '{{location}}', '{{message}}'], [$settings['site_name'] ?? 'LKVM', $name, $email, $mob, $sub, $loc, $msg], $tplAdmin['subject']);
                    $aBody = str_replace(['{{org_name}}', '{{user_name}}', '{{user_email}}', '{{user_mobile}}', '{{subject}}', '{{location}}', '{{message}}'], [$settings['site_name'] ?? 'LKVM', $name, $email, $mob, $sub, $loc, $msg], $tplAdmin['body']);
                    @send_smtp_email($adminEmail, $aSub, nl2br($aBody), $settings);
                }
            } catch (\Throwable $e) {}

            exit();
        } else {
            $stmt->close();
            header("Location: ../contact_us.php?error=db_error");
            exit();
        }
    } else {
        header("Location: ../contact_us.php?error=system_error");
        exit();
    }
} else {
    header("Location: ../contact_us.php");
    exit();
}

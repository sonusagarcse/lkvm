<?php
// code/manage_member.php - Hardened Student Self-Registration Handler
require_once '../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['flag']) && $_GET['flag'] === 'add') {
    
    // 1. Anti-Bot Honeypot Trap (Hidden to human visitors, filled by automated scrapers/bots)
    if (!empty($_POST['website_hp_field'])) {
        if (function_exists('security_log')) {
            security_log('BOT_HONEYPOT_TRIGGERED', 'Automated spam bot trapped in student registration honeypot.');
        }
        // Silently exit with fake success so automated bots do not mutate and retry
        header("Location: ../register?msg=send");
        exit();
    }

    // 2. Strict CSRF Protection
    if (function_exists('verify_csrf_token') && !verify_csrf_token()) {
        if (function_exists('security_log')) {
            security_log('CSRF_ATTACK_BLOCKED', 'Student registration failed CSRF token verification.');
        }
        die("<div style='font-family: sans-serif; text-align: center; padding: 50px;'>
                <h2>Security Verification Failed</h2>
                <p>CSRF token verification failed. Please refresh the page and try again.</p>
                <a href='../register'>Back to Registration</a>
             </div>");
    }

    // 3. IP Rate Limiting (Max 3 submissions per 10 minutes per IP)
    $client_ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    $rate_limit_key = 'student_reg_rl_' . md5($client_ip);
    $now = time();
    if (!isset($_SESSION[$rate_limit_key]) || !is_array($_SESSION[$rate_limit_key])) {
        $_SESSION[$rate_limit_key] = [];
    }
    // Purge timestamps older than 600s (10 minutes)
    $_SESSION[$rate_limit_key] = array_filter($_SESSION[$rate_limit_key], function($t) use ($now) {
        return ($now - $t) < 600;
    });
    if (count($_SESSION[$rate_limit_key]) >= 3) {
        if (function_exists('security_log')) {
            security_log('RATE_LIMIT_EXCEEDED', "IP $client_ip exceeded student registration rate limit (3/10m).");
        }
        die("<div style='font-family: sans-serif; text-align: center; padding: 50px;'>
                <h2>Too Many Requests</h2>
                <p>You have submitted multiple registration requests recently. Please wait a few minutes before trying again.</p>
                <a href='../register'>Back to Registration</a>
             </div>");
    }

    // 4. Input Collection and Sanitization
    $raw_name     = trim($_POST['name'] ?? '');
    $raw_father   = trim($_POST['father'] ?? '');
    $raw_mother   = trim($_POST['mother'] ?? '');
    $raw_mob      = trim($_POST['mob'] ?? '');
    $raw_email    = trim($_POST['email'] ?? '');
    $dob          = trim($_POST['dob'] ?? '');
    $gender       = trim($_POST['gender'] ?? 'Male');
    $category     = trim($_POST['category'] ?? 'GEN');
    $marital      = trim($_POST['marital_status'] ?? 'Unmarried');
    $qualification= trim($_POST['qualification'] ?? 'Graduation');
    $aadhar       = trim($_POST['aadhar'] ?? '');
    $state        = trim($_POST['state'] ?? 'Bihar');
    $dis          = trim($_POST['dis'] ?? '');
    $pincode      = trim($_POST['pincode'] ?? '');
    $wat_no       = trim($_POST['wat_no'] ?? '');
    $pass         = trim($_POST['pass'] ?? '');
    $c_pass       = trim($_POST['c_pass'] ?? '');
    $address      = trim($_POST['address'] ?? '');
    $bid          = intval($_POST['bid'] ?? 0);
    $program      = intval($_POST['program'] ?? 0);
    $course       = intval($_POST['course'] ?? 0);

    // 5. Anti-Spam / URL Injection Detection (Student & Parent names should NEVER contain URLs or promocode ads)
    $spam_regex = '/(http:\/\/|https:\/\/|www\.|\.com|\.ru|\.xyz|\.net|\.top|promocode|promo\b|\$|\btake\s*->|telegram|whatsapp|bonus|casino|crypto|invest\b|<script)/i';
    if (preg_match($spam_regex, $raw_name) || preg_match($spam_regex, $raw_father) || preg_match($spam_regex, $raw_mother) || preg_match($spam_regex, $address)) {
        if (function_exists('security_log')) {
            security_log('SPAM_PAYLOAD_BLOCKED', "Spam promo detected in student registration from IP $client_ip: Name='$raw_name'");
        }
        // Log to firewall threat log as suspicious
        if (function_exists('firewall_log_threat')) {
            firewall_log_threat($con, $_SERVER['REQUEST_URI'] ?? '/register', 1);
        }
        // Reject with general error
        header("Location: ../register?msg=invalid_input");
        exit();
    }

    // 6. Name Format Validation (Must only contain letters, spaces, dots, hyphens; 2 to 70 chars)
    if (!preg_match("/^[a-zA-Z\s\.\'-]{2,70}$/", $raw_name)) {
        header("Location: ../register?msg=invalid_name");
        exit();
    }
    if (!empty($raw_father) && !preg_match("/^[a-zA-Z\s\.\'-]{2,70}$/", $raw_father)) {
        header("Location: ../register?msg=invalid_father_name");
        exit();
    }
    if (!empty($raw_mother) && !preg_match("/^[a-zA-Z\s\.\'-]{2,70}$/", $raw_mother)) {
        header("Location: ../register?msg=invalid_mother_name");
        exit();
    }

    // 7. Mobile Number Validation (Indian 10-digit mobile)
    $clean_mob = preg_replace('/[^0-9]/', '', $raw_mob);
    if (strlen($clean_mob) < 10 || strlen($clean_mob) > 12) {
        header("Location: ../register?msg=invalid_mobile");
        exit();
    }

    // 8. Password Match Check
    if (empty($pass) || $pass !== $c_pass) {
        header("Location: ../register?msg=password_not_matched");
        exit();
    }

    // 9. Branch Existence Check
    if ($bid <= 0) {
        header("Location: ../register?msg=missing_branch");
        exit();
    }
    $branch_check = $con->prepare("SELECT id FROM branch WHERE id = ?");
    $branch_check->bind_param("i", $bid);
    $branch_check->execute();
    if ($branch_check->get_result()->num_rows === 0) {
        $branch_check->close();
        header("Location: ../register?msg=invalid_branch");
        exit();
    }
    $branch_check->close();

    // 10. Record Rate Limit Hit on Validated Submission
    $_SESSION[$rate_limit_key][] = $now;

    // 11. Auto-generate incrementing Roll/Registration values
    $query_last = mysqli_query($con, "SELECT username, srno, rollno FROM registration ORDER BY id DESC LIMIT 1");
    $last_username = 1001;
    $last_srno = 1;
    $last_rollno = 1;

    if ($query_last && mysqli_num_rows($query_last) > 0) {
        $last_row = mysqli_fetch_assoc($query_last);
        $last_username = intval($last_row['username']) + 1;
        $last_srno = intval($last_row['srno']) + 1;
        $last_rollno = intval($last_row['rollno']) + 1;
    }

    $asession = date('Y');
    $doj = date('Y-m-d');
    $reg_date = date('d-m-Y');
    
    // Generate unique registration number
    $regno = "LKVM1003783/" . $last_rollno . "/" . $asession;

    // Default parameters for new self-registrations
    $section = 0;
    $mcode = '';
    $caste = '';
    $hsrollno = '';
    $pmob = 0;
    $otp = '';
    $fee = '0';
    $status = 0; // Inactive by default, pending admin verification & approval
    $img = '';
    $signimg = '';
    $thumbimg = '';
    $idproofimg = '';
    $qualificationimg = '';
    $refer = '';
    $bfee = '250';
    $reg_type = 1;

    // Prepared INSERT statement
    $insert_stmt = $con->prepare("INSERT INTO registration (
        username, regno, srno, rollno, program, course, section, mcode, name, father, asession, mother, dob, 
        category, aadhar, qualification, marital_status, caste, hsrollno, pmob, doj, gender, email, mob, 
        state, dis, wat_no, pass, address, pincode, otp, fee, status, date, img, signimg, thumbimg, 
        idproofimg, qualificationimg, refer, bfee, reg_type, bid
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if ($insert_stmt) {
        $insert_stmt->bind_param(
            "isiiiisssssssssssssisssssssssisssssssssssii",
            $last_username, $regno, $last_srno, $last_rollno, $program, $course, $section, $mcode, $raw_name, $raw_father, $asession, $raw_mother, $dob,
            $category, $aadhar, $qualification, $marital, $caste, $hsrollno, $pmob, $doj, $gender, $raw_email, $clean_mob,
            $state, $dis, $wat_no, $pass, $address, $pincode, $otp, $fee, $status, $reg_date, $img, $signimg, $thumbimg,
            $idproofimg, $qualificationimg, $refer, $bfee, $reg_type, $bid
        );

        if ($insert_stmt->execute()) {
            $insert_stmt->close();
            header("Location: ../register?msg=send");
            exit();
        } else {
            if (function_exists('security_log')) {
                security_log('DB_ERROR', 'Student registration execute failed: ' . $insert_stmt->error);
            }
            $insert_stmt->close();
            header("Location: ../register?msg=db_error");
            exit();
        }
    } else {
        if (function_exists('security_log')) {
            security_log('DB_ERROR', 'Student registration prepare failed: ' . $con->error);
        }
        header("Location: ../register?msg=system_error");
        exit();
    }
} else {
    header("Location: ../register");
    exit();
}

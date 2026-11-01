<?php
// code/career_submit.php - Secure Job Application Processor & Email Dispatcher
require_once '../connection.php';
require_once '../includes/smtp_helper.php';

if (isset($_POST['submit'])) {
    // CSRF Protection Check
    if (function_exists('verify_csrf_token') && !verify_csrf_token()) {
        die("<div style='font-family: sans-serif; text-align: center; padding: 50px;'>
                <h2>Security Verification Failed</h2>
                <p>CSRF token validation failed. Please return and try again.</p>
                <a href='../career'>Back to Career Page</a>
             </div>");
    }

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $mob = trim($_POST['mob'] ?? '');
    $position = trim($_POST['position'] ?? 'General Application');
    $qualification = trim($_POST['qualification'] ?? 'Graduate');
    $experience = trim($_POST['experience'] ?? 'Fresher');
    $location = trim($_POST['location'] ?? 'Bihar');
    $message = trim($_POST['message'] ?? '');
    $date = date('d-m-Y');

    // Basic sanitization
    if (empty($name) || empty($email) || empty($mob)) {
        header("Location: ../career?error=missing_fields");
        exit();
    }

    $resumePath = '';
    // Secure File Upload (Resume)
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] !== UPLOAD_ERR_NO_FILE) {
        $target_dir = "../uploads/resumes/";
        if (!is_dir($target_dir)) {
            @mkdir($target_dir, 0755, true);
        }

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
            header("Location: ../career?error=" . urlencode($upload_result['error']));
            exit();
        }
    }

    // Insert Application into Database
    $stmt = $con->prepare("
        INSERT INTO career (name, email, mob, position, qualification, experience, location, message, resume, date) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    if ($stmt) {
        $stmt->bind_param("ssssssssss", $name, $email, $mob, $position, $qualification, $experience, $location, $message, $resumePath, $date);
        
        if ($stmt->execute()) {
            $inserted_id = $stmt->insert_id;
            $stmt->close();

            // Prepare emails data
            $adminEmail = !empty($CONTACT_EMAIL) ? $CONTACT_EMAIL : ($settings['email'] ?? 'info@lkvmbihar.in');
            $adminSubject = "New Job Application: " . ($position ?: 'General Opening') . " - " . $name;
            $resumeUrl = !empty($resumePath) ? ($SITE_URL . "/" . $resumePath) : '';

            $adminHtml = "
            <div style='font-family: Arial, sans-serif; max-width: 620px; margin: 0 auto; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; background: #ffffff;'>
                <div style='background: #002147; color: #ffffff; padding: 24px; text-align: center;'>
                    <h2 style='margin: 0; font-size: 20px; font-weight: bold;'>New Job Application Received</h2>
                    <p style='margin: 6px 0 0; font-size: 13px; color: #fdc800;'>LKVM Career Portal • Application #" . $inserted_id . "</p>
                </div>
                <div style='padding: 24px;'>
                    <table style='width: 100%; border-collapse: collapse; font-size: 14px;'>
                        <tr style='border-bottom: 1px solid #f1f5f9;'><td style='padding: 9px 0; color: #64748b; width: 35%;'><strong>Applied Position:</strong></td><td style='padding: 9px 0; color: #002147; font-weight: bold;'>" . htmlspecialchars($position) . "</td></tr>
                        <tr style='border-bottom: 1px solid #f1f5f9;'><td style='padding: 9px 0; color: #64748b;'><strong>Applicant Name:</strong></td><td style='padding: 9px 0; color: #0f172a; font-weight: 600;'>" . htmlspecialchars($name) . "</td></tr>
                        <tr style='border-bottom: 1px solid #f1f5f9;'><td style='padding: 9px 0; color: #64748b;'><strong>Email Address:</strong></td><td style='padding: 9px 0;'><a href='mailto:" . htmlspecialchars($email) . "' style='color: #0284c7; text-decoration: none;'>" . htmlspecialchars($email) . "</a></td></tr>
                        <tr style='border-bottom: 1px solid #f1f5f9;'><td style='padding: 9px 0; color: #64748b;'><strong>Contact Phone:</strong></td><td style='padding: 9px 0;'><a href='tel:" . htmlspecialchars($mob) . "' style='color: #0284c7; text-decoration: none; font-weight: bold;'>" . htmlspecialchars($mob) . "</a></td></tr>
                        <tr style='border-bottom: 1px solid #f1f5f9;'><td style='padding: 9px 0; color: #64748b;'><strong>Qualification:</strong></td><td style='padding: 9px 0; color: #334155;'>" . htmlspecialchars($qualification) . "</td></tr>
                        <tr style='border-bottom: 1px solid #f1f5f9;'><td style='padding: 9px 0; color: #64748b;'><strong>Experience:</strong></td><td style='padding: 9px 0; color: #334155;'>" . htmlspecialchars($experience) . "</td></tr>
                        <tr style='border-bottom: 1px solid #f1f5f9;'><td style='padding: 9px 0; color: #64748b;'><strong>Location:</strong></td><td style='padding: 9px 0; color: #334155;'>" . htmlspecialchars($location) . "</td></tr>
                        <tr><td style='padding: 9px 0; color: #64748b;'><strong>Application Date:</strong></td><td style='padding: 9px 0; color: #334155;'>" . htmlspecialchars($date) . "</td></tr>
                    </table>

                    " . (!empty($message) ? "
                    <div style='margin-top: 18px; padding: 14px; background: #f8fafc; border-left: 4px solid #002147; border-radius: 4px; font-size: 13px; color: #334155;'>
                        <strong style='display:block; margin-bottom: 4px; color: #002147;'>Candidate Message / Note:</strong>
                        " . nl2br(htmlspecialchars($message)) . "
                    </div>" : "") . "

                    <div style='margin-top: 26px; text-align: center;'>
                        " . (!empty($resumeUrl) ? "<a href='" . $resumeUrl . "' target='_blank' style='display: inline-block; background: #16a34a; color: #ffffff; text-decoration: none; padding: 10px 22px; border-radius: 50px; font-weight: bold; font-size: 14px; margin-right: 10px;'>Download Resume</a>" : "<span style='color: #94a3b8; font-size: 13px;'>No resume uploaded</span>") . "
                        <a href='" . $SITE_URL . "/admin/applications' target='_blank' style='display: inline-block; background: #002147; color: #ffffff; text-decoration: none; padding: 10px 22px; border-radius: 50px; font-weight: bold; font-size: 14px;'>Open in Admin</a>
                    </div>
                </div>
            </div>";

            // 2. Prepare Applicant Confirmation Email
            $applicantSubject = "Application Received: " . ($position ?: 'Career Opportunity') . " - " . ($settings['site_name'] ?? 'LKVM');
            $applicantHtml = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; background: #ffffff;'>
                <div style='background: #002147; color: #ffffff; padding: 24px; text-align: center;'>
                    <h2 style='margin: 0; font-size: 20px; font-weight: bold;'>Application Acknowledged</h2>
                    <p style='margin: 6px 0 0; font-size: 13px; color: #fdc800;'>" . htmlspecialchars($settings['site_name'] ?? 'Lok Kala Vikas Manch') . "</p>
                </div>
                <div style='padding: 24px; color: #334155; line-height: 1.6; font-size: 14px;'>
                    <p>Dear <strong>" . htmlspecialchars($name) . "</strong>,</p>
                    <p>Thank you for your interest in joining <strong>" . htmlspecialchars($settings['site_name'] ?? 'Lok Kala Vikas Manch') . "</strong>. We have successfully received your application for the position of <strong>" . htmlspecialchars($position) . "</strong>.</p>
                    <p>Our Human Resources and Department Coordinators will review your resume and qualifications. If your profile matches our requirements, we will reach out to you directly via phone or email for the interview process.</p>
                    <div style='margin-top: 20px; padding: 12px; background: #f8fafc; border-radius: 8px;'>
                        <span style='font-size: 12px; color: #64748b;'>Reference ID: #" . $inserted_id . " | Submitted on: " . $date . "</span>
                    </div>
                    <p style='margin-top: 24px;'>Warm regards,<br><strong>HR & Talent Acquisition Team</strong><br>" . htmlspecialchars($settings['site_name'] ?? 'Lok Kala Vikas Manch') . "<br><a href='" . $SITE_URL . "' style='color: #0284c7; text-decoration: none;'>" . $SITE_URL . "</a></p>
                </div>
            </div>";

            // Fast Non-Blocking Response: Flush HTTP 302 Redirect to the visitor immediately
            if (session_status() === PHP_SESSION_ACTIVE) {
                session_write_close();
            }

            if (function_exists('fastcgi_finish_request')) {
                header("Location: ../career?msg=applied");
                fastcgi_finish_request();
            } else {
                ignore_user_abort(true);
                while (ob_get_level() > 0) {
                    ob_end_clean();
                }
                header("Location: ../career?msg=applied");
                header("Connection: close");
                header("Content-Length: 0");
                ob_start();
                ob_end_flush();
                @flush();
            }

            // Dispatch emails asynchronously in the background so candidate response is never delayed
            try {
                @send_smtp_email($adminEmail, $adminSubject, $adminHtml, $settings);
                @send_smtp_email($email, $applicantSubject, $applicantHtml, $settings);
            } catch (\Throwable $e) {
                // Silently log if logger is available
                if (function_exists('security_log')) {
                    security_log('EMAIL_SEND_EXCEPTION', $e->getMessage());
                }
            }
            exit();
        } else {
            if (function_exists('security_log')) {
                security_log('DB_ERROR', 'Insert career record failed: ' . $stmt->error);
            }
            header("Location: ../career?error=db_error");
            exit();
        }
    } else {
        header("Location: ../career?error=system_error");
        exit();
    }
} else {
    header("Location: ../career");
    exit();
}

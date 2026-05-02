<?php
/**
 * process_donation.php — Razorpay Donation Payment Handler
 * Verifies payment signature and records donation to DB.
 */
require_once 'connection.php';
require_once 'includes/smtp_helper.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

$rzp_key_id     = $settings['razorpay_key_id'] ?? '';
$rzp_key_secret = $settings['razorpay_key_secret'] ?? '';

$action = $_POST['action'] ?? '';

// ── Step 1: Create Razorpay Order ───────────────────────────────────────────
if ($action === 'create_order') {
    $amount  = intval(floatval($_POST['amount'] ?? 0) * 100); // paise
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $phone   = trim($_POST['contact'] ?? '');

    if ($amount < 100) {
        echo json_encode(['status' => 'error', 'message' => 'Minimum donation is ₹1']);
        exit;
    }

    if (empty($rzp_key_id) || empty($rzp_key_secret)) {
        echo json_encode(['status' => 'error', 'message' => 'Payment gateway not configured. Please contact admin.']);
        exit;
    }

    $payload = json_encode([
        'amount'          => $amount,
        'currency'        => 'INR',
        'receipt'         => 'donate_' . time(),
        'payment_capture' => 1,
        'notes'           => ['name' => $name, 'email' => $email]
    ]);

    $ch = curl_init('https://api.razorpay.com/v1/orders');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $payload,
        CURLOPT_USERPWD        => "$rzp_key_id:$rzp_key_secret",
        CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
        CURLOPT_SSL_VERIFYPEER => false,
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200) {
        $order = json_decode($response, true);
        echo json_encode(['status' => 'success', 'order_id' => $order['id'], 'amount' => $amount]);
    } else {
        $err = json_decode($response, true);
        echo json_encode(['status' => 'error', 'message' => $err['error']['description'] ?? 'Order creation failed']);
    }
    exit;
}

// ── Step 2: Verify Payment Signature & Save Donation ───────────────────────
if ($action === 'verify_payment') {
    $order_id   = $_POST['razorpay_order_id'] ?? '';
    $payment_id = $_POST['razorpay_payment_id'] ?? '';
    $signature  = $_POST['razorpay_signature'] ?? '';

    // Verify HMAC SHA256 signature
    $expected = hash_hmac('sha256', $order_id . '|' . $payment_id, $rzp_key_secret);

    if (hash_equals($expected, $signature)) {
        // Save donation record
        $name    = $con->real_escape_string($_POST['name'] ?? '');
        $email   = $con->real_escape_string($_POST['email'] ?? '');
        $phone   = $con->real_escape_string($_POST['contact'] ?? '');
        $pan     = $con->real_escape_string($_POST['pan'] ?? '');
        $city    = $con->real_escape_string($_POST['city'] ?? '');
        $address = $con->real_escape_string($_POST['address'] ?? '');
        $pincode = $con->real_escape_string($_POST['pincode'] ?? '');
        $country = $con->real_escape_string($_POST['country'] ?? 'India');
        $amount  = floatval($_POST['amount_inr'] ?? 0);

        $con->query("
            INSERT INTO donations (name, email, mobile, pan, city, address, pincode, country, amount, payment_id, order_id, created_at)
            VALUES ('$name','$email','$phone','$pan','$city','$address','$pincode','$country',$amount,'$payment_id','$order_id',NOW())
        ");

        // ── Send Email Notification ───────────────────────────────────────────
        $tplQ = mysqli_query($con, "SELECT * FROM email_templates WHERE template_key='donation_thanks'");
        $tpl  = mysqli_fetch_assoc($tplQ);

        if ($tpl && !empty($settings['smtp_user'])) {
            $msgSubject = $tpl['subject'];
            $msgBody    = $tpl['body'];

            // Replace Placeholders
            $placeholders = [
                '{{donor_name}}' => $name,
                '{{amount}}'     => number_format($amount, 2),
                '{{payment_id}}' => $payment_id,
                '{{date}}'       => date('d M Y'),
                '{{org_name}}'   => $settings['site_name'] ?? 'Our Organization',
                '{{org_email}}'  => $settings['email'] ?? ''
            ];

            foreach ($placeholders as $key => $val) {
                $msgSubject = str_replace($key, $val, $msgSubject);
                $msgBody    = str_replace($key, $val, $msgBody);
            }

            // Send Email
            $emailResult = send_smtp_email($email, $msgSubject, $msgBody, $settings);
        }

        // ── Step 3: Notify Admin of Donation ──────────────────────────────────
        $tplAdminQ = mysqli_query($con, "SELECT * FROM email_templates WHERE template_key='donation_admin'");
        $tplAdmin = mysqli_fetch_assoc($tplAdminQ);
        $adminEmail = 'info@lkvmbihar.in';

        if ($tplAdmin && !empty($settings['smtp_user'])) {
            $aSub = str_replace(
                ['{{donor_name}}', '{{amount}}', '{{currency}}', '{{payment_id}}', '{{donor_email}}', '{{donor_mobile}}', '{{org_name}}'],
                [$name, number_format($amount, 2), 'INR', $payment_id, $email, $phone, $settings['site_name'] ?? 'LKVM'],
                $tplAdmin['subject']
            );
            $aBody = str_replace(
                ['{{donor_name}}', '{{amount}}', '{{currency}}', '{{payment_id}}', '{{donor_email}}', '{{donor_mobile}}', '{{org_name}}'],
                [$name, number_format($amount, 2), 'INR', $payment_id, $email, $phone, $settings['site_name'] ?? 'LKVM'],
                $tplAdmin['body']
            );
            send_smtp_email($adminEmail, $aSub, $aBody, $settings);
        }

        echo json_encode(['status' => 'success', 'payment_id' => $payment_id]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Payment verification failed. Please contact support.']);
    }
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Unknown action']);

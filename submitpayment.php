<?php
require_once 'connection.php';

// Restrict Access-Control-Allow-Origin to safe/specific origins or dynamically handle (keeping strict security)
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$allowed_origins = [rtrim($SITE_URL, '/')];
if (in_array(rtrim($origin, '/'), $allowed_origins)) {
    header('Access-Control-Allow-Origin: ' . $origin);
} else {
    header('Access-Control-Allow-Origin: ' . rtrim($SITE_URL, '/'));
}
header('Access-Control-Allow-Methods: POST, GET');
header("Content-Type: application/json");
header("Accept: application/json");
header('Access-Control-Allow-Headers: Content-Type');

if (isset($_POST['action']) && $_POST['action'] === 'payOrder') {

  $razorpay_key = $RAZORPAY_KEY_ID;
  $razorpay_secret = $RAZORPAY_KEY_SECRET;

  if (empty($razorpay_key) || empty($razorpay_secret)) {
      echo json_encode(['res' => 'error', 'info' => 'Payment gateway not configured correctly.']);
      exit;
  }

  $authAPIkey = "Basic " . base64_encode($razorpay_key . ":" . $razorpay_secret);

  // Set transaction details
  $order_id = uniqid();

  $billing_name = filter_var($_POST['billing_name'] ?? '', FILTER_DEFAULT);
  $billing_mobile = filter_var($_POST['billing_mobile'] ?? '', FILTER_DEFAULT);
  $billing_email = filter_var($_POST['billing_email'] ?? '', FILTER_VALIDATE_EMAIL);
  $shipping_name = filter_var($_POST['shipping_name'] ?? '', FILTER_DEFAULT);
  $shipping_mobile = filter_var($_POST['shipping_mobile'] ?? '', FILTER_DEFAULT);
  $shipping_email = filter_var($_POST['shipping_email'] ?? '', FILTER_VALIDATE_EMAIL);
  $payAmount = floatval($_POST['payAmount'] ?? 0);

  if ($payAmount <= 0) {
      echo json_encode(['res' => 'error', 'info' => 'Invalid payment amount.']);
      exit;
  }

  $note = "Payment of amount Rs. " . $payAmount;

  $postdata = array(
    "amount" => intval($payAmount * 100),
    "currency" => "INR",
    "receipt" => $note,
    "notes" => array(
      "notes_key_1" => $note,
      "notes_key_2" => ""
    )
  );
  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.razorpay.com/v1/orders',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => json_encode($postdata),
    CURLOPT_HTTPHEADER => array(
      'Content-Type: application/json',
      'Authorization: ' . $authAPIkey
    ),
  ));

  $response = curl_exec($curl);

  if (curl_errno($curl)) {
      security_log('PAYMENT_ERROR', 'Curl error in order creation: ' . curl_error($curl));
      echo json_encode(['res' => 'error', 'info' => 'Unable to contact payment provider.']);
      curl_close($curl);
      exit;
  }

  curl_close($curl);
  $orderRes = json_decode($response);

  if (isset($orderRes->id)) {

    $rpay_order_id = $orderRes->id;

    $dataArr = array(
      'amount' => $payAmount,
      'description' => "Pay bill of Rs. " . $payAmount,
      'rpay_order_id' => $rpay_order_id,
      'name' => $billing_name,
      'email' => $billing_email,
      'mobile' => $billing_mobile
    );
    echo json_encode(['res' => 'success', 'order_number' => $order_id, 'userData' => $dataArr, 'razorpay_key' => $razorpay_key]);
    exit;
  } else {
    security_log('PAYMENT_ERROR', 'Razorpay Order response missing ID: ' . $response);
    echo json_encode(['res' => 'error', 'order_id' => $order_id, 'info' => 'Error with payment provider integration']);
    exit;
  }
} else {
  echo json_encode(['res' => 'error', 'info' => 'Invalid action.']);
  exit;
}
?>
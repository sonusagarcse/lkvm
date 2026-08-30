<?php
require_once dirname(__DIR__) . '/connection.php';
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

$course_id = $_GET['id'] ?? 0;
$user_id = $_SESSION['student_id'];

// Fetch course details
$course_q = mysqli_query($con, "SELECT id, title, price, is_free FROM lms_courses WHERE id = $course_id");
$course = mysqli_fetch_assoc($course_q);

if (!$course) {
    header("Location: index.php");
    exit;
}

// Check if already enrolled
$check = mysqli_query($con, "SELECT id FROM lms_enrollments WHERE user_id = '$user_id' AND course_id = $course_id");
if (mysqli_num_rows($check) > 0) {
    header("Location: dashboard.php");
    exit;
}

// HANDLE FREE ENROLLMENT
if ($course['is_free'] == 1 || $course['price'] <= 0) {
    $amount = 0;
    $pay_id = "FREE_ENROLL_" . time();
    $sql = "INSERT INTO lms_enrollments (user_id, course_id, amount, payment_id, status) 
            VALUES ('$user_id', '$course_id', '$amount', '$pay_id', 'completed')";
    if (mysqli_query($con, $sql)) {
        header("Location: learn.php?slug=" . urlencode($course['title']) . "&enrolled=success");
        // Note: Better to use slug if available, using title for now or fetching slug
        // Actually course has slug, let's fetch it
        $res = mysqli_query($con, "SELECT slug FROM lms_courses WHERE id = $course_id");
        $c_data = mysqli_fetch_assoc($res);
        header("Location: learn.php?slug=" . $c_data['slug'] . "&enrolled=success");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - <?php echo htmlspecialchars($course['title']); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8fafc; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .checkout-card { background: #fff; border-radius: 24px; padding: 40px; box-shadow: 0 20px 50px rgba(0,0,0,0.05); width: 100%; max-width: 500px; text-align: center; }
    </style>
</head>
<body>

<div class="checkout-card">
    <div class="mb-4">
        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex p-4 mb-4">
            <i class="fa fa-shopping-cart fs-1"></i>
        </div>
        <h2 class="fw-800">Secure Checkout</h2>
        <p class="text-muted">Finalize your enrollment in <strong><?php echo htmlspecialchars($course['title']); ?></strong></p>
    </div>

    <div class="bg-light p-4 rounded-4 mb-4">
        <div class="d-flex justify-content-between mb-2">
            <span class="text-muted">Course Price</span>
            <span class="fw-bold">₹<?php echo number_format($course['price']); ?></span>
        </div>
        <div class="d-flex justify-content-between">
            <span class="text-muted">GST (0%)</span>
            <span class="fw-bold">₹0</span>
        </div>
        <hr>
        <div class="d-flex justify-content-between">
            <span class="h5 fw-800 mb-0">Total Amount</span>
            <span class="h5 fw-800 text-primary mb-0">₹<?php echo number_format($course['price']); ?></span>
        </div>
    </div>

    <button id="rzp-button1" class="btn btn-primary w-100 py-3 rounded-pill fw-800 shadow-lg mb-3">Pay Now & Start Learning</button>
    <p class="small text-muted mb-0"><i class="fa fa-lock me-2"></i> Secured by Razorpay</p>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
var options = {
    "key": "<?php echo $RAZORPAY_KEY_ID; ?>",
    "amount": "<?php echo $course['price'] * 100; ?>", 
    "currency": "INR",
    "name": "LKVM Academy",
    "description": "Enrollment for <?php echo htmlspecialchars($course['title']); ?>",
    "handler": function (response){
        // Redirect to success handler
        window.location.href = "process_payment.php?course_id=<?php echo $course_id; ?>&payment_id=" + response.razorpay_payment_id;
    },
    "prefill": {
        "name": "<?php echo $_SESSION['student_name'] ?? ''; ?>",
        "email": "<?php echo $_SESSION['student_email'] ?? ''; ?>"
    },
    "theme": {
        "color": "#6366f1"
    }
};
var rzp1 = new Razorpay(options);
document.getElementById('rzp-button1').onclick = function(e){
    rzp1.open();
    e.preventDefault();
}
</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</body>
</html>

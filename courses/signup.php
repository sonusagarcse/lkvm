<?php
require_once dirname(__DIR__) . '/connection.php';
session_start();

if (isset($_SESSION['student_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";
if (isset($_POST['signup'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    
    $check = mysqli_query($con, "SELECT id FROM lms_students WHERE email = '$email'");
    if (mysqli_num_rows($check) > 0) {
        $error = "Email already exists!";
    } else {
        $sql = "INSERT INTO lms_students (name, email, password, phone) VALUES ('$name', '$email', '$password', '$phone')";
        if (mysqli_query($con, $sql)) {
            $_SESSION['student_id'] = mysqli_insert_id($con);
            $_SESSION['student_name'] = $name;
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Our Academy - Signup</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --primary: #6366f1; --dark: #0f172a; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .auth-card { background: #fff; border-radius: 24px; padding: 50px; box-shadow: 0 20px 50px rgba(0,0,0,0.05); width: 100%; max-width: 500px; }
        .form-control { padding: 12px 20px; border-radius: 12px; border: 1px solid #e2e8f0; font-weight: 500; }
        .form-control:focus { box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1); border-color: var(--primary); }
        .btn-primary { padding: 12px; border-radius: 12px; font-weight: 700; background: var(--primary); border: none; }
    </style>
</head>
<body>

<div class="auth-card">
    <div class="text-center mb-5">
        <h2 class="fw-800">Create <span class="text-primary">Account</span></h2>
        <p class="text-muted">Join thousands of students learning today.</p>
    </div>

    <?php if($error): ?>
        <div class="alert alert-danger rounded-4 border-0 small mb-4"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label fw-bold">Full Name</label>
            <input type="text" name="name" class="form-control" placeholder="John Doe" required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Email Address</label>
            <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Phone Number</label>
            <input type="text" name="phone" class="form-control" placeholder="+91 00000 00000" required>
        </div>
        <div class="mb-4">
            <label class="form-label fw-bold">Password</label>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>
        <button type="submit" name="signup" class="btn btn-primary w-100 mb-4 shadow-lg">Sign Up</button>
        <p class="text-center text-muted mb-0">Already have an account? <a href="login.php" class="text-primary fw-bold text-decoration-none">Login</a></p>
    </form>
</div>

</body>
</html>

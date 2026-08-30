<?php
require_once dirname(__DIR__) . '/connection.php';
session_start();

if (isset($_SESSION['student_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";
if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = $_POST['password'];
    
    $query = "SELECT * FROM lms_students WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($con, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])) {
            $_SESSION['student_id'] = $user['id'];
            $_SESSION['student_name'] = $user['name'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "Email not found!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login - Academy</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --primary: #6366f1; --dark: #0f172a; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .auth-card { background: #fff; border-radius: 24px; padding: 50px; box-shadow: 0 20px 50px rgba(0,0,0,0.05); width: 100%; max-width: 450px; }
        .form-control { padding: 12px 20px; border-radius: 12px; border: 1px solid #e2e8f0; font-weight: 500; }
        .form-control:focus { box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1); border-color: var(--primary); }
        .btn-primary { padding: 12px; border-radius: 12px; font-weight: 700; background: var(--primary); border: none; }
    </style>
</head>
<body>

<div class="auth-card">
    <div class="text-center mb-5">
        <h2 class="fw-800">Welcome <span class="text-primary">Back</span></h2>
        <p class="text-muted">Login to continue your learning journey.</p>
    </div>

    <?php if($error): ?>
        <div class="alert alert-danger rounded-4 border-0 small mb-4"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label fw-bold">Email Address</label>
            <input type="email" name="email" class="form-control" placeholder="name@example.com" required>
        </div>
        <div class="mb-4">
            <div class="d-flex justify-content-between">
                <label class="form-label fw-bold">Password</label>
                <a href="#" class="text-primary small fw-bold text-decoration-none">Forgot?</a>
            </div>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>
        <button type="submit" name="login" class="btn btn-primary w-100 mb-4 shadow-lg">Log In</button>
        <p class="text-center text-muted mb-0">Don't have an account? <a href="signup.php" class="text-primary fw-bold text-decoration-none">Join Now</a></p>
    </form>
</div>

</body>
</html>

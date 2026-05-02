<?php
session_start();
// Check if user is already logged in
if (isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

// Database Connection
require_once '../connection.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    // Check in admin_login table
    $query = "SELECT * FROM admin_login WHERE email = '$email' AND status = 1";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        // Direct password comparison as per previous system (ensure security later if hash needed)
        // Previous system seemed to use plain text based on SQL dump 'LKVM@bihar_2023'
        if ($password == $row['pass']) {
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_name'] = $row['uname'];
            $_SESSION['admin_type'] = $row['utype'];

            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid Password";
        }
    } else {
        $error = "Invalid Email or Account Disabled";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - <?php echo $SITE_NAME; ?></title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body.auth-bg {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .auth-bg::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(67, 97, 238, 0.15) 0%, rgba(67, 97, 238, 0) 70%);
            top: -250px;
            right: -250px;
            z-index: 0;
        }

        .auth-bg::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(76, 201, 240, 0.1) 0%, rgba(76, 201, 240, 0) 70%);
            bottom: -200px;
            left: -200px;
            z-index: 0;
        }

        .auth-container {
            position: relative;
            z-index: 1;
        }

        .auth-card {
            background: rgba(255, 255, 255, 0.05) !important;
            backdrop-filter: blur(20px) !important;
            -webkit-backdrop-filter: blur(20px) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-radius: 24px !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5) !important;
        }

        .auth-icon-wrapper {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-color), var(--info-color));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 10px 20px rgba(67, 97, 238, 0.3);
        }

        .auth-title {
            color: #f8fafc;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .text-muted {
            color: #94a3b8 !important;
        }

        .form-label {
            color: #cbd5e1;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .input-group {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            overflow: hidden;
            transition: var(--transition);
        }

        .input-group:focus-within {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px var(--primary-light);
        }

        .input-group-text {
            background: transparent !important;
            border: none !important;
            color: #94a3b8 !important;
            padding-left: 1rem;
        }

        .form-control {
            background: transparent !important;
            border: none !important;
            color: #f8fafc !important;
            padding: 0.75rem 1rem 0.75rem 0.5rem;
        }

        .form-control::placeholder {
            color: #64748b;
        }

        .form-control:focus {
            box-shadow: none !important;
        }

        .btn-auth {
            background: linear-gradient(135deg, var(--primary-color), var(--info-color));
            border: none;
            padding: 0.8rem;
            border-radius: 12px;
            font-weight: 600;
            color: white;
            transition: var(--transition);
        }

        .btn-auth:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(67, 97, 238, 0.4);
            color: white;
        }

        .toggle-password {
            cursor: pointer;
            padding: 0 1rem;
            color: #94a3b8;
            transition: var(--transition);
        }

        .toggle-password:hover {
            color: #f8fafc;
        }
    </style>
</head>

<body class="auth-bg">

    <div class="container auth-container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="card auth-card border-0">
                    <div class="card-body p-4 p-md-5 text-center">
                        <div class="auth-icon-wrapper">
                            <i class="fas fa-shield-alt fa-2x text-white"></i>
                        </div>
                        <h2 class="auth-title mb-2">Secure Login</h2>
                        <p class="text-muted small mb-4">Enter your credentials to access the LKVM dashboard</p>

                        <?php if ($error): ?>
                            <div class="alert alert-danger py-2 small border-0 bg-danger bg-opacity-10 text-danger mb-4">
                                <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <div class="mb-3 text-start">
                                <label class="form-label">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" name="email" class="form-control" placeholder="admin@lkvm.in"
                                        required value="<?php echo htmlspecialchars($email ?? 'info@lkvmbihar.in'); ?>">
                                </div>
                            </div>

                            <div class="mb-4 text-start">
                                <label class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" name="password" id="login_pass" class="form-control"
                                        placeholder="••••••••" required>
                                    <span class="input-group-text toggle-password" onclick="togglePass()">
                                        <i class="fas fa-eye" id="pass_icon"></i>
                                    </span>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-auth w-100 mb-4">
                                <i class="fas fa-sign-in-alt me-2"></i> Log In
                            </button>

                            <div class="pt-2">
                                <a href="../index.php" class="text-decoration-none text-muted small transition">
                                    <i class="fas fa-arrow-left me-1"></i> Return to Website
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePass() {
            const pass = document.getElementById('login_pass');
            const icon = document.getElementById('pass_icon');
            if (pass.type === 'password') {
                pass.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                pass.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>

</html>
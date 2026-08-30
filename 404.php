<?php
require_once 'connection.php';
$page_title = "404 - Page Not Found";
?>
<!doctype html>
<html lang="en-US">
<head>
    <?php include('csslink.php'); ?>
    <style>
        .error-area {
            padding: 120px 0;
            background: #ffffff;
            min-height: 70vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        .error-area::before {
            content: '404';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 40vw;
            font-weight: 900;
            color: rgba(0, 33, 71, 0.03);
            z-index: 0;
            pointer-events: none;
        }
        .error-content {
            position: relative;
            z-index: 1;
            text-align: center;
        }
        .error-content h1 {
            font-size: 160px;
            font-weight: 900;
            margin-bottom: 10px;
            line-height: 1;
            color: #002147;
            letter-spacing: -8px;
            display: inline-block;
            position: relative;
        }
        .error-content h1 span {
            color: #fdc800;
        }
        .error-content h2 {
            font-size: 42px;
            font-weight: 800;
            color: #002147;
            margin-bottom: 25px;
            letter-spacing: -1px;
        }
        .error-content p {
            font-size: 18px;
            color: #64748b;
            max-width: 600px;
            margin: 0 auto 45px;
            line-height: 1.8;
        }
        .error-actions {
            display: flex;
            gap: 20px;
            justify-content: center;
        }
        .btn-aesthetic {
            padding: 16px 45px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 16px;
            text-decoration: none;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        .btn-primary-aesthetic {
            background: #002147;
            color: #fff !important;
            box-shadow: 0 10px 25px rgba(0, 33, 71, 0.2);
        }
        .btn-primary-aesthetic:hover {
            background: #fdc800;
            color: #002147 !important;
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(253, 200, 0, 0.3);
        }
        .btn-outline-aesthetic {
            border: 2px solid #002147;
            color: #002147 !important;
        }
        .btn-outline-aesthetic:hover {
            background: #002147;
            color: #fff !important;
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 33, 71, 0.15);
        }
        @media (max-width: 768px) {
            .error-content h1 { font-size: 100px; letter-spacing: -4px; }
            .error-content h2 { font-size: 28px; }
            .error-actions { flex-direction: column; gap: 15px; }
            .btn-aesthetic { width: 100%; justify-content: center; }
        }
    </style>
</head>
<body class="error404 theme-eikra header-style-9 footer-style-2">
    <div id="page" class="site">
        <?php include('header.php'); ?>
        
        <main id="content" class="site-content">
            <div class="error-area">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="error-content">
                                <h1>4<span>0</span>4</h1>
                                <h2>Lost in Space?</h2>
                                <p>We can't seem to find the page you're looking for. It might have been moved or deleted. Let's get you back on track!</p>
                                <div class="error-actions">
                                    <a href="<?php echo $SITE_URL; ?>" class="btn-aesthetic btn-primary-aesthetic">
                                        <i class="fas fa-home"></i> Take Me Home
                                    </a>
                                    <a href="<?php echo $SITE_URL; ?>/contact_us.php" class="btn-aesthetic btn-outline-aesthetic">
                                        <i class="fas fa-envelope"></i> Contact Support
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <?php include('footer.php'); ?>
    </div>
</body>
</html>

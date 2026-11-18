<?php
// shipping_policy.php - Official Shipping & Delivery Policy
$page_title = "Shipping & Delivery Policy";
require_once 'connection.php';
?>
<!doctype html>
<html lang="en-US">
<head>
    <?php include('csslink.php'); ?>
    <title>Shipping &amp; Delivery Policy - <?php echo $SITE_TITLE; ?></title>
    <meta name="description" content="Shipping and Fulfillment Policy for student study kits, course materials, certificates, and publications at Lok Kala Vikas Manch (LKVM).">
    <style id='eikra-style-inline-css' type='text/css'>
        .entry-banner {
            background: linear-gradient(rgba(0, 33, 71, 0.88), rgba(0, 33, 71, 0.92)), url('images/banner.jpg') no-repeat scroll center center / cover;
            padding: 70px 0 60px;
            color: #ffffff;
            text-align: center;
        }
        .policy-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 40px;
            margin-bottom: 40px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            line-height: 1.8;
            color: #334155;
            font-size: 15px;
        }
        .policy-card h2 {
            color: #002147;
            font-size: 22px;
            font-weight: 700;
            margin-top: 32px;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 2px solid #f1f5f9;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .policy-card h2 i {
            color: #fdc800;
            font-size: 20px;
        }
        .policy-card h2:first-of-type {
            margin-top: 0;
        }
        .policy-card p {
            margin-bottom: 16px;
            text-align: justify;
        }
        .policy-card ul {
            padding-left: 20px;
            margin-bottom: 20px;
        }
        .policy-card ul li {
            margin-bottom: 8px;
        }
        .policy-notice-box {
            background: #f8fafc;
            border-left: 4px solid #002147;
            padding: 18px 22px;
            border-radius: 8px;
            margin: 25px 0;
        }
        .policy-badge {
            background: #e0f2fe;
            color: #0369a1;
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 700;
            display: inline-block;
            margin-bottom: 15px;
        }
    </style>
</head>
<body class="home page-template-default page wp-embed-responsive theme-eikra header-style-9 footer-style-2 has-topbar no-sidebar">
    <div id="page" class="site">
        <?php include('header.php'); ?>

        <div id="content" class="site-content">
            <div class="entry-banner">
                <div class="container">
                    <div class="entry-banner-content">
                        <span class="policy-badge"><i class="fas fa-truck me-1"></i> Fulfillment Standards</span>
                        <h1 class="entry-title" style="color: #ffffff; font-size: 36px; font-weight: 800; margin-bottom: 10px;">Shipping &amp; Delivery Policy</h1>
                        <div class="breadcrumb-area">
                            <div class="entry-breadcrumb">
                                <a href="<?php echo $SITE_URL; ?>" style="color: #fdc800;">Home</a> &gt; <span>Shipping Policy</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-area py-5" style="background: #f8fafc;">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-10">
                            <div class="policy-card">
                                <div class="d-flex justify-content-between align-items-center flex-wrap pb-3 border-bottom mb-4">
                                    <span class="text-muted small"><i class="far fa-calendar-alt me-1"></i> Last Updated: <?php echo date('F d, Y'); ?></span>
                                    <span class="text-muted small"><i class="fas fa-boxes me-1"></i> Dispatch &amp; Digital Fulfillment</span>
                                </div>

                                <div class="policy-notice-box">
                                    <strong style="color: #002147; display: block; margin-bottom: 6px;"><i class="fas fa-info-circle me-1"></i> Delivery Scope:</strong>
                                    Lok Kala Vikas Manch (LKVM) delivers educational services, digital course materials, student kits, books, and authorized certificates across Bihar and throughout India.
                                </div>

                                <h2><i class="fas fa-cloud-download-alt"></i> 1. Digital Services &amp; Electronic Delivery</h2>
                                <p>Most educational resources provided by LKVM are fulfilled electronically:</p>
                                <ul>
                                    <li><strong>LMS &amp; Portal Credentials:</strong> Upon successful enrollment or donation, login credentials and electronic receipts are delivered instantly to your registered email address and mobile number.</li>
                                    <li><strong>Digital Certificates &amp; Verification:</strong> Authorized course completion certificates and marksheets are generated digitally and made accessible via our online student verification portal within <strong>7 to 14 days</strong> of examination completion.</li>
                                    <li><strong>E-Books &amp; Study Notes:</strong> Digital study materials and curriculum guidelines are accessible directly via the online student portal.</li>
                                </ul>

                                <h2><i class="fas fa-box"></i> 2. Physical Shipping of Study Kits &amp; Certificates</h2>
                                <p>For courses or programs requiring printed study kits, institutional identity cards, or physical certificates sent by post:</p>
                                <ul>
                                    <li><strong>Dispatch Timeline:</strong> Physical items are dispatched from our central office within <strong>3 to 5 business days</strong> following batch confirmation or formal certificate issuance.</li>
                                    <li><strong>Delivery Duration:</strong> Shipments are fulfilled via India Post (Speed Post / Registered Post) or reputable domestic courier partners. Standard delivery takes <strong>5 to 10 working days</strong> depending on the destination district within Bihar or other Indian states.</li>
                                    <li><strong>Tracking:</strong> Tracking consignment numbers are provided via SMS or email once parcels are dispatched.</li>
                                </ul>

                                <h2><i class="fas fa-map-marker-alt"></i> 3. Shipping Charges</h2>
                                <p>Standard course registration fees typically include initial study material shipping costs unless explicitly noted otherwise on the specific course enrollment page. In cases where express delivery or duplicate certificate re-issue is requested, nominal postal handling charges will be communicated in advance.</p>

                                <h2><i class="fas fa-envelope"></i> 4. Shipping Inquiries</h2>
                                <p>If you have not received your physical study kit or certificate within the expected delivery window, please contact our dispatch team:</p>
                                <ul>
                                    <li><strong>Head Office:</strong> <?php echo htmlspecialchars($ADDRESS); ?></li>
                                    <li><strong>Email:</strong> <a href="mailto:<?php echo htmlspecialchars($CONTACT_EMAIL); ?>"><?php echo htmlspecialchars($CONTACT_EMAIL); ?></a></li>
                                    <li><strong>Helpline:</strong> <a href="tel:<?php echo htmlspecialchars($CONTACT_MOBILE); ?>"><?php echo htmlspecialchars($CONTACT_MOBILE); ?></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include('footer.php'); ?>
    </div>
</body>
</html>

<?php
// terms_conditions.php - Official Terms & Conditions
$page_title = "Terms & Conditions";
require_once 'connection.php';
?>
<!doctype html>
<html lang="en-US">
<head>
    <?php include('csslink.php'); ?>
    <title>Terms &amp; Conditions - <?php echo $SITE_TITLE; ?></title>
    <meta name="description" content="Terms and Conditions for using Lok Kala Vikas Manch (LKVM) website, educational portals, courses, and digital services.">
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
                        <span class="policy-badge"><i class="fas fa-file-contract me-1"></i> User Agreement</span>
                        <h1 class="entry-title" style="color: #ffffff; font-size: 36px; font-weight: 800; margin-bottom: 10px;">Terms and Conditions</h1>
                        <div class="breadcrumb-area">
                            <div class="entry-breadcrumb">
                                <a href="<?php echo $SITE_URL; ?>" style="color: #fdc800;">Home</a> &gt; <span>Terms &amp; Conditions</span>
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
                                    <span class="text-muted small"><i class="far fa-calendar-alt me-1"></i> Effective Date: <?php echo date('F d, Y'); ?></span>
                                    <span class="text-muted small"><i class="fas fa-university me-1"></i> Lok Kala Vikas Manch (LKVM)</span>
                                </div>

                                <div class="policy-notice-box">
                                    <strong style="color: #002147; display: block; margin-bottom: 6px;"><i class="fas fa-handshake me-1"></i> Agreement to Terms:</strong>
                                    Welcome to <strong>Lok Kala Vikas Manch</strong>. These Terms and Conditions constitute a legally binding agreement between you and Lok Kala Vikas Manch regarding your access to and use of <strong><?php echo htmlspecialchars($SITE_URL); ?></strong> and associated digital educational services.
                                </div>

                                <h2><i class="fas fa-check-circle"></i> 1. Acceptance of Terms</h2>
                                <p>By accessing or using our website, submitting an application, or enrolling in any program, you confirm that you have read, understood, and agreed to be bound by these Terms and our Privacy Policy. If you do not agree with any part of these Terms, you must discontinue using our services immediately.</p>

                                <h2><i class="fas fa-user-graduate"></i> 2. Academic Enrollment &amp; Student Code of Conduct</h2>
                                <p>Students registering for vocational, computer, or digital literacy programs (including KYP, BCA, IT certifications) must adhere to the following rules:</p>
                                <ul>
                                    <li>All information supplied during admission or verification must be truthful, authentic, and complete.</li>
                                    <li>Students must maintain academic honesty, regular laboratory attendance, and respect for instructors and fellow learners.</li>
                                    <li>Any tampering with computer lab equipment, software licenses, or institutional property will lead to immediate cancellation of admission and appropriate disciplinary action.</li>
                                </ul>

                                <h2><i class="fas fa-briefcase"></i> 3. Job Applications &amp; Career Portal</h2>
                                <p>Candidates submitting job applications through our Career portal must ensure that submitted resumes, qualifications, and work experience details are accurate. LKVM reserves the right to reject applications or terminate employment at any stage if fraudulent credentials are discovered.</p>

                                <h2><i class="fas fa-laptop-code"></i> 4. Acceptable Website Use &amp; Security</h2>
                                <p>You agree not to use the website for any unlawful purpose or in any way that could damage, disable, overburden, or impair server functionality. Specifically, you agree NOT to:</p>
                                <ul>
                                    <li>Perform automated rapid scanning, scraping, vulnerability probing, or SQL injection attacks;</li>
                                    <li>Attempt to access unauthorized administrative directories or backend databases;</li>
                                    <li>Post or transmit defamatory, abusive, obscene, or fraudulent material;</li>
                                    <li>Circumvent or tamper with security firewalls or access controls.</li>
                                </ul>
                                <p>Our automated Application Layer Firewall detects rapid probing or malicious scans and enforces automatic temporary IP blocking to preserve system availability.</p>

                                <h2><i class="fas fa-copyright"></i> 5. Intellectual Property Rights</h2>
                                <p>The content, organization, graphics, design, compilation, digital study material, and software used on this site are the exclusive property of Lok Kala Vikas Manch or its licensed partners and are protected by applicable copyright, trademark, and intellectual property laws. Reproduction, redistribution, or modification of any material without prior written permission is strictly prohibited.</p>

                                <h2><i class="fas fa-ad"></i> 6. Third-Party Advertisements &amp; Google AdSense</h2>
                                <p>Advertisements displayed on this website are served by third-party advertising platforms, including Google AdSense. We do not endorse or make any representations about the products or services advertised. Any transactions between you and any third-party advertiser are solely your responsibility.</p>

                                <h2><i class="fas fa-credit-card"></i> 7. Payments &amp; Donations</h2>
                                <p>Donations made in support of our community welfare, education, and cultural preservation missions are processed securely through certified payment gateways. For details regarding transaction disputes, fee cancellations, or refunds, please review our separate <a href="<?php echo $SITE_URL; ?>/refund" style="color: #0284c7; font-weight: bold;">Refund &amp; Cancellation Policy</a>.</p>

                                <h2><i class="fas fa-gavel"></i> 8. Governing Law &amp; Jurisdiction</h2>
                                <p>These Terms shall be governed by and construed in accordance with the laws of India. Any dispute, claim, or controversy arising out of or relating to these Terms or the breach, termination, enforcement, or interpretation thereof shall be subject to the exclusive jurisdiction of the competent courts in <strong>Gaya / Patna, Bihar, India</strong>.</p>

                                <h2><i class="fas fa-envelope"></i> 9. Contact Us</h2>
                                <p>For any queries or formal notices regarding these Terms, please contact our administrative secretariat:</p>
                                <ul>
                                    <li><strong>Organization:</strong> Lok Kala Vikas Manch (LKVM)</li>
                                    <li><strong>Head Office:</strong> <?php echo htmlspecialchars($ADDRESS); ?></li>
                                    <li><strong>Email:</strong> <a href="mailto:<?php echo htmlspecialchars($CONTACT_EMAIL); ?>"><?php echo htmlspecialchars($CONTACT_EMAIL); ?></a></li>
                                    <li><strong>Phone:</strong> <a href="tel:<?php echo htmlspecialchars($CONTACT_MOBILE); ?>"><?php echo htmlspecialchars($CONTACT_MOBILE); ?></a></li>
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

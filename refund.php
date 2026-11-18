<?php
// refund.php - Official Refund & Cancellation Policy
$page_title = "Refund & Cancellation Policy";
require_once 'connection.php';
?>
<!doctype html>
<html lang="en-US">
<head>
    <?php include('csslink.php'); ?>
    <title>Refund &amp; Cancellation Policy - <?php echo $SITE_TITLE; ?></title>
    <meta name="description" content="Refund and Cancellation Policy for donations, student admission fees, and courses at Lok Kala Vikas Manch (LKVM).">
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
                        <span class="policy-badge"><i class="fas fa-undo-alt me-1"></i> Transparent Procedures</span>
                        <h1 class="entry-title" style="color: #ffffff; font-size: 36px; font-weight: 800; margin-bottom: 10px;">Refund &amp; Cancellation Policy</h1>
                        <div class="breadcrumb-area">
                            <div class="entry-breadcrumb">
                                <a href="<?php echo $SITE_URL; ?>" style="color: #fdc800;">Home</a> &gt; <span>Refund &amp; Cancellation</span>
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
                                    <span class="text-muted small"><i class="far fa-calendar-alt me-1"></i> Policy Updated: <?php echo date('F d, Y'); ?></span>
                                    <span class="text-muted small"><i class="fas fa-receipt me-1"></i> LKVM Financial Office</span>
                                </div>

                                <div class="policy-notice-box">
                                    <strong style="color: #002147; display: block; margin-bottom: 6px;"><i class="fas fa-info-circle me-1"></i> Policy Summary:</strong>
                                    Lok Kala Vikas Manch (LKVM) believes in complete transparency regarding all financial contributions, course admissions, and donation processing. Please review our refund guidelines below.
                                </div>

                                <h2><i class="fas fa-hand-holding-heart"></i> 1. Charitable Donations Policy</h2>
                                <p>As an educational and cultural development non-profit organization, voluntary donations made to Lok Kala Vikas Manch are utilized directly for community skill development, youth education, women empowerment, and cultural preservation initiatives.</p>
                                <ul>
                                    <li>Normally, charitable donations once executed are <strong>non-refundable</strong> as they are allocated to active welfare programs.</li>
                                    <li><strong>Duplicate / Erroneous Charges:</strong> In the event that a technical error occurs during payment processing (such as a double-charge or incorrect amount debited), the donor must notify us within <strong>7 business days</strong> of the transaction date.</li>
                                    <li>Upon verification of banking logs, any unintended duplicate donation will be refunded to the original payment source within <strong>5 to 10 working days</strong>.</li>
                                </ul>

                                <h2><i class="fas fa-user-graduate"></i> 2. Student Admission &amp; Course Fees</h2>
                                <ul>
                                    <li><strong>Cancellation Before Batch Commencement:</strong> If a student requests cancellation of enrollment in a paid vocational or computer training program at least <strong>3 days prior</strong> to the scheduled batch start date, a refund of 80% of the tuition fee will be processed (20% retained toward administrative processing).</li>
                                    <li><strong>After Batch Commencement:</strong> No refunds shall be entertained once classes have begun or study kits/login credentials have been issued.</li>
                                    <li><strong>Government Schemes (KYP / Free Schemes):</strong> Government-subsidized or free vocational schemes follow official Bihar Skill Development Mission (BSDM) regulations. Any refundable security deposits are disbursed strictly according to governmental guidelines.</li>
                                </ul>

                                <h2><i class="fas fa-sync-alt"></i> 3. Refund Request Procedure</h2>
                                <p>To submit a cancellation or refund inquiry, please follow these steps:</p>
                                <ul>
                                    <li>Send an email to <strong><a href="mailto:<?php echo htmlspecialchars($CONTACT_EMAIL); ?>"><?php echo htmlspecialchars($CONTACT_EMAIL); ?></a></strong> with the subject line <em>"Refund Request - [Your Name / Transaction ID]"</em>.</li>
                                    <li>Attach the transaction receipt, date of payment, payment gateway reference ID, and a brief description of the issue.</li>
                                    <li>Our accounts team will review and respond within <strong>48 to 72 business hours</strong>.</li>
                                </ul>

                                <h2><i class="fas fa-envelope"></i> 4. Contact Information</h2>
                                <p>For all billing and refund queries, please contact:</p>
                                <ul>
                                    <li><strong>Organization:</strong> Lok Kala Vikas Manch (LKVM)</li>
                                    <li><strong>Address:</strong> <?php echo htmlspecialchars($ADDRESS); ?></li>
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

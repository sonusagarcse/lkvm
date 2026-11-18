<?php
// privacy_policy.php - Google AdSense Compliant Privacy Policy
$page_title = "Privacy Policy";
require_once 'connection.php';
?>
<!doctype html>
<html lang="en-US">
<head>
    <?php include('csslink.php'); ?>
    <title>Privacy Policy - <?php echo $SITE_TITLE; ?></title>
    <meta name="description" content="Privacy Policy for Lok Kala Vikas Manch (LKVM). Information about how we collect, use, protect personal data, and details regarding Google AdSense, cookies, and user rights.">
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
            margin-top: 34px;
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
        .policy-card h3 {
            color: #1e293b;
            font-size: 18px;
            font-weight: 600;
            margin-top: 20px;
            margin-bottom: 8px;
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
        .adsense-highlight-box {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-left: 4px solid #3b82f6;
            padding: 20px;
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
                        <span class="policy-badge"><i class="fas fa-user-shield me-1"></i> Privacy & Data Protection</span>
                        <h1 class="entry-title" style="color: #ffffff; font-size: 36px; font-weight: 800; margin-bottom: 10px;">Privacy Policy</h1>
                        <div class="breadcrumb-area">
                            <div class="entry-breadcrumb">
                                <a href="<?php echo $SITE_URL; ?>" style="color: #fdc800;">Home</a> &gt; <span>Privacy Policy</span>
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
                                    <span class="text-muted small"><i class="fas fa-lock me-1"></i> SSL 256-bit Secure</span>
                                </div>

                                <div class="policy-notice-box">
                                    <strong style="color: #002147; display: block; margin-bottom: 6px;"><i class="fas fa-shield-alt me-1"></i> Our Privacy Commitment:</strong>
                                    At <strong>Lok Kala Vikas Manch (LKVM)</strong> (accessible via <strong><?php echo htmlspecialchars($SITE_URL); ?></strong>), the privacy of our visitors, students, donors, job applicants, and community members is of paramount importance to us. This Privacy Policy outlines the types of information we collect, how it is used, how we safeguard it, and our compliance with advertising standards and international data regulations.
                                </div>

                                <h2><i class="fas fa-info-circle"></i> 1. Who We Are</h2>
                                <p>Lok Kala Vikas Manch (LKVM) is a registered socio-cultural, educational, and vocational non-governmental organization headquartered in Bihar, India. We are dedicated to youth empowerment, computer training (including KYP & skill courses), community welfare, and folk art preservation across Bihar.</p>

                                <h2><i class="fas fa-database"></i> 2. Information We Collect</h2>
                                <p>We collect information in several ways when you interact with our website and services:</p>
                                <ul>
                                    <li><strong>Personal Details Provided Voluntarily:</strong> When you apply for a job via our Career portal, enroll in a course, make a donation, register for events, or contact us through our web forms, we may ask you for personal information such as your full name, email address, telephone/WhatsApp number, current district/city, educational qualifications, work experience, resume/CV documents, and cover messages.</li>
                                    <li><strong>Automated Log &amp; Device Data:</strong> Like most modern websites, our servers automatically collect non-personally identifiable log information when you browse our pages. This includes your Internet Protocol (IP) address, browser type and version, operating system, referring and exit URLs, date and time stamp, pages visited, and clickstream data.</li>
                                    <li><strong>Payment &amp; Donation Transactions:</strong> Financial donations and fee payments are processed securely through certified third-party payment gateways (such as Razorpay / UPI / Net Banking). LKVM does not store your credit/debit card numbers or net banking passwords on its servers.</li>
                                </ul>

                                <h2><i class="fas fa-cookie-bite"></i> 3. Cookies and Web Beacons</h2>
                                <p>Our website uses standard "cookies" (small text files placed on your device) to enhance user experience, remember preferences, measure website traffic, and maintain session security. You can instruct your web browser to refuse all cookies or indicate when a cookie is being sent. However, if you disable cookies, some portions of our service (such as student login or interactive forms) may not function properly.</p>

                                <div class="adsense-highlight-box">
                                    <h2 style="margin-top: 0; border: none; padding: 0; color: #1e40af;"><i class="fas fa-ad" style="color: #3b82f6;"></i> 4. Google AdSense &amp; Advertising Cookies (DoubleClick DART)</h2>
                                    <p>Google is one of our trusted third-party advertising partners on this website. Please review the following mandatory advertising disclosures:</p>
                                    <ul>
                                        <li><strong>DoubleClick DART Cookie:</strong> Google uses cookies, including the DoubleClick DART cookie, to serve advertisements to visitors on our site based upon their visit to <code><?php echo htmlspecialchars($_SERVER['HTTP_HOST'] ?? 'lkvmbihar.in'); ?></code> and other sites on the Internet.</li>
                                        <li><strong>Interest-Based &amp; Personalized Ads:</strong> These advertising cookies allow Google and its certified partners to display relevant advertisements to you based on your browsing interests and search interactions.</li>
                                        <li><strong>User Opt-Out Choice:</strong> You have the absolute right to opt out of the use of the DoubleClick DART cookie for interest-based advertising. You may manage your ad preferences or opt out by visiting the official <strong><a href="https://adssettings.google.com/" target="_blank" rel="noopener noreferrer" style="color: #1d4ed8; text-decoration: underline; font-weight: bold;">Google Ads Settings</a></strong>.</li>
                                        <li>Additionally, you can opt out of third-party vendor cookies across multiple advertising networks by visiting the <strong><a href="https://www.aboutads.info/choices/" target="_blank" rel="noopener noreferrer" style="color: #1d4ed8; text-decoration: underline; font-weight: bold;">Network Advertising Initiative (NAI) / AboutAds.info Consumer Choice Page</a></strong>.</li>
                                    </ul>
                                </div>

                                <h2><i class="fas fa-network-wired"></i> 5. Third-Party Advertisers and Links</h2>
                                <p>Third-party ad servers or ad networks use technologies like cookies, JavaScript, or Web Beacons that are sent directly to users' browsers within the advertisements that appear on our website. They automatically receive your IP address when this occurs. These technologies are used to measure the effectiveness of their advertising campaigns and/or to personalize the advertising content that you see.</p>
                                <p>Please note that <strong>Lok Kala Vikas Manch has no access to or control over these cookies</strong> that are used by third-party advertisers. Our Privacy Policy does not apply to other advertisers or websites. We advise you to consult the respective Privacy Policies of these third-party ad servers for more detailed information.</p>

                                <h2><i class="fas fa-tasks"></i> 6. How We Use Your Information</h2>
                                <p>We use the information we collect for legitimate purposes, including:</p>
                                <ul>
                                    <li>Processing job applications and contacting candidates for interviews;</li>
                                    <li>Registering students for digital literacy, KYP, and vocational courses;</li>
                                    <li>Issuing student certificates and official donation receipts;</li>
                                    <li>Improving website performance, content quality, and navigation structure;</li>
                                    <li>Defending our servers against malicious URL probing, automated scanning, and cyber threats through our Application Firewall;</li>
                                    <li>Communicating institutional announcements, examination schedules, and community events.</li>
                                </ul>

                                <h2><i class="fas fa-balance-scale"></i> 7. Data Protection Rights (India DPDP, GDPR &amp; CCPA)</h2>
                                <p>We respect your privacy rights under the <strong>Digital Personal Data Protection Act (DPDP Act 2023, India)</strong>, Information Technology Act 2000, and international data standards such as GDPR and CCPA:</p>
                                <ul>
                                    <li><strong>Right to Access:</strong> You have the right to request copies of your personal data stored with us.</li>
                                    <li><strong>Right to Rectification:</strong> You have the right to request that we correct any information you believe is inaccurate or incomplete.</li>
                                    <li><strong>Right to Erasure:</strong> You have the right to request that we erase your personal data under certain conditions.</li>
                                    <li><strong>Right to Withdraw Consent:</strong> Where processing is based on consent, you may withdraw your consent at any time.</li>
                                </ul>

                                <h2><i class="fas fa-child"></i> 8. Children's Online Privacy (COPPA Compliance)</h2>
                                <p>Protecting the privacy of young children is especially important to us. LKVM does not knowingly collect any personally identifiable information from children under the age of 13 without verified parental or institutional guardian consent. If you believe that your child has provided personal information on our website without your consent, please contact us immediately, and we will promptly delete such information from our records.</p>

                                <h2><i class="fas fa-shield-alt"></i> 9. Data Security</h2>
                                <p>We implement rigorous security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction. Our website utilizes 256-bit SSL (Secure Sockets Layer) encryption, strict database sanitization, CSRF tokens, and automated intrusion prevention firewalls. However, no electronic transmission over the Internet is 100% secure, and we encourage users to maintain caution.</p>

                                <h2><i class="fas fa-edit"></i> 10. Changes to This Privacy Policy</h2>
                                <p>We may update our Privacy Policy periodically to reflect technological, legal, or operational updates. Any revisions will be published on this page with an updated "Last Updated" timestamp. We encourage visitors to review this page periodically to stay informed.</p>

                                <h2><i class="fas fa-envelope-open-text"></i> 11. Contact &amp; Grievance Officer</h2>
                                <p>If you have any questions, feedback, or grievance requests regarding this Privacy Policy or our data practices, please contact our designated Grievance Team:</p>
                                <ul>
                                    <li><strong>Organization:</strong> Lok Kala Vikas Manch (LKVM)</li>
                                    <li><strong>Registered Office:</strong> <?php echo htmlspecialchars($ADDRESS); ?></li>
                                    <li><strong>Official Email:</strong> <a href="mailto:<?php echo htmlspecialchars($CONTACT_EMAIL); ?>"><?php echo htmlspecialchars($CONTACT_EMAIL); ?></a></li>
                                    <li><strong>Support Helpline:</strong> <a href="tel:<?php echo htmlspecialchars($CONTACT_MOBILE); ?>"><?php echo htmlspecialchars($CONTACT_MOBILE); ?></a></li>
                                    <li><strong>Contact Page:</strong> <a href="<?php echo htmlspecialchars($SITE_URL); ?>/contact_us"><?php echo htmlspecialchars($SITE_URL); ?>/contact_us</a></li>
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

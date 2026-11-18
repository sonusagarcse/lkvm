<?php
// disclaimer.php - Official Website Disclaimer
$page_title = "Disclaimer";
require_once 'connection.php';
?>
<!doctype html>
<html lang="en-US">
<head>
    <?php include('csslink.php'); ?>
    <title>Disclaimer - <?php echo $SITE_TITLE; ?></title>
    <meta name="description" content="Official website disclaimer for Lok Kala Vikas Manch (LKVM). Information regarding educational content, third-party advertisements, external links, and limitation of liability.">
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
                        <span class="policy-badge"><i class="fas fa-shield-alt me-1"></i> Legal Transparency</span>
                        <h1 class="entry-title" style="color: #ffffff; font-size: 36px; font-weight: 800; margin-bottom: 10px;">Website Disclaimer</h1>
                        <div class="breadcrumb-area">
                            <div class="entry-breadcrumb">
                                <a href="<?php echo $SITE_URL; ?>" style="color: #fdc800;">Home</a> &gt; <span>Disclaimer</span>
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
                                    <span class="text-muted small"><i class="fas fa-building me-1"></i> Lok Kala Vikas Manch (LKVM)</span>
                                </div>

                                <div class="policy-notice-box">
                                    <strong style="color: #002147; display: block; margin-bottom: 6px;"><i class="fas fa-info-circle me-1"></i> Notice to Visitors:</strong>
                                    Please read this Disclaimer carefully before using the website <strong><?php echo htmlspecialchars($SITE_URL); ?></strong> operated by Lok Kala Vikas Manch ("LKVM", "we", "us", or "our"). By accessing or browsing this website, you acknowledge and agree to the terms described below.
                                </div>

                                <h2><i class="fas fa-graduation-cap"></i> 1. Educational & General Information Purpose</h2>
                                <p>All information, study resources, course outlines, news, notifications, and materials provided on this website are published in good faith and solely for general educational, vocational, and informational purposes. Lok Kala Vikas Manch makes every effort to keep course curricula, examination guidelines, and institutional announcements current and accurate. However, we make no representations or warranties of any kind, express or implied, regarding the completeness, accuracy, reliability, or timeliness of any content published on this site.</p>

                                <h2><i class="fas fa-ad"></i> 2. Google AdSense & Third-Party Advertisements</h2>
                                <p>This website may display advertisements served by <strong>Google AdSense</strong> and other third-party advertising networks or programmatic exchanges. Please note:</p>
                                <ul>
                                    <li>Advertisements displayed on this website are automated by third-party advertising algorithms based on content context and visitor preferences.</li>
                                    <li>The appearance of any third-party advertisement does not constitute an endorsement, guarantee, recommendation, or warranty by Lok Kala Vikas Manch of the product, service, institution, or claim promoted in that advertisement.</li>
                                    <li>We do not control the content or claims made in third-party advertisements. Any interaction, correspondence, purchase, or contract you enter into with an advertiser found through this website is strictly between you and that third party.</li>
                                </ul>

                                <h2><i class="fas fa-external-link-alt"></i> 3. External Links Disclaimer</h2>
                                <p>Our website may contain links to external third-party websites (such as government skill portals, educational departments, banking gateways, or partner platforms) that are not operated, controlled, or maintained by Lok Kala Vikas Manch. While we strive to provide only quality and ethical links to relevant sources, we have no control over the content, security, nature, or availability of those external sites. The inclusion of any links does not necessarily imply a recommendation or endorsement of the views expressed within them.</p>

                                <h2><i class="fas fa-briefcase"></i> 4. No Guarantee of Employment or Certification Privileges</h2>
                                <p>Enrollment in or completion of vocational and computer training courses provided by LKVM is intended for skill enhancement, digital empowerment, and personal development. Participation in any training program does not guarantee employment, job placement, government recruitment, or specific financial earnings unless an explicit placement contract is executed under a specific governmental scheme.</p>

                                <h2><i class="fas fa-copyright"></i> 5. Intellectual Property & Fair Use</h2>
                                <p>All original text, organizational photographs, graphic banners, and syllabi created by Lok Kala Vikas Manch are the intellectual property of the organization. Logos or trademarks of third-party organizations, government schemes (such as Bihar Skill Development Mission, KYP), or software platforms referenced on this website are the property of their respective owners and are referenced under the doctrine of Fair Dealing for identification and informational purposes only.</p>

                                <h2><i class="fas fa-exclamation-triangle"></i> 6. Limitation of Liability</h2>
                                <p>In no event shall Lok Kala Vikas Manch, its office bearers, trustees, instructors, or staff be liable for any direct, indirect, incidental, consequential, or punitive damages arising out of your access to, use of, or inability to use this website, or any errors or omissions in the content provided herein.</p>

                                <h2><i class="fas fa-envelope"></i> 7. Contact Us</h2>
                                <p>If you have any questions, clarifications, or feedback regarding this Disclaimer, please reach out to us:</p>
                                <ul>
                                    <li><strong>Organization:</strong> Lok Kala Vikas Manch (LKVM)</li>
                                    <li><strong>Registered Address:</strong> <?php echo htmlspecialchars($ADDRESS); ?></li>
                                    <li><strong>Email:</strong> <a href="mailto:<?php echo htmlspecialchars($CONTACT_EMAIL); ?>"><?php echo htmlspecialchars($CONTACT_EMAIL); ?></a></li>
                                    <li><strong>Phone:</strong> <a href="tel:<?php echo htmlspecialchars($CONTACT_MOBILE); ?>"><?php echo htmlspecialchars($CONTACT_MOBILE); ?></a></li>
                                    <li><strong>Website:</strong> <a href="<?php echo htmlspecialchars($SITE_URL); ?>"><?php echo htmlspecialchars($SITE_URL); ?></a></li>
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

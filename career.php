<?php
// career.php - Public Job Openings & Career Portal
require_once 'connection.php';

$page_title = "Current Job Openings";

// Fetch Active Jobs
$jobs_query = mysqli_query($con, "SELECT * FROM jobs WHERE status = 1 ORDER BY id ASC");
$all_jobs = [];
if ($jobs_query) {
    while ($j = mysqli_fetch_assoc($jobs_query)) {
        $all_jobs[] = $j;
    }
}

// Pre-selected job
$selected_job_id = intval($_GET['job_id'] ?? 0);
$selected_position = trim($_GET['position'] ?? '');
if ($selected_job_id > 0) {
    foreach ($all_jobs as $j) {
        if ($j['id'] == $selected_job_id) {
            $selected_position = $j['title'];
            break;
        }
    }
}

$msg = $_GET['msg'] ?? '';
$error = $_GET['error'] ?? '';
?>
<!doctype html>
<html lang="en-US">
<head>
    <?php include('csslink.php'); ?>
    <title>Current Job Openings - <?php echo $SITE_TITLE; ?></title>
    <meta name="description" content="Browse through our open positions below. Click Apply Now to load your role directly into the application form.">
    <style id='eikra-style-inline-css' type='text/css'>
        .entry-banner {
            background: linear-gradient(rgba(0, 33, 71, 0.88), rgba(0, 33, 71, 0.92)), url('images/banner.jpg') no-repeat scroll center center / cover;
            padding: 75px 0 65px;
            color: #ffffff;
            text-align: center;
        }
        .career-badge {
            background: #fdc800;
            color: #002147;
            padding: 7px 20px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            margin-bottom: 16px;
            box-shadow: 0 4px 15px rgba(253, 200, 0, 0.4);
        }
        .career-title {
            font-family: Roboto, sans-serif;
            font-size: 38px;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 12px;
            letter-spacing: -0.5px;
        }
        .career-subtitle {
            font-family: Roboto, sans-serif;
            font-size: 16px;
            color: #e2e8f0;
            max-width: 680px;
            margin: 0 auto;
            line-height: 1.6;
        }
        .job-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 24px 22px;
            transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
            position: relative;
            height: 100%;
            display: flex;
            flex-direction: column;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
        }
        .job-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 16px 32px rgba(0, 33, 71, 0.12);
            border-color: #002147;
        }
        .job-dept-pill {
            background: #f1f5f9;
            color: #002147;
            font-size: 12px;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 50px;
            display: inline-block;
            margin-bottom: 12px;
        }
        .job-card-title {
            font-family: Roboto, sans-serif;
            font-size: 19px;
            font-weight: 800;
            color: #002147;
            line-height: 1.35;
            margin-bottom: 14px;
        }
        .job-meta-item {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .job-meta-item i {
            width: 16px;
            color: #fdc800;
        }
        .job-desc {
            font-size: 13.5px;
            color: #475569;
            line-height: 1.6;
            margin: 14px 0 20px;
            flex-grow: 1;
        }
        .btn-apply-job {
            background: #002147;
            color: #ffffff !important;
            border-radius: 50px;
            padding: 10px 22px;
            font-weight: 700;
            font-size: 14px;
            text-align: center;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.25s ease;
            width: 100%;
            border: 2px solid #002147;
        }
        .btn-apply-job:hover {
            background: #fdc800;
            border-color: #fdc800;
            color: #002147 !important;
            transform: translateY(-2px);
        }

        /* PROPER FORM STYLING */
        .application-form-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-top: 6px solid #002147;
            border-radius: 20px;
            box-shadow: 0 20px 45px rgba(0, 33, 71, 0.08);
            padding: 40px 36px;
            position: relative;
        }
        .form-header-badge {
            background: rgba(0, 33, 71, 0.08);
            color: #002147;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            padding: 5px 15px;
            border-radius: 50px;
            display: inline-block;
            margin-bottom: 10px;
        }
        .form-section-title {
            font-family: Roboto, sans-serif;
            font-size: 26px;
            font-weight: 800;
            color: #002147;
            margin-bottom: 6px;
            letter-spacing: -0.3px;
        }
        .form-section-sub {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 28px;
        }
        .form-group-custom {
            margin-bottom: 20px;
        }
        .form-label-custom {
            font-family: Roboto, sans-serif;
            font-weight: 700;
            font-size: 13.5px;
            color: #0f172a;
            margin-bottom: 7px;
            display: block;
        }
        .input-icon-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        .input-icon-wrapper i {
            position: absolute;
            left: 15px;
            color: #94a3b8;
            font-size: 14px;
            pointer-events: none;
            transition: color 0.2s ease;
        }
        .form-control-custom, .form-select-custom {
            width: 100%;
            border: 1.5px solid #cbd5e1;
            border-radius: 12px;
            padding: 12px 16px 12px 42px;
            font-size: 14.5px;
            color: #0f172a;
            background-color: #ffffff;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            outline: none;
        }
        .form-control-custom:focus, .form-select-custom:focus {
            border-color: #002147;
            box-shadow: 0 0 0 3px rgba(0, 33, 71, 0.12);
        }
        .input-icon-wrapper:focus-within i {
            color: #002147;
        }
        textarea.form-control-custom {
            padding: 14px 16px;
            min-height: 100px;
            resize: vertical;
        }

        /* RESUME UPLOAD DROPZONE */
        .resume-upload-zone {
            border: 2px dashed #94a3b8;
            background: #f8fafc;
            border-radius: 14px;
            padding: 24px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.25s ease;
        }
        .resume-upload-zone:hover {
            border-color: #002147;
            background: #f1f5f9;
        }
        .upload-icon {
            font-size: 32px;
            color: #002147;
            margin-bottom: 10px;
            display: inline-block;
        }
        .upload-title {
            font-weight: 700;
            font-size: 14px;
            color: #0f172a;
            display: block;
            margin-bottom: 3px;
        }
        .upload-desc {
            font-size: 12px;
            color: #64748b;
            display: block;
        }

        /* PROPER SUBMIT BUTTON */
        .btn-submit-app {
            background: linear-gradient(135deg, #002147 0%, #0a366b 100%);
            color: #ffffff !important;
            font-family: Roboto, sans-serif;
            font-weight: 800;
            font-size: 16px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            padding: 16px 32px;
            border-radius: 50px;
            border: none;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 10px 25px rgba(0, 33, 71, 0.25);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .btn-submit-app:hover {
            background: #fdc800 !important;
            color: #002147 !important;
            transform: translateY(-2px);
            box-shadow: 0 14px 30px rgba(253, 200, 0, 0.4);
        }
    </style>
</head>
<body class="home page-template-default page page-id-54 wp-embed-responsive theme-eikra woocommerce-no-js Eikra-version-4.4.6 header-style-9 footer-style-2 has-topbar topbar-style-7 no-sidebar rt-course-grid-view product-grid-view wpb-js-composer js-comp-ver-6.9.0 vc_responsive">
    <div id="page" class="site">
        <?php include('header.php'); ?>

        <div id="content" class="site-content">
            <!-- Hero Banner with requested text -->
            <div class="entry-banner">
                <div class="container">
                    <span class="career-badge"><i class="fas fa-briefcase"></i> Active Vacancies</span>
                    <h1 class="career-title">Current Job Openings</h1>
                    <p class="career-subtitle">
                        Browse through our open positions below. Click "Apply Now" to load your role directly into the application form.
                    </p>
                </div>
            </div>

            <!-- Notifications -->
            <div class="container mt-4">
                <?php if ($msg === 'applied'): ?>
                    <div class="alert alert-success alert-dismissible fade show rounded-4 p-4 shadow-sm border-0 d-flex align-items-center gap-3" role="alert" style="background: #ecfdf5; border-left: 5px solid #10b981 !important;">
                        <div class="rounded-circle bg-success text-white p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="fas fa-check fa-lg"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-success mb-1">Application Submitted Successfully!</h5>
                            <p class="mb-0 text-muted small">
                                Thank you for applying! Our HR and Academic evaluation team has received your profile and resume. We have also sent a confirmation acknowledgement to your email address.
                            </p>
                        </div>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show rounded-4 p-3 shadow-sm border-0" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Notice:</strong> <?php echo htmlspecialchars($error); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Job Openings Grid -->
            <section class="py-5">
                <div class="container">
                    <div class="row g-4">
                        <?php if (!empty($all_jobs)): ?>
                            <?php foreach ($all_jobs as $job): ?>
                                <div class="col-lg-4 col-md-6">
                                    <div class="job-card">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <span class="job-dept-pill"><?php echo htmlspecialchars($job['department']); ?></span>
                                            <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1 small"><?php echo htmlspecialchars($job['type']); ?></span>
                                        </div>

                                        <h3 class="job-card-title"><?php echo htmlspecialchars($job['title']); ?></h3>

                                        <div class="job-meta-item">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span><?php echo htmlspecialchars($job['location']); ?></span>
                                        </div>
                                        <div class="job-meta-item">
                                            <i class="fas fa-graduation-cap"></i>
                                            <span><?php echo htmlspecialchars($job['qualification']); ?></span>
                                        </div>
                                        <div class="job-meta-item">
                                            <i class="fas fa-history"></i>
                                            <span>Exp: <?php echo htmlspecialchars($job['experience']); ?></span>
                                        </div>
                                        <div class="job-meta-item">
                                            <i class="fas fa-money-bill-wave"></i>
                                            <strong class="text-dark"><?php echo htmlspecialchars($job['salary']); ?></strong>
                                        </div>

                                        <p class="job-desc">
                                            <?php echo htmlspecialchars($job['description']); ?>
                                        </p>

                                        <div class="mt-auto pt-3 border-top">
                                            <button type="button" class="btn-apply-job" onclick="selectRole('<?php echo htmlspecialchars(addslashes($job['title'])); ?>', <?php echo $job['id']; ?>)">
                                                Apply For This Role <i class="fas fa-arrow-right"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-12 text-center py-5">
                                <p class="text-muted">No specific openings listed right now. You can still submit a general application below!</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>

            <!-- Properly Designed Application Form Section -->
            <section class="py-5" id="apply-form" style="background: #f8fafc; border-top: 1px solid #e2e8f0;">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="application-form-card">
                                <div class="text-center">
                                    <span class="form-header-badge"><i class="fas fa-id-card me-1"></i> Direct Application</span>
                                    <h2 class="form-section-title">Job Application Form</h2>
                                    <p class="form-section-sub">Please fill out all required details accurately and attach your latest resume.</p>
                                </div>

                                <form action="code/career_submit.php" method="POST" enctype="multipart/form-data" id="careerForm" onsubmit="return handleCareerSubmit(this);">
                                    <?php echo function_exists('csrf_field') ? csrf_field() : ''; ?>

                                    <div class="row">
                                        <!-- Position Selection -->
                                        <div class="col-12 form-group-custom">
                                            <label class="form-label-custom">Position You Are Applying For <span class="text-danger">*</span></label>
                                            <div class="input-icon-wrapper">
                                                <i class="fas fa-briefcase"></i>
                                                <select name="position" id="positionSelect" class="form-select-custom" required>
                                                    <option value="">-- Choose From Active Job Openings --</option>
                                                    <?php foreach ($all_jobs as $j): ?>
                                                        <option value="<?php echo htmlspecialchars($j['title']); ?>" data-job-id="<?php echo $j['id']; ?>" <?php echo ($selected_position === $j['title'] || $selected_job_id == $j['id']) ? 'selected' : ''; ?>>
                                                            <?php echo htmlspecialchars($j['title']); ?> (<?php echo htmlspecialchars($j['location']); ?>)
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Full Name -->
                                        <div class="col-md-6 form-group-custom">
                                            <label class="form-label-custom">Full Name <span class="text-danger">*</span></label>
                                            <div class="input-icon-wrapper">
                                                <i class="fas fa-user"></i>
                                                <input type="text" name="name" class="form-control-custom" required placeholder="Candidate Full Name">
                                            </div>
                                        </div>

                                        <!-- Email Address -->
                                        <div class="col-md-6 form-group-custom">
                                            <label class="form-label-custom">Email Address <span class="text-danger">*</span></label>
                                            <div class="input-icon-wrapper">
                                                <i class="fas fa-envelope"></i>
                                                <input type="email" name="email" class="form-control-custom" required placeholder="name@example.com">
                                            </div>
                                        </div>

                                        <!-- Mobile / WhatsApp -->
                                        <div class="col-md-6 form-group-custom">
                                            <label class="form-label-custom">Mobile / WhatsApp Number <span class="text-danger">*</span></label>
                                            <div class="input-icon-wrapper">
                                                <i class="fas fa-phone-alt"></i>
                                                <input type="tel" name="mob" class="form-control-custom" required pattern="[0-9]{10}" placeholder="10-digit mobile number">
                                            </div>
                                        </div>

                                        <!-- Current City / District -->
                                        <div class="col-md-6 form-group-custom">
                                            <label class="form-label-custom">Current City / District <span class="text-danger">*</span></label>
                                            <div class="input-icon-wrapper">
                                                <i class="fas fa-map-marker-alt"></i>
                                                <input type="text" name="location" class="form-control-custom" required placeholder="e.g. Patna, Gaya, Darbhanga">
                                            </div>
                                        </div>

                                        <!-- Highest Qualification -->
                                        <div class="col-md-6 form-group-custom">
                                            <label class="form-label-custom">Highest Qualification <span class="text-danger">*</span></label>
                                            <div class="input-icon-wrapper">
                                                <i class="fas fa-graduation-cap"></i>
                                                <select name="qualification" class="form-select-custom" required>
                                                    <option value="">-- Select Qualification --</option>
                                                    <option value="BCA / B.Sc (IT) / B.Tech (CS)">BCA / B.Sc (IT) / B.Tech (CS)</option>
                                                    <option value="MCA / M.Tech / M.Sc (IT)">MCA / M.Tech / M.Sc (IT)</option>
                                                    <option value="ITI / Polytechnic Diploma">ITI / Polytechnic Diploma</option>
                                                    <option value="Graduate (B.A / B.Com / B.Sc)">Graduate (B.A / B.Com / B.Sc)</option>
                                                    <option value="Post Graduate (M.A / M.Com / MBA)">Post Graduate (M.A / M.Com / MBA)</option>
                                                    <option value="12th Pass / Intermediate">12th Pass / Intermediate</option>
                                                    <option value="Other Certification">Other Certification</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Total Experience -->
                                        <div class="col-md-6 form-group-custom">
                                            <label class="form-label-custom">Total Experience <span class="text-danger">*</span></label>
                                            <div class="input-icon-wrapper">
                                                <i class="fas fa-business-time"></i>
                                                <select name="experience" class="form-select-custom" required>
                                                    <option value="Fresher (0 - 1 Year)">Fresher (0 - 1 Year)</option>
                                                    <option value="1 - 2 Years">1 - 2 Years</option>
                                                    <option value="2 - 5 Years">2 - 5 Years</option>
                                                    <option value="5+ Years (Experienced)">5+ Years (Experienced)</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Proper Resume Upload Box -->
                                        <div class="col-12 form-group-custom">
                                            <label class="form-label-custom">Attach Resume / CV <span class="text-danger">*</span></label>
                                            <div class="resume-upload-zone" onclick="document.getElementById('resumeFileInput').click();">
                                                <i class="fas fa-cloud-arrow-up upload-icon"></i>
                                                <span class="upload-title" id="resumeFileLabel">Click to browse &amp; attach your Resume / CV</span>
                                                <span class="upload-desc">Accepted file types: PDF, DOC, DOCX (Maximum file size: 5MB)</span>
                                                <input type="file" name="resume" id="resumeFileInput" class="d-none" accept=".pdf,.doc,.docx" required onchange="handleResumeChange(this);">
                                            </div>
                                        </div>

                                        <!-- Cover Note / Message -->
                                        <div class="col-12 form-group-custom">
                                            <label class="form-label-custom">Cover Note / Why do you want to join LKVM? (Optional)</label>
                                            <textarea name="message" class="form-control-custom" rows="3" placeholder="Briefly introduce your skills, teaching background, and interest in LKVM..."></textarea>
                                        </div>

                                        <!-- Submit Button -->
                                        <div class="col-12 mt-3">
                                            <button type="submit" name="submit" class="btn-submit-app">
                                                <i class="fas fa-paper-plane"></i>
                                                <span>Submit Job Application</span>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <?php include('footer.php'); ?>
    </div>

    <script>
        function selectRole(roleTitle, jobId) {
            const selectEl = document.getElementById('positionSelect');
            if (selectEl) {
                let matched = false;
                for (let i = 0; i < selectEl.options.length; i++) {
                    const opt = selectEl.options[i];
                    const optJobId = opt.getAttribute('data-job-id');
                    if ((jobId && optJobId == jobId) || opt.value === roleTitle || opt.value.trim().toLowerCase() === roleTitle.trim().toLowerCase()) {
                        selectEl.selectedIndex = i;
                        matched = true;
                        break;
                    }
                }
                if (!matched) {
                    for (let i = 0; i < selectEl.options.length; i++) {
                        if (selectEl.options[i].value.includes(roleTitle) || roleTitle.includes(selectEl.options[i].value)) {
                            selectEl.selectedIndex = i;
                            matched = true;
                            break;
                        }
                    }
                }
            }

            const formCard = document.getElementById('apply-form');
            if (formCard) {
                formCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
                if (selectEl) {
                    selectEl.style.transition = 'all 0.3s ease';
                    selectEl.style.borderColor = '#fdc800';
                    selectEl.style.boxShadow = '0 0 0 4px rgba(253, 200, 0, 0.4)';
                    setTimeout(() => {
                        selectEl.style.borderColor = '';
                        selectEl.style.boxShadow = '';
                    }, 1800);
                }
            }
        }

        function handleResumeChange(input) {
            const labelEl = document.getElementById('resumeFileLabel');
            if (input.files && input.files[0]) {
                const fileName = input.files[0].name;
                const fileSize = (input.files[0].size / 1024 / 1024).toFixed(2);
                labelEl.innerHTML = '<span style="color: #16a34a; font-weight: 800;"><i class="fas fa-file-circle-check me-1"></i> Selected: ' + fileName + ' (' + fileSize + ' MB)</span>';
            }
        }

        function handleCareerSubmit(form) {
            const btn = form.querySelector('.btn-submit-app');
            if (btn) {
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span>Submitting Application...</span>';
                btn.style.opacity = '0.85';
                btn.style.pointerEvents = 'none';
            }
            return true;
        }
    </script>
</body>
</html>

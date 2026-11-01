<!-- admin/includes/sidebar.php -->
<?php
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$adminType = $_SESSION['admin_type'] ?? null;
?>
<nav class="sidebar glass-panel" id="sidebar">
    <div class="sidebar-brand">
        <i class="fas fa-shield-halved"></i>
        <span>LKVM Admin</span>
    </div>

    <div class="sidebar-nav-wrapper">
        <ul class="sidebar-nav">
        <li class="nav-item">
            <a href="index"
                class="nav-link <?php echo $currentPage == 'index' ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="news"
                class="nav-link <?php echo $currentPage == 'news' ? 'active' : ''; ?>">
                <i class="fas fa-newspaper"></i>
                <span>News & Notice</span>
            </a>
        </li>

        <!-- Blog Management Dropdown -->
        <li class="nav-item">
            <a class="nav-link <?php echo in_array($currentPage, ['blog_manage', 'blog_add', 'blog_edit', 'blog_categories']) ? '' : 'collapsed'; ?>" data-bs-toggle="collapse" href="#blogCollapse" role="button" aria-expanded="<?php echo in_array($currentPage, ['blog_manage', 'blog_add', 'blog_edit', 'blog_categories']) ? 'true' : 'false'; ?>" aria-controls="blogCollapse" style="display: flex; align-items: center;">
                <i class="fas fa-blog"></i>
                <span class="ms-1">Blog System</span>
                <i class="fas fa-chevron-down ms-auto" style="font-size: 0.8em; margin-left: auto;"></i>
            </a>
            <div class="collapse <?php echo in_array($currentPage, ['blog_manage', 'blog_add', 'blog_edit', 'blog_categories']) ? 'show' : ''; ?>" id="blogCollapse">
                <ul class="nav flex-column ms-3 mt-1" style="border-left: 2px solid rgba(255,255,255,0.1); padding-left: 10px;">
                    <li class="nav-item">
                        <a href="blog_manage" class="nav-link <?php echo $currentPage == 'blog_manage' ? 'active' : ''; ?>" style="padding: 0.5rem 1rem;">
                            <i class="fas fa-list" style="font-size: 0.8em; width: 20px;"></i>
                            <span>All Blogs</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="blog_add" class="nav-link <?php echo $currentPage == 'blog_add' ? 'active' : ''; ?>" style="padding: 0.5rem 1rem;">
                            <i class="fas fa-plus-circle" style="font-size: 0.8em; width: 20px;"></i>
                            <span>Write New Blog</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="blog_categories" class="nav-link <?php echo $currentPage == 'blog_categories' ? 'active' : ''; ?>" style="padding: 0.5rem 1rem;">
                            <i class="fas fa-tags" style="font-size: 0.8em; width: 20px;"></i>
                            <span>Blog Categories</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>


        <?php if ($adminType == 1): ?>
        <li class="nav-item">
            <a href="home_settings"
                class="nav-link <?php echo $currentPage == 'home_settings' ? 'active' : ''; ?>">
                <i class="fas fa-home"></i>
                <span>Home Page Settings</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="popup_analytics"
                class="nav-link <?php echo $currentPage == 'popup_analytics' ? 'active' : ''; ?>">
                <i class="fas fa-bullhorn"></i>
                <span>Popup Analytics</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="manage_pages"
                class="nav-link <?php echo $currentPage == 'manage_pages' ? 'active' : ''; ?>">
                <i class="fas fa-file-alt"></i>
                <span>Manage Pages</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="branches"
                class="nav-link <?php echo $currentPage == 'branches' ? 'active' : ''; ?>">
                <i class="fas fa-code-branch"></i>
                <span>Branches</span>
            </a>
        </li>
        <?php endif; ?>

        <!-- Employee Management Dropdown (Visible to all admins/coordinators) -->
        <li class="nav-item">
            <a class="nav-link <?php echo in_array($currentPage, ['emp_manage', 'emp_attendance_upload', 'emp_salary_slips', 'emp_slip_history', 'emp_settings']) ? '' : 'collapsed'; ?>" data-bs-toggle="collapse" href="#employeeCollapse" role="button" aria-expanded="<?php echo in_array($currentPage, ['emp_manage', 'emp_attendance_upload', 'emp_salary_slips', 'emp_slip_history', 'emp_settings']) ? 'true' : 'false'; ?>" aria-controls="employeeCollapse" style="display: flex; align-items: center;">
                <i class="fas fa-users-cog"></i>
                <span class="ms-1">Employee</span>
                <i class="fas fa-chevron-down ms-auto" style="font-size: 0.8em; margin-left: auto;"></i>
            </a>
            <div class="collapse <?php echo in_array($currentPage, ['emp_manage', 'emp_attendance_upload', 'emp_salary_slips', 'emp_slip_history', 'emp_settings']) ? 'show' : ''; ?>" id="employeeCollapse">
                <ul class="nav flex-column ms-3 mt-1" style="border-left: 2px solid rgba(255,255,255,0.1); padding-left: 10px;">
                    <li class="nav-item">
                        <a href="emp_manage" class="nav-link <?php echo $currentPage == 'emp_manage' ? 'active' : ''; ?>" style="padding: 0.5rem 1rem;">
                            <i class="fas fa-user-edit" style="font-size: 0.8em; width: 20px;"></i>
                            <span>All Employees</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="emp_attendance_upload" class="nav-link <?php echo $currentPage == 'emp_attendance_upload' ? 'active' : ''; ?>" style="padding: 0.5rem 1rem;">
                            <i class="fas fa-upload" style="font-size: 0.8em; width: 20px;"></i>
                            <span>Attendance</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="emp_salary_slips" class="nav-link <?php echo $currentPage == 'emp_salary_slips' ? 'active' : ''; ?>" style="padding: 0.5rem 1rem;">
                            <i class="fas fa-file-invoice-dollar" style="font-size: 0.8em; width: 20px;"></i>
                            <span>Salary Slips</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="emp_slip_history" class="nav-link <?php echo $currentPage == 'emp_slip_history' ? 'active' : ''; ?>" style="padding: 0.5rem 1rem;">
                            <i class="fas fa-history" style="font-size: 0.8em; width: 20px;"></i>
                            <span>Slip History</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="emp_settings" class="nav-link <?php echo $currentPage == 'emp_settings' ? 'active' : ''; ?>" style="padding: 0.5rem 1rem;">
                            <i class="fas fa-cogs" style="font-size: 0.8em; width: 20px;"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        <!-- End Employee Management -->


        <li class="nav-item">
            <a href="students"
                class="nav-link <?php echo $currentPage == 'students' ? 'active' : ''; ?>">
                <i class="fas fa-user-graduate"></i>
                <span>Students</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="courses"
                class="nav-link <?php echo $currentPage == 'courses' ? 'active' : ''; ?>">
                <i class="fas fa-book"></i>
                <span>Courses</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="admit_cards"
                class="nav-link <?php echo $currentPage == 'admit_cards' ? 'active' : ''; ?>">
                <i class="fas fa-id-card"></i>
                <span>Admit Cards</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="slider"
                class="nav-link <?php echo $currentPage == 'slider' ? 'active' : ''; ?>">
                <i class="fas fa-images"></i>
                <span>Slider</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="gallery"
                class="nav-link <?php echo ($currentPage == 'gallery' && !isset($_GET['cid'])) ? 'active' : ''; ?>">
                <i class="fas fa-photo-video"></i>
                <span>Gallery</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="gallery?cid=9"
                class="nav-link <?php echo ($currentPage == 'gallery' && isset($_GET['cid']) && $_GET['cid'] == 9) ? 'active' : ''; ?>">
                <i class="fas fa-users"></i>
                <span>Staff Members</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="study_materials"
                class="nav-link <?php echo $currentPage == 'study_materials' ? 'active' : ''; ?>">
                <i class="fas fa-book-reader"></i>
                <span>Study Materials</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="video_gallery"
                class="nav-link <?php echo $currentPage == 'video_gallery' ? 'active' : ''; ?>">
                <i class="fas fa-video"></i>
                <span>Video Gallery</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="enquiries"
                class="nav-link <?php echo $currentPage == 'enquiries' ? 'active' : ''; ?>">
                <i class="fas fa-envelope-open-text"></i>
                <span>Enquiries</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="jobs_manage"
                class="nav-link <?php echo in_array($currentPage, ['jobs_manage', 'jobs_manage.php']) ? 'active' : ''; ?>">
                <i class="fas fa-briefcase"></i>
                <span>Job Openings</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="applications"
                class="nav-link <?php echo $currentPage == 'applications' ? 'active' : ''; ?>">
                <i class="fas fa-user-tie"></i>
                <span>Job Applications</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="projects"
                class="nav-link <?php echo $currentPage == 'projects' ? 'active' : ''; ?>">
                <i class="fas fa-tasks"></i>
                <span>Running Projects</span>
            </a>
        </li>

        <?php if ($adminType == 1): ?>
        <li class="nav-item">
            <a href="donations"
                class="nav-link <?php echo $currentPage == 'donations' ? 'active' : ''; ?>">
                <i class="fas fa-hand-holding-usd"></i>
                <span>Donations</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="email_templates"
                class="nav-link <?php echo $currentPage == 'email_templates' ? 'active' : ''; ?>">
                <i class="fas fa-envelope-open-text"></i>
                <span>Email Templates</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="visitors"
                class="nav-link <?php echo $currentPage == 'visitors' ? 'active' : ''; ?>">
                <i class="fas fa-chart-line"></i>
                <span>Visitors</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="firewall_manage"
                class="nav-link <?php echo in_array($currentPage, ['firewall_manage', 'firewall_manage.php']) ? 'active' : ''; ?>">
                <i class="fas fa-shield-alt text-warning"></i>
                <span>IP Firewall & Security</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="settings"
                class="nav-link <?php echo $currentPage == 'settings' ? 'active' : ''; ?>">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>
        </li>
        <?php endif; ?>
    </ul>
    </div>
</nav>
<div class="sidebar-overlay" id="sidebar-overlay" onclick="toggleSidebar()"></div>

<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('active');
        document.getElementById('main-content').classList.toggle('active');
        document.getElementById('sidebar-overlay').classList.toggle('active');
        document.getElementById('topbar').classList.toggle('active');
    }
</script>

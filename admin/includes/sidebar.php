<!-- admin/includes/sidebar.php -->
<nav class="sidebar glass-panel" id="sidebar">
    <div class="sidebar-brand">
        <i class="fas fa-shield-halved"></i>
        <span>LKVM Admin</span>
    </div>

    <div class="sidebar-nav-wrapper">
        <ul class="sidebar-nav">
        <li class="nav-item">
            <a href="index.php"
                class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="news.php"
                class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'news.php' ? 'active' : ''; ?>">
                <i class="fas fa-newspaper"></i>
                <span>News & Notice</span>
            </a>
        </li>


        <li class="nav-item">
            <a href="home_settings.php"
                class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'home_settings.php' ? 'active' : ''; ?>">
                <i class="fas fa-home"></i>
                <span>Home Page Settings</span>
            </a>
        </li>


        <li class="nav-item">
            <a href="branches.php"
                class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'branches.php' ? 'active' : ''; ?>">
                <i class="fas fa-code-branch"></i>
                <span>Branches</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="students.php"
                class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'students.php' ? 'active' : ''; ?>">
                <i class="fas fa-user-graduate"></i>
                <span>Students</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="courses.php"
                class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'courses.php' ? 'active' : ''; ?>">
                <i class="fas fa-book"></i>
                <span>Courses</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="admit_cards.php"
                class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'admit_cards.php' ? 'active' : ''; ?>">
                <i class="fas fa-id-card"></i>
                <span>Admit Cards</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="slider.php"
                class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'slider.php' ? 'active' : ''; ?>">
                <i class="fas fa-images"></i>
                <span>Slider</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="gallery.php"
                class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'gallery.php' && !isset($_GET['cid'])) ? 'active' : ''; ?>">
                <i class="fas fa-photo-video"></i>
                <span>Gallery</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="gallery.php?cid=9"
                class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'gallery.php' && isset($_GET['cid']) && $_GET['cid'] == 9) ? 'active' : ''; ?>">
                <i class="fas fa-users"></i>
                <span>Staff Members</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="study_materials.php"
                class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'study_materials.php' ? 'active' : ''; ?>">
                <i class="fas fa-book-reader"></i>
                <span>Study Materials</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="video_gallery.php"
                class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'video_gallery.php' ? 'active' : ''; ?>">
                <i class="fas fa-video"></i>
                <span>Video Gallery</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="enquiries.php"
                class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'enquiries.php' ? 'active' : ''; ?>">
                <i class="fas fa-envelope-open-text"></i>
                <span>Enquiries</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="applications.php"
                class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'applications.php' ? 'active' : ''; ?>">
                <i class="fas fa-user-tie"></i>
                <span>Job Applications</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="projects.php"
                class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'projects.php' ? 'active' : ''; ?>">
                <i class="fas fa-tasks"></i>
                <span>Running Projects</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="donations.php"
                class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'donations.php' ? 'active' : ''; ?>">
                <i class="fas fa-hand-holding-usd"></i>
                <span>Donations</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="email_templates.php"
                class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'email_templates.php' ? 'active' : ''; ?>">
                <i class="fas fa-envelope-open-text"></i>
                <span>Email Templates</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="visitors.php"
                class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'visitors.php' ? 'active' : ''; ?>">
                <i class="fas fa-chart-line"></i>
                <span>Visitors</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="settings.php"
                class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : ''; ?>">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>
        </li>
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
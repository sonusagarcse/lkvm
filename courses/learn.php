<?php
require_once dirname(__DIR__) . '/connection.php';
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit;
}

$slug = $_GET['slug'] ?? '';
$lesson_id = $_GET['lesson'] ?? 0;
$user_id = $_SESSION['student_id'] ?? null;

// Fetch active lesson and course to check preview status
$active_lesson = null;
if ($lesson_id > 0) {
    $al_q = mysqli_query($con, "SELECT * FROM lms_lessons WHERE id = $lesson_id");
    $active_lesson = mysqli_fetch_assoc($al_q);
}

// Fetch course
$course_q = mysqli_query($con, "SELECT * FROM lms_courses WHERE slug = '$slug' LIMIT 1");
$course = mysqli_fetch_assoc($course_q);

if (!$course) die("Course not found.");

// Verify access: Either enrolled OR lesson is a preview
$is_enrolled = false;
if ($user_id) {
    $check_enrolled = mysqli_query($con, "SELECT id FROM lms_enrollments WHERE user_id = '$user_id' AND course_id = {$course['id']} AND status = 'completed'");
    $is_enrolled = mysqli_num_rows($check_enrolled) > 0;
}

if (!$is_enrolled && (!$active_lesson || $active_lesson['is_preview'] != 1)) {
    header("Location: login.php?redirect=" . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

// Handle Mark as Complete
if (isset($_POST['complete_lesson']) && $user_id) {
    $l_id = $_POST['lesson_id'];
    mysqli_query($con, "INSERT IGNORE INTO lms_progress (user_id, lesson_id) VALUES ('$user_id', '$l_id')");
}

// Fetch lessons
$lessons_query = "SELECT * FROM lms_lessons WHERE course_id = ? ORDER BY sort_order ASC, id ASC";
$l_stmt = $con->prepare($lessons_query);
$l_stmt->bind_param("i", $course['id']);
$l_stmt->execute();
$lessons_res = $l_stmt->get_result();

$lessons_array = [];
while($row = $lessons_res->fetch_assoc()) {
    $lessons_array[] = $row;
}

if (!$active_lesson && !empty($lessons_array)) {
    $active_lesson = $lessons_array[0];
}

$next_lesson = null;
$prev_lesson = null;
foreach($lessons_array as $index => $lesson) {
    if ($lesson['id'] == ($active_lesson['id'] ?? 0)) {
        $next_lesson = $lessons_array[$index + 1] ?? null;
        $prev_lesson = $lessons_array[$index - 1] ?? null;
        break;
    }
}

// Fetch completed lessons
$completed_lessons = [];
if ($user_id) {
    $prog_res = mysqli_query($con, "SELECT lesson_id FROM lms_progress WHERE user_id = '$user_id'");
    while($p = mysqli_fetch_assoc($prog_res)) $completed_lessons[] = $p['lesson_id'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($active_lesson['title'] ?? 'Learning'); ?> - <?php echo htmlspecialchars($course['title']); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" />
    <style>
        :root { --primary: #6366f1; --dark: #0f172a; --bg: #f8fafc; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg); height: 100vh; display: flex; flex-direction: column; }
        .learn-header { background: #fff; height: 70px; display: flex; align-items: center; border-bottom: 1px solid #e2e8f0; padding: 0 30px; position: sticky; top: 0; z-index: 1050; }
        .wrapper { display: flex; flex: 1; overflow: hidden; }
        .sidebar { background: #fff; width: 350px; height: 100%; border-right: 1px solid #e2e8f0; overflow-y: auto; transition: 0.3s; }
        .main-content { flex: 1; height: 100%; overflow-y: auto; padding: 40px; background: #fff; }
        .lesson-item { padding: 18px 25px; border-bottom: 1px solid #f1f5f9; cursor: pointer; transition: 0.3s; text-decoration: none; display: flex; align-items: center; gap: 15px; color: var(--dark); }
        .lesson-item:hover { background: #f8fafc; }
        .lesson-item.active { background: #eef2ff; border-left: 4px solid var(--primary); }
        .lesson-item.completed .lesson-icon i { color: #10b981 !important; }
        .video-wrapper { border-radius: 20px; overflow: hidden; background: #000; margin-bottom: 30px; box-shadow: 0 20px 50px rgba(0,0,0,0.1); }
        .back-btn { color: var(--dark); text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 10px; }
        
        /* Plyr Customizations */
        .plyr--full-ui.plyr--video .plyr__control--overlaid { background: var(--primary); }
        .plyr--video .plyr__controls { padding: 10px 20px; }

        @media (max-width: 992px) {
            .sidebar { position: fixed; left: -100%; z-index: 1100; height: 100%; width: 300px; }
            .sidebar.active { left: 0; }
            .main-content { padding: 20px; }
            .learn-header { padding: 0 15px; }
            .mobile-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1090; display: none; }
            .mobile-overlay.active { display: block; }
        }
    </style>
</head>
<body>

<header class="learn-header justify-content-between">
    <div class="d-flex align-items-center gap-3">
        <button class="btn btn-light d-lg-none" id="sidebarToggle"><i class="fa fa-bars"></i></button>
        <a href="dashboard.php" class="back-btn"><i class="fa fa-arrow-left"></i> <span class="d-none d-md-inline">Exit Player</span></a>
        <h5 class="mb-0 fw-bold d-none d-lg-block ms-3"><?php echo htmlspecialchars($course['title']); ?></h5>
    </div>
    <div class="progress-wrap d-none d-md-flex align-items-center gap-3" style="width: 250px;">
        <?php 
        $progress_pct = count($lessons_array) > 0 ? (count($completed_lessons) / count($lessons_array)) * 100 : 0;
        ?>
        <div class="progress flex-grow-1" style="height: 6px; background: #e2e8f0;">
            <div class="progress-bar bg-primary rounded-pill" style="width: <?php echo $progress_pct; ?>%;"></div>
        </div>
        <small class="fw-bold"><?php echo round($progress_pct); ?>%</small>
    </div>
</header>

<div class="mobile-overlay" id="mobileOverlay"></div>

<div class="wrapper">
    <div class="sidebar" id="sidebar">
        <div class="p-4 border-bottom bg-light d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0">Course Content</h6>
            <button class="btn-close d-lg-none" id="closeSidebar"></button>
        </div>
        <?php foreach($lessons_array as $index => $lesson): ?>
            <a href="?slug=<?php echo $slug; ?>&lesson=<?php echo $lesson['id']; ?>" 
               class="lesson-item <?php echo ($active_lesson && $active_lesson['id'] == $lesson['id']) ? 'active' : ''; ?> <?php echo in_array($lesson['id'], $completed_lessons) ? 'completed' : ''; ?>">
                <div class="lesson-icon">
                    <?php if(in_array($lesson['id'], $completed_lessons)): ?>
                        <i class="fa-solid fa-circle-check fs-4 text-success"></i>
                    <?php else: ?>
                        <i class="fa-regular <?php echo $lesson['content_type'] == 'video' ? 'fa-circle-play' : 'fa-file-pdf'; ?> fs-4 text-muted"></i>
                    <?php endif; ?>
                </div>
                <div class="lesson-info">
                    <small class="text-muted d-block uppercase small fw-bold">LESSON <?php echo $index + 1; ?></small>
                    <span class="fw-semibold"><?php echo htmlspecialchars($lesson['title']); ?></span>
                </div>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="main-content">
        <div class="container-fluid" style="max-width: 1000px; margin: 0 auto;">
            <?php if ($active_lesson): ?>
                <div class="video-wrapper">
                    <?php if($active_lesson['content_type'] == 'video'): 
                        $v_url = $active_lesson['content_url'];
                        $v_id = "";
                        if(strpos($v_url, 'youtube.com/watch?v=') !== false) {
                            parse_str(parse_url($v_url, PHP_URL_QUERY), $my_array_of_vars);
                            $v_id = $my_array_of_vars['v'];
                        } elseif(strpos($v_url, 'youtu.be/') !== false) {
                            $v_id = substr(parse_url($v_url, PHP_URL_PATH), 1);
                        } else {
                            $v_id = $v_url;
                        }
                    ?>
                        <div id="player" data-plyr-provider="youtube" data-plyr-embed-id="<?php echo $v_id; ?>"></div>
                    <?php elseif($active_lesson['content_type'] == 'pdf'): ?>
                        <div class="ratio ratio-16x9">
                            <iframe src="<?php echo $active_lesson['content_url']; ?>"></iframe>
                        </div>
                    <?php else: ?>
                        <div class="bg-dark d-flex align-items-center justify-content-center text-white p-5 rounded-4">
                            <p class="lead mb-0"><?php echo nl2br(htmlspecialchars($active_lesson['description'])); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start mb-4 gap-3">
                    <div>
                        <h2 class="fw-bold mb-2 h3"><?php echo htmlspecialchars($active_lesson['title']); ?></h2>
                        <span class="badge bg-light text-primary border px-3 py-2 rounded-pill">
                            <i class="fa fa-clock me-2"></i> Duration: 15 mins
                        </span>
                    </div>
                    <form method="POST">
                        <input type="hidden" name="lesson_id" value="<?php echo $active_lesson['id']; ?>">
                        <?php if(!in_array($active_lesson['id'], $completed_lessons)): ?>
                            <button type="submit" name="complete_lesson" class="btn btn-success px-4 py-2 rounded-pill fw-bold shadow-sm w-100">
                                <i class="fa fa-check-circle me-2"></i> Mark as Complete
                            </button>
                        <?php else: ?>
                            <button class="btn btn-outline-success px-4 py-2 rounded-pill fw-bold w-100" disabled>
                                <i class="fa fa-check-double me-2"></i> Completed
                            </button>
                        <?php endif; ?>
                    </form>
                </div>

                <div class="text-muted mb-5" style="line-height: 1.8; font-size: 1.05rem;">
                    <?php echo nl2br(htmlspecialchars($active_lesson['description'])); ?>
                </div>
                
                <div class="d-flex justify-content-between mt-5 pt-4 border-top">
                    <?php if($prev_lesson): ?>
                        <a href="?slug=<?php echo $slug; ?>&lesson=<?php echo $prev_lesson['id']; ?>" class="btn btn-outline-secondary px-4 py-2 rounded-pill fw-bold">
                            <i class="fa fa-chevron-left me-2"></i> Prev
                        </a>
                    <?php else: ?>
                        <div></div>
                    <?php endif; ?>

                    <?php if($next_lesson): ?>
                        <a href="?slug=<?php echo $slug; ?>&lesson=<?php echo $next_lesson['id']; ?>" class="btn btn-primary px-4 py-2 rounded-pill fw-bold" style="background: var(--primary); border: none;">
                            Next <i class="fa fa-chevron-right ms-2"></i>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.plyr.io/3.7.8/plyr.js"></script>
<script>
    // Initialize Plyr
    const player = new Plyr('#player', {
        controls: ['play-large', 'play', 'progress', 'current-time', 'mute', 'volume', 'captions', 'settings', 'pip', 'fullscreen'],
        youtube: { noCookie: true, rel: 0, showinfo: 0, iv_load_policy: 3, modestbranding: 1 }
    });

    // Mobile Sidebar Toggle
    const sidebar = document.getElementById('sidebar');
    const toggle = document.getElementById('sidebarToggle');
    const close = document.getElementById('closeSidebar');
    const overlay = document.getElementById('mobileOverlay');

    const toggleSidebar = () => {
        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
    };

    toggle?.addEventListener('click', toggleSidebar);
    close?.addEventListener('click', toggleSidebar);
    overlay?.addEventListener('click', toggleSidebar);
</script>
</body>
</html>

</body>
</html>

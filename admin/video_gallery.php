<?php
// admin/video_gallery.php
require_once '../connection.php';
include 'includes/header.php';

$message = '';
$error = '';

// Get current category filter
$catFilter = isset($_GET['cid']) ? intval($_GET['cid']) : 'all';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    // For videos, we just delete the record. No file to unlink since it's a URL.
    mysqli_query($con, "DELETE FROM videos WHERE id = $id");
    echo "<script>window.location.href='video_gallery.php?msg=deleted&cid=$catFilter';</script>";
}

// Handle Add Video
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_video'])) {
    $cid = intval($_POST['cid']);
    // Basic sanitization for URL
    $vdo = mysqli_real_escape_string($con, $_POST['vdo']);

    // Convert YouTube watch URL to embed URL if necessary
    // Example: https://www.youtube.com/watch?v=VIDEO_ID -> https://www.youtube.com/embed/VIDEO_ID
    if (strpos($vdo, 'watch?v=') !== false) {
        $vdo = str_replace('watch?v=', 'embed/', $vdo);
        // Remove additional parameters if any
        if (strpos($vdo, '&') !== false) {
            $vdo = explode('&', $vdo)[0];
        }
    } else if (strpos($vdo, 'youtu.be/') !== false) {
        // Example: https://youtu.be/VIDEO_ID -> https://www.youtube.com/embed/VIDEO_ID
        $vdo = str_replace('youtu.be/', 'www.youtube.com/embed/', $vdo);
    }

    if (!empty($vdo)) {
        $query = "INSERT INTO videos (cid, vdo, status) VALUES ('$cid', '$vdo', 1)";
        if (mysqli_query($con, $query)) {
            echo "<script>window.location.href='video_gallery.php?msg=saved&cid=$cid';</script>";
        } else {
            $error = "Error adding video: " . mysqli_error($con);
        }
    }
}

// Handle Add Category
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_category'])) {
    $catName = mysqli_real_escape_string($con, $_POST['cat_name']);
    $date = date('d-m-Y'); // Although video_category might not strictly use date, keeping consistent if column exists or ignored
    // Assuming structure: id, name, status (and maybe date) - based on photo_category similarity
    // Checking schema via logic: likely 'name' and 'status' are key.
    $query = "INSERT INTO video_category (name, status) VALUES ('$catName', 1)";
    mysqli_query($con, $query);
    echo "<script>window.location.href='video_gallery.php?msg=cat_saved';</script>";
}

// Fetch Categories
$cats = [];
$cQ = mysqli_query($con, "SELECT * FROM video_category");
while ($r = mysqli_fetch_assoc($cQ))
    $cats[] = $r;
?>

<div class="row mb-4">
    <div class="col-md-6 d-flex align-items-center">
        <h2 class="h3 mb-0 text-gray-800 me-3">Video Gallery</h2>
        <div class="dropdown">
            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <?php
                $currCatName = "All Categories";
                foreach ($cats as $c) {
                    if ($c['id'] == $catFilter)
                        $currCatName = $c['name'];
                }
                echo $currCatName;
                ?>
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="video_gallery.php">All Categories</a></li>
                <?php foreach ($cats as $c): ?>
                    <li><a class="dropdown-item"
                            href="video_gallery.php?cid=<?php echo $c['id']; ?>"><?php echo $c['name']; ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <div class="col-md-6 text-end">
        <button class="btn btn-secondary me-2" data-bs-toggle="modal" data-bs-target="#catModal"><i
                class="fas fa-list"></i> Add Category</button>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#videoModal"><i class="fas fa-plus"></i>
            Add Video</button>
    </div>
</div>

<?php if (isset($_GET['msg'])) {
    $msgType = $_GET['msg'];
    if ($msgType == 'saved')
        echo '<div class="alert alert-success">Video added successfully!</div>';
    if ($msgType == 'deleted')
        echo '<div class="alert alert-success">Video deleted successfully!</div>';
    if ($msgType == 'cat_saved')
        echo '<div class="alert alert-success">Category added successfully!</div>';
} ?>

<?php if ($error) {
    echo '<div class="alert alert-danger">' . $error . '</div>';
} ?>

<div class="row">
    <?php
    $where = ($catFilter != 'all') ? "WHERE v.cid = $catFilter" : "";
    $query = "SELECT v.*, c.name as cat_name FROM videos v LEFT JOIN video_category c ON v.cid = c.id $where ORDER BY v.id DESC";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100 position-relative">
                        <div style="height: 200px; overflow: hidden; border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
                             <iframe width="100%" height="100%" src="' . htmlspecialchars($row['vdo']) . '" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                        <div class="card-body p-3">
                            <small class="text-muted d-block mb-2">' . htmlspecialchars($row['cat_name']) . '</small>
                            <p class="text-truncate" title="' . htmlspecialchars($row['vdo']) . '"><small>' . htmlspecialchars($row['vdo']) . '</small></p>
                            <a href="video_gallery.php?delete=' . $row['id'] . '&cid=' . $catFilter . '" class="btn btn-danger btn-sm w-100" onclick="return confirm(\'Are you sure?\')"><i class="fas fa-trash"></i> Delete</a>
                        </div>
                    </div>
                </div>';
        }
    } else {
        echo '<div class="col-12"><div class="alert alert-info">No videos found in this category.</div></div>';
    }
    ?>
</div>

<!-- Add Video Modal -->
<div class="modal fade" id="videoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Video</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Category</label>
                        <select name="cid" class="form-select" required>
                            <?php foreach ($cats as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php if ($cat['id'] == $catFilter)
                                       echo 'selected'; ?>><?php echo $cat['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>YouTube Video URL</label>
                        <input type="text" name="vdo" class="form-control" required
                            placeholder="https://www.youtube.com/watch?v=...">
                        <div class="form-text">Paste the full YouTube URL (e.g. https://www.youtube.com/watch?v=xxxxx).
                            It will be automatically converted to an embed link.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="save_video" class="btn btn-primary">Add Video</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="catModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Category Name</label>
                        <input type="text" name="cat_name" class="form-control" required
                            placeholder="e.g. Seminars, Cultural Events">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="save_category" class="btn btn-primary">Save Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
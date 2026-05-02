<?php
// admin/gallery.php
require_once '../connection.php';
include 'includes/header.php';

$message = '';
$error = '';
$uploadDir = '../images/photos/';
if (!file_exists($uploadDir))
    mkdir($uploadDir, 0777, true);

// Get current category filter
$catFilter = isset($_GET['cid']) ? intval($_GET['cid']) : 'all';

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    // Fetch CID before deleting to clear cache
    $q = mysqli_query($con, "SELECT cid FROM photos WHERE id = $id");
    if ($r = mysqli_fetch_assoc($q)) {
        $delCid = $r['cid'];
        // Clear cache for this category
        if (function_exists('cache_delete')) {
            cache_delete('members_photos_' . $delCid);
            // Also clearing potential other keys if they exist
            cache_delete('gallery_photos_' . $delCid);
        }
    }

    mysqli_query($con, "DELETE FROM photos WHERE id = $id");

    // Also clear cache for logos/partners if deleted (cid=10)
    if (isset($delCid) && $delCid == 10) {
        if (function_exists('cache_delete')) {
            cache_delete('logos_photos_10');
        }
    }

    echo "<script>window.location.href='gallery.php?msg=deleted&cid=$catFilter';</script>";
}

// Handle Add Photo
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_photo'])) {
    $cid = intval($_POST['cid']);
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $name = isset($_POST['name']) ? mysqli_real_escape_string($con, $_POST['name']) : $title;
    $date = date('d-m-Y');

    if (isset($_FILES['img']) && $_FILES['img']['error'] == 0) {
        $filename = time() . '_' . $_FILES['img']['name'];
        if (move_uploaded_file($_FILES['img']['tmp_name'], $uploadDir . $filename)) {
            $query = "INSERT INTO photos (cid, title, img, date, status, name) VALUES ('$cid', '$title', '$filename', '$date', 1, '$name')";
            mysqli_query($con, $query);
            // Clear cache for this category
            if (function_exists('cache_delete')) {
                cache_delete('members_photos_' . $cid);
                cache_delete('gallery_photos_' . $cid);
            }

            // Clear partner cache if applicable
            if ($cid == 10 && function_exists('cache_delete'))
                cache_delete('logos_photos_10');

            echo "<script>window.location.href='gallery.php?msg=saved&cid=$cid';</script>";
        }
    }
}

// Handle Add Category
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_category'])) {
    $catName = mysqli_real_escape_string($con, $_POST['cat_name']);
    $date = date('d-m-Y');
    mysqli_query($con, "INSERT INTO photo_category (name, date, status) VALUES ('$catName', '$date', 1)");
    echo "<script>window.location.href='gallery.php?msg=cat_saved';</script>";
}

// Fetch Categories
$cats = [];
$cQ = mysqli_query($con, "SELECT * FROM photo_category");
while ($r = mysqli_fetch_assoc($cQ))
    $cats[] = $r;
?>

<div class="row mb-4">
    <div class="col-md-6 d-flex align-items-center">
        <h2 class="h3 mb-0 text-gray-800 me-3">Gallery</h2>
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
                <li><a class="dropdown-item" href="gallery.php">All Categories</a></li>
                <?php foreach ($cats as $c): ?>
                    <li><a class="dropdown-item"
                            href="gallery.php?cid=<?php echo $c['id']; ?>"><?php echo $c['name']; ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <div class="col-md-6 text-end">
        <button class="btn btn-secondary me-2" data-bs-toggle="modal" data-bs-target="#catModal"><i
                class="fas fa-list"></i> Add Category</button>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#photoModal"><i class="fas fa-plus"></i>
            Add Photo</button>
    </div>
</div>

<?php if (isset($_GET['msg'])) {
    $msgType = $_GET['msg'];
    if ($msgType == 'saved')
        echo '<div class="alert alert-success">Photo added successfully!</div>';
    if ($msgType == 'deleted')
        echo '<div class="alert alert-success">Photo deleted successfully!</div>';
    if ($msgType == 'cat_saved')
        echo '<div class="alert alert-success">Category added successfully!</div>';
} ?>

<div class="row">
    <?php
    $where = ($catFilter != 'all') ? "WHERE p.cid = $catFilter" : "";
    $query = "SELECT p.*, c.name as cat_name FROM photos p LEFT JOIN photo_category c ON p.cid = c.id $where ORDER BY p.id DESC";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $imgSrc = $uploadDir . $row['img'];
            // Warning if image file missing
            $warning = (!file_exists($imgSrc)) ? '<span class="badge bg-danger position-absolute top-0 start-0 m-2">Missing File</span>' : '';

            echo '<div class="col-md-3 mb-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100 position-relative">
                        ' . $warning . '
                        <div style="height: 180px; overflow: hidden; border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
                             <img src="' . $imgSrc . '" class="w-100 h-100" style="object-fit: cover;" alt="Photo" loading="lazy">
                        </div>
                        <div class="card-body p-3">
                            <h6 class="card-title text-truncate" title="' . htmlspecialchars($row['title']) . '">' . (htmlspecialchars($row['title']) ?: 'Untitled') . '</h6>
                            <small class="text-muted d-block mb-2">' . htmlspecialchars($row['cat_name']) . '</small>
                            <a href="gallery.php?delete=' . $row['id'] . '&cid=' . $catFilter . '" class="btn btn-danger btn-sm w-100" onclick="return confirm(\'Are you sure?\')"><i class="fas fa-trash"></i></a>
                        </div>
                    </div>
                </div>';
        }
    } else {
        echo '<div class="col-12"><div class="alert alert-info">No photos found in this category.</div></div>';
    }
    ?>
</div>

<!-- Add Photo Modal -->
<div class="modal fade" id="photoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Photo</h5>
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
                        <div class="form-text">Tip: Select "Our Partners" (ID 10) for Logos.</div>
                    </div>
                    <div class="mb-3">
                        <label>Image</label>
                        <input type="file" name="img" class="form-control" accept="image/*" required>
                    </div>
                    <div class="mb-3">
                        <label>Name (for Staff/Members)</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. John Doe">
                    </div>
                    <div class="mb-3">
                        <label>Title/Designation/Description</label>
                        <input type="text" name="title" class="form-control" required
                            placeholder="e.g. Director or Image Caption">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="save_photo" class="btn btn-primary">Upload</button>
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
                            placeholder="e.g. Events, Campus, Partners">
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
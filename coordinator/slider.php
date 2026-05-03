<?php
// admin/slider.php
require_once '../connection.php';
include 'includes/header.php';

$message = '';
$error = '';
$uploadDir = '../images/slider/'; // Assumption based on common practice, will create if missing

if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    // Optional: Delete physical file logic here if needed
    $query = "DELETE FROM slider WHERE id = $id";
    if (mysqli_query($con, $query)) {
        if (function_exists('cache_delete')) {
            cache_delete('slider_images');
        }
        echo "<script>window.location.href='slider.php?msg=deleted';</script>";
    }
}

// Handle Add
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_slider'])) {
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $subtitle = mysqli_real_escape_string($con, $_POST['subtitle']);
    $date = date('d-m-Y');

    // File Upload
    $img = '';
    if (isset($_FILES['img']) && $_FILES['img']['error'] == 0) {
        $filename = time() . '_' . $_FILES['img']['name'];
        if (move_uploaded_file($_FILES['img']['tmp_name'], $uploadDir . $filename)) {
            $img = $filename;
        } else {
            $error = "Failed to upload image.";
        }
    }

    if (!$error && $img) {
        $query = "INSERT INTO slider (img, title, subtitle, date) VALUES ('$img', '$title', '$subtitle', '$date')";
        if (mysqli_query($con, $query)) {
            if (function_exists('cache_delete')) {
                cache_delete('slider_images');
            }
            echo "<script>window.location.href='slider.php?msg=saved';</script>";
        } else {
            $error = "Database Error: " . mysqli_error($con);
        }
    } else if (!$error) {
        $error = "Please select an image.";
    }
}
?>

<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h2 class="h3 mb-0 text-gray-800">Slider Management</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#sliderModal">
            <i class="fas fa-plus"></i> Add New Slide
        </button>
    </div>
</div>

<?php if (isset($_GET['msg']) && $_GET['msg'] == 'saved')
    echo '<div class="alert alert-success">Slide added successfully!</div>'; ?>
<?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted')
    echo '<div class="alert alert-success">Slide deleted successfully!</div>'; ?>
<?php if ($error)
    echo '<div class="alert alert-danger">' . $error . '</div>'; ?>

<div class="row">
    <?php
    $query = "SELECT * FROM slider ORDER BY id DESC";
    $result = mysqli_query($con, $query);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $imgName = $row['img'];
            $imgSrc = $uploadDir . $imgName; // Default: ../images/slider/
    
            // Fallback for old images in root images/
            if (!file_exists($imgSrc)) {
                if (file_exists('../images/' . $imgName)) {
                    $imgSrc = '../images/' . $imgName;
                }
            }

            echo '<div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <img src="' . $imgSrc . '" class="card-img-top" alt="Slide" style="height: 200px; object-fit: cover; border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
                        <div class="card-body">
                            <h5 class="card-title">' . htmlspecialchars($row['title']) . '</h5>
                            <p class="card-text text-muted">' . htmlspecialchars($row['subtitle']) . '</p>
                            <p class="card-text"><small class="text-muted">Added: ' . $row['date'] . '</small></p>
                            <a href="slider.php?delete=' . $row['id'] . '" class="btn btn-danger btn-sm w-100" onclick="return confirm(\'Are you sure?\')"><i class="fas fa-trash"></i> Delete</a>
                        </div>
                    </div>
                </div>';
        }
    } else {
        echo '<div class="col-12"><div class="alert alert-info">No slides found.</div></div>';
    }
    ?>
</div>

<!-- Add Modal -->
<div class="modal fade" id="sliderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Slide</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Slider Image</label>
                        <input type="file" name="img" class="form-control" accept="image/*" required>
                    </div>
                    <div class="mb-3">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Subtitle/Description</label>
                        <input type="text" name="subtitle" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="save_slider" class="btn btn-primary">Upload Slide</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
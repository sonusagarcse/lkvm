<?php
$page_title = 'Manage Categories';
include 'includes/header.php';

// Handle Add
if (isset($_POST['add_cat'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
    mysqli_query($con, "INSERT INTO lms_categories (name, slug) VALUES ('$name', '$slug')");
    echo "<script>window.location.href='categories.php';</script>";
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($con, "DELETE FROM lms_categories WHERE id = $id");
    echo "<script>window.location.href='categories.php';</script>";
}

// Fetch Categories
$cats_res = mysqli_query($con, "SELECT c.*, (SELECT COUNT(*) FROM lms_courses WHERE category_id = c.id) as course_count FROM lms_categories c ORDER BY c.id DESC");
$total_cats = mysqli_num_rows($cats_res);
?>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="admin-card p-4 border-0 shadow-sm">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-4">
                    <i class="fa fa-plus-circle fs-4"></i>
                </div>
                <h5 class="fw-800 mb-0">Create Category</h5>
            </div>
            <form method="POST">
                <div class="mb-4">
                    <label class="form-label small fw-bold text-muted text-uppercase" style="letter-spacing: 0.5px;">Category Name</label>
                    <input type="text" name="name" class="form-control p-3 border-0 bg-light rounded-3" required placeholder="e.g. Web Development">
                </div>
                <button type="submit" name="add_cat" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm" style="background: var(--primary); border: none;">
                    Add Category
                </button>
            </form>
        </div>
    </div>
    
    <div class="col-lg-8">
        <div class="admin-card p-4 border-0 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-4 px-2">
                <h5 class="fw-800 mb-0">All Categories</h5>
                <span class="badge bg-light text-dark rounded-pill px-3"><?php echo $total_cats; ?> Total</span>
            </div>
            <div class="table-responsive">
                <table class="table table-premium mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Category Details</th>
                            <th>Slug</th>
                            <th>Courses</th>
                            <th class="text-end pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($total_cats > 0):
                            while($row = mysqli_fetch_assoc($cats_res)):
                        ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-light p-2 rounded-3 text-primary fw-bold" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                        <?php echo strtoupper(substr($row['name'], 0, 1)); ?>
                                    </div>
                                    <div class="fw-bold text-dark"><?php echo htmlspecialchars($row['name']); ?></div>
                                </div>
                            </td>
                            <td><code class="small text-primary bg-primary bg-opacity-10 px-2 py-1 rounded"><?php echo $row['slug']; ?></code></td>
                            <td><span class="fw-bold"><?php echo $row['course_count']; ?></span> <small class="text-muted">Courses</small></td>
                            <td class="text-end pe-4">
                                <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-light text-danger rounded-3 p-2" title="Delete" onclick="return confirm('Delete this category?')"><i class="fa fa-trash-alt"></i></a>
                            </td>
                        </tr>
                        <?php 
                            endwhile;
                        else:
                            echo "<tr><td colspan='4' class='text-center py-5 text-muted'>No categories found.</td></tr>";
                        endif;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

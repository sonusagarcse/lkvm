<?php
// admin/blog_categories.php - Blog Category Management
require_once 'includes/auth_check.php';
require_once '../connection.php';

$message = '';
$error = '';

// Helper function to create clean slug
function slugify($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    return empty($text) ? 'n-a' : $text;
}

// Handle Add / Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_category'])) {
    if (!verify_csrf_token()) {
        die("CSRF validation failed.");
    }

    $id = intval($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $status = isset($_POST['status']) ? 1 : 0;

    if (empty($name)) {
        $error = "Category name is required.";
    } else {
        if (empty($slug)) {
            $slug = slugify($name);
        } else {
            $slug = slugify($slug);
        }

        // Check unique slug
        $check_sql = "SELECT id FROM blog_categories WHERE slug = ? AND id != ?";
        $stmt_check = $con->prepare($check_sql);
        $stmt_check->bind_param("si", $slug, $id);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $slug = $slug . '-' . time();
        }
        $stmt_check->close();

        if ($id > 0) {
            $stmt = $con->prepare("UPDATE blog_categories SET name=?, slug=?, description=?, status=? WHERE id=?");
            $stmt->bind_param("sssii", $name, $slug, $description, $status, $id);
            if ($stmt->execute()) {
                $message = "Category updated successfully!";
            } else {
                $error = "Error updating category: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $stmt = $con->prepare("INSERT INTO blog_categories (name, slug, description, status) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssi", $name, $slug, $description, $status);
            if ($stmt->execute()) {
                $message = "New category created successfully!";
            } else {
                $error = "Error creating category: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}

// Handle Delete
if (isset($_GET['delete'])) {
    $del_id = intval($_GET['delete']);
    // Check if posts exist
    $check_posts = mysqli_query($con, "SELECT COUNT(*) FROM blogs WHERE category_id = $del_id");
    $p_count = mysqli_fetch_row($check_posts)[0] ?? 0;

    if ($p_count > 0) {
        $error = "Cannot delete category! It contains {$p_count} blog post(s). Please reassign or delete them first.";
    } else {
        if (mysqli_query($con, "DELETE FROM blog_categories WHERE id = $del_id")) {
            $message = "Category deleted successfully.";
        } else {
            $error = "Failed to delete category.";
        }
    }
}

// Fetch all categories with post counts
$query = "
    SELECT c.*, COUNT(b.id) AS post_count 
    FROM blog_categories c
    LEFT JOIN blogs b ON b.category_id = c.id
    GROUP BY c.id
    ORDER BY c.id DESC
";
$categories = mysqli_query($con, $query);

require_once 'includes/header.php';
?>

<div class="row mb-4 align-items-center">
    <div class="col-md-7">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="index">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="blog_manage">Blog System</a></li>
                <li class="breadcrumb-item active" aria-current="page">Categories</li>
            </ol>
        </nav>
        <h3 class="fw-bold mb-0 text-dark">
            <i class="fas fa-tags text-primary me-2"></i>Blog Categories
        </h3>
        <p class="text-muted small mb-0">Manage taxonomy and categorization for all published articles</p>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0 d-flex gap-2 justify-content-md-end">
        <a href="blog_manage" class="btn btn-outline-secondary rounded-pill px-3 shadow-sm">
            <i class="fas fa-arrow-left me-1"></i> All Blogs
        </a>
        <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold" onclick="openAddModal()">
            <i class="fas fa-plus me-1"></i> Add Category
        </button>
    </div>
</div>

<?php if ($message): ?>
    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
        <i class="fas fa-check-circle me-2"></i><?php echo htmlspecialchars($message); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i><?php echo htmlspecialchars($error); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Categories Table -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr class="small text-uppercase text-muted">
                    <th class="ps-4">ID</th>
                    <th>Category Name</th>
                    <th>SEO Slug</th>
                    <th>Description</th>
                    <th>Articles</th>
                    <th>Status</th>
                    <th class="pe-4 text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($categories) > 0): ?>
                    <?php while ($cat = mysqli_fetch_assoc($categories)): ?>
                        <tr>
                            <td class="ps-4 font-monospace text-muted">#<?php echo $cat['id']; ?></td>
                            <td>
                                <strong class="text-dark"><?php echo htmlspecialchars($cat['name']); ?></strong>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark font-monospace border">
                                    <?php echo htmlspecialchars($cat['slug']); ?>
                                </span>
                            </td>
                            <td class="small text-muted" style="max-width: 250px;">
                                <?php echo htmlspecialchars($cat['description'] ?: '-'); ?>
                            </td>
                            <td>
                                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-1 rounded-pill">
                                    <i class="fas fa-file-alt me-1"></i><?php echo intval($cat['post_count']); ?> Posts
                                </span>
                            </td>
                            <td>
                                <?php if ($cat['status'] == 1): ?>
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1"><i class="fas fa-check-circle me-1"></i>Active</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-2 py-1"><i class="fas fa-pause-circle me-1"></i>Disabled</span>
                                <?php endif; ?>
                            </td>
                            <td class="pe-4 text-end">
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill me-1" 
                                        onclick='openEditModal(<?php echo json_encode($cat, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>)'>
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <a href="blog_categories?delete=<?php echo $cat['id']; ?>" 
                                   class="btn btn-sm btn-outline-danger rounded-pill" 
                                   onclick="return confirm('Are you sure you want to delete this category?');">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fas fa-tags fa-3x mb-3 text-secondary opacity-50 d-block"></i>
                            No categories created yet. Click "Add Category" above to create one.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Category Modal (Add / Edit) -->
<div class="modal fade" id="categoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="modalTitle">
                    <i class="fas fa-tag text-primary me-2"></i>Add Blog Category
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="id" id="cat_id" value="0">
                <div class="modal-body py-3">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Category Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="cat_name" class="form-control" placeholder="e.g. Technology & Innovations" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">URL Slug (SEO)</label>
                        <input type="text" name="slug" id="cat_slug" class="form-control" placeholder="auto-generated-if-empty">
                        <div class="form-text">Used in category URLs: <code>/blog/category/your-slug</code></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Description</label>
                        <textarea name="description" id="cat_description" class="form-control" rows="3" placeholder="Brief summary of articles in this category"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Status</label>
                        <div class="form-check form-switch mt-1">
                            <input class="form-check-input" type="checkbox" name="status" id="cat_status" value="1" checked style="width: 48px; height: 24px;">
                            <label class="form-check-label ms-2 fw-medium" for="cat_status">Active & Visible</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="save_category" class="btn btn-primary rounded-pill px-4 fw-bold">
                        <i class="fas fa-save me-1"></i> Save Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const categoryModal = new bootstrap.Modal(document.getElementById('categoryModal'));
const catId = document.getElementById('cat_id');
const catName = document.getElementById('cat_name');
const catSlug = document.getElementById('cat_slug');
const catDesc = document.getElementById('cat_description');
const catStatus = document.getElementById('cat_status');
const modalTitle = document.getElementById('modalTitle');

function slugify(text) {
    return text.toString().toLowerCase()
        .replace(/\s+/g, '-')
        .replace(/[^\w\-]+/g, '')
        .replace(/\-\-+/g, '-')
        .replace(/^-+/, '')
        .replace(/-+$/, '');
}

catName.addEventListener('input', function() {
    if (catId.value === '0' || catSlug.value === '') {
        catSlug.value = slugify(this.value);
    }
});

function openAddModal() {
    catId.value = '0';
    catName.value = '';
    catSlug.value = '';
    catDesc.value = '';
    catStatus.checked = true;
    modalTitle.innerHTML = '<i class="fas fa-plus text-primary me-2"></i>Add Blog Category';
    categoryModal.show();
}

function openEditModal(cat) {
    catId.value = cat.id;
    catName.value = cat.name;
    catSlug.value = cat.slug;
    catDesc.value = cat.description || '';
    catStatus.checked = cat.status == 1;
    modalTitle.innerHTML = '<i class="fas fa-edit text-primary me-2"></i>Edit Category: ' + cat.name;
    categoryModal.show();
}
</script>

<?php require_once 'includes/footer.php'; ?>


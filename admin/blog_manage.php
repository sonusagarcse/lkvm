<?php
// admin/blog_manage.php - Comprehensive Blog Management Dashboard
require_once 'includes/auth_check.php';
require_once '../connection.php';

$message = '';
$error = '';

// Handle Delete
if (isset($_GET['delete'])) {
    $del_id = intval($_GET['delete']);
    // Fetch image to delete
    $q = mysqli_query($con, "SELECT featured_image FROM blogs WHERE id = $del_id");
    if ($q && $r = mysqli_fetch_assoc($q)) {
        if (!empty($r['featured_image']) && file_exists('../uploads/' . $r['featured_image'])) {
            @unlink('../uploads/' . $r['featured_image']);
        }
    }
    if (mysqli_query($con, "DELETE FROM blogs WHERE id = $del_id")) {
        $message = "Blog post deleted successfully.";
    } else {
        $error = "Failed to delete blog post.";
    }
}

// Handle Status Toggle
if (isset($_GET['toggle_status']) && isset($_GET['id'])) {
    $toggle_id = intval($_GET['id']);
    $new_status = intval($_GET['toggle_status']) == 1 ? 1 : 0;
    mysqli_query($con, "UPDATE blogs SET status = $new_status WHERE id = $toggle_id");
    header("Location: blog_manage?msg=updated");
    exit();
}

if (isset($_GET['msg']) && $_GET['msg'] === 'updated') {
    $message = "Blog post status updated successfully!";
}

// Filters & Search
$search = trim($_GET['search'] ?? '');
$filter_cat = intval($_GET['category'] ?? 0);
$filter_status = $_GET['status'] ?? 'all';

$where_clauses = ["1=1"];
if (!empty($search)) {
    $s_escaped = mysqli_real_escape_string($con, $search);
    $where_clauses[] = "(b.title LIKE '%$s_escaped%' OR b.tags LIKE '%$s_escaped%' OR b.author_name LIKE '%$s_escaped%')";
}
if ($filter_cat > 0) {
    $where_clauses[] = "b.category_id = $filter_cat";
}
if ($filter_status === 'published') {
    $where_clauses[] = "b.status = 1";
} elseif ($filter_status === 'draft') {
    $where_clauses[] = "b.status = 0";
}
$where_sql = implode(' AND ', $where_clauses);

// Pagination
$per_page = 15;
$page = max(1, intval($_GET['p'] ?? 1));
$offset = ($page - 1) * $per_page;

$total_rows = mysqli_fetch_row(mysqli_query($con, "SELECT COUNT(*) FROM blogs b WHERE $where_sql"))[0] ?? 0;
$total_pages = ceil($total_rows / $per_page);

// Fetch blogs
$blogs_sql = "
    SELECT b.*, c.name AS category_name, c.slug AS category_slug
    FROM blogs b
    LEFT JOIN blog_categories c ON c.id = b.category_id
    WHERE $where_sql
    ORDER BY b.id DESC
    LIMIT $offset, $per_page
";
$blogs_result = mysqli_query($con, $blogs_sql);

// KPI Stats
$stats = [
    'total' => mysqli_fetch_row(mysqli_query($con, "SELECT COUNT(*) FROM blogs"))[0] ?? 0,
    'published' => mysqli_fetch_row(mysqli_query($con, "SELECT COUNT(*) FROM blogs WHERE status = 1"))[0] ?? 0,
    'drafts' => mysqli_fetch_row(mysqli_query($con, "SELECT COUNT(*) FROM blogs WHERE status = 0"))[0] ?? 0,
    'views' => mysqli_fetch_row(mysqli_query($con, "SELECT COALESCE(SUM(views), 0) FROM blogs"))[0] ?? 0,
];

// Fetch categories for filter dropdown
$cats_list = mysqli_query($con, "SELECT id, name FROM blog_categories ORDER BY name ASC");

require_once 'includes/header.php';
?>

<div class="row mb-4 align-items-center">
    <div class="col-md-7">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="index">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Blog Management</li>
            </ol>
        </nav>
        <h3 class="fw-bold mb-0 text-dark">
            <i class="fas fa-blog text-primary me-2"></i>Blog Posts & Articles
        </h3>
        <p class="text-muted small mb-0">Create, optimize for SEO, and manage blog articles published on LKVM</p>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0 d-flex gap-2 justify-content-md-end flex-wrap">
        <a href="../blog" target="_blank" class="btn btn-outline-secondary rounded-pill px-3 shadow-sm">
            <i class="fas fa-external-link-alt me-1"></i> View Live Blog
        </a>
        <a href="blog_categories" class="btn btn-outline-primary rounded-pill px-3 shadow-sm">
            <i class="fas fa-tags me-1"></i> Categories
        </a>
        <a href="blog_add" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold">
            <i class="fas fa-pen-nib me-1"></i> Write New Blog
        </a>
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

<!-- Top Metric Cards -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white" style="border-left: 4px solid #4361ee !important;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-uppercase small text-muted fw-bold" style="font-size: 0.72rem;">Total Articles</span>
                    <h3 class="fw-bold mb-0 text-dark"><?php echo number_format($stats['total']); ?></h3>
                </div>
                <div class="p-3 rounded-3 text-white" style="background: #4361ee;"><i class="fas fa-newspaper fa-lg"></i></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white" style="border-left: 4px solid #10b981 !important;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-uppercase small text-muted fw-bold" style="font-size: 0.72rem;">Published Live</span>
                    <h3 class="fw-bold mb-0 text-dark"><?php echo number_format($stats['published']); ?></h3>
                </div>
                <div class="p-3 rounded-3 text-white" style="background: #10b981;"><i class="fas fa-check fa-lg"></i></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white" style="border-left: 4px solid #f59e0b !important;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-uppercase small text-muted fw-bold" style="font-size: 0.72rem;">Drafts</span>
                    <h3 class="fw-bold mb-0 text-dark"><?php echo number_format($stats['drafts']); ?></h3>
                </div>
                <div class="p-3 rounded-3 text-white" style="background: #f59e0b;"><i class="fas fa-edit fa-lg"></i></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white" style="border-left: 4px solid #0284c7 !important;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-uppercase small text-muted fw-bold" style="font-size: 0.72rem;">Total Blog Reads</span>
                    <h3 class="fw-bold mb-0 text-dark"><?php echo number_format($stats['views']); ?></h3>
                </div>
                <div class="p-3 rounded-3 text-white" style="background: #0284c7;"><i class="fas fa-eye fa-lg"></i></div>
            </div>
        </div>
    </div>
</div>

<!-- Filters & Search Toolbar -->
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-3">
        <form method="GET" action="" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 bg-light" placeholder="Search by title, tag, or author..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
            </div>
            <div class="col-md-3">
                <select name="category" class="form-select bg-light">
                    <option value="0">All Categories</option>
                    <?php while ($c = mysqli_fetch_assoc($cats_list)): ?>
                        <option value="<?php echo $c['id']; ?>" <?php echo $filter_cat == $c['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($c['name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select bg-light">
                    <option value="all" <?php echo $filter_status === 'all' ? 'selected' : ''; ?>>All Status</option>
                    <option value="published" <?php echo $filter_status === 'published' ? 'selected' : ''; ?>>Published</option>
                    <option value="draft" <?php echo $filter_status === 'draft' ? 'selected' : ''; ?>>Drafts</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary rounded-pill px-3 flex-grow-1">Filter</button>
                <?php if (!empty($search) || $filter_cat > 0 || $filter_status !== 'all'): ?>
                    <a href="blog_manage" class="btn btn-light rounded-pill px-3" title="Clear Filters"><i class="fas fa-times"></i></a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<!-- Blogs Table -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr class="small text-uppercase text-muted">
                    <th class="ps-4">Article</th>
                    <th>Category</th>
                    <th>Author</th>
                    <th>Views</th>
                    <th>SEO Health</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th class="pe-4 text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($blogs_result) > 0): ?>
                    <?php while ($blog = mysqli_fetch_assoc($blogs_result)): ?>
                        <tr>
                            <!-- Article Info & Featured Image -->
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <?php if (!empty($blog['featured_image']) && file_exists('../uploads/' . $blog['featured_image'])): ?>
                                        <img src="../uploads/<?php echo htmlspecialchars($blog['featured_image']); ?>" style="width: 70px; height: 42px; object-fit: cover; border-radius: 8px; border: 1px solid #e2e8f0;">
                                    <?php else: ?>
                                        <div style="width: 70px; height: 42px; background: #e2e8f0; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #94a3b8;">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <a href="blog_edit?id=<?php echo $blog['id']; ?>" class="fw-bold text-dark text-decoration-none d-block text-truncate" style="max-width: 320px;" title="<?php echo htmlspecialchars($blog['title']); ?>">
                                            <?php echo htmlspecialchars($blog['title']); ?>
                                        </a>
                                        <small class="text-muted font-monospace" style="font-size: 0.75rem;">
                                            /blog/<?php echo htmlspecialchars($blog['slug']); ?>
                                        </small>
                                    </div>
                                </div>
                            </td>

                            <!-- Category -->
                            <td>
                                <span class="badge bg-light text-primary border rounded-pill px-3 py-1">
                                    <?php echo htmlspecialchars($blog['category_name'] ?? 'Uncategorized'); ?>
                                </span>
                            </td>

                            <!-- Author -->
                            <td class="small text-muted">
                                <i class="fas fa-user-circle me-1 text-secondary"></i>
                                <?php echo htmlspecialchars($blog['author_name'] ?? 'Admin'); ?>
                            </td>

                            <!-- Reads / Views -->
                            <td>
                                <span class="badge bg-info bg-opacity-10 text-info px-2 py-1 rounded-pill">
                                    <i class="fas fa-eye me-1"></i><?php echo number_format($blog['views']); ?>
                                </span>
                            </td>

                            <!-- SEO Health Indicator -->
                            <td>
                                <?php
                                $seo_score = 0;
                                if (!empty($blog['meta_title'])) $seo_score += 35;
                                if (!empty($blog['meta_description'])) $seo_score += 35;
                                if (!empty($blog['meta_keywords'])) $seo_score += 15;
                                if (!empty($blog['featured_image'])) $seo_score += 15;
                                ?>
                                <?php if ($seo_score >= 80): ?>
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1" title="Meta Title & Description configured">
                                        <i class="fas fa-check-double me-1"></i>Optimized (<?php echo $seo_score; ?>%)
                                    </span>
                                <?php elseif ($seo_score >= 40): ?>
                                    <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-2 py-1" title="Partial SEO tags configured">
                                        <i class="fas fa-exclamation me-1"></i>Fair (<?php echo $seo_score; ?>%)
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2 py-1" title="Missing meta title or description">
                                        <i class="fas fa-times me-1"></i>Needs SEO
                                    </span>
                                <?php endif; ?>
                            </td>

                            <!-- Status Toggle -->
                            <td>
                                <?php if ($blog['status'] == 1): ?>
                                    <a href="blog_manage?toggle_status=0&id=<?php echo $blog['id']; ?>" class="badge bg-success text-white text-decoration-none rounded-pill px-2 py-1" title="Click to unpublish">
                                        <i class="fas fa-circle fa-2xs me-1"></i>Published
                                    </a>
                                <?php else: ?>
                                    <a href="blog_manage?toggle_status=1&id=<?php echo $blog['id']; ?>" class="badge bg-secondary text-white text-decoration-none rounded-pill px-2 py-1" title="Click to publish">
                                        <i class="fas fa-pause-circle me-1"></i>Draft
                                    </a>
                                <?php endif; ?>
                            </td>

                            <!-- Date -->
                            <td class="small text-muted text-nowrap">
                                <?php echo date('M d, Y', strtotime($blog['published_at'] ?? 'now')); ?>
                            </td>

                            <!-- Actions -->
                            <td class="pe-4 text-end text-nowrap">
                                <a href="../blog/<?php echo $blog['slug']; ?>" target="_blank" class="btn btn-sm btn-outline-info rounded-pill me-1" title="View Article on Live Site">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                                <a href="blog_edit?id=<?php echo $blog['id']; ?>" class="btn btn-sm btn-outline-primary rounded-pill me-1" title="Edit Article & SEO">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="blog_manage?delete=<?php echo $blog['id']; ?>" class="btn btn-sm btn-outline-danger rounded-pill" onclick="return confirm('Delete this blog permanently?');" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="fas fa-newspaper fa-3x mb-3 text-secondary opacity-50 d-block"></i>
                            No blog articles match your current criteria.
                            <div class="mt-3">
                                <a href="blog_add" class="btn btn-sm btn-primary rounded-pill px-4">
                                    <i class="fas fa-plus me-1"></i> Create Your First Blog
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
        <div class="card-footer bg-white border-0 py-3 d-flex justify-content-between align-items-center">
            <span class="small text-muted">Showing <?php echo min($total_rows, $offset + 1); ?> - <?php echo min($total_rows, $offset + $per_page); ?> of <?php echo $total_rows; ?> posts</span>
            <ul class="pagination pagination-sm mb-0">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
                        <a class="page-link" href="?p=<?php echo $i; ?>&category=<?php echo $filter_cat; ?>&status=<?php echo $filter_status; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>


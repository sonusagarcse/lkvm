<?php
// admin/blog_edit.php - Edit Blog Post with Full SEO Suite
require_once 'includes/auth_check.php';
require_once '../connection.php';

$message = '';
$error = '';
$uploadDir = '../uploads/';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    header("Location: blog_manage");
    exit();
}

// Fetch existing blog post
$stmt = $con->prepare("SELECT * FROM blogs WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$blog = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$blog) {
    header("Location: blog_manage");
    exit();
}

// Helper function to create clean slug
function slugify($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    return empty($text) ? 'post-' . time() : $text;
}

// Initialize form variables with existing database values or previous POST values
$title = $_POST['title'] ?? $blog['title'];
$slug = $_POST['slug'] ?? $blog['slug'];
$category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : intval($blog['category_id']);
$author_name = $_POST['author_name'] ?? $blog['author_name'];
$excerpt = $_POST['excerpt'] ?? $blog['excerpt'];
$content = $_POST['content'] ?? $blog['content'];
$tags = $_POST['tags'] ?? $blog['tags'];
$status = isset($_POST['status']) ? 1 : (($_SERVER['REQUEST_METHOD'] === 'POST') ? 0 : intval($blog['status']));

$meta_title = $_POST['meta_title'] ?? $blog['meta_title'];
$meta_description = $_POST['meta_description'] ?? $blog['meta_description'];
$meta_keywords = $_POST['meta_keywords'] ?? $blog['meta_keywords'];
$canonical_url = $_POST['canonical_url'] ?? $blog['canonical_url'];
$schema_type = $_POST['schema_type'] ?? ($blog['schema_type'] ?: 'BlogPosting');
$published_at = $_POST['published_at'] ?? $blog['published_at'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['save_blog']) || isset($_POST['title']))) {
    if (!verify_csrf_token()) {
        die("CSRF validation failed. Please refresh the page and try again.");
    }

    $title = trim($_POST['title'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $category_id = intval($_POST['category_id'] ?? 0);
    $author_name = trim($_POST['author_name'] ?? 'LKVM Team');
    $excerpt = trim($_POST['excerpt'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $tags = trim($_POST['tags'] ?? '');
    $status = isset($_POST['status']) ? 1 : 0;

    // SEO Fields
    $meta_title = trim($_POST['meta_title'] ?? '');
    $meta_description = trim($_POST['meta_description'] ?? '');
    $meta_keywords = trim($_POST['meta_keywords'] ?? '');
    $canonical_url = trim($_POST['canonical_url'] ?? '');
    $schema_type = trim($_POST['schema_type'] ?? 'BlogPosting');
    $published_at_raw = !empty($_POST['published_at']) ? $_POST['published_at'] : $blog['published_at'];
    $published_at_db = date('Y-m-d H:i:s', strtotime($published_at_raw));

    // Content check
    $content_text = trim(strip_tags($content, '<img><iframe><video><audio>'));

    if (empty($title)) {
        $error = "Blog title is required.";
    } elseif ($category_id <= 0) {
        $error = "Please select a valid blog category.";
    } elseif (empty($content) || (empty($content_text) && strpos($content, '<img') === false)) {
        $error = "Blog content cannot be empty. Please enter your article body before saving.";
    } else {
        $slug = empty($slug) ? slugify($title) : slugify($slug);

        // Check slug uniqueness (excluding current post)
        $stmt_check = $con->prepare("SELECT id FROM blogs WHERE slug = ? AND id != ?");
        $stmt_check->bind_param("si", $slug, $id);
        $stmt_check->execute();
        $stmt_check->store_result();
        if ($stmt_check->num_rows > 0) {
            $slug = $slug . '-' . time();
        }
        $stmt_check->close();

        if (empty($meta_title)) {
            $meta_title = $title . ' | ' . ($SITE_NAME ?? 'LKVM');
        }
        if (empty($meta_description)) {
            $meta_description = !empty($excerpt) ? substr($excerpt, 0, 160) : substr(strip_tags($content), 0, 160);
        }

        $featured_image = $blog['featured_image'];

        // Handle Featured Image Upload with Automatic Space-Saving Compression
        if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $allowed_exts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            $allowed_mimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
            // Compress and resize image (max 1280x1280, 80% quality, modern WebP format) to save ~80-95% space
            $upload_res = secure_upload_file($_FILES['featured_image'], $uploadDir, $allowed_exts, $allowed_mimes, 10485760, true, 1280, 1280, 80, 'blog_cover_');
            if ($upload_res['success']) {
                if (!empty($featured_image) && file_exists($uploadDir . $featured_image)) {
                    @unlink($uploadDir . $featured_image);
                }
                $featured_image = $upload_res['filename'];
            } else {
                $error = "Featured Image Error: " . $upload_res['error'];
            }
        }

        // Handle Image Removal Checkbox
        if (isset($_POST['remove_image']) && $_POST['remove_image'] == '1') {
            if (!empty($featured_image) && file_exists($uploadDir . $featured_image)) {
                @unlink($uploadDir . $featured_image);
            }
            $featured_image = '';
        }

        // Automatically compress any inline base64 images that were pasted into the editor
        if (empty($error)) {
            $content = extract_and_compress_base64_images($content, $uploadDir, $SITE_URL, 'blog_inline_');
        }

        if (empty($error)) {
            $update_sql = "
                UPDATE blogs SET 
                    category_id = ?, 
                    title = ?, 
                    slug = ?, 
                    excerpt = ?, 
                    content = ?, 
                    featured_image = ?, 
                    author_name = ?, 
                    tags = ?, 
                    meta_title = ?, 
                    meta_description = ?, 
                    meta_keywords = ?, 
                    canonical_url = ?, 
                    schema_type = ?, 
                    status = ?, 
                    published_at = ? 
                WHERE id = ?
            ";
            $stmt = $con->prepare($update_sql);
            if ($stmt) {
                $stmt->bind_param(
                    "issssssssssssisi",
                    $category_id,
                    $title,
                    $slug,
                    $excerpt,
                    $content,
                    $featured_image,
                    $author_name,
                    $tags,
                    $meta_title,
                    $meta_description,
                    $meta_keywords,
                    $canonical_url,
                    $schema_type,
                    $status,
                    $published_at_db,
                    $id
                );

                if ($stmt->execute()) {
                    $stmt->close();
                    header("Location: blog_manage?msg=updated");
                    exit();
                } else {
                    $error = "Failed to update blog: " . $stmt->error;
                    $stmt->close();
                }
            } else {
                $error = "Database prepare failed: " . $con->error;
            }
        }
    }
}

// Fetch categories safely into array
$categories = [];
$cats_q = mysqli_query($con, "SELECT id, name FROM blog_categories WHERE status = 1 ORDER BY name ASC");
if ($cats_q) {
    while ($cat = mysqli_fetch_assoc($cats_q)) {
        $categories[] = $cat;
    }
}

require_once 'includes/header.php';
?>

<!-- Summernote Lite Editor CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css" rel="stylesheet">
<style>
.note-editor.note-frame {
    border: 1.5px solid #cbd5e1 !important;
    border-radius: 14px !important;
    overflow: hidden !important;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03) !important;
}
.note-toolbar {
    background: #f8fafc !important;
    border-bottom: 1px solid #e2e8f0 !important;
    padding: 8px 12px !important;
}
.note-btn {
    border-radius: 6px !important;
    background: #ffffff !important;
    border: 1px solid #e2e8f0 !important;
    color: #334155 !important;
}

/* Editor Typography - Matches Blog Detail Page Exactly */
.note-editable {
    font-size: 16.5px !important;
    line-height: 1.75 !important;
    color: #334155 !important;
    min-height: 400px !important;
    background: #ffffff !important;
    padding: 24px !important;
}
.note-editable p {
    margin-top: 0 !important;
    margin-bottom: 14px !important;
    line-height: 1.75 !important;
    color: #334155 !important;
}
.note-editable h1, 
.note-editable h2, 
.note-editable h3, 
.note-editable h4, 
.note-editable h5, 
.note-editable h6 {
    color: #0f172a !important;
    font-weight: 700 !important;
    line-height: 1.35 !important;
    margin-top: 24px !important;
    margin-bottom: 10px !important;
    letter-spacing: -0.012em !important;
}
.note-editable h1 { font-size: 1.75rem !important; }
.note-editable h2 { font-size: 1.5rem !important; }
.note-editable h3 { font-size: 1.3rem !important; }
.note-editable h4 { font-size: 1.15rem !important; }
.note-editable h5 { font-size: 1.05rem !important; }

.note-editable ul, 
.note-editable ol {
    margin-top: 6px !important;
    margin-bottom: 16px !important;
    padding-left: 28px !important;
}
.note-editable ul { list-style-type: disc !important; }
.note-editable ol { list-style-type: decimal !important; }
.note-editable li {
    font-size: 16px !important;
    color: #334155 !important;
    line-height: 1.65 !important;
    margin-bottom: 6px !important;
    padding-left: 4px !important;
}
.note-editable li > p,
.note-editable li p {
    margin-top: 0 !important;
    margin-bottom: 0 !important;
    display: inline !important;
    line-height: inherit !important;
}
.note-editable blockquote {
    border-left: 4px solid #fdc800 !important;
    background: #f8fafc !important;
    padding: 16px 24px !important;
    border-radius: 0 12px 12px 0 !important;
    font-style: italic !important;
    color: #1e293b !important;
    margin: 20px 0 !important;
    line-height: 1.65 !important;
}

/* Summernote Modal Isolation Fix (Fixes Picture option glitch with Bootstrap 5) */
.note-modal {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    right: 0 !important;
    bottom: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    z-index: 10500 !important;
    background: rgba(15, 23, 42, 0.6) !important;
    backdrop-filter: blur(4px) !important;
    align-items: center !important;
    justify-content: center !important;
    margin: 0 !important;
    padding: 20px !important;
}
.note-modal:not(.open) {
    display: none !important;
}
.note-modal.open {
    display: flex !important;
}
.note-modal-backdrop {
    display: none !important;
}
.note-modal .modal-dialog {
    position: relative !important;
    width: 100% !important;
    max-width: 520px !important;
    margin: auto !important;
    transform: none !important;
    z-index: 10510 !important;
    pointer-events: auto !important;
}
.note-modal .modal-content {
    background: #ffffff !important;
    border: none !important;
    border-radius: 20px !important;
    box-shadow: 0 25px 60px rgba(0, 0, 0, 0.25) !important;
    overflow: hidden !important;
}
.note-modal .modal-header {
    background: #f8fafc !important;
    border-bottom: 1px solid #e2e8f0 !important;
    padding: 18px 24px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
}
.note-modal .modal-title {
    font-size: 1.15rem !important;
    font-weight: 700 !important;
    color: #0f172a !important;
    margin: 0 !important;
}
.note-modal .close {
    border: none !important;
    background: #e2e8f0 !important;
    width: 32px !important;
    height: 32px !important;
    border-radius: 50% !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-size: 20px !important;
    color: #475569 !important;
    cursor: pointer !important;
    transition: all 0.2s !important;
    line-height: 1 !important;
}
.note-modal .close:hover {
    background: #cbd5e1 !important;
    color: #0f172a !important;
}
.note-modal .modal-body {
    padding: 24px !important;
}
.note-modal .note-form-group, 
.note-modal .form-group {
    margin-bottom: 16px !important;
}
.note-modal .note-form-label {
    font-size: 0.9rem !important;
    font-weight: 600 !important;
    color: #334155 !important;
    margin-bottom: 6px !important;
    display: block !important;
}
.note-modal .form-control,
.note-modal .note-input {
    border: 1.5px solid #cbd5e1 !important;
    border-radius: 10px !important;
    padding: 10px 14px !important;
    font-size: 0.95rem !important;
    width: 100% !important;
}
.note-modal .modal-footer {
    background: #f8fafc !important;
    border-top: 1px solid #e2e8f0 !important;
    padding: 14px 24px !important;
}
.note-modal .btn-primary, 
.note-modal .note-btn-primary {
    background: #002147 !important;
    border: none !important;
    border-radius: 50px !important;
    padding: 9px 24px !important;
    font-weight: 700 !important;
    color: #ffffff !important;
}
</style>

<div class="row mb-4 align-items-center">
    <div class="col-md-7">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="index">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="blog_manage">Blog System</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Article #<?php echo $id; ?></li>
            </ol>
        </nav>
        <h3 class="fw-bold mb-0 text-dark">
            <i class="fas fa-edit text-primary me-2"></i>Edit Blog Article
        </h3>
        <p class="text-muted small mb-0">Modify content, cover image, and enhance SEO settings</p>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0 d-flex justify-content-md-end gap-2">
        <a href="<?php echo $SITE_URL; ?>/blog/<?php echo urlencode($blog['slug']); ?>" target="_blank" class="btn btn-outline-info rounded-pill px-3 shadow-sm">
            <i class="fas fa-external-link-alt me-1"></i> Preview Live
        </a>
        <a href="blog_manage" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm">
            <i class="fas fa-arrow-left me-1"></i> Back to Blog List
        </a>
    </div>
</div>

<?php if (!empty($error)): ?>
    <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i><?php echo htmlspecialchars($error); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<form method="POST" action="" enctype="multipart/form-data" id="blogForm">
    <?php echo csrf_field(); ?>
    <div class="row g-4">
        <!-- Left Main Content Column -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <!-- Title -->
                    <div class="mb-3">
                        <label class="form-label fw-bold fs-6">Article Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="blog_title" class="form-control form-control-lg fw-bold" placeholder="Enter an engaging headline..." value="<?php echo htmlspecialchars($title); ?>" required>
                    </div>

                    <!-- Slug Display & Editor -->
                    <div class="mb-4 p-3 bg-light rounded-3 border d-flex align-items-center gap-2 flex-wrap">
                        <span class="small fw-bold text-muted"><i class="fas fa-link me-1"></i>Permalink:</span>
                        <span class="small text-muted font-monospace"><?php echo $SITE_URL; ?>/blog/</span>
                        <input type="text" name="slug" id="blog_slug" class="form-control form-control-sm font-monospace flex-grow-1" style="max-width: 320px;" value="<?php echo htmlspecialchars($slug); ?>" required>
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill" onclick="regenerateSlug()" title="Regenerate from Title"><i class="fas fa-sync-alt"></i></button>
                    </div>

                    <!-- Excerpt / Summary -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label fw-bold mb-0">Short Excerpt / Summary</label>
                            <span class="small text-muted" id="excerpt_counter">0 / 250</span>
                        </div>
                        <textarea name="excerpt" id="blog_excerpt" class="form-control" rows="3" placeholder="A captivating summary..."><?php echo htmlspecialchars($excerpt); ?></textarea>
                    </div>

                    <!-- Rich Text Editor -->
                    <div class="mb-2">
                        <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                            <label class="form-label fw-bold mb-0">Full Content Body <span class="text-danger">*</span></label>
                            <div class="d-flex align-items-center gap-2">
                                <span class="small text-muted" id="upload_status"></span>
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm" onclick="triggerQuickImageUpload()" title="Insert an image directly into the article">
                                    <i class="fas fa-camera me-1"></i> Add Image to Article
                                </button>
                                <input type="file" id="quick_editor_image_file" accept="image/*" style="display:none;" onchange="handleQuickImageUpload(this)">
                            </div>
                        </div>
                        <textarea name="content" id="blog_content" class="form-control" rows="14" style="width: 100%; min-height: 400px;"><?php echo htmlspecialchars($content); ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Complete SEO Suite Section -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark">
                        <i class="fab fa-google text-primary me-2"></i>Complete SEO & Search Engine Optimization
                    </h5>
                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1 fw-bold">
                        <i class="fas fa-shield-alt me-1"></i>SERP Suite
                    </span>
                </div>
                <div class="card-body p-4">
                    <!-- Google Search Result Live Simulator -->
                    <div class="mb-4 p-3 rounded-4" style="background: #f8fafc; border: 1.5px dashed #cbd5e1;">
                        <small class="text-uppercase fw-bold text-muted d-block mb-2" style="font-size: 0.72rem; letter-spacing: 0.5px;">
                            <i class="fas fa-search me-1 text-primary"></i>Google Search Engine Result Snippet (SERP Live Preview)
                        </small>
                        <div class="p-3 bg-white rounded-3 shadow-sm" style="max-width: 600px; font-family: Arial, sans-serif;">
                            <div class="d-flex align-items-center gap-2 mb-1" style="font-size: 13px; color: #202124;">
                                <div style="width: 18px; height: 18px; border-radius: 50%; background: #002147; display: flex; align-items: center; justify-content: center; color: white; font-size: 10px; font-weight: bold;">L</div>
                                <span style="color: #202124;"><?php echo parse_url($SITE_URL, PHP_URL_HOST); ?></span>
                                <span style="color: #5f6368;">› blog › <span id="serp_slug_preview"><?php echo htmlspecialchars($slug); ?></span></span>
                            </div>
                            <div id="serp_title_preview" style="color: #1a0dab; font-size: 19px; line-height: 1.3; font-weight: 400; cursor: pointer; text-decoration: none;">
                                <?php echo htmlspecialchars($meta_title ?: $title); ?>
                            </div>
                            <div id="serp_desc_preview" style="color: #4d5156; font-size: 14px; line-height: 1.58; margin-top: 3px;">
                                <?php echo htmlspecialchars($meta_description ?: ($excerpt ?: 'Article description will appear here in Google search results.')); ?>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <!-- Meta Title -->
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label fw-bold mb-0">SEO Meta Title</label>
                                <span class="small font-monospace" id="meta_title_counter">0 / 60 characters</span>
                            </div>
                            <input type="text" name="meta_title" id="meta_title" class="form-control" placeholder="Targeted SEO Title for browser tabs & Google search..." value="<?php echo htmlspecialchars($meta_title); ?>">
                            <div class="form-text">Recommended: 50 - 60 characters. Leave blank to auto-use article title.</div>
                        </div>

                        <!-- Meta Description -->
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label fw-bold mb-0">SEO Meta Description</label>
                                <span class="small font-monospace" id="meta_desc_counter">0 / 160 characters</span>
                            </div>
                            <textarea name="meta_description" id="meta_description" class="form-control" rows="3" placeholder="Concise summary shown under headline on Google search results..."><?php echo htmlspecialchars($meta_description); ?></textarea>
                            <div class="form-text">Recommended: 140 - 160 characters. Highlight keywords and a clear call to action.</div>
                        </div>

                        <!-- Meta Keywords -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Focus & Meta Keywords</label>
                            <input type="text" name="meta_keywords" class="form-control" placeholder="e.g. computer training, bihar education, kyp" value="<?php echo htmlspecialchars($meta_keywords); ?>">
                            <div class="form-text">Comma-separated keywords.</div>
                        </div>

                        <!-- Schema.org Type -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Schema.org Structured Data Type</label>
                            <select name="schema_type" class="form-select">
                                <option value="BlogPosting" <?php echo ($schema_type === 'BlogPosting') ? 'selected' : ''; ?>>BlogPosting (Recommended)</option>
                                <option value="Article" <?php echo ($schema_type === 'Article') ? 'selected' : ''; ?>>Article</option>
                                <option value="NewsArticle" <?php echo ($schema_type === 'NewsArticle') ? 'selected' : ''; ?>>NewsArticle</option>
                                <option value="EducationalOccupationalCredential" <?php echo ($schema_type === 'EducationalOccupationalCredential') ? 'selected' : ''; ?>>Educational Article</option>
                            </select>
                            <div class="form-text">Powers Google Rich Snippets & knowledge panels.</div>
                        </div>

                        <!-- Canonical URL -->
                        <div class="col-12">
                            <label class="form-label fw-bold">Canonical URL (Advanced)</label>
                            <input type="url" name="canonical_url" class="form-control" placeholder="Defaults to this article URL automatically" value="<?php echo htmlspecialchars($canonical_url); ?>">
                            <div class="form-text">Only needed if this article was syndicated from another canonical source.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Sidebar Settings Column -->
        <div class="col-lg-4">
            <!-- Publishing & Status Box -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 sticky-top" style="top: 20px; z-index: 5;">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-bold mb-0 text-dark"><i class="fas fa-sliders-h text-primary me-2"></i>Publishing Settings</h6>
                </div>
                <div class="card-body p-4">
                    <!-- Status Toggle -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Post Status</label>
                        <div class="form-check form-switch mt-1">
                            <input class="form-check-input" type="checkbox" name="status" id="blog_status" value="1" <?php echo ($status === 1) ? 'checked' : ''; ?> style="width: 50px; height: 26px;">
                            <label class="form-check-label fw-bold ms-2" for="blog_status" id="status_label"><?php echo ($status === 1) ? 'Published Live' : 'Saved as Draft'; ?></label>
                        </div>
                    </div>

                    <!-- Category -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label fw-bold mb-0">Category <span class="text-danger">*</span></label>
                            <a href="blog_categories" target="_blank" class="small text-primary text-decoration-none">+ New Category</a>
                        </div>
                        <select name="category_id" id="blog_category" class="form-select" required>
                            <option value="">-- Choose Category --</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo ($category_id == $cat['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Author Name -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Author Name</label>
                        <input type="text" name="author_name" class="form-control" value="<?php echo htmlspecialchars($author_name); ?>" required>
                    </div>

                    <!-- Tags -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tags</label>
                        <input type="text" name="tags" class="form-control" placeholder="e.g. Technology, Students, KYP" value="<?php echo htmlspecialchars($tags); ?>">
                        <div class="form-text">Comma-separated tags for filtering & search.</div>
                    </div>

                    <!-- Publication Date -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Publish Date & Time</label>
                        <input type="datetime-local" name="published_at" class="form-control" value="<?php echo date('Y-m-d\TH:i', strtotime($published_at ?: 'now')); ?>">
                    </div>

                    <!-- Featured Image Upload -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Featured Cover Image</label>
                        <div class="border rounded-3 p-3 bg-light text-center">
                            <?php if (!empty($blog['featured_image']) && file_exists('../uploads/' . $blog['featured_image'])): ?>
                                <div id="existing_image_box" class="mb-2 position-relative">
                                    <img src="../uploads/<?php echo htmlspecialchars($blog['featured_image']); ?>" id="current_img_display" class="img-fluid rounded-3 shadow-sm" style="max-height: 180px; width: 100%; object-fit: cover; aspect-ratio: 16/9;">
                                    <div class="form-check mt-2 text-start">
                                        <input class="form-check-input" type="checkbox" name="remove_image" value="1" id="removeImgCheck">
                                        <label class="form-check-label text-danger small fw-bold" for="removeImgCheck">Remove this image</label>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div id="image_preview_box" class="mb-2" style="display: none;">
                                <img src="" id="img_preview" class="img-fluid rounded-3 shadow-sm" style="max-height: 180px; width: 100%; object-fit: cover; aspect-ratio: 16/9;">
                            </div>
                            <input type="file" name="featured_image" id="featured_image_input" class="form-control" accept="image/*">
                            <div class="form-text mt-2 small text-muted">
                                <strong>Recommended Size: 1280 &times; 720 Px</strong> (16:9 Aspect Ratio, Max 3MB)
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-grid gap-2">
                        <button type="submit" name="save_blog" id="btnUpdate" class="btn btn-primary rounded-pill py-2 fw-bold shadow-sm">
                            <i class="fas fa-save me-2"></i> Update Blog Post
                        </button>
                        <a href="blog_manage" class="btn btn-light rounded-pill py-2">Cancel</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Summernote Lite Script (via Cloudflare CDN) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"></script>
<script>
// Initialize Summernote safely
function initSummernoteEditor() {
    if (window.jQuery && typeof jQuery.fn.summernote === 'function') {
        $('#blog_content').summernote({
            placeholder: 'Write your comprehensive blog article here...',
            tabsize: 2,
            height: 420,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video', 'hr']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            callbacks: {
                onImageUpload: function(files) {
                    for (let i = 0; i < files.length; i++) {
                        uploadEditorImage(files[i]);
                    }
                },
                onPaste: function(e) {
                    const clipboardData = e.originalEvent.clipboardData || window.clipboardData;
                    if (clipboardData && clipboardData.items) {
                        for (let i = 0; i < clipboardData.items.length; i++) {
                            const item = clipboardData.items[i];
                            if (item.type && item.type.indexOf("image") !== -1) {
                                const file = item.getAsFile();
                                if (file) {
                                    uploadEditorImage(file);
                                    e.preventDefault();
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        });
    } else {
        setTimeout(initSummernoteEditor, 50);
    }
}

// Quick Image Upload handlers
function triggerQuickImageUpload() {
    const input = document.getElementById('quick_editor_image_file');
    if (input) input.click();
}

function handleQuickImageUpload(input) {
    if (input.files && input.files[0]) {
        uploadEditorImage(input.files[0]);
        input.value = '';
    }
}

// Upload inline Summernote image to server instead of massive Base64
function uploadEditorImage(file) {
    const data = new FormData();
    data.append("image", file);
    const csrfToken = document.querySelector('input[name="csrf_token"]');
    if (csrfToken) {
        data.append("csrf_token", csrfToken.value);
    }

    const saveStatus = document.getElementById('upload_status');
    if (saveStatus) saveStatus.innerText = 'Compressing & uploading image...';

    fetch('ajax_blog_image', {
        method: 'POST',
        body: data
    })
    .then(r => r.json())
    .then(res => {
        if (res && res.success) {
            $('#blog_content').summernote('insertImage', res.url, function($img) {
                $img.addClass('img-fluid rounded shadow-sm my-3');
            });

            // Cleanly close any open Summernote modal dialog
            if (window.jQuery) {
                $('.note-modal').removeClass('open').hide();
                $('.note-modal-backdrop').remove();
            }

            if (saveStatus) {
                let savingsText = '';
                if (res.original_size && res.reduced_size) {
                    const origKB = Math.round(res.original_size / 1024);
                    const redKB = Math.round(res.reduced_size / 1024);
                    const pct = Math.round((1 - (res.reduced_size / res.original_size)) * 100);
                    savingsText = ` (Reduced ${origKB}KB → ${redKB}KB, -${pct}%)`;
                }
                saveStatus.innerText = 'Image compressed & inserted!' + savingsText;
            }
        } else {
            alert('Image upload failed: ' + (res.error || 'Server error'));
            if (saveStatus) saveStatus.innerText = '';
        }
    })
    .catch(err => {
        console.error(err);
        alert('Image upload failed. Upload file manually or check file size (max 10MB).');
        if (saveStatus) saveStatus.innerText = '';
    });
}

// Event bindings for Summernote Modal Dialogs (Fixes Bootstrap 5 modal glitch)
if (window.jQuery) {
    $(document).on('change', '.note-image-input', function(e) {
        if (this.files && this.files.length) {
            for (let i = 0; i < this.files.length; i++) {
                uploadEditorImage(this.files[i]);
            }
            $(this).closest('.note-modal').removeClass('open').hide();
            $('.note-modal-backdrop').remove();
        }
    });

    $(document).on('click', '.note-modal .close, .note-modal [data-dismiss="modal"]', function(e) {
        e.preventDefault();
        $(this).closest('.note-modal').removeClass('open').hide();
        $('.note-modal-backdrop').remove();
    });

    $(document).on('click', '.note-modal', function(e) {
        if ($(e.target).hasClass('note-modal')) {
            $(this).removeClass('open').hide();
            $('.note-modal-backdrop').remove();
        }
    });
}

initSummernoteEditor();

document.addEventListener("DOMContentLoaded", function() {
    const blogForm = document.getElementById('blogForm');
    const titleInput = document.getElementById('blog_title');
    const slugInput = document.getElementById('blog_slug');
    const excerptInput = document.getElementById('blog_excerpt');
    const metaTitleInput = document.getElementById('meta_title');
    const metaDescInput = document.getElementById('meta_description');

    const serpTitle = document.getElementById('serp_title_preview');
    const serpSlug = document.getElementById('serp_slug_preview');
    const serpDesc = document.getElementById('serp_desc_preview');

    const metaTitleCounter = document.getElementById('meta_title_counter');
    const metaDescCounter = document.getElementById('meta_desc_counter');
    const excerptCounter = document.getElementById('excerpt_counter');

    const statusToggle = document.getElementById('blog_status');
    const statusLabel = document.getElementById('status_label');
    const imageInput = document.getElementById('featured_image_input');
    const imgPreview = document.getElementById('img_preview');
    const imagePreviewBox = document.getElementById('image_preview_box');
    const existingImageBox = document.getElementById('existing_image_box');

    function slugify(text) {
        return text.toString().toLowerCase()
            .replace(/\s+/g, '-')
            .replace(/[^\w\-]+/g, '')
            .replace(/\-\-+/g, '-')
            .replace(/^-+/, '')
            .replace(/-+$/, '');
    }

    window.regenerateSlug = function() {
        slugInput.value = slugify(titleInput.value);
        updateSERP();
    };

    slugInput.addEventListener('input', updateSERP);
    titleInput.addEventListener('input', updateSERP);

    excerptInput.addEventListener('input', function() {
        excerptCounter.innerText = `${this.value.length} / 250`;
        updateSERP();
    });

    metaTitleInput.addEventListener('input', updateSERP);
    metaDescInput.addEventListener('input', updateSERP);

    function updateSERP() {
        const titleVal = metaTitleInput.value.trim() || titleInput.value.trim() || 'Your Article Headline Will Appear Here';
        const slugVal = slugInput.value.trim() || slugify(titleInput.value.trim()) || 'your-slug';
        let descVal = metaDescInput.value.trim() || excerptInput.value.trim();

        if (!descVal && window.jQuery && typeof jQuery.fn.summernote === 'function') {
            const rawCode = $('#blog_content').summernote('code');
            const d = document.createElement('div');
            d.innerHTML = rawCode;
            descVal = (d.textContent || d.innerText || '').substring(0, 160);
        }
        if (!descVal) {
            descVal = 'Meta description snippet will appear here in Google search results.';
        }

        if (serpTitle) serpTitle.innerText = titleVal;
        if (serpSlug) serpSlug.innerText = slugVal;
        if (serpDesc) serpDesc.innerText = descVal;

        // Counters
        const titleLen = metaTitleInput.value.length;
        if (metaTitleCounter) {
            metaTitleCounter.innerText = `${titleLen} / 60 chars`;
            if (titleLen > 60) {
                metaTitleCounter.className = 'small font-monospace text-danger';
            } else if (titleLen >= 45) {
                metaTitleCounter.className = 'small font-monospace text-success fw-bold';
            } else {
                metaTitleCounter.className = 'small font-monospace text-muted';
            }
        }

        const descLen = metaDescInput.value.length;
        if (metaDescCounter) {
            metaDescCounter.innerText = `${descLen} / 160 chars`;
            if (descLen > 160) {
                metaDescCounter.className = 'small font-monospace text-danger';
            } else if (descLen >= 120) {
                metaDescCounter.className = 'small font-monospace text-success fw-bold';
            } else {
                metaDescCounter.className = 'small font-monospace text-muted';
            }
        }
    }

    if (statusToggle) {
        statusToggle.addEventListener('change', function() {
            statusLabel.innerText = this.checked ? 'Published Live' : 'Saved as Draft';
        });
    }

    if (imageInput) {
        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imgPreview.src = e.target.result;
                    imagePreviewBox.style.display = 'block';
                    if (existingImageBox) existingImageBox.style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // CRITICAL: Synchronize Summernote editor code into <textarea> before form submit
    blogForm.addEventListener('submit', function(e) {
        if (window.jQuery && typeof jQuery.fn.summernote === 'function') {
            const editorCode = $('#blog_content').summernote('code');
            const isEmpty = $('#blog_content').summernote('isEmpty');

            if (isEmpty || editorCode === '<p><br></p>' || editorCode.trim() === '') {
                $('#blog_content').val('');
            } else {
                $('#blog_content').val(editorCode);
            }
        }

        const finalVal = document.getElementById('blog_content').value.trim();
        const stripped = finalVal.replace(/<[^>]*>?/gm, '').trim();

        if (!finalVal || (stripped === '' && !finalVal.includes('<img'))) {
            e.preventDefault();
            alert('Please write your blog article content before saving.');
            if (window.jQuery && typeof jQuery.fn.summernote === 'function') {
                $('#blog_content').summernote('focus');
            }
            return false;
        }

        const catSelect = document.getElementById('blog_category');
        if (!catSelect.value) {
            e.preventDefault();
            alert('Please select a category for this article.');
            catSelect.focus();
            return false;
        }

        // Show update spinner on button
        const btn = document.getElementById('btnUpdate');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Saving Changes...';
            let hiddenSave = document.getElementById('hidden_save_blog');
            if (!hiddenSave) {
                hiddenSave = document.createElement('input');
                hiddenSave.type = 'hidden';
                hiddenSave.name = 'save_blog';
                hiddenSave.id = 'hidden_save_blog';
                hiddenSave.value = '1';
                blogForm.appendChild(hiddenSave);
            }
        }
    });

    updateSERP();
});
</script>

<?php require_once 'includes/footer.php'; ?>

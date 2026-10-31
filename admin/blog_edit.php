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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_blog'])) {
    if (!verify_csrf_token()) {
        die("CSRF validation failed.");
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
    $published_at = !empty($_POST['published_at']) ? $_POST['published_at'] : $blog['published_at'];

    if (empty($title)) {
        $error = "Blog title is required.";
    } elseif ($category_id <= 0) {
        $error = "Please select a valid blog category.";
    } elseif (empty($content)) {
        $error = "Blog content cannot be empty.";
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
            $meta_title = $title . ' | ' . $SITE_NAME;
        }
        if (empty($meta_description)) {
            $meta_description = !empty($excerpt) ? substr($excerpt, 0, 160) : substr(strip_tags($content), 0, 160);
        }

        $featured_image = $blog['featured_image'];

        // Handle Image Upload
        if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $allowed_exts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            $allowed_mimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
            $upload_res = secure_upload_file($_FILES['featured_image'], $uploadDir, $allowed_exts, $allowed_mimes, 3145728);
            if ($upload_res['success']) {
                if (!empty($featured_image) && file_exists($uploadDir . $featured_image)) {
                    @unlink($uploadDir . $featured_image);
                }
                $featured_image = $upload_res['filename'];
            } else {
                $error = "Featured Image Error: " . $upload_res['error'];
            }
        }

        // Handle Image Removal
        if (isset($_POST['remove_image']) && $_POST['remove_image'] == '1') {
            if (!empty($featured_image) && file_exists($uploadDir . $featured_image)) {
                @unlink($uploadDir . $featured_image);
            }
            $featured_image = '';
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
                    $published_at,
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

// Fetch categories
$cats_q = mysqli_query($con, "SELECT id, name FROM blog_categories WHERE status = 1 ORDER BY name ASC");

require_once 'includes/header.php';
?>

<!-- Summernote Lite Editor CSS (via High-Speed Cloudflare CDN) -->
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
.note-editable {
    font-size: 15.5px !important;
    line-height: 1.75 !important;
    color: #1e293b !important;
    min-height: 360px !important;
    background: #ffffff !important;
}
</style>

<div class="row mb-4 align-items-center">
    <div class="col-md-7">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="index">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="blog_manage">Blog System</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Blog #<?php echo $blog['id']; ?></li>
            </ol>
        </nav>
        <h3 class="fw-bold mb-0 text-dark">
            <i class="fas fa-edit text-primary me-2"></i>Edit Article: <?php echo htmlspecialchars($blog['title']); ?>
        </h3>
        <p class="text-muted small mb-0">Update content, fine-tune SEO metadata, and optimize reader engagement</p>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0 d-flex gap-2 justify-content-md-end">
        <a href="../blog/<?php echo $blog['slug']; ?>" target="_blank" class="btn btn-outline-info rounded-pill px-3 shadow-sm">
            <i class="fas fa-external-link-alt me-1"></i> View Live
        </a>
        <a href="blog_manage" class="btn btn-outline-secondary rounded-pill px-4 shadow-sm">
            <i class="fas fa-arrow-left me-1"></i> Back to Blog List
        </a>
    </div>
</div>

<?php if ($error): ?>
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
                        <input type="text" name="title" id="blog_title" class="form-control form-control-lg fw-bold" placeholder="Enter an engaging headline..." value="<?php echo htmlspecialchars($blog['title']); ?>" required>
                    </div>

                    <!-- Slug Display & Editor -->
                    <div class="mb-4 p-3 bg-light rounded-3 border d-flex align-items-center gap-2 flex-wrap">
                        <span class="small fw-bold text-muted"><i class="fas fa-link me-1"></i>Permalink:</span>
                        <span class="small text-muted font-monospace"><?php echo $SITE_URL; ?>/blog/</span>
                        <input type="text" name="slug" id="blog_slug" class="form-control form-control-sm font-monospace flex-grow-1" style="max-width: 320px;" value="<?php echo htmlspecialchars($blog['slug']); ?>" required>
                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill" onclick="regenerateSlug()" title="Regenerate from Title"><i class="fas fa-sync-alt"></i></button>
                    </div>

                    <!-- Excerpt / Summary -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label fw-bold mb-0">Short Excerpt / Summary</label>
                            <span class="small text-muted" id="excerpt_counter">0 / 250</span>
                        </div>
                        <textarea name="excerpt" id="blog_excerpt" class="form-control" rows="3" placeholder="A captivating summary..."><?php echo htmlspecialchars($blog['excerpt'] ?? ''); ?></textarea>
                    </div>

                    <!-- Rich Text Editor -->
                    <div class="mb-2">
                        <label class="form-label fw-bold">Full Content Body <span class="text-danger">*</span></label>
                        <textarea name="content" id="blog_content" class="form-control" rows="14" style="width: 100%; min-height: 380px; font-family: inherit; font-size: 15px; line-height: 1.6;"><?php echo htmlspecialchars($blog['content'] ?? ''); ?></textarea>
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
                                <span style="color: #5f6368;">› blog › <span id="serp_slug_preview"><?php echo htmlspecialchars($blog['slug']); ?></span></span>
                            </div>
                            <div id="serp_title_preview" style="color: #1a0dab; font-size: 19px; line-height: 1.3; font-weight: 400; cursor: pointer; text-decoration: none;">
                                <?php echo htmlspecialchars($blog['meta_title'] ?: $blog['title']); ?>
                            </div>
                            <div id="serp_desc_preview" style="color: #4d5156; font-size: 14px; line-height: 1.58; margin-top: 3px;">
                                <?php echo htmlspecialchars($blog['meta_description'] ?: $blog['excerpt'] ?: 'Description snippet will appear here.'); ?>
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
                            <input type="text" name="meta_title" id="meta_title" class="form-control" placeholder="Targeted SEO Title for browser tabs & Google search..." value="<?php echo htmlspecialchars($blog['meta_title'] ?? ''); ?>">
                            <div class="form-text">Recommended: 50 - 60 characters.</div>
                        </div>

                        <!-- Meta Description -->
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="form-label fw-bold mb-0">SEO Meta Description</label>
                                <span class="small font-monospace" id="meta_desc_counter">0 / 160 characters</span>
                            </div>
                            <textarea name="meta_description" id="meta_description" class="form-control" rows="3" placeholder="Concise summary shown under headline on Google search results..."><?php echo htmlspecialchars($blog['meta_description'] ?? ''); ?></textarea>
                            <div class="form-text">Recommended: 140 - 160 characters.</div>
                        </div>

                        <!-- Meta Keywords -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Focus & Meta Keywords</label>
                            <input type="text" name="meta_keywords" class="form-control" placeholder="e.g. computer training, bihar education, kyp" value="<?php echo htmlspecialchars($blog['meta_keywords'] ?? ''); ?>">
                            <div class="form-text">Comma-separated keywords.</div>
                        </div>

                        <!-- Schema.org Type -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Schema.org Structured Data Type</label>
                            <select name="schema_type" class="form-select">
                                <option value="BlogPosting" <?php echo ($blog['schema_type'] ?? '') === 'BlogPosting' ? 'selected' : ''; ?>>BlogPosting (Recommended)</option>
                                <option value="Article" <?php echo ($blog['schema_type'] ?? '') === 'Article' ? 'selected' : ''; ?>>Article</option>
                                <option value="NewsArticle" <?php echo ($blog['schema_type'] ?? '') === 'NewsArticle' ? 'selected' : ''; ?>>NewsArticle</option>
                                <option value="EducationalOccupationalCredential" <?php echo ($blog['schema_type'] ?? '') === 'EducationalOccupationalCredential' ? 'selected' : ''; ?>>Educational Article</option>
                            </select>
                            <div class="form-text">Powers Google Rich Snippets.</div>
                        </div>

                        <!-- Canonical URL -->
                        <div class="col-12">
                            <label class="form-label fw-bold">Canonical URL (Advanced)</label>
                            <input type="url" name="canonical_url" class="form-control" placeholder="Defaults to this article URL automatically" value="<?php echo htmlspecialchars($blog['canonical_url'] ?? ''); ?>">
                            <div class="form-text">Only needed if this article was syndicated from another canonical source.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Sidebar Settings Column -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4 sticky-top" style="top: 20px; z-index: 5;">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-dark"><i class="fas fa-sliders-h text-primary me-2"></i>Publishing Settings</h6>
                    <span class="badge bg-light text-muted"><i class="fas fa-eye me-1"></i><?php echo number_format($blog['views']); ?> Reads</span>
                </div>
                <div class="card-body p-4">
                    <!-- Status Toggle -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Post Status</label>
                        <div class="form-check form-switch mt-1">
                            <input class="form-check-input" type="checkbox" name="status" id="blog_status" value="1" <?php echo $blog['status'] == 1 ? 'checked' : ''; ?> style="width: 50px; height: 26px;">
                            <label class="form-check-label fw-bold ms-2" for="blog_status" id="status_label">
                                <?php echo $blog['status'] == 1 ? 'Published Live' : 'Saved as Draft'; ?>
                            </label>
                        </div>
                    </div>

                    <!-- Category -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label fw-bold mb-0">Category <span class="text-danger">*</span></label>
                            <a href="blog_categories" target="_blank" class="small text-primary text-decoration-none">+ New Category</a>
                        </div>
                        <select name="category_id" class="form-select" required>
                            <option value="">-- Choose Category --</option>
                            <?php while ($cat = mysqli_fetch_assoc($cats_q)): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo $blog['category_id'] == $cat['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <!-- Author Name -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Author Name</label>
                        <input type="text" name="author_name" class="form-control" value="<?php echo htmlspecialchars($blog['author_name'] ?? 'LKVM Editorial'); ?>" required>
                    </div>

                    <!-- Tags -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tags</label>
                        <input type="text" name="tags" class="form-control" placeholder="e.g. Technology, Students, KYP" value="<?php echo htmlspecialchars($blog['tags'] ?? ''); ?>">
                        <div class="form-text">Comma-separated tags for filtering & search.</div>
                    </div>

                    <!-- Publication Date -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Publish Date & Time</label>
                        <input type="datetime-local" name="published_at" class="form-control" value="<?php echo date('Y-m-d\TH:i', strtotime($blog['published_at'] ?? 'now')); ?>">
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
                        <button type="submit" name="save_blog" class="btn btn-primary rounded-pill py-2 fw-bold shadow-sm">
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
(function initEditor() {
    function setupSummernote() {
        if (window.jQuery && typeof jQuery.fn.summernote === 'function') {
            $('#blog_content').summernote({
                placeholder: 'Write your comprehensive blog article here...',
                tabsize: 2,
                height: 400,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        } else {
            setTimeout(setupSummernote, 60);
        }
    }
    setupSummernote();
})();

document.addEventListener("DOMContentLoaded", function() {

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
        const descVal = metaDescInput.value.trim() || excerptInput.value.trim() || 'Meta description snippet will appear here in Google search results.';

        serpTitle.innerText = titleVal;
        serpSlug.innerText = slugVal;
        serpDesc.innerText = descVal;

        const titleLen = metaTitleInput.value.length;
        metaTitleCounter.innerText = `${titleLen} / 60 chars`;
        if (titleLen > 60) {
            metaTitleCounter.className = 'small font-monospace text-danger';
        } else if (titleLen >= 45) {
            metaTitleCounter.className = 'small font-monospace text-success fw-bold';
        } else {
            metaTitleCounter.className = 'small font-monospace text-muted';
        }

        const descLen = metaDescInput.value.length;
        metaDescCounter.innerText = `${descLen} / 160 chars`;
        if (descLen > 160) {
            metaDescCounter.className = 'small font-monospace text-danger';
        } else if (descLen >= 120) {
            metaDescCounter.className = 'small font-monospace text-success fw-bold';
        } else {
            metaDescCounter.className = 'small font-monospace text-muted';
        }
    }

    statusToggle.addEventListener('change', function() {
        statusLabel.innerText = this.checked ? 'Published Live' : 'Saved as Draft';
    });

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

    updateSERP();
});
</script>

<?php require_once 'includes/footer.php'; ?>


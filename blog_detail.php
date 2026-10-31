<?php
// blog_detail.php - Public Single Blog Article Page with Complete SEO & Schema Markup
require_once 'connection.php';

$slug = trim($_GET['slug'] ?? '');
if (empty($slug)) {
    $req_path = trim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');
    $parts = explode('/', $req_path);
    $slug = end($parts);
}

if (empty($slug) || $slug === 'blog' || $slug === 'blog.php' || $slug === 'blog_detail.php') {
    header("Location: " . $SITE_URL . "/blog");
    exit();
}

// Fetch article with category details
$stmt = $con->prepare("
    SELECT b.*, c.name AS category_name, c.slug AS category_slug 
    FROM blogs b 
    LEFT JOIN blog_categories c ON c.id = b.category_id 
    WHERE b.slug = ? AND b.status = 1 
    LIMIT 1
");
$stmt->bind_param("s", $slug);
$stmt->execute();
$blog = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$blog) {
    // Attempt fuzzy match for slug variations (e.g. with/without "-in-")
    $alt_slug = str_contains($slug, '-in-2026') ? str_replace('-in-2026', '-2026', $slug) : str_replace('-2026', '-in-2026', $slug);
    $stmt = $con->prepare("
        SELECT b.*, c.name AS category_name, c.slug AS category_slug 
        FROM blogs b 
        LEFT JOIN blog_categories c ON c.id = b.category_id 
        WHERE b.slug = ? AND b.status = 1 
        LIMIT 1
    ");
    $stmt->bind_param("s", $alt_slug);
    $stmt->execute();
    $blog = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    if ($blog) {
        // Canonical 301 redirect to primary slug
        header("Location: " . $SITE_URL . "/blog/" . $blog['slug'], true, 301);
        exit();
    }
}

if (!$blog) {
    http_response_code(404);
    include '404.php';
    exit();
}

// Increment unique view count per session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$view_key = 'viewed_blog_' . $blog['id'];
if (!isset($_SESSION[$view_key])) {
    mysqli_query($con, "UPDATE blogs SET views = views + 1 WHERE id = {$blog['id']}");
    $_SESSION[$view_key] = true;
    $blog['views']++;
}

// Reading time calculation (average 200 words per minute)
$word_count = str_word_count(strip_tags($blog['content']));
$read_time = max(1, round($word_count / 200));

// SEO Meta Variables
$article_title = !empty($blog['meta_title']) ? $blog['meta_title'] : ($blog['title'] . ' | ' . $SITE_NAME);
$meta_desc = !empty($blog['meta_description']) ? $blog['meta_description'] : (!empty($blog['excerpt']) ? $blog['excerpt'] : substr(strip_tags($blog['content']), 0, 160));
$meta_keywords = !empty($blog['meta_keywords']) ? $blog['meta_keywords'] : $blog['tags'];
$canonical_url = !empty($blog['canonical_url']) ? $blog['canonical_url'] : ($SITE_URL . '/blog/' . $blog['slug']);

$featured_img_url = !empty($blog['featured_image']) && file_exists('uploads/' . $blog['featured_image'])
    ? $SITE_URL . '/uploads/' . htmlspecialchars($blog['featured_image'])
    : (!empty($settings['og_image']) ? $settings['og_image'] : $SITE_URL . '/images/banner.jpg');

$schema_type = !empty($blog['schema_type']) ? $blog['schema_type'] : 'BlogPosting';

// Previous & Next Articles
$prev_q = mysqli_query($con, "SELECT id, title, slug FROM blogs WHERE status = 1 AND id < {$blog['id']} ORDER BY id DESC LIMIT 1");
$prev_blog = mysqli_fetch_assoc($prev_q);

$next_q = mysqli_query($con, "SELECT id, title, slug FROM blogs WHERE status = 1 AND id > {$blog['id']} ORDER BY id ASC LIMIT 1");
$next_blog = mysqli_fetch_assoc($next_q);

// Related Articles (same category, excluding current)
$related_q = mysqli_query($con, "
    SELECT id, title, slug, featured_image, published_at 
    FROM blogs 
    WHERE category_id = {$blog['category_id']} AND id != {$blog['id']} AND status = 1 
    ORDER BY published_at DESC 
    LIMIT 3
");

// Fetch Categories for Sidebar
$sidebar_cats = mysqli_query($con, "
    SELECT c.*, COUNT(b.id) AS post_count 
    FROM blog_categories c
    LEFT JOIN blogs b ON b.category_id = c.id AND b.status = 1
    WHERE c.status = 1
    GROUP BY c.id
    ORDER BY c.name ASC
");

// Fetch Recent Posts for Sidebar
$sidebar_recent = mysqli_query($con, "SELECT id, title, slug, featured_image, published_at FROM blogs WHERE status = 1 AND id != {$blog['id']} ORDER BY published_at DESC LIMIT 5");
?>
<!doctype html>
<html lang="en-US">

<head>
    <?php 
    $custom_seo = true;
    include('csslink.php'); 
    ?>
    <!-- Google Fonts for Modern Warm Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Lora:ital,wght@0,500;0,600;1,400&display=swap" rel="stylesheet">

    <!-- FontAwesome 6 Free for Sharp Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- Primary SEO Metadata -->
    <title><?php echo htmlspecialchars($article_title); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($meta_desc); ?>">
    <?php if (!empty($meta_keywords)): ?>
        <meta name="keywords" content="<?php echo htmlspecialchars($meta_keywords); ?>">
    <?php endif; ?>
    <meta name="author" content="<?php echo htmlspecialchars($blog['author_name'] ?: $SITE_NAME); ?>">
    <link rel="canonical" href="<?php echo htmlspecialchars($canonical_url); ?>">

    <!-- Open Graph (Facebook / WhatsApp / LinkedIn) -->
    <meta property="og:locale" content="en_US">
    <meta property="og:type" content="article">
    <meta property="og:title" content="<?php echo htmlspecialchars($article_title); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($meta_desc); ?>">
    <meta property="og:url" content="<?php echo htmlspecialchars($canonical_url); ?>">
    <meta property="og:site_name" content="<?php echo htmlspecialchars($SITE_NAME); ?>">
    <meta property="og:image" content="<?php echo htmlspecialchars($featured_img_url); ?>">
    <meta property="og:image:width" content="1280">
    <meta property="og:image:height" content="720">
    <meta property="article:published_time" content="<?php echo date('c', strtotime($blog['published_at'])); ?>">
    <meta property="article:modified_time" content="<?php echo date('c', strtotime($blog['updated_at'] ?? $blog['published_at'])); ?>">
    <meta property="article:section" content="<?php echo htmlspecialchars($blog['category_name'] ?? 'General'); ?>">
    <?php if (!empty($blog['tags'])): ?>
        <?php foreach (explode(',', $blog['tags']) as $t): ?>
            <meta property="article:tag" content="<?php echo htmlspecialchars(trim($t)); ?>">
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($article_title); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($meta_desc); ?>">
    <meta name="twitter:image" content="<?php echo htmlspecialchars($featured_img_url); ?>">

    <!-- Schema.org JSON-LD Structured Data for Google Rich Snippets -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "<?php echo addslashes($schema_type); ?>",
        "mainEntityOfPage": {
            "@type": "WebPage",
            "@id": "<?php echo addslashes($canonical_url); ?>"
        },
        "headline": <?php echo json_encode($blog['title']); ?>,
        "description": <?php echo json_encode($meta_desc); ?>,
        "image": [
            "<?php echo addslashes($featured_img_url); ?>"
        ],
        "datePublished": "<?php echo date('c', strtotime($blog['published_at'])); ?>",
        "dateModified": "<?php echo date('c', strtotime($blog['updated_at'] ?? $blog['published_at'])); ?>",
        "author": {
            "@type": "Person",
            "name": <?php echo json_encode($blog['author_name'] ?: 'LKVM Editorial'); ?>
        },
        "publisher": {
            "@type": "Organization",
            "name": <?php echo json_encode($SITE_NAME); ?>,
            "logo": {
                "@type": "ImageObject",
                "url": "<?php echo $SITE_URL; ?>/images/logo.png"
            }
        }
    }
    </script>

    <style>
        :root {
            --lk-primary: #002147;
            --lk-accent: #f59e0b;
            --lk-accent-gold: #fdc800;
            --lk-sky: #0284c7;
            --lk-text-dark: #0f172a;
            --lk-text-body: #334155;
            --lk-text-muted: #64748b;
            --lk-bg-soft: #f8fafc;
            --lk-card-border: #e2e8f0;
            --lk-font-main: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            --lk-font-serif: 'Lora', Georgia, serif;
        }

        body.theme-eikra {
            background-color: var(--lk-bg-soft);
            color: var(--lk-text-body);
            font-family: var(--lk-font-main);
            font-size: 15.5px;
            line-height: 1.7;
            -webkit-font-smoothing: antialiased;
        }

        .blog-single-header {
            background: radial-gradient(circle at 80% 20%, rgba(2, 132, 199, 0.45) 0%, transparent 50%),
                        linear-gradient(135deg, #001733 0%, #002b5e 60%, #043673 100%);
            padding: 65px 0 55px 0;
            color: #ffffff;
            margin-bottom: 45px;
            position: relative;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }
        .blog-single-header::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: radial-gradient(rgba(255, 255, 255, 0.08) 1px, transparent 1px);
            background-size: 24px 24px;
            opacity: 0.4;
            pointer-events: none;
        }
        .blog-single-header .breadcrumb {
            background: transparent;
            padding: 0;
            margin-bottom: 18px;
        }
        .blog-single-header .breadcrumb-item,
        .blog-single-header .breadcrumb-item a {
            color: rgba(255, 255, 255, 0.75) !important;
            font-size: 13.5px;
            text-decoration: none !important;
        }
        .blog-single-header .breadcrumb-item a:hover {
            color: #ffffff !important;
            text-decoration: underline !important;
        }
        .blog-single-header .breadcrumb-item.active {
            color: #ffffff !important;
            font-weight: 600;
        }
        .blog-single-header .breadcrumb-item + .breadcrumb-item::before {
            color: rgba(255, 255, 255, 0.4);
            content: "/";
            padding: 0 10px;
        }

        .blog-single-cat-badge {
            display: inline-block;
            background: #f59e0b;
            color: #001733 !important;
            font-size: 11.5px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 5px 15px;
            border-radius: 50px;
            margin-bottom: 14px;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.35);
        }

        .blog-single-header h1.blog-detail-title,
        .blog-single-header h1 {
            color: #ffffff !important;
            font-family: var(--lk-font-main);
            font-size: clamp(1.85rem, 3.2vw, 2.7rem) !important;
            font-weight: 800 !important;
            line-height: 1.28 !important;
            margin-top: 10px !important;
            margin-bottom: 22px !important;
            letter-spacing: -0.018em !important;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.45);
        }

        .blog-single-meta-row {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            color: #e2e8f0;
            font-size: 13.5px;
            margin-top: 12px;
            padding-top: 6px;
        }
        .blog-single-meta-row .meta-item {
            display: inline-flex;
            align-items: center;
            color: #e2e8f0;
            margin-right: 18px;
            margin-bottom: 6px;
            font-weight: 500;
        }
        .blog-single-meta-row .meta-item i {
            margin-right: 7px;
            font-size: 14px;
        }
        .blog-single-meta-row .meta-item i.fa-user-circle {
            color: #fdc800 !important;
        }
        .blog-single-meta-row .meta-item i.fa-calendar-alt {
            color: #38bdf8 !important;
        }
        .blog-single-meta-row .meta-item i.fa-clock {
            color: #fdc800 !important;
        }
        .blog-single-meta-row .meta-item i.fa-eye {
            color: #38bdf8 !important;
        }
        .blog-single-meta-row .meta-dot {
            color: rgba(255, 255, 255, 0.4);
            margin: 0 16px 6px 0;
            font-size: 12px;
        }
        /* Article Card & Content */
        .article-card {
            background: #ffffff;
            border-radius: 24px;
            padding: 42px;
            box-shadow: 0 4px 25px rgba(15, 23, 42, 0.04);
            border: 1px solid var(--lk-card-border);
            margin-bottom: 40px;
        }
        .article-featured-media {
            width: 100%;
            aspect-ratio: 16 / 9;
            max-height: 480px;
            object-fit: cover;
            border-radius: 18px;
            margin-bottom: 35px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
        }
        .article-content {
            font-size: 17.5px;
            line-height: 1.88;
            color: #334155;
        }
        .article-content h2, .article-content h3, .article-content h4 {
            color: #0f172a;
            font-weight: 800;
            margin-top: 36px;
            margin-bottom: 18px;
            line-height: 1.35;
            letter-spacing: -0.015em;
        }
        .article-content p {
            margin-bottom: 22px;
            color: #334155;
        }
        .article-content blockquote {
            border-left: 4px solid #fdc800;
            background: #f8fafc;
            padding: 22px 28px;
            border-radius: 0 14px 14px 0;
            font-style: italic;
            color: #1e293b;
            font-size: 1.08rem;
            margin: 32px 0;
            line-height: 1.7;
        }
        .article-content img {
            max-width: 100%;
            height: auto;
            border-radius: 14px;
            margin: 22px 0;
        }

        /* Tags Section */
        .tags-section-wrap {
            margin-top: 35px;
            padding-top: 22px;
            border-top: 1px solid #f1f5f9;
        }
        .tags-heading {
            font-size: 13.5px;
            font-weight: 700;
            color: #64748b;
            margin-right: 12px;
            margin-bottom: 10px;
            display: inline-flex;
            align-items: center;
        }
        .tags-heading i {
            margin-right: 6px !important;
            color: var(--lk-primary);
        }
        .tag-pill {
            display: inline-block;
            background: #f1f5f9;
            color: #334155;
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 12.5px;
            font-weight: 600;
            margin-right: 8px !important;
            margin-bottom: 8px !important;
            text-decoration: none !important;
            transition: all 0.2s;
            border: 1px solid #e2e8f0;
        }
        .tag-pill:hover {
            background: var(--lk-primary);
            color: #ffffff !important;
            border-color: var(--lk-primary);
        }

        /* Social Share Toolbar */
        .share-section-wrap {
            margin-top: 30px;
            padding-top: 24px;
            border-top: 1px solid #f1f5f9;
        }
        .share-section-title {
            font-size: 14.5px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
        }
        .share-section-title i {
            color: var(--lk-sky);
            margin-right: 8px !important;
            font-size: 16px;
        }
        .share-buttons-container {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
        }
        .share-btn {
            display: inline-flex;
            align-items: center;
            padding: 9px 20px;
            border-radius: 50px;
            font-size: 13.5px;
            font-weight: 700;
            color: #ffffff !important;
            text-decoration: none !important;
            margin-right: 12px !important;
            margin-bottom: 12px !important;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border: none;
            outline: none;
            cursor: pointer;
        }
        .share-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0,0,0,0.18);
        }
        .share-btn i {
            margin-right: 8px !important;
            font-size: 15px;
        }
        .share-whatsapp { background: #25d366; }
        .share-facebook { background: #1877f2; }
        .share-twitter  { background: #000000; }
        .share-linkedin { background: #0077b5; }
        .share-copy     { background: #475569; }

        /* Author Bio Box */
        .author-box {
            background: #f8fafc;
            border-radius: 18px;
            padding: 26px;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            margin-top: 35px;
        }
        .author-box-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #002147, #0284c7);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            font-weight: 800;
            flex-shrink: 0;
            margin-right: 20px !important;
            box-shadow: 0 4px 12px rgba(0, 33, 71, 0.15);
        }
        .author-box-info h6 {
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 4px;
        }
        .author-box-info p {
            font-size: 13.5px;
            color: #64748b;
            line-height: 1.55;
            margin-bottom: 0;
        }

        /* Nav Post Cards */
        .nav-post-card {
            padding: 22px;
            border-radius: 16px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            text-decoration: none !important;
            display: block;
            height: 100%;
            transition: all 0.2s;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        }
        .nav-post-card:hover {
            border-color: var(--lk-sky);
            transform: translateY(-3px);
            box-shadow: 0 8px 22px rgba(0, 33, 71, 0.08);
        }
        .nav-post-card small {
            display: flex;
            align-items: center;
            font-size: 12px;
            color: #64748b;
            margin-bottom: 6px;
        }
        .nav-post-card small i {
            margin-right: 6px !important;
        }
        .nav-post-card.text-md-end small {
            justify-content: flex-end;
        }
        .nav-post-card.text-md-end small i {
            margin-right: 0 !important;
            margin-left: 6px !important;
        }
        .nav-post-card strong {
            font-size: 14px;
            color: #0f172a;
            display: block;
            line-height: 1.35;
        }

        /* Related Articles Cards */
        .related-card {
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            transition: all 0.25s;
            height: 100%;
            display: flex;
            flex-direction: column;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        }
        .related-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(0, 33, 71, 0.09);
            border-color: #cbd5e1;
        }
        .related-card-img-wrap {
            aspect-ratio: 16 / 9;
            overflow: hidden;
            display: block;
            background: #e2e8f0;
        }
        .related-card-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s;
        }
        .related-card:hover .related-card-img-wrap img {
            transform: scale(1.06);
        }
        .related-card-body {
            padding: 16px 18px 20px 18px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }
        .related-card-date {
            font-size: 11.5px;
            color: #64748b;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }
        .related-card-date i {
            margin-right: 6px !important;
            color: #f59e0b;
        }
        .related-card-title {
            font-size: 14.5px;
            font-weight: 700;
            line-height: 1.38;
            color: #0f172a;
            margin-bottom: 0;
        }
        .related-card-title a {
            color: inherit;
            text-decoration: none !important;
        }
        .related-card-title a:hover {
            color: var(--lk-sky);
        }

        /* Sidebar Widgets */
        .sidebar-widget {
            background: #ffffff;
            border-radius: 20px;
            padding: 28px;
            border: 1px solid var(--lk-card-border);
            margin-bottom: 28px;
            box-shadow: 0 4px 18px rgba(15, 23, 42, 0.03);
        }
        .widget-title {
            font-family: var(--lk-font-main);
            font-size: 17.5px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 12px;
            display: flex;
            align-items: center;
        }
        .widget-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 38px;
            height: 3px;
            background: var(--lk-accent-gold);
            border-radius: 2px;
        }
        .widget-title i {
            margin-right: 8px !important;
            color: var(--lk-primary);
        }

        /* Sidebar Categories */
        .sidebar-cat-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            color: #334155;
            text-decoration: none !important;
            border-bottom: 1px dashed #f1f5f9;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
        }
        .sidebar-cat-item:last-child {
            border-bottom: none;
        }
        .sidebar-cat-item:hover, .sidebar-cat-item.active {
            color: var(--lk-primary);
            font-weight: 700;
            padding-left: 6px;
        }
        .sidebar-cat-item i {
            margin-right: 8px !important;
            color: var(--lk-primary);
        }
        .sidebar-cat-badge {
            background: #f1f5f9;
            color: #64748b;
            font-size: 11px;
            font-weight: 700;
            padding: 3px 9px;
            border-radius: 50px;
            border: 1px solid #e2e8f0;
        }
        .sidebar-cat-item.active .sidebar-cat-badge {
            background: var(--lk-primary);
            color: #ffffff;
            border-color: var(--lk-primary);
        }

        /* Sidebar Recent Items (Fixed Spacing) */
        .sidebar-recent-card {
            display: flex;
            align-items: center;
            text-decoration: none !important;
            padding: 11px 0;
            border-bottom: 1px solid #f8fafc;
            transition: all 0.2s;
        }
        .sidebar-recent-card:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
        .sidebar-recent-thumb {
            width: 78px;
            height: 58px;
            object-fit: cover;
            border-radius: 12px;
            flex-shrink: 0;
            margin-right: 15px !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            transition: transform 0.2s;
        }
        .sidebar-recent-card:hover .sidebar-recent-thumb {
            transform: scale(1.05);
        }
        .sidebar-recent-info {
            flex-grow: 1;
            min-width: 0;
        }
        .sidebar-recent-title {
            font-size: 13.5px;
            font-weight: 700;
            line-height: 1.35;
            color: #0f172a;
            margin-bottom: 4px;
            transition: color 0.2s;
        }
        .sidebar-recent-card:hover .sidebar-recent-title {
            color: var(--lk-sky);
        }
        .sidebar-recent-date {
            font-size: 11.5px;
            color: #64748b;
            display: flex;
            align-items: center;
        }
        .sidebar-recent-date i {
            color: #f59e0b;
            margin-right: 6px !important;
            font-size: 12px;
        }

        /* Sidebar Search Box */
        .sidebar-search-box {
            display: flex;
            align-items: center;
            border-radius: 50px;
            border: 1.5px solid #cbd5e1;
            overflow: hidden;
            background: #ffffff;
            transition: border-color 0.2s;
        }
        .sidebar-search-box:focus-within {
            border-color: var(--lk-primary);
            box-shadow: 0 0 0 4px rgba(0, 33, 71, 0.08);
        }
        .sidebar-search-box input {
            border: none;
            padding: 10px 18px;
            font-size: 14px;
            color: #0f172a;
            flex-grow: 1;
            outline: none;
        }
        .sidebar-search-box button {
            border: none;
            background: var(--lk-primary);
            color: #ffffff;
            padding: 0 20px;
            height: 42px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .sidebar-search-box button:hover {
            background: var(--lk-sky);
        }

        @media (max-width: 767px) {
            .article-card {
                padding: 24px 18px;
            }
            .article-content {
                font-size: 16px;
            }
        }
    </style>
</head>

<body class="home page-template-default page wp-embed-responsive theme-eikra header-style-9 footer-style-2">
    <div id="page" class="site">
        <?php include('header.php'); ?>

        <!-- Hero Header -->
        <section class="blog-single-header">
            <div class="container">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0 mb-3">
                        <li class="breadcrumb-item"><a href="<?php echo $SITE_URL; ?>/index">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo $SITE_URL; ?>/blog">Blog</a></li>
                        <?php if (!empty($blog['category_name'])): ?>
                            <li class="breadcrumb-item"><a href="<?php echo $SITE_URL; ?>/blog/category/<?php echo htmlspecialchars($blog['category_slug']); ?>"><?php echo htmlspecialchars($blog['category_name']); ?></a></li>
                        <?php endif; ?>
                    </ol>
                </nav>

                <div>
                    <span class="blog-single-cat-badge">
                        <i class="fas fa-bookmark mr-1 me-1"></i><?php echo htmlspecialchars($blog['category_name'] ?? 'General'); ?>
                    </span>
                </div>

                <h1 class="blog-detail-title">
                    <?php echo htmlspecialchars($blog['title']); ?>
                </h1>

                <div class="blog-single-meta-row">
                    <span class="meta-item"><i class="fas fa-user-circle"></i> By <?php echo htmlspecialchars($blog['author_name'] ?? 'LKVM Editorial'); ?></span>
                    <span class="meta-dot">&bull;</span>
                    <span class="meta-item"><i class="far fa-calendar-alt"></i> <?php echo date('F d, Y', strtotime($blog['published_at'])); ?></span>
                    <span class="meta-dot">&bull;</span>
                    <span class="meta-item"><i class="far fa-clock"></i> <?php echo $read_time; ?> min read</span>
                    <span class="meta-dot">&bull;</span>
                    <span class="meta-item"><i class="far fa-eye"></i> <?php echo number_format($blog['views']); ?> views</span>
                </div>
            </div>
        </section>

        <!-- Main Article Body -->
        <div class="container mb-5">
            <div class="row">
                <!-- Left Column: Article Body -->
                <div class="col-lg-8">
                    <article class="article-card">
                        <!-- Featured Image (1280x720 16:9) -->
                        <?php if (!empty($blog['featured_image']) && file_exists('uploads/' . $blog['featured_image'])): ?>
                            <img src="<?php echo $SITE_URL; ?>/uploads/<?php echo htmlspecialchars($blog['featured_image']); ?>" 
                                 alt="<?php echo htmlspecialchars($blog['title']); ?>" 
                                 class="article-featured-media">
                        <?php endif; ?>

                        <!-- Full Content -->
                        <div class="article-content">
                            <?php echo $blog['content']; ?>
                        </div>

                        <!-- Tags Cloud -->
                        <?php if (!empty($blog['tags'])): ?>
                            <div class="tags-section-wrap">
                                <span class="tags-heading"><i class="fas fa-tags"></i>Tags:</span>
                                <?php foreach (explode(',', $blog['tags']) as $t): ?>
                                    <a href="<?php echo $SITE_URL; ?>/blog?tag=<?php echo urlencode(trim($t)); ?>" class="tag-pill">
                                        #<?php echo htmlspecialchars(trim($t)); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Social Share Toolbar -->
                        <div class="share-section-wrap">
                            <div class="share-section-title">
                                <i class="fas fa-share-nodes"></i> Share this article with colleagues &amp; friends:
                            </div>
                            <div class="share-buttons-container">
                                <?php
                                $share_url = urlencode($canonical_url);
                                $share_title = urlencode($blog['title']);
                                ?>
                                <a href="https://api.whatsapp.com/send?text=<?php echo $share_title; ?>%20<?php echo $share_url; ?>" target="_blank" class="share-btn share-whatsapp" title="Share on WhatsApp">
                                    <i class="fab fa-whatsapp"></i> WhatsApp
                                </a>
                                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share_url; ?>" target="_blank" class="share-btn share-facebook" title="Share on Facebook">
                                    <i class="fab fa-facebook-f"></i> Facebook
                                </a>
                                <a href="https://twitter.com/intent/tweet?url=<?php echo $share_url; ?>&text=<?php echo $share_title; ?>" target="_blank" class="share-btn share-twitter" title="Share on X (Twitter)">
                                    <i class="fab fa-x-twitter"></i> X (Twitter)
                                </a>
                                <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $share_url; ?>" target="_blank" class="share-btn share-linkedin" title="Share on LinkedIn">
                                    <i class="fab fa-linkedin-in"></i> LinkedIn
                                </a>
                                <button type="button" class="share-btn share-copy" onclick="copyArticleLink()" title="Copy Link to Clipboard">
                                    <i class="fas fa-link"></i> <span id="copy_btn_text">Copy Link</span>
                                </button>
                            </div>
                        </div>

                        <!-- Author Bio Box -->
                        <div class="author-box">
                            <div class="author-box-avatar">
                                <?php echo strtoupper(substr($blog['author_name'] ?: 'L', 0, 1)); ?>
                            </div>
                            <div class="author-box-info">
                                <h6><?php echo htmlspecialchars($blog['author_name'] ?: 'LKVM Editorial'); ?></h6>
                                <p>Contributing author and educator at Lalit Kala Vikas Manch (LKVM), dedicated to fostering skill development, computer literacy, and artistic heritage across Bihar.</p>
                            </div>
                        </div>
                    </article>

                    <!-- Previous & Next Navigation -->
                    <div class="row mb-5">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <?php if ($prev_blog): ?>
                                <a href="<?php echo $SITE_URL; ?>/blog/<?php echo htmlspecialchars($prev_blog['slug']); ?>" class="nav-post-card">
                                    <small><i class="fas fa-arrow-left"></i> Previous Article</small>
                                    <strong class="text-truncate"><?php echo htmlspecialchars($prev_blog['title']); ?></strong>
                                </a>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <?php if ($next_blog): ?>
                                <a href="<?php echo $SITE_URL; ?>/blog/<?php echo htmlspecialchars($next_blog['slug']); ?>" class="nav-post-card text-md-end">
                                    <small>Next Article <i class="fas fa-arrow-right"></i></small>
                                    <strong class="text-truncate"><?php echo htmlspecialchars($next_blog['title']); ?></strong>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Related Articles -->
                    <?php if (mysqli_num_rows($related_q) > 0): ?>
                        <div class="mb-5">
                            <h4 class="fw-bold mb-4" style="color: #0f172a;"><i class="fas fa-layer-group text-primary mr-2" style="margin-right: 8px;"></i>Related Articles You May Like</h4>
                            <div class="row">
                                <?php while ($rel = mysqli_fetch_assoc($related_q)): ?>
                                    <?php
                                    $rel_img = !empty($rel['featured_image']) && file_exists('uploads/' . $rel['featured_image'])
                                        ? $SITE_URL . '/uploads/' . htmlspecialchars($rel['featured_image'])
                                        : $SITE_URL . '/images/banner.jpg';
                                    ?>
                                    <div class="col-md-4 mb-4">
                                        <article class="related-card">
                                            <a href="<?php echo $SITE_URL; ?>/blog/<?php echo htmlspecialchars($rel['slug']); ?>" class="related-card-img-wrap">
                                                <img src="<?php echo $rel_img; ?>" alt="<?php echo htmlspecialchars($rel['title']); ?>" loading="lazy">
                                            </a>
                                            <div class="related-card-body">
                                                <div class="related-card-date">
                                                    <i class="far fa-calendar-alt"></i><?php echo date('M d, Y', strtotime($rel['published_at'])); ?>
                                                </div>
                                                <h6 class="related-card-title">
                                                    <a href="<?php echo $SITE_URL; ?>/blog/<?php echo htmlspecialchars($rel['slug']); ?>">
                                                        <?php echo htmlspecialchars($rel['title']); ?>
                                                    </a>
                                                </h6>
                                            </div>
                                        </article>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Right Column: Sidebar -->
                <aside class="col-lg-4">
                    <!-- Search Widget -->
                    <div class="sidebar-widget">
                        <h4 class="widget-title"><i class="fas fa-search"></i>Search Blog</h4>
                        <form method="GET" action="<?php echo $SITE_URL; ?>/blog">
                            <div class="sidebar-search-box">
                                <input type="text" name="q" placeholder="Search topics..." required>
                                <button type="submit"><i class="fas fa-search"></i></button>
                            </div>
                        </form>
                    </div>

                    <!-- Categories Widget -->
                    <div class="sidebar-widget">
                        <h4 class="widget-title"><i class="fas fa-folder-open"></i>Categories</h4>
                        <div class="d-flex flex-column">
                            <?php while ($scat = mysqli_fetch_assoc($sidebar_cats)): ?>
                                <a href="<?php echo $SITE_URL; ?>/blog/category/<?php echo htmlspecialchars($scat['slug']); ?>" 
                                   class="sidebar-cat-item <?php echo $blog['category_id'] == $scat['id'] ? 'active' : ''; ?>">
                                    <span>
                                        <i class="fas fa-angle-right"></i><?php echo htmlspecialchars($scat['name']); ?>
                                    </span>
                                    <span class="sidebar-cat-badge"><?php echo intval($scat['post_count']); ?></span>
                                </a>
                            <?php endwhile; ?>
                        </div>
                    </div>

                    <!-- Recent Articles Widget -->
                    <div class="sidebar-widget">
                        <h4 class="widget-title"><i class="fas fa-clock"></i>Recent Articles</h4>
                        <div>
                            <?php while ($srec = mysqli_fetch_assoc($sidebar_recent)): ?>
                                <?php
                                $srec_img = !empty($srec['featured_image']) && file_exists('uploads/' . $srec['featured_image'])
                                    ? $SITE_URL . '/uploads/' . htmlspecialchars($srec['featured_image'])
                                    : $SITE_URL . '/images/banner.jpg';
                                ?>
                                <a href="<?php echo $SITE_URL; ?>/blog/<?php echo htmlspecialchars($srec['slug']); ?>" class="sidebar-recent-card">
                                    <img src="<?php echo $srec_img; ?>" alt="<?php echo htmlspecialchars($srec['title']); ?>" class="sidebar-recent-thumb" loading="lazy">
                                    <div class="sidebar-recent-info">
                                        <h6 class="sidebar-recent-title">
                                            <?php echo htmlspecialchars($srec['title']); ?>
                                        </h6>
                                        <span class="sidebar-recent-date">
                                            <i class="far fa-calendar-alt"></i><?php echo date('M d, Y', strtotime($srec['published_at'])); ?>
                                        </span>
                                    </div>
                                </a>
                            <?php endwhile; ?>
                        </div>
                    </div>

                    <!-- Student Helpline / Quick Info -->
                    <div class="sidebar-widget text-center" style="background: linear-gradient(135deg, #001f42 0%, #0284c7 100%); color: #ffffff;">
                        <div class="rounded-circle text-white mx-auto mb-3" style="background: rgba(255,255,255,0.15); width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-headset fa-lg text-warning"></i>
                        </div>
                        <h5 class="fw-bold text-white mb-2">Need Course Guidance?</h5>
                        <p class="small text-white-50 mb-3" style="line-height: 1.6;">Speak with our academic counselors for admission and training program details.</p>
                        <a href="tel:<?php echo $CONTACT_MOBILE; ?>" class="btn btn-warning rounded-pill px-4 fw-bold shadow-sm" style="color: #002147;">
                            <i class="fas fa-phone-alt" style="margin-right: 6px;"></i> Call Now
                        </a>
                    </div>
                </aside>
            </div>
        </div>

        <?php include('footer.php'); ?>
    </div>

    <script>
    function copyArticleLink() {
        navigator.clipboard.writeText("<?php echo addslashes($canonical_url); ?>").then(function() {
            var btnText = document.getElementById('copy_btn_text');
            btnText.innerText = 'Copied!';
            setTimeout(function() {
                btnText.innerText = 'Copy Link';
            }, 2500);
        });
    }
    </script>
</body>
</html>

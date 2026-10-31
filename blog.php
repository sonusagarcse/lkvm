<?php
// blog.php - Public Blog Listing & Archive Page with Modern Editorial Human-Touch UI
require_once 'connection.php';

// Filter & Search Handling
$search_query = trim($_GET['q'] ?? '');
$category_slug = trim($_GET['category'] ?? '');
$tag_filter = trim($_GET['tag'] ?? '');

$where = ["b.status = 1"];
$params = [];
$types = "";

if (!empty($search_query)) {
    $where[] = "(b.title LIKE ? OR b.excerpt LIKE ? OR b.content LIKE ? OR b.tags LIKE ?)";
    $like = '%' . $search_query . '%';
    $params[] = $like; $params[] = $like; $params[] = $like; $params[] = $like;
    $types .= "ssss";
}

$active_category = null;
if (!empty($category_slug)) {
    $cat_q = $con->prepare("SELECT id, name, slug, description FROM blog_categories WHERE slug = ? AND status = 1 LIMIT 1");
    $cat_q->bind_param("s", $category_slug);
    $cat_q->execute();
    $active_category = $cat_q->get_result()->fetch_assoc();
    $cat_q->close();

    if ($active_category) {
        $where[] = "b.category_id = ?";
        $params[] = $active_category['id'];
        $types .= "i";
    }
}

if (!empty($tag_filter)) {
    $where[] = "b.tags LIKE ?";
    $params[] = '%' . $tag_filter . '%';
    $types .= "s";
}

$where_sql = implode(' AND ', $where);

// Pagination
$per_page = 8;
$page = max(1, intval($_GET['p'] ?? 1));
$offset = ($page - 1) * $per_page;

// Count total
$count_sql = "SELECT COUNT(*) FROM blogs b WHERE $where_sql";
$stmt_cnt = $con->prepare($count_sql);
if (!empty($types)) {
    $stmt_cnt->bind_param($types, ...$params);
}
$stmt_cnt->execute();
$total_posts = $stmt_cnt->get_result()->fetch_row()[0] ?? 0;
$stmt_cnt->close();

$total_pages = ceil($total_posts / $per_page);

// Fetch blogs
$blogs_sql = "
    SELECT b.*, c.name AS category_name, c.slug AS category_slug
    FROM blogs b
    LEFT JOIN blog_categories c ON c.id = b.category_id
    WHERE $where_sql
    ORDER BY b.published_at DESC
    LIMIT ?, ?
";
$params_with_limit = $params;
$params_with_limit[] = $offset;
$params_with_limit[] = $per_page;
$types_with_limit = $types . "ii";

$stmt_blogs = $con->prepare($blogs_sql);
$stmt_blogs->bind_param($types_with_limit, ...$params_with_limit);
$stmt_blogs->execute();
$blogs_res = $stmt_blogs->get_result();
$all_posts = [];
while ($row = $blogs_res->fetch_assoc()) {
    $all_posts[] = $row;
}
$stmt_blogs->close();

// Separate featured hero post on first page without filters
$featured_hero = null;
$grid_posts = $all_posts;
if ($page === 1 && empty($search_query) && empty($category_slug) && empty($tag_filter) && count($all_posts) > 0) {
    $featured_hero = $all_posts[0];
    $grid_posts = array_slice($all_posts, 1);
}

// Fetch Categories for Filter Pills & Sidebar
$categories_list_data = [];
$cat_res = mysqli_query($con, "
    SELECT c.*, COUNT(b.id) AS post_count 
    FROM blog_categories c
    LEFT JOIN blogs b ON b.category_id = c.id AND b.status = 1
    WHERE c.status = 1
    GROUP BY c.id
    ORDER BY c.name ASC
");
while ($c = mysqli_fetch_assoc($cat_res)) {
    $categories_list_data[] = $c;
}

// Fetch Total Published Count across all categories
$total_published = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as total FROM blogs WHERE status = 1"))['total'] ?? 0;

// Fetch Recent Posts for Sidebar
$recent_posts_q = mysqli_query($con, "SELECT id, title, slug, featured_image, published_at FROM blogs WHERE status = 1 ORDER BY published_at DESC LIMIT 4");
$recent_posts_list = [];
while ($rp = mysqli_fetch_assoc($recent_posts_q)) {
    $recent_posts_list[] = $rp;
}

// SEO Meta Variables
$page_title = "Stories & Insights";
if ($active_category) {
    $page_title = htmlspecialchars($active_category['name']) . " - Stories & Articles";
} elseif (!empty($search_query)) {
    $page_title = "Search: " . htmlspecialchars($search_query) . " - Blog";
}

$page_desc = "Discover stories of youth empowerment, Mithila cultural preservation, computer skill courses, and community change across Bihar from Lalit Kala Vikas Manch.";
$canonical_url = $SITE_URL . "/blog";
if ($active_category) {
    $canonical_url = $SITE_URL . "/blog/category/" . $active_category['slug'];
}
?>
<!doctype html>
<html lang="en-US">

<head>
    <?php include('csslink.php'); ?>
    <!-- Google Fonts for Modern Warm Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Lora:ital,wght@0,500;0,600;1,400&display=swap" rel="stylesheet">

    <!-- FontAwesome 6 Free for Sharp Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <title><?php echo $page_title; ?> | <?php echo htmlspecialchars($SITE_NAME); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($page_desc); ?>">
    <link rel="canonical" href="<?php echo htmlspecialchars($canonical_url); ?>">
    <meta property="og:title" content="<?php echo $page_title; ?> | <?php echo htmlspecialchars($SITE_NAME); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($page_desc); ?>">
    <meta property="og:url" content="<?php echo htmlspecialchars($canonical_url); ?>">
    <meta property="og:type" content="website">

    <style>
        :root {
            --lk-primary: #002147;
            --lk-primary-dark: #001530;
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
            font-size: 15px;
            line-height: 1.68;
            -webkit-font-smoothing: antialiased;
        }

        /* --- Human-Touch Hero Banner --- */
        .journal-hero {
            position: relative;
            background: radial-gradient(circle at 80% 20%, rgba(2, 132, 199, 0.45) 0%, transparent 50%),
                        linear-gradient(135deg, #001733 0%, #002b5e 60%, #043673 100%);
            padding: 70px 0 60px 0;
            color: #ffffff;
            overflow: hidden;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        .journal-hero::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: radial-gradient(rgba(255, 255, 255, 0.08) 1px, transparent 1px);
            background-size: 24px 24px;
            opacity: 0.45;
            pointer-events: none;
        }
        .journal-pill-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.22);
            color: #fdc800;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            padding: 6px 16px;
            border-radius: 50px;
            backdrop-filter: blur(8px);
            margin-bottom: 16px;
        }
        .journal-hero-title {
            font-family: var(--lk-font-main);
            font-weight: 800;
            font-size: clamp(2rem, 4vw, 2.85rem) !important;
            line-height: 1.22 !important;
            letter-spacing: -0.02em;
            color: #ffffff !important;
            margin-bottom: 14px !important;
            text-shadow: 0 2px 10px rgba(0,0,0,0.4);
        }
        .journal-hero-subtitle {
            font-size: 1.08rem;
            color: #e2e8f0 !important;
            line-height: 1.65;
            max-width: 640px;
            margin: 0 auto;
            font-weight: 400;
        }

        /* --- Category Pills Navigation Bar --- */
        .category-filter-bar {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 14px 0;
            position: sticky;
            top: 0;
            z-index: 30;
            box-shadow: 0 4px 18px rgba(15, 23, 42, 0.04);
        }
        .cat-scroll-container {
            display: flex;
            align-items: center;
            overflow-x: auto;
            white-space: nowrap;
            padding-bottom: 4px;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .cat-scroll-container::-webkit-scrollbar {
            display: none;
        }
        .cat-pill-btn {
            display: inline-flex;
            align-items: center;
            padding: 8px 18px;
            border-radius: 50px;
            font-size: 13.5px;
            font-weight: 600;
            color: var(--lk-text-body);
            background: #f1f5f9;
            text-decoration: none !important;
            transition: all 0.22s ease-in-out;
            border: 1px solid transparent;
            flex-shrink: 0;
            margin-right: 10px;
            margin-bottom: 4px;
        }
        .cat-pill-btn i {
            margin-right: 6px;
        }
            transition: all 0.22s ease-in-out;
            border: 1px solid transparent;
            flex-shrink: 0;
        }
        .cat-pill-btn:hover {
            background: #e2e8f0;
            color: var(--lk-text-dark);
            transform: translateY(-1px);
        }
        .cat-pill-btn.active {
            background: var(--lk-primary);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 33, 71, 0.25);
            border-color: var(--lk-primary);
        }
        .cat-pill-btn .count-badge {
            background: rgba(0, 0, 0, 0.08);
            color: inherit;
            font-size: 11px;
            padding: 2px 7px;
            border-radius: 20px;
            font-weight: 700;
        }
        .cat-pill-btn.active .count-badge {
            background: rgba(255, 255, 255, 0.22);
            color: #ffffff;
        }

        /* --- Featured Story Hero Card (Human Touch Editorial) --- */
        .featured-hero-card {
            background: #ffffff;
            border-radius: 24px;
            border: 1px solid var(--lk-card-border);
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
            margin-bottom: 40px;
            transition: all 0.35s ease;
        }
        .featured-hero-card:hover {
            box-shadow: 0 18px 45px rgba(0, 33, 71, 0.12);
            transform: translateY(-3px);
        }
        .featured-img-col {
            position: relative;
            min-height: 320px;
            background: #0f172a;
        }
        .featured-img-col img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            position: absolute;
            top: 0; left: 0;
            transition: transform 0.5s ease;
        }
        .featured-hero-card:hover .featured-img-col img {
            transform: scale(1.04);
        }
        .featured-content-col {
            padding: 42px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .editor-pick-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #b45309;
            background: #fef3c7;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            padding: 4px 12px;
            border-radius: 50px;
            margin-bottom: 14px;
            width: fit-content;
        }
        .featured-title {
            font-family: var(--lk-font-main);
            font-size: clamp(1.4rem, 2.2vw, 1.85rem);
            font-weight: 800;
            line-height: 1.3;
            color: var(--lk-text-dark);
            margin-bottom: 14px;
            letter-spacing: -0.015em;
        }
        .featured-title a {
            color: inherit;
            text-decoration: none;
            transition: color 0.2s;
        }
        .featured-title a:hover {
            color: var(--lk-sky);
        }
        .featured-excerpt {
            color: var(--lk-text-body);
            font-size: 15.5px;
            line-height: 1.7;
            margin-bottom: 24px;
        }
        .human-meta-row {
            display: flex;
            align-items: center;
            margin-top: auto;
            padding-top: 20px;
            border-top: 1px solid #f1f5f9;
        }
        .human-meta-row .author-avatar-chip {
            margin-right: 14px;
        }
        .author-avatar-chip {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: linear-gradient(135deg, #002147, #0284c7);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 15px;
            box-shadow: 0 4px 10px rgba(0, 33, 71, 0.15);
            flex-shrink: 0;
        }
        .author-details {
            display: flex;
            flex-direction: column;
        }
        .author-details .name {
            font-weight: 700;
            color: var(--lk-text-dark);
            font-size: 14px;
            line-height: 1.2;
        }
        .author-details .date-time {
            font-size: 12px;
            color: var(--lk-text-muted);
            margin-top: 3px;
        }
        .author-details .date-time i {
            margin-right: 4px;
        }

        /* --- Standard Blog Cards Grid --- */
        .story-card {
            background: #ffffff;
            border-radius: 20px;
            border: 1px solid var(--lk-card-border);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 100%;
            transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
            box-shadow: 0 4px 18px rgba(15, 23, 42, 0.03);
            position: relative;
        }
        .story-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 36px rgba(0, 33, 71, 0.09);
            border-color: #cbd5e1;
        }
        .story-thumb-wrap {
            position: relative;
            aspect-ratio: 16 / 9.5;
            overflow: hidden;
            background: #e2e8f0;
        }
        .story-thumb-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.45s ease;
        }
        .story-card:hover .story-thumb-wrap img {
            transform: scale(1.05);
        }
        .story-cat-badge {
            position: absolute;
            top: 14px;
            left: 14px;
            background: rgba(15, 23, 42, 0.88);
            color: #ffffff;
            font-size: 11px;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 50px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            backdrop-filter: blur(6px);
            z-index: 2;
        }
        .story-card-body {
            padding: 24px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }
        .story-micro-meta {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            font-size: 12.5px;
            color: var(--lk-text-muted);
            margin-bottom: 12px;
        }
        .story-micro-meta span {
            margin-right: 10px;
        }
        .story-micro-meta i {
            margin-right: 5px;
        }
        .story-card-title {
            font-family: var(--lk-font-main);
            font-size: 18.5px;
            font-weight: 800;
            line-height: 1.38;
            color: var(--lk-text-dark);
            margin-bottom: 12px;
            letter-spacing: -0.01em;
        }
        .story-card-title a {
            color: inherit;
            text-decoration: none;
            transition: color 0.2s;
        }
        .story-card-title a:hover {
            color: var(--lk-sky);
        }
        .story-card-excerpt {
            color: var(--lk-text-body);
            font-size: 14px;
            line-height: 1.65;
            margin-bottom: 20px;
            flex-grow: 1;
        }
        .story-card-footer {
            border-top: 1px solid #f1f5f9;
            padding-top: 15px;
            margin-top: auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .read-story-btn {
            font-size: 13.5px;
            font-weight: 700;
            color: var(--lk-primary);
            text-decoration: none !important;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: gap 0.2s, color 0.2s;
        }
        .read-story-btn:hover {
            color: var(--lk-sky);
            gap: 10px;
        }

        /* --- Modern Sidebar Styling --- */
        .journal-sidebar-box {
            background: #ffffff;
            border-radius: 20px;
            padding: 28px;
            border: 1px solid var(--lk-card-border);
            margin-bottom: 28px;
            box-shadow: 0 4px 18px rgba(15, 23, 42, 0.03);
        }
        .sidebar-heading {
            font-family: var(--lk-font-main);
            font-size: 17px;
            font-weight: 800;
            color: var(--lk-text-dark);
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .sidebar-heading::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 38px;
            height: 3px;
            background: var(--lk-accent-gold);
            border-radius: 2px;
        }
        .search-pill-input {
            border-radius: 50px;
            border: 1.5px solid #cbd5e1;
            padding: 10px 18px;
            font-size: 14px;
            color: var(--lk-text-dark);
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .search-pill-input:focus {
            border-color: var(--lk-primary);
            box-shadow: 0 0 0 4px rgba(0, 33, 71, 0.08);
            outline: none;
        }
        .search-pill-btn {
            border-radius: 50px;
            padding: 0 22px;
            background: var(--lk-primary);
            border: none;
            color: #ffffff;
            font-weight: 600;
            transition: background 0.2s;
        }
        .search-pill-btn:hover {
            background: var(--lk-sky);
        }

        .side-cat-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            color: var(--lk-text-body);
            text-decoration: none !important;
            border-bottom: 1px dashed #f1f5f9;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
        }
        .side-cat-item:last-child {
            border-bottom: none;
        }
        .side-cat-item:hover, .side-cat-item.active {
            color: var(--lk-primary);
            font-weight: 700;
            padding-left: 6px;
        }
        .side-cat-badge {
            background: #f1f5f9;
            color: var(--lk-text-muted);
            font-size: 11px;
            font-weight: 700;
            padding: 3px 9px;
            border-radius: 50px;
            border: 1px solid #e2e8f0;
        }
        .side-cat-item.active .side-cat-badge {
            background: var(--lk-primary);
            color: #ffffff;
            border-color: var(--lk-primary);
        }

        .recent-story-card {
            display: flex;
            align-items: center;
            text-decoration: none !important;
            padding-bottom: 14px;
            border-bottom: 1px solid #f8fafc;
        }
        .recent-story-card:last-child {
            padding-bottom: 0;
            border-bottom: none;
        }
        .recent-story-img {
            width: 76px;
            height: 64px;
            object-fit: cover;
            border-radius: 12px;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            transition: transform 0.2s;
            margin-right: 14px;
        }
        .recent-story-card:hover .recent-story-img {
            transform: scale(1.05);
        }
        .recent-story-title {
            font-size: 13.5px;
            font-weight: 700;
            line-height: 1.35;
            color: var(--lk-text-dark);
            margin-bottom: 4px;
            transition: color 0.2s;
        }
        .recent-story-card:hover .recent-story-title {
            color: var(--lk-sky);
        }
        .recent-story-date {
            font-size: 11.5px;
            color: var(--lk-text-muted);
            display: flex;
            align-items: center;
        }
        .recent-story-date i {
            margin-right: 5px !important;
            color: #f59e0b;
        }
        .story-card-footer .author-avatar-chip {
            margin-right: 8px !important;
        }
        .read-story-btn i {
            margin-left: 6px !important;
        }

        /* --- Human Connection / Community Box --- */
        .human-community-card {
            background: linear-gradient(135deg, #001f42 0%, #0284c7 100%);
            border-radius: 20px;
            padding: 32px 26px;
            color: #ffffff;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0, 33, 71, 0.15);
            position: relative;
            overflow: hidden;
        }
        .human-community-card::before {
            content: '“';
            position: absolute;
            top: -20px;
            right: 15px;
            font-size: 110px;
            font-family: Georgia, serif;
            color: rgba(255, 255, 255, 0.08);
            line-height: 1;
            pointer-events: none;
        }
        .community-quote {
            font-family: var(--lk-font-serif);
            font-size: 15px;
            line-height: 1.6;
            color: #f1f5f9;
            font-style: italic;
            margin-bottom: 18px;
        }
        .join-mission-btn {
            background: var(--lk-accent-gold);
            color: #002147 !important;
            font-weight: 800;
            font-size: 13.5px;
            padding: 10px 24px;
            border-radius: 50px;
            display: inline-block;
            text-decoration: none !important;
            transition: all 0.2s;
            box-shadow: 0 4px 14px rgba(253, 200, 0, 0.3);
        }
        .join-mission-btn:hover {
            background: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
        }

        /* --- Pagination --- */
        .journal-pagination .page-link {
            border: 1px solid #cbd5e1;
            color: var(--lk-text-dark);
            font-weight: 700;
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50% !important;
            margin: 0 4px;
            transition: all 0.2s;
        }
        .journal-pagination .page-item.active .page-link {
            background-color: var(--lk-primary);
            border-color: var(--lk-primary);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 33, 71, 0.25);
        }
        .journal-pagination .page-link:hover {
            background-color: #e2e8f0;
            color: var(--lk-text-dark);
        }
    </style>
</head>

<body class="home page-template-default page wp-embed-responsive theme-eikra header-style-9 footer-style-2">
    <div id="page" class="site">
        <?php include('header.php'); ?>

        <!-- Human-Touch Editorial Hero Banner -->
        <header class="journal-hero text-center">
            <div class="container position-relative">
                <div class="journal-pill-badge">
                    <i class="fas fa-feather-alt"></i> Stories &amp; Insights
                </div>
                <h1 class="journal-hero-title">
                    <?php if ($active_category): ?>
                        Category: <?php echo htmlspecialchars($active_category['name']); ?>
                    <?php elseif (!empty($search_query)): ?>
                        Search: &ldquo;<?php echo htmlspecialchars($search_query); ?>&rdquo;
                    <?php else: ?>
                        Empowering Bihar, One Story at a Time
                    <?php endif; ?>
                </h1>
                <p class="journal-hero-subtitle">
                    <?php if ($active_category): ?>
                        <?php echo htmlspecialchars($active_category['description'] ?: 'Browse all articles, educational guides, and initiatives in ' . $active_category['name'] . '.'); ?>
                    <?php else: ?>
                        Read inspiring stories of student transformation, free vocational computer courses, cultural heritage preservation, and ground updates from LKVM.
                    <?php endif; ?>
                </p>
            </div>
        </header>

        <!-- Category Horizontal Pills Bar -->
        <div class="category-filter-bar">
            <div class="container">
                <div class="cat-scroll-container">
                    <a href="<?php echo $SITE_URL; ?>/blog" class="cat-pill-btn <?php echo empty($category_slug) ? 'active' : ''; ?>">
                        <i class="fas fa-sparkles text-warning"></i>
                        <span>All Stories</span>
                        <span class="count-badge"><?php echo $total_published; ?></span>
                    </a>
                    <?php foreach ($categories_list_data as $cat): ?>
                        <a href="<?php echo $SITE_URL; ?>/blog/category/<?php echo htmlspecialchars($cat['slug']); ?>" 
                           class="cat-pill-btn <?php echo $category_slug === $cat['slug'] ? 'active' : ''; ?>">
                            <span><?php echo htmlspecialchars($cat['name']); ?></span>
                            <span class="count-badge"><?php echo intval($cat['post_count']); ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Main Blog Listing Section -->
        <main class="py-5">
            <div class="container">
                <div class="row g-4">
                    <!-- Left: Stories Stream -->
                    <div class="col-lg-8">
                        <?php if (!empty($search_query) || $active_category || !empty($tag_filter)): ?>
                            <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-white rounded-4 border">
                                <div>
                                    <span class="text-muted small">Showing results for:</span>
                                    <strong class="ms-1" style="color: var(--lk-text-dark);">
                                        <?php echo htmlspecialchars($search_query ?: ($active_category['name'] ?? $tag_filter)); ?>
                                    </strong>
                                    <span class="badge bg-primary text-white ms-2 rounded-pill px-3 py-1"><?php echo $total_posts; ?> articles</span>
                                </div>
                                <a href="<?php echo $SITE_URL; ?>/blog" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                    <i class="fas fa-times me-1"></i> Clear Filter
                                </a>
                            </div>
                        <?php endif; ?>

                        <!-- Featured Lead Post (on Page 1 without filter) -->
                        <?php if ($featured_hero): ?>
                            <?php
                            $f_read_time = max(1, round(str_word_count(strip_tags($featured_hero['content'])) / 200));
                            $f_img = !empty($featured_hero['featured_image']) && file_exists('uploads/' . $featured_hero['featured_image'])
                                ? $SITE_URL . '/uploads/' . htmlspecialchars($featured_hero['featured_image'])
                                : $SITE_URL . '/images/banner.jpg';
                            ?>
                            <article class="featured-hero-card">
                                <div class="row g-0">
                                    <div class="col-md-6 featured-img-col">
                                        <a href="<?php echo $SITE_URL; ?>/blog/<?php echo htmlspecialchars($featured_hero['slug']); ?>">
                                            <img src="<?php echo $f_img; ?>" alt="<?php echo htmlspecialchars($featured_hero['title']); ?>">
                                        </a>
                                    </div>
                                    <div class="col-md-6 featured-content-col">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <span class="editor-pick-tag">
                                                <i class="fas fa-star text-warning"></i> Featured Story
                                            </span>
                                            <span class="badge bg-light text-primary border rounded-pill px-2 py-1" style="font-size: 11px;">
                                                <?php echo htmlspecialchars($featured_hero['category_name'] ?? 'General'); ?>
                                            </span>
                                        </div>

                                        <h2 class="featured-title">
                                            <a href="<?php echo $SITE_URL; ?>/blog/<?php echo htmlspecialchars($featured_hero['slug']); ?>">
                                                <?php echo htmlspecialchars($featured_hero['title']); ?>
                                            </a>
                                        </h2>

                                        <p class="featured-excerpt">
                                            <?php echo htmlspecialchars($featured_hero['excerpt'] ?: substr(strip_tags($featured_hero['content']), 0, 140) . '...'); ?>
                                        </p>

                                        <div class="human-meta-row">
                                            <div class="author-avatar-chip">
                                                <?php echo strtoupper(substr($featured_hero['author_name'] ?? 'LK', 0, 2)); ?>
                                            </div>
                                            <div class="author-details">
                                                <span class="name"><?php echo htmlspecialchars($featured_hero['author_name'] ?? 'LKVM Team'); ?></span>
                                                <span class="date-time">
                                                    <i class="far fa-calendar-alt text-warning me-1"></i><?php echo date('M d, Y', strtotime($featured_hero['published_at'])); ?> 
                                                    &bull; <?php echo $f_read_time; ?> min read
                                                </span>
                                            </div>
                                            <a href="<?php echo $SITE_URL; ?>/blog/<?php echo htmlspecialchars($featured_hero['slug']); ?>" class="ms-auto btn btn-primary rounded-pill px-3 py-2 fw-bold" style="font-size: 13px;">
                                                Read Story <i class="fas fa-arrow-right fa-xs ms-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        <?php endif; ?>

                        <!-- Standard Stories Grid -->
                        <div class="row g-4">
                            <?php if (!empty($grid_posts)): ?>
                                <?php foreach ($grid_posts as $b): ?>
                                    <?php
                                    $read_time = max(1, round(str_word_count(strip_tags($b['content'])) / 200));
                                    $img_src = !empty($b['featured_image']) && file_exists('uploads/' . $b['featured_image']) 
                                        ? $SITE_URL . '/uploads/' . htmlspecialchars($b['featured_image']) 
                                        : $SITE_URL . '/images/banner.jpg';
                                    ?>
                                    <div class="col-md-6 col-12">
                                        <article class="story-card">
                                            <div class="story-thumb-wrap">
                                                <a href="<?php echo $SITE_URL; ?>/blog/<?php echo htmlspecialchars($b['slug']); ?>">
                                                    <img src="<?php echo $img_src; ?>" alt="<?php echo htmlspecialchars($b['title']); ?>" loading="lazy">
                                                </a>
                                                <span class="story-cat-badge">
                                                    <?php echo htmlspecialchars($b['category_name'] ?? 'General'); ?>
                                                </span>
                                            </div>

                                            <div class="story-card-body">
                                                <div class="story-micro-meta">
                                                    <span><i class="far fa-calendar-alt text-warning me-1"></i><?php echo date('M d, Y', strtotime($b['published_at'])); ?></span>
                                                    <span>&bull;</span>
                                                    <span><i class="far fa-clock text-info me-1"></i><?php echo $read_time; ?> min read</span>
                                                </div>

                                                <h3 class="story-card-title">
                                                    <a href="<?php echo $SITE_URL; ?>/blog/<?php echo htmlspecialchars($b['slug']); ?>">
                                                        <?php echo htmlspecialchars($b['title']); ?>
                                                    </a>
                                                </h3>

                                                <p class="story-card-excerpt">
                                                    <?php echo htmlspecialchars($b['excerpt'] ?: substr(strip_tags($b['content']), 0, 115) . '...'); ?>
                                                </p>

                                                <div class="story-card-footer">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="author-avatar-chip" style="width: 28px; height: 28px; font-size: 11px;">
                                                            <?php echo strtoupper(substr($b['author_name'] ?? 'LK', 0, 2)); ?>
                                                        </div>
                                                        <span class="small fw-semibold" style="color: var(--lk-text-dark); font-size: 12.5px;">
                                                            <?php echo htmlspecialchars($b['author_name'] ?? 'LKVM Team'); ?>
                                                        </span>
                                                    </div>
                                                    <a href="<?php echo $SITE_URL; ?>/blog/<?php echo htmlspecialchars($b['slug']); ?>" class="read-story-btn">
                                                        Read Article <i class="fas fa-arrow-right fa-xs"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </article>
                                    </div>
                                <?php endforeach; ?>
                            <?php elseif (!$featured_hero): ?>
                                <div class="col-12 text-center py-5">
                                    <div class="p-5 bg-white rounded-4 border shadow-sm">
                                        <div class="mb-3">
                                            <i class="fas fa-book-open fa-3x text-warning opacity-75"></i>
                                        </div>
                                        <h4 class="fw-bold" style="color: var(--lk-text-dark);">No Articles Found</h4>
                                        <p class="text-muted mb-4">We couldn't find any articles matching your search or category selection.</p>
                                        <a href="<?php echo $SITE_URL; ?>/blog" class="btn btn-primary rounded-pill px-4">
                                            <i class="fas fa-arrow-left me-1"></i> View All Articles
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Pagination -->
                        <?php if ($total_pages > 1): ?>
                            <div class="d-flex justify-content-center mt-5">
                                <ul class="pagination journal-pagination">
                                    <?php if ($page > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?p=<?php echo $page - 1; ?>&category=<?php echo urlencode($category_slug); ?>&q=<?php echo urlencode($search_query); ?>" aria-label="Previous">
                                                <i class="fas fa-chevron-left"></i>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                        <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
                                            <a class="page-link" href="?p=<?php echo $i; ?>&category=<?php echo urlencode($category_slug); ?>&q=<?php echo urlencode($search_query); ?>">
                                                <?php echo $i; ?>
                                            </a>
                                        </li>
                                    <?php endfor; ?>

                                    <?php if ($page < $total_pages): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?p=<?php echo $page + 1; ?>&category=<?php echo urlencode($category_slug); ?>&q=<?php echo urlencode($search_query); ?>" aria-label="Next">
                                                <i class="fas fa-chevron-right"></i>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Right: Human-Touch Sidebar -->
                    <aside class="col-lg-4">
                        <!-- Search Box -->
                        <div class="journal-sidebar-box">
                            <h4 class="sidebar-heading">
                                <i class="fas fa-search text-primary"></i> Search Journal
                            </h4>
                            <form method="GET" action="<?php echo $SITE_URL; ?>/blog">
                                <div class="input-group">
                                    <input type="text" name="q" class="form-control search-pill-input" placeholder="Type topic or keyword..." value="<?php echo htmlspecialchars($search_query); ?>" required>
                                    <button type="submit" class="search-pill-btn">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Categories List -->
                        <div class="journal-sidebar-box">
                            <h4 class="sidebar-heading">
                                <i class="fas fa-folder-open text-warning"></i> Explore by Category
                            </h4>
                            <div class="d-flex flex-column">
                                <a href="<?php echo $SITE_URL; ?>/blog" class="side-cat-item <?php echo empty($category_slug) ? 'active' : ''; ?>">
                                    <span><i class="fas fa-th-large me-2 text-warning opacity-75"></i>All Categories</span>
                                    <span class="side-cat-badge"><?php echo $total_published; ?></span>
                                </a>
                                <?php foreach ($categories_list_data as $cat): ?>
                                    <a href="<?php echo $SITE_URL; ?>/blog/category/<?php echo htmlspecialchars($cat['slug']); ?>" 
                                       class="side-cat-item <?php echo $category_slug === $cat['slug'] ? 'active' : ''; ?>">
                                        <span><i class="fas fa-angle-right me-2 text-primary opacity-75"></i><?php echo htmlspecialchars($cat['name']); ?></span>
                                        <span class="side-cat-badge"><?php echo intval($cat['post_count']); ?></span>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Recent Articles -->
                        <?php if (!empty($recent_posts_list)): ?>
                            <div class="journal-sidebar-box">
                                <h4 class="sidebar-heading">
                                    <i class="fas fa-clock text-info"></i> Recent Stories
                                </h4>
                                <div class="d-flex flex-column gap-3">
                                    <?php foreach ($recent_posts_list as $rp): ?>
                                        <?php
                                        $rp_img = !empty($rp['featured_image']) && file_exists('uploads/' . $rp['featured_image'])
                                            ? $SITE_URL . '/uploads/' . htmlspecialchars($rp['featured_image'])
                                            : $SITE_URL . '/images/banner.jpg';
                                        ?>
                                        <a href="<?php echo $SITE_URL; ?>/blog/<?php echo htmlspecialchars($rp['slug']); ?>" class="recent-story-card">
                                            <img src="<?php echo $rp_img; ?>" alt="<?php echo htmlspecialchars($rp['title']); ?>" class="recent-story-img" loading="lazy">
                                            <div>
                                                <h5 class="recent-story-title">
                                                    <?php echo htmlspecialchars($rp['title']); ?>
                                                </h5>
                                                <span class="recent-story-date">
                                                    <i class="far fa-calendar-alt text-warning me-1"></i><?php echo date('M d, Y', strtotime($rp['published_at'])); ?>
                                                </span>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Human Mission Quote & Community Card -->
                        <div class="human-community-card">
                            <div class="mb-3">
                                <i class="fas fa-hand-holding-heart fa-2x text-warning"></i>
                            </div>
                            <h5 class="fw-bold mb-2 text-white">Our Grassroots Mission</h5>
                            <p class="community-quote">
                                &ldquo;Empowering rural and semi-urban students of Bihar through technology education, cultural heritage, and vocational independence.&rdquo;
                            </p>
                            <a href="https://courses.lkvmbihar.in/" class="join-mission-btn">
                                Explore Skill Programs <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </aside>
                </div>
            </div>
        </main>

        <?php include('footer.php'); ?>
    </div>
</body>
</html>
<?php
header("Content-Type: application/xml; charset=utf-8");
require_once 'connection.php';

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    
    <!-- Static Pages -->
    <url>
        <loc><?php echo $SITE_URL; ?>/</loc>
        <priority>1.0</priority>
        <changefreq>daily</changefreq>
    </url>
    <url>
        <loc><?php echo $SITE_URL; ?>/news.php</loc>
        <priority>0.8</priority>
        <changefreq>daily</changefreq>
    </url>
    <url>
        <loc><?php echo $SITE_URL; ?>/contact_us.php</loc>
        <priority>0.5</priority>
        <changefreq>monthly</changefreq>
    </url>
    <url>
        <loc><?php echo $SITE_URL; ?>/notices.php</loc>
        <priority>0.7</priority>
        <changefreq>daily</changefreq>
    </url>
    <url>
        <loc><?php echo $SITE_URL; ?>/blog</loc>
        <priority>0.9</priority>
        <changefreq>daily</changefreq>
    </url>

    <?php
    // Dynamic Blog Articles
    $b_res = mysqli_query($con, "SELECT slug, updated_at, published_at FROM blogs WHERE status = 1 ORDER BY published_at DESC");
    if ($b_res) {
        while ($b_row = mysqli_fetch_assoc($b_res)) {
            $last_date = date('Y-m-d', strtotime($b_row['updated_at'] ?? $b_row['published_at']));
            echo "    <url>\n";
            echo "        <loc>" . htmlspecialchars($SITE_URL . '/blog/' . $b_row['slug']) . "</loc>\n";
            echo "        <lastmod>" . $last_date . "</lastmod>\n";
            echo "        <changefreq>weekly</changefreq>\n";
            echo "        <priority>0.8</priority>\n";
            echo "    </url>\n";
        }
    }

    // Dynamic Blog Categories
    $cat_res = mysqli_query($con, "SELECT slug FROM blog_categories WHERE status = 1");
    if ($cat_res) {
        while ($c_row = mysqli_fetch_assoc($cat_res)) {
            echo "    <url>\n";
            echo "        <loc>" . htmlspecialchars($SITE_URL . '/blog/category/' . $c_row['slug']) . "</loc>\n";
            echo "        <changefreq>weekly</changefreq>\n";
            echo "        <priority>0.7</priority>\n";
            echo "    </url>\n";
        }
    }
    ?>
</urlset>

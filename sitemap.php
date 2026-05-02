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

    <?php
    // Dynamic News items (assuming news.php?id=... logic exists if needed, or just link to list)
    // For now, index the archive pages mainly. 
    // If there were individual news pages like news_detail.php?id=123, we'd loop here:
    /*
    $res = mysqli_query($con, "SELECT id FROM news WHERE status=1");
    while($row = mysqli_fetch_assoc($res)) {
        echo '<url><loc>'.$SITE_URL.'/news_detail.php?id='.$row['id'].'</loc><priority>0.6</priority></url>';
    }
    */
    ?>
</urlset>

<?php
$page_title = 'Browse Categories';
include 'includes/header.php';

$cat_id = $_GET['id'] ?? 0;

$filter = ($cat_id > 0) ? "AND c.category_id = $cat_id" : "";
$query = "SELECT c.*, cat.name as category_name 
          FROM lms_courses c 
          LEFT JOIN lms_categories cat ON c.category_id = cat.id 
          WHERE c.status = 1 $filter
          ORDER BY c.id DESC";
$result = mysqli_query($con, $query);
?>

<div class="py-5 bg-light">
    <div class="container">
        <h1 class="fw-bold mb-4">Categories</h1>
        <div class="d-flex flex-wrap gap-2 mb-5">
            <a href="categories.php" class="btn btn-white shadow-sm rounded-pill px-4 <?php echo !$cat_id ? 'bg-primary text-white' : 'bg-white'; ?>">All Categories</a>
            <?php
            $cats = mysqli_query($con, "SELECT * FROM lms_categories");
            while($c = mysqli_fetch_assoc($cats)):
                $active = ($c['id'] == $cat_id) ? 'bg-primary text-white' : 'bg-white';
            ?>
                <a href="?id=<?php echo $c['id']; ?>" class="btn shadow-sm rounded-pill px-4 <?php echo $active; ?>"><?php echo $c['name']; ?></a>
            <?php endwhile; ?>
        </div>

        <div class="row g-4">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($course = mysqli_fetch_assoc($result)): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="lms-card">
                            <div class="lms-card-img">
                                <span class="lms-badge"><?php echo htmlspecialchars($course['category_name']); ?></span>
                                <?php if($course['thumbnail']): ?>
                                    <img src="<?php echo $course['thumbnail']; ?>" alt="<?php echo htmlspecialchars($course['title']); ?>">
                                <?php else: ?>
                                    <img src="https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&q=80&w=600" alt="Course">
                                <?php endif; ?>
                            </div>
                            <div class="lms-card-body">
                                <h3 class="lms-card-title"><?php echo htmlspecialchars($course['title']); ?></h3>
                                <div class="lms-meta">
                                    <span><i class="fa-regular fa-clock"></i> <?php echo $course['duration']; ?> Hours</span>
                                    <span><i class="fa-solid fa-signal"></i> <?php echo $course['level']; ?></span>
                                </div>
                                <div class="lms-price-row">
                                    <div class="lms-price">
                                        <?php if($course['is_free']): ?>
                                            <span class="text-success fw-bold">FREE</span>
                                        <?php else: ?>
                                            ₹<?php echo number_format($course['price'], 0); ?>
                                        <?php endif; ?>
                                    </div>
                                    <a href="details.php?slug=<?php echo $course['slug']; ?>" class="btn-view">View Details</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <p class="text-muted">No courses found in this category.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

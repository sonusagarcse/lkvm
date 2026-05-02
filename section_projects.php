<?php
// section_projects.php
$projectsQ = mysqli_query($con, "SELECT * FROM projects WHERE status = 'active' ORDER BY id DESC");
$pBadge = !empty($settings['project_badge']) ? htmlspecialchars($settings['project_badge']) : 'Running Now';
$pTitle = !empty($settings['project_title']) ? htmlspecialchars($settings['project_title']) : 'Our Running Projects';
$pSubtitle = !empty($settings['project_subtitle']) ? htmlspecialchars($settings['project_subtitle']) : 'Witness the change we are bringing today through our active initiatives across the nation.';
?>

<section class="projects-section py-5 position-relative" style="background: #ffffff; overflow: hidden;">
    <div class="container position-relative" style="z-index: 2;">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <span class="badge bg-success-soft text-success text-uppercase px-3 py-2 rounded-pill fw-bold mb-3 shadow-sm border border-success">
                    <i class="fas fa-rocket me-1"></i> <?php echo $pBadge; ?>
                </span>
                <h2 class="display-4 fw-bold mt-2 section-title" style="color: #012751;"><?php echo $pTitle; ?></h2>
                <p class="text-muted mx-auto lead" style="max-width: 600px;"><?php echo $pSubtitle; ?></p>
                <div class="mx-auto mt-4" style="width: 80px; height: 4px; background: linear-gradient(to right, #157347, #2ecc71); border-radius: 2px;"></div>
            </div>
        </div>

        <div class="owl-carousel projects-carousel owl-theme">
            <?php if (mysqli_num_rows($projectsQ) > 0): ?>
                <?php while ($proj = mysqli_fetch_assoc($projectsQ)): 
                    $projImg = !empty($proj['image']) && file_exists('images/projects/' . $proj['image']) ? 'images/projects/' . $proj['image'] : 'https://placehold.co/800x600?text=' . urlencode($proj['title']);
                ?>
                <div class="item p-2">
                    <div class="project-premium-card d-flex flex-column h-100">
                        <div class="card-img-wrapper">
                            <img src="<?php echo $projImg; ?>" class="img-fluid" alt="<?php echo htmlspecialchars($proj['title']); ?>">
                            <div class="card-date">
                                <span class="d-block day"><?php echo date('d', strtotime($proj['created_at'])); ?></span>
                                <span class="d-block month"><?php echo date('M', strtotime($proj['created_at'])); ?></span>
                            </div>
                        </div>
                        <div class="card-content p-4 flex-grow-1 d-flex flex-column">
                            <h4 class="fw-bold mb-3 title"><?php echo htmlspecialchars($proj['title']); ?></h4>
                            <p class="text-muted description flex-grow-1">
                                <?php echo (strlen($proj['description']) > 150) ? substr(strip_tags($proj['description']), 0, 150) . '...' : $proj['description']; ?>
                            </p>
                            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top mt-auto">
                                <?php if (!empty($proj['link'])): ?>
                                    <a href="<?php echo htmlspecialchars($proj['link']); ?>" class="explore-btn">
                                        Explore More <i class="fas fa-arrow-right ms-2"></i>
                                    </a>
                                <?php else: ?>
                                    <span class="text-success small fw-bold"><i class="fas fa-check-circle me-1"></i> Continuous</span>
                                <?php endif; ?>
                                <div class="share-icons">
                                    <i class="fas fa-share-alt text-muted cursor-pointer"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Decorative elements -->
    <div class="position-absolute top-0 end-0 p-5 mt-5 opacity-10">
        <i class="fas fa-quote-right fa-10x text-success"></i>
    </div>
</section>

<style>
.bg-success-soft {
    background-color: rgba(21, 115, 71, 0.1);
}
.section-title {
    font-family: 'Playfair Display', serif;
    letter-spacing: -1px;
}
.project-premium-card {
    background: #fff;
    border-radius: 24px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.05);
    transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    height: 100% !important;
    display: flex;
    flex-direction: column;
    border: 1px solid rgba(0,0,0,0.05);
    overflow: hidden;
}
.projects-carousel .item {
    height: 100%;
}
.project-premium-card:hover {
    transform: translateY(-12px);
    box-shadow: 0 30px 60px rgba(0,0,0,0.12);
}
.card-img-wrapper {
    position: relative;
    height: 240px;
    flex-shrink: 0;
    overflow: hidden;
}
.card-img-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.8s ease;
}
.project-premium-card:hover .card-img-wrapper img {
    transform: scale(1.15);
}
.card-date {
    position: absolute;
    top: 20px;
    left: 20px;
    background: #157347;
    color: #fff;
    padding: 8px 15px;
    border-radius: 12px;
    text-align: center;
    box-shadow: 0 4px 15px rgba(21, 115, 71, 0.3);
    z-index: 3;
}
.card-date .day { font-size: 1.2rem; font-weight: 800; line-height: 1; }
.card-date .month { font-size: 0.75rem; text-transform: uppercase; font-weight: 600; opacity: 0.9; }

.card-content .title {
    color: #012751;
    font-size: 1.25rem;
    transition: color 0.3s ease;
}
.project-premium-card:hover .title {
    color: #157347;
}
.card-content .description {
    font-size: 0.95rem;
    line-height: 1.6;
}
.explore-btn {
    text-decoration: none;
    color: #157347;
    font-weight: 700;
    font-size: 0.9rem;
    display: inline-flex;
    align-items: center;
    transition: gap 0.3s ease;
}
.explore-btn:hover {
    color: #012751;
    gap: 12px;
}

/* Owl Carousel Custom Controls */
.projects-carousel .owl-nav button {
    width: 45px;
    height: 45px;
    background: #fff !important;
    color: #157347 !important;
    border-radius: 50% !important;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
    transition: all 0.3s ease !important;
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
}
.projects-carousel .owl-nav button:hover {
    background: #157347 !important;
    color: #fff !important;
}
.projects-carousel .owl-nav .owl-prev { left: -60px; }
.projects-carousel .owl-nav .owl-next { right: -60px; }

@media (max-width: 1200px) {
    .projects-carousel .owl-nav { display: none; }
}
</style>

<script>
$(document).ready(function(){
    $(".projects-carousel").owlCarousel({
        loop: true,
        margin: 20,
        autoplay: true,
        autoplayTimeout: 4000,
        autoplayHoverPause: true,
        nav: true,
        dots: true,
        navText: ['<i class="fas fa-chevron-left"></i>','<i class="fas fa-chevron-right"></i>'],
        responsive: {
            0: { items: 1 },
            768: { items: 2 },
            1200: { items: 3 }
        }
    });
});
</script>

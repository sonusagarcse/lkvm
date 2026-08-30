<?php
$page_title = 'Find Your Course';
include 'includes/header.php';

// Fetch Courses from the new lms_courses table
$query = "SELECT c.*, cat.name as category_name 
          FROM lms_courses c 
          LEFT JOIN lms_categories cat ON c.category_id = cat.id 
          WHERE c.status = 1 
          ORDER BY c.id DESC";
$result = mysqli_query($con, $query);
?>

<section class="hero-banner position-relative overflow-hidden">
    <div class="container position-relative z-1">
        <div class="badge bg-soft-primary text-primary px-3 py-2 rounded-pill mb-3"
            style="background-color: #eef2ff; color: #6366f1; font-weight: 700;">TRUSTED BY 10,000+ STUDENTS</div>
        <h1 class="display-3 fw-800 mb-4">Master the Skills of the <span class="text-gradient">Future</span></h1>
        <p class="lead text-muted mx-auto mb-5" style="max-width: 650px;">Join our academy to access premium courses
            taught by industry experts. Start your journey towards professional excellence today.</p>

        <div class="search-box-wrap mx-auto" style="max-width: 600px;">
            <form action="index.php" method="GET" class="p-2 bg-white rounded-pill shadow-lg d-flex gap-2">
                <input type="text" name="s" class="form-control border-0 px-4 rounded-pill"
                    placeholder="What do you want to learn?">
                <button type="submit" class="btn btn-primary rounded-pill px-4 py-2"
                    style="background: var(--primary); border: none;">Search</button>
            </form>
        </div>
    </div>
    <!-- Decorative elements -->
    <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10 pointer-events-none">
        <div class="position-absolute rounded-circle bg-primary"
            style="width: 300px; height: 300px; top: -100px; left: -100px; filter: blur(100px);"></div>
        <div class="position-absolute rounded-circle bg-secondary"
            style="width: 250px; height: 250px; bottom: -50px; right: -50px; filter: blur(80px);"></div>
    </div>
</section>

<section class="featured-categories py-5 bg-white">
    <div class="container">
        <div class="row g-4 text-center">
            <?php
            $cat_query = "SELECT * FROM lms_categories LIMIT 4";
            $cats = mysqli_query($con, $cat_query);
            while ($cat = mysqli_fetch_assoc($cats)): ?>
                <div class="col-6 col-lg-3">
                    <a href="categories.php?id=<?php echo $cat['id']; ?>"
                        class="category-item p-4 d-block text-decoration-none rounded-4 border transition">
                        <div class="icon-circle mb-3 mx-auto bg-light rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 60px; height: 60px;">
                            <i class="fa fa-code text-primary fs-4"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-0"><?php echo htmlspecialchars($cat['name']); ?></h6>
                    </a>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</section>


<section class="why-choose-us py-5">
    <div class="container py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <h2 class="display-5 fw-800 mb-4">Why Start Your <span class="text-primary">Journey</span> With Us?</h2>
                <p class="text-muted mb-5">We provide the most effective and practical learning environment to help you
                    achieve your career goals faster than ever.</p>

                <div class="d-grid gap-4">
                    <div class="feature-box d-flex gap-3">
                        <div class="icon-wrap bg-primary bg-opacity-10 text-primary p-3 rounded-4 h-fit">
                            <i class="fa fa-certificate fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1">Recognized Certificates</h5>
                            <p class="text-muted mb-0">Get certified upon completion and share your achievement with the
                                world.</p>
                        </div>
                    </div>
                    <div class="feature-box d-flex gap-3">
                        <div class="icon-wrap bg-success bg-opacity-10 text-success p-3 rounded-4 h-fit">
                            <i class="fa fa-infinity fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1">Lifetime Access</h5>
                            <p class="text-muted mb-0">Learn at your own pace with lifetime access to all your enrolled
                                courses.</p>
                        </div>
                    </div>
                    <div class="feature-box d-flex gap-3">
                        <div class="icon-wrap bg-warning bg-opacity-10 text-warning p-3 rounded-4 h-fit">
                            <i class="fa fa-users-gear fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1">Expert Mentorship</h5>
                            <p class="text-muted mb-0">Direct support from industry leaders to clear your doubts and
                                guide you.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="position-relative">
                    <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&q=80&w=800"
                        class="img-fluid rounded-5 shadow-2xl" alt="Learning">
                    <div
                        class="position-absolute bottom-0 start-0 bg-white p-4 rounded-4 shadow-lg m-4 d-none d-md-block border-start border-primary border-5">
                        <h6 class="fw-800 mb-1">"Best platform for practical skills!"</h6>
                        <div class="text-warning mb-2"><i class="fa fa-star"></i><i class="fa fa-star"></i><i
                                class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></div>
                        <p class="small text-muted mb-0">- Happy Student</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="stats-section py-5">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-6 col-md-3">
                <div class="stat-card p-4 rounded-4 bg-white shadow-sm border-0">
                    <h2 class="fw-800 text-primary mb-1">15k+</h2>
                    <p class="text-muted mb-0 small fw-bold">ACTIVE STUDENTS</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card p-4 rounded-4 bg-white shadow-sm border-0">
                    <h2 class="fw-800 text-primary mb-1">200+</h2>
                    <p class="text-muted mb-0 small fw-bold">TOTAL COURSES</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card p-4 rounded-4 bg-white shadow-sm border-0">
                    <h2 class="fw-800 text-primary mb-1">50+</h2>
                    <p class="text-muted mb-0 small fw-bold">EXPERT MENTORS</p>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card p-4 rounded-4 bg-white shadow-sm border-0">
                    <h2 class="fw-800 text-primary mb-1">98%</h2>
                    <p class="text-muted mb-0 small fw-bold">SATISFACTION RATE</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="courses-grid py-5 bg-light">
    <div class="container mb-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-4">
            <div>
                <h2 class="fw-bold mb-0 h1">Top <span class="text-primary">Featured</span> Courses</h2>
                <p class="text-muted mb-0">Hand-picked courses by our expert team</p>
            </div>
            <div class="filter-pills d-flex gap-2 overflow-auto pb-2">
                <a href="index.php"
                    class="btn btn-outline-secondary rounded-pill px-4 py-2 active border-0 bg-white shadow-sm">All</a>
                <a href="#" class="btn btn-outline-secondary rounded-pill px-4 py-2 border-0">Popular</a>
                <a href="#" class="btn btn-outline-secondary rounded-pill px-4 py-2 border-0">Latest</a>
            </div>
        </div>

        <div class="row g-4">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($course = mysqli_fetch_assoc($result)): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="lms-card">
                            <div class="lms-card-img">
                                <span
                                    class="lms-badge"><?php echo htmlspecialchars($course['category_name'] ?? 'General'); ?></span>
                                <?php if($course['thumbnail']): ?>
                                    <img src="<?php echo $course['thumbnail']; ?>" alt="<?php echo htmlspecialchars($course['title']); ?>">
                                <?php else: ?>
                                    <img src="https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&q=80&w=600" alt="<?php echo htmlspecialchars($course['title']); ?>">
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
                                            <?php echo $course['price'] > 0 ? '₹' . number_format($course['price'], 0) : 'Free'; ?>
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
                    <img src="https://illustrations.popsy.co/gray/not-found.svg" alt="Empty" style="height: 200px;"
                        class="mb-4">
                    <h3 class="fw-bold">No courses found</h3>
                    <p class="text-muted">We're currently preparing some amazing content for you. Stay tuned!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="testimonials py-5 bg-white">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="fw-800 display-6">What Our <span class="text-primary">Students</span> Say</h2>
            <p class="text-muted">Real stories from real learners who transformed their careers.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="testimonial-card p-4 rounded-4 border bg-light h-100">
                    <div class="stars text-warning mb-3"><i class="fa fa-star"></i><i class="fa fa-star"></i><i
                            class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></div>
                    <p class="mb-4">"The instructors are top-notch and the curriculum is very practical. I landed my
                        first job after taking the Web Dev course!"</p>
                    <div class="d-flex align-items-center gap-3">
                        <img src="https://ui-avatars.com/api/?name=Rahul+Kumar&background=random" class="rounded-circle"
                            style="width: 50px;">
                        <div>
                            <h6 class="fw-bold mb-0">Rahul Kumar</h6>
                            <small class="text-muted">Full Stack Developer</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="testimonial-card p-4 rounded-4 border bg-light h-100">
                    <div class="stars text-warning mb-3"><i class="fa fa-star"></i><i class="fa fa-star"></i><i
                            class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></div>
                    <p class="mb-4">"Best decision I ever made for my career. The projects are real-world and the
                        support is incredible."</p>
                    <div class="d-flex align-items-center gap-3">
                        <img src="https://ui-avatars.com/api/?name=Priya+Sharma&background=random"
                            class="rounded-circle" style="width: 50px;">
                        <div>
                            <h6 class="fw-bold mb-0">Priya Sharma</h6>
                            <small class="text-muted">UI/UX Designer</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="testimonial-card p-4 rounded-4 border bg-light h-100">
                    <div class="stars text-warning mb-3"><i class="fa fa-star"></i><i class="fa fa-star"></i><i
                            class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></div>
                    <p class="mb-4">"I love the lifetime access. I can always come back and refresh my skills whenever I
                        need to."</p>
                    <div class="d-flex align-items-center gap-3">
                        <img src="https://ui-avatars.com/api/?name=Aman+Singh&background=random" class="rounded-circle"
                            style="width: 50px;">
                        <div>
                            <h6 class="fw-bold mb-0">Aman Singh</h6>
                            <small class="text-muted">Marketing Specialist</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="final-cta py-5">
    <div class="container">
        <div class="bg-primary rounded-5 p-5 text-center text-white shadow-2xl position-relative overflow-hidden"
            style="background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);">
            <div class="position-relative z-1 py-4">
                <h2 class="display-5 fw-800 mb-3">Ready to Start Learning?</h2>
                <p class="lead mb-5 opacity-90">Join thousands of students and start building your future today.</p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="../login.php?action=signup"
                        class="btn btn-white btn-lg px-5 py-3 rounded-pill fw-bold text-primary shadow-lg bg-white">Get
                        Started Now</a>
                    <a href="categories.php"
                        class="btn btn-outline-white btn-lg px-5 py-3 rounded-pill fw-bold text-white border-2 border-white">Browse
                        Catalog</a>
                </div>
            </div>
            <div class="position-absolute top-0 start-0 w-100 h-100 opacity-20">
                <div class="position-absolute bg-white rounded-circle"
                    style="width: 200px; height: 200px; top: -100px; right: -50px;"></div>
                <div class="position-absolute bg-white rounded-circle"
                    style="width: 150px; height: 150px; bottom: -50px; left: -50px;"></div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
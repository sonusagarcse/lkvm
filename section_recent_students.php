<?php
require_once 'connection.php';
global $con;

// Fetch registered students
$recent_students = array();
$res_query = mysqli_query($con, "SELECT r.name as student_name, r.img, c.name as course_name FROM registration r JOIN courses c ON r.course = c.id ORDER BY r.id DESC LIMIT 12");
if ($res_query) {
    while ($row = mysqli_fetch_assoc($res_query)) {
        // If student image is empty, assign a gorgeous UI avatar based on their name
        if (empty($row['img'])) {
            $row['img'] = 'https://ui-avatars.com/api/?name=' . urlencode($row['student_name']) . '&background=002147&color=fff&bold=true&size=150';
        } else {
            // Check if it is a full URL or a filename
            if (filter_var($row['img'], FILTER_VALIDATE_URL)) {
                // Keep as is
            } else {
                $row['img'] = 'images/' . $row['img'];
            }
        }
        $recent_students[] = $row;
    }
}

$student_count = count($recent_students);
?>

<?php if ($student_count > 0): ?>
<!-- High-Performance GPU Accelerated Marquee Styles -->
<style>
.student-marquee-container {
    overflow: hidden;
    width: 100%;
    position: relative;
    padding: 10px 0;
}

/* Semi-transparent blur overlay shadows on left/right edges for premium layout depth */
.student-marquee-container::before,
.student-marquee-container::after {
    content: "";
    height: 100%;
    width: 100px;
    position: absolute;
    top: 0;
    z-index: 2;
    pointer-events: none;
    transition: opacity 0.3s ease;
}
.student-marquee-container::before {
    left: 0;
    background: linear-gradient(to right, rgba(253,253,253,1) 0%, rgba(253,253,253,0) 100%);
}
.student-marquee-container::after {
    right: 0;
    background: linear-gradient(to left, rgba(253,253,253,1) 0%, rgba(253,253,253,0) 100%);
}

.student-marquee-content {
    display: flex;
    width: max-content;
    animation: studentMarqueeScroll 30s linear infinite;
    will-change: transform;
}

/* Pause scroll animation on mouse hover */
.student-marquee-container:hover .student-marquee-content {
    animation-play-state: paused;
}

@keyframes studentMarqueeScroll {
    0% {
        transform: translate3d(0, 0, 0);
    }
    100% {
        transform: translate3d(-50%, 0, 0);
    }
}

.student-marquee-item {
    width: 280px;
    flex-shrink: 0;
    padding: 12px;
    box-sizing: border-box;
}

@media (max-width: 576px) {
    .student-marquee-item {
        width: 240px;
        padding: 8px;
    }
    .student-marquee-container::before,
    .student-marquee-container::after {
        width: 40px;
    }
}
</style>

<div class="vc_row wpb_row vc_row-fluid vc_custom_student_section" style="padding-top: 50px; padding-bottom: 50px; background: #fdfdfd;">
    <div class="wpb_column vc_column_container vc_col-sm-12">
        <div class="vc_column-inner">
            <div class="wpb_wrapper">
                
                <!-- Section Title -->
                <div class="rt-vc-title style1">
                    <div class="vc_custom_student_title" style="margin-bottom: 40px; text-align: center;">
                        <h2 style="font-size:30px; position: relative; display: inline-block; padding-bottom: 12px; font-weight: 700; color: #002147; font-family: inherit;">Our Recent Joined Students</h2>
                        <div style="width: 70px; height: 3px; background: #fdc800; margin: 0 auto;"></div>
                    </div>
                </div>

                <?php if ($student_count >= 4): ?>
                    <!-- Recent Students Marquee -->
                    <div class="student-marquee-container">
                        <div class="student-marquee-content">
                            
                            <!-- First Group (Original Cards) -->
                            <?php foreach ($recent_students as $student): ?>
                            <div class="student-marquee-item">
                                <div class="student-card" style="background: #ffffff; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.06); border: 1px solid #f0f0f0; padding: 25px 15px; text-align: center; transition: all 0.3s ease; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center;" onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 15px 30px rgba(0,0,0,0.1)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 25px rgba(0,0,0,0.06)';" onclick="void(0)">
                                    <!-- Large Student Avatar -->
                                    <div class="student-avatar-wrap" style="width: 140px; height: 140px; border-radius: 50%; overflow: hidden; margin: 0 auto 15px; border: 4px solid #f8f9fa; box-shadow: 0 5px 15px rgba(0,0,0,0.08);">
                                        <img src="<?php echo htmlspecialchars($student['img']); ?>" alt="<?php echo htmlspecialchars($student['student_name']); ?>" style="width: 100%; height: 100%; object-fit: cover;" />
                                    </div>
                                    <!-- Smaller Name & Text -->
                                    <h4 class="student-name" style="margin: 0 0 4px 0; font-size: 15px; font-weight: 700; color: #002147; font-family: inherit;"><?php echo htmlspecialchars($student['student_name']); ?></h4>
                                    <div class="student-badge" style="background: rgba(253, 200, 0, 0.15); color: #002147; padding: 2px 10px; border-radius: 20px; font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; display: inline-block; margin-bottom: 8px;">Newly Enrolled</div>
                                    <p class="student-course" style="color: #666; font-size: 12px; line-height: 1.4; margin: 0; font-weight: 500; height: 34px; display: flex; align-items: center; justify-content: center; font-family: inherit;"><?php echo htmlspecialchars($student['course_name']); ?></p>
                                </div>
                            </div>
                            <?php endforeach; ?>

                            <!-- Second Group (Duplicated Cards for seamless loop wrapping) -->
                            <?php foreach ($recent_students as $student): ?>
                            <div class="student-marquee-item">
                                <div class="student-card" style="background: #ffffff; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.06); border: 1px solid #f0f0f0; padding: 25px 15px; text-align: center; transition: all 0.3s ease; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center;" onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 15px 30px rgba(0,0,0,0.1)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 25px rgba(0,0,0,0.06)';" onclick="void(0)">
                                    <!-- Large Student Avatar -->
                                    <div class="student-avatar-wrap" style="width: 140px; height: 140px; border-radius: 50%; overflow: hidden; margin: 0 auto 15px; border: 4px solid #f8f9fa; box-shadow: 0 5px 15px rgba(0,0,0,0.08);">
                                        <img src="<?php echo htmlspecialchars($student['img']); ?>" alt="<?php echo htmlspecialchars($student['student_name']); ?>" style="width: 100%; height: 100%; object-fit: cover;" />
                                    </div>
                                    <!-- Smaller Name & Text -->
                                    <h4 class="student-name" style="margin: 0 0 4px 0; font-size: 15px; font-weight: 700; color: #002147; font-family: inherit;"><?php echo htmlspecialchars($student['student_name']); ?></h4>
                                    <div class="student-badge" style="background: rgba(253, 200, 0, 0.15); color: #002147; padding: 2px 10px; border-radius: 20px; font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; display: inline-block; margin-bottom: 8px;">Newly Enrolled</div>
                                    <p class="student-course" style="color: #666; font-size: 12px; line-height: 1.4; margin: 0; font-weight: 500; height: 34px; display: flex; align-items: center; justify-content: center; font-family: inherit;"><?php echo htmlspecialchars($student['course_name']); ?></p>
                                </div>
                            </div>
                            <?php endforeach; ?>

                        </div>
                    </div>
                <?php else: ?>
                    <!-- Centered Static Student Cards (Used when there are < 4 students to prevent empty layout scrolling issues) -->
                    <div class="d-flex flex-wrap justify-content-center gap-4">
                        <?php foreach ($recent_students as $student): ?>
                        <div style="width: 280px; padding: 10px;">
                            <div class="student-card" style="background: #ffffff; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.06); border: 1px solid #f0f0f0; padding: 25px 15px; text-align: center; transition: all 0.3s ease; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center;" onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 15px 30px rgba(0,0,0,0.1)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 25px rgba(0,0,0,0.06)';" onclick="void(0)">
                                <!-- Large Student Avatar -->
                                <div class="student-avatar-wrap" style="width: 140px; height: 140px; border-radius: 50%; overflow: hidden; margin: 0 auto 15px; border: 4px solid #f8f9fa; box-shadow: 0 5px 15px rgba(0,0,0,0.08);">
                                    <img src="<?php echo htmlspecialchars($student['img']); ?>" alt="<?php echo htmlspecialchars($student['student_name']); ?>" style="width: 100%; height: 100%; object-fit: cover;" />
                                </div>
                                <!-- Smaller Name & Text -->
                                <h4 class="student-name" style="margin: 0 0 4px 0; font-size: 15px; font-weight: 700; color: #002147; font-family: inherit;"><?php echo htmlspecialchars($student['student_name']); ?></h4>
                                <div class="student-badge" style="background: rgba(253, 200, 0, 0.15); color: #002147; padding: 2px 10px; border-radius: 20px; font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; display: inline-block; margin-bottom: 8px;">Newly Enrolled</div>
                                <p class="student-course" style="color: #666; font-size: 12px; line-height: 1.4; margin: 0; font-weight: 500; height: 34px; display: flex; align-items: center; justify-content: center; font-family: inherit;"><?php echo htmlspecialchars($student['course_name']); ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>
<?php endif; ?>

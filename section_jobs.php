<?php
// section_jobs.php - Clean, Light Ribbon Bar without Heavy Background & Prominently Highlighted 'We Are Hiring'
?>

<div class="career-ribbon-wrapper py-4" style="background: transparent; font-family: Roboto, sans-serif;">
    <div class="container">
        <div class="career-ribbon-bar">
            <!-- Left Info Area -->
            <div class="career-info-area">
                <div class="career-hiring-badge">
                    <span class="live-pulse-dot"></span>
                    <i class="fas fa-bullhorn"></i>
                    <span>WE ARE HIRING</span>
                </div>
                <div class="career-text-group">
                    <h3 class="career-main-title">
                        Careers at LKVM: Multiple Openings for Computer Faculty, Trainers & Coordinators
                    </h3>
                    <p class="career-sub-desc">
                        Work with Bihar's leading digital & vocational mission &bull; Competitive salary &amp; growth opportunities
                    </p>
                </div>
            </div>

            <!-- Right Action Button -->
            <div class="career-action-area">
                <a href="<?php echo $SITE_URL; ?>/career" class="career-apply-btn">
                    <span>Apply Now</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.career-ribbon-wrapper {
    background: transparent !important;
}
.career-ribbon-bar {
    background: #ffffff !important;
    border-radius: 16px;
    padding: 22px 30px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    box-shadow: 0 10px 30px rgba(0, 33, 71, 0.08);
    border: 1px solid #e2e8f0 !important;
    border-left: 6px solid #fdc800 !important;
    position: relative;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}
.career-ribbon-bar:hover {
    transform: translateY(-2px);
    box-shadow: 0 16px 36px rgba(0, 33, 71, 0.12);
}
.career-info-area {
    display: flex;
    align-items: center;
    gap: 20px;
    flex: 1;
}
/* Prominently Highlighted 'WE ARE HIRING' Badge */
.career-hiring-badge {
    background: linear-gradient(135deg, #fdc800 0%, #f59e0b 100%) !important;
    color: #002147 !important;
    font-family: Roboto, sans-serif !important;
    font-size: 13px !important;
    font-weight: 900 !important;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    padding: 10px 20px;
    border-radius: 50px;
    display: inline-flex;
    align-items: center;
    gap: 9px;
    white-space: nowrap;
    box-shadow: 0 4px 18px rgba(253, 200, 0, 0.45);
    border: 1px solid rgba(255, 255, 255, 0.6);
    animation: badgeShine 3s infinite;
}
@keyframes badgeShine {
    0%, 100% { box-shadow: 0 4px 18px rgba(253, 200, 0, 0.4); }
    50% { box-shadow: 0 4px 25px rgba(245, 158, 11, 0.7); }
}
.career-hiring-badge i {
    font-size: 14px;
    color: #002147;
}
/* Live Pulsing Dot */
.live-pulse-dot {
    width: 9px;
    height: 9px;
    background: #ef4444;
    border-radius: 50%;
    display: inline-block;
    box-shadow: 0 0 0 rgba(239, 68, 68, 0.7);
    animation: pulseLive 1.5s infinite;
}
@keyframes pulseLive {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
    70% { transform: scale(1.05); box-shadow: 0 0 0 8px rgba(239, 68, 68, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
}
.career-text-group {
    flex: 1;
}
.career-main-title {
    font-family: Roboto, sans-serif !important;
    font-size: 18.5px !important;
    font-weight: 800 !important;
    color: #002147 !important;
    margin: 0 0 5px 0 !important;
    line-height: 1.35 !important;
    letter-spacing: -0.2px;
}
.career-sub-desc {
    font-family: Roboto, sans-serif !important;
    font-size: 14px !important;
    color: #64748b !important;
    margin: 0 !important;
    line-height: 1.4 !important;
    font-weight: 400 !important;
}
.career-action-area {
    flex-shrink: 0;
}
.career-apply-btn {
    background: #002147 !important;
    color: #ffffff !important;
    font-family: Roboto, sans-serif !important;
    font-weight: 800 !important;
    font-size: 14.5px !important;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    padding: 12px 28px;
    border-radius: 50px;
    text-decoration: none !important;
    display: inline-flex;
    align-items: center;
    gap: 9px;
    transition: all 0.25s ease;
    box-shadow: 0 6px 18px rgba(0, 33, 71, 0.2);
    white-space: nowrap;
    border: 2px solid #002147;
}
.career-apply-btn:hover {
    background: #fdc800 !important;
    border-color: #fdc800 !important;
    color: #002147 !important;
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(253, 200, 0, 0.4);
}
.career-apply-btn i {
    transition: transform 0.2s ease;
}
.career-apply-btn:hover i {
    transform: translateX(4px);
}

@media (max-width: 991px) {
    .career-ribbon-bar {
        flex-direction: column;
        align-items: flex-start;
        padding: 20px 18px;
    }
    .career-info-area {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
        width: 100%;
    }
    .career-main-title {
        font-size: 16.5px !important;
    }
    .career-action-area {
        width: 100%;
        margin-top: 8px;
    }
    .career-apply-btn {
        width: 100%;
        justify-content: center;
    }
}
</style>

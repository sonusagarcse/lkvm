<?php
// section5.php (News & Notice Board Redesign)
require_once 'connection.php';
global $con;

// Fetch News (Cached)
$news_data = cache_remember('news_active_news', function () use ($con) {
    $res = mysqli_query($con, "SELECT * FROM news WHERE status = 1 AND (type = 'news' OR type IS NULL OR type = '') ORDER BY id DESC LIMIT 10");
    $data = [];
    while ($row = mysqli_fetch_assoc($res)) $data[] = $row;
    return $data;
}, 900);

// Fetch Notices (Cached)
$notice_data = cache_remember('news_active_notice', function () use ($con) {
    $res = mysqli_query($con, "SELECT * FROM news WHERE status = 1 AND type = 'notice' ORDER BY id DESC LIMIT 10");
    $data = [];
    while ($row = mysqli_fetch_assoc($res)) $data[] = $row;
    return $data;
}, 900);
?>

<div class="news-notice-section py-5">
    <div class="container">
        <div class="row g-4">
            <!-- News Column -->
            <div class="col-lg-6">
                <div class="glass-board shadow-lg h-100 overflow-hidden">
                    <div class="board-header news-header d-flex align-items-center justify-content-between p-4">
                        <div class="d-flex align-items-center">
                            <div class="icon-box me-3"><i class="fas fa-bullhorn text-white"></i></div>
                            <h3 class="m-0 text-white fw-bold h4">News & Events</h3>
                        </div>
                        <span class="badge bg-white text-primary rounded-pill px-3">Latest</span>
                    </div>
                    <div class="board-body p-0">
                        <div class="ticker-wrapper" style="height: 450px; overflow: hidden; position: relative;">
                            <div class="news-ticker">
                                <?php if (!empty($news_data)): 
                                    // Combine data with itself for seamless marquee effect
                                    $news_loop = array_merge($news_data, $news_data);
                                    foreach ($news_loop as $n): ?>
                                    <div class="ticker-item p-4 border-bottom transition">
                                        <div class="d-flex align-items-start">
                                            <div class="date-tag me-3 text-center">
                                                <span class="day"><?php echo date('d', strtotime($n['date'] ?? 'now')); ?></span>
                                                <span class="month"><?php echo date('M', strtotime($n['date'] ?? 'now')); ?></span>
                                            </div>
                                            <div>
                                                <h5 class="fw-bold text-dark mb-1 h6"><?php echo htmlspecialchars($n['name']); ?></h5>
                                                <p class="text-muted small mb-0"><?php echo (strlen($n['des']) > 150) ? substr(strip_tags($n['des']), 0, 150) . '...' : $n['des']; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; else: ?>
                                    <div class="p-5 text-center text-muted">No news available at the moment.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="board-footer p-3 text-center bg-light-soft border-top">
                        <a href="news.php" class="text-primary fw-bold text-decoration-none small">View All News <i class="fas fa-external-link-alt ms-1"></i></a>
                    </div>
                </div>
            </div>

            <!-- Notice Column -->
            <div class="col-lg-6">
                <div class="glass-board shadow-lg h-100 overflow-hidden">
                    <div class="board-header notice-header d-flex align-items-center justify-content-between p-4">
                        <div class="d-flex align-items-center">
                            <div class="icon-box me-3"><i class="fas fa-bell text-white"></i></div>
                            <h3 class="m-0 text-white fw-bold h4">Notice Board</h3>
                        </div>
                        <i class="fas fa-thumbtack text-white opacity-50 h5"></i>
                    </div>
                    <div class="board-body p-0">
                        <div class="ticker-wrapper" style="height: 450px; overflow: hidden; position: relative;">
                            <div class="notice-ticker">
                                <?php if (!empty($notice_data)): 
                                    // Combine data with itself for seamless marquee effect
                                    $notice_loop = array_merge($notice_data, $notice_data);
                                    foreach ($notice_loop as $n): ?>
                                    <div class="ticker-item p-4 border-bottom transition">
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="badge bg-danger-soft text-danger me-2"><i class="fas fa-info-circle"></i> Important</span>
                                            <small class="text-muted"><i class="far fa-calendar-alt me-1"></i> <?php echo date('d M Y', strtotime($n['date'] ?? 'now')); ?></small>
                                        </div>
                                        <h5 class="fw-bold text-primary mb-1 h6"><?php echo htmlspecialchars($n['name']); ?></h5>
                                        <p class="text-muted small mb-0"><?php echo (strlen($n['des']) > 150) ? substr(strip_tags($n['des']), 0, 150) . '...' : $n['des']; ?></p>
                                    </div>
                                <?php endforeach; else: ?>
                                    <div class="p-5 text-center text-muted">No notices available at the moment.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="board-footer p-3 text-center bg-light-soft border-top">
                        <a href="notices.php" class="text-danger fw-bold text-decoration-none small">Access Archive <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.glass-board {
    background: #fff;
    border-radius: 30px;
    border: 1px solid rgba(0,0,0,0.05);
    transition: transform 0.3s ease;
}
.glass-board:hover {
    transform: translateY(-5px);
}
.board-header { padding: 1.5rem 2rem; }
.news-header { background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); }
.notice-header { background: linear-gradient(135deg, #cc2b5e 0%, #753a88 100%); }

.icon-box {
    width: 45px;
    height: 45px;
    background: rgba(255,255,255,0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    font-size: 1.2rem;
}

.date-tag {
    background: #f8f9fa;
    padding: 8px;
    border-radius: 12px;
    min-width: 60px;
    border: 1px solid #eee;
}
.date-tag .day { display: block; font-size: 1.2rem; font-weight: 800; color: #1e3c72; line-height: 1; }
.date-tag .month { font-size: 0.75rem; text-transform: uppercase; font-weight: 600; color: #666; }

.bg-danger-soft { background: rgba(220, 53, 69, 0.1); }
.bg-light-soft { background: rgba(0,0,0,0.02); }

.ticker-item {
    transition: all 0.3s ease;
}
.ticker-item:hover {
    background: rgba(0,0,0,0.01);
}

/* Animations for tickers */
@keyframes tickerUp {
    0% { transform: translateY(0); }
    100% { transform: translateY(-50%); }
}

.news-ticker, .notice-ticker {
    display: flex;
    flex-direction: column;
    animation: tickerUp <?php echo (int)($settings['ticker_speed'] ?? 40); ?>s linear infinite;
}

.news-ticker:hover, .notice-ticker:hover {
    animation-play-state: paused;
}
</style>
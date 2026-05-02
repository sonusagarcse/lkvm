<?php
// Get statistics
$stats = [
    'branches' => $con->query("SELECT COUNT(*) as count FROM branch WHERE status = 1")->fetch_assoc()['count'],
    'courses' => $con->query("SELECT COUNT(*) as count FROM courses")->fetch_assoc()['count'],
    'admit_cards' => $con->query("SELECT COUNT(*) as count FROM admit_card WHERE status = 1")->fetch_assoc()['count'],
    'news' => $con->query("SELECT COUNT(*) as count FROM news")->fetch_assoc()['count']
];

// Get recent news
$news_result = $con->query("SELECT * FROM news ORDER BY id DESC LIMIT 5");
$recent_news = [];
while($row = $news_result->fetch_assoc()) {
    $recent_news[] = $row;
}

// Get recent admit cards
$admit_cards_result = $con->query("SELECT ac.*, b.bname FROM admit_card ac 
                                  LEFT JOIN branch b ON ac.bid = b.id 
                                  WHERE ac.status = 1 
                                  ORDER BY ac.id DESC LIMIT 5");
$recent_admit_cards = [];
while($row = $admit_cards_result->fetch_assoc()) {
    $recent_admit_cards[] = $row;
}
?>

<div class="row">
    <!-- Statistics Cards -->
    <div class="col-md-3 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title">Study Centers</h5>
                <h2><?php echo $stats['branches']; ?></h2>
                <a href="?page=branches" class="text-white">Manage Centers →</a>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Total Courses</h5>
                <h2><?php echo $stats['courses']; ?></h2>
                <a href="?page=courses" class="text-white">Manage Courses →</a>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5 class="card-title">Admit Cards</h5>
                <h2><?php echo $stats['admit_cards']; ?></h2>
                <a href="?page=admit_cards" class="text-white">Manage Admit Cards →</a>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <h5 class="card-title">News & Events</h5>
                <h2><?php echo $stats['news']; ?></h2>
                <a href="?page=news" class="text-dark">Manage News →</a>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <a href="?page=branches&action=add" class="btn btn-outline-primary w-100 mb-2">
                            <i class="fas fa-plus"></i> Add Study Center
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="?page=courses&action=add" class="btn btn-outline-success w-100 mb-2">
                            <i class="fas fa-plus"></i> Add New Course
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="?page=admit_cards&action=add" class="btn btn-outline-info w-100 mb-2">
                            <i class="fas fa-plus"></i> Generate Admit Card
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="?page=news&action=add" class="btn btn-outline-warning w-100 mb-2">
                            <i class="fas fa-plus"></i> Add News/Event
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent News -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Recent News & Events</h5>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <?php foreach($recent_news as $news): ?>
                    <a href="?page=news&action=edit&id=<?php echo $news['id']; ?>" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1"><?php echo htmlspecialchars($news['title'] ?? $news['name']); ?></h6>
                            <small><?php echo date('d M Y', strtotime($news['date'])); ?></small>
                        </div>
                        <small class="text-muted"><?php echo substr(strip_tags($news['description'] ?? ''), 0, 100); ?>...</small>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Admit Cards -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Recent Admit Cards</h5>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <?php foreach($recent_admit_cards as $card): ?>
                    <div class="list-group-item">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">Roll No: <?php echo htmlspecialchars($card['rollno']); ?></h6>
                            <small><?php echo date('d M Y', strtotime($card['exam_date'])); ?></small>
                        </div>
                        <p class="mb-1">
                            <strong>Center:</strong> <?php echo htmlspecialchars($card['bname']); ?><br>
                            <strong>Exam:</strong> <?php echo htmlspecialchars($card['exam']); ?> (<?php echo htmlspecialchars($card['exam_time']); ?>)
                        </p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div> 
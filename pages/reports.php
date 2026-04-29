<?php
session_start();
require_once '../config/database.php';
require_once '../classes/Database.php';
require_once '../classes/Task.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$database = new Database();
$db = $database->connect();
$task = new Task($db);

$tasks = $task->getTasks($_SESSION['user_id']);
$total = count($tasks);
$completed = count(array_filter($tasks, fn($t) => $t['status'] === 'completed'));
$pending = $total - $completed;
$completion_rate = $total > 0 ? round(($completed / $total) * 100) : 0;

// Category stats
$categories = [];
foreach($tasks as $t) {
    $cat = $t['category'] ?? 'Other';
    $categories[$cat] = ($categories[$cat] ?? 0) + 1;
}

$pageTitle = "Reports & Analytics";
require_once '../includes/header.php';
require_once '../includes/navbar.php';
?>

<div class="page-hero mb-5">
    <div class="glass-card p-4 d-flex align-items-center">
        <i class="bi bi-bar-chart-line fs-2 me-3 text-primary"></i>
        <div>
            <h2 class="mb-1 fw-bold">Reports & Analytics</h2>
            <p class="text-muted mb-0">Task completion insights and statistics</p>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="glass-card p-4 mb-4">
            <h4 class="fw-bold mb-4">Task Overview</h4>
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="stat-card text-center p-4">
                        <div class="stat-number fs-2 fw-bold text-primary"><?php echo $total; ?></div>
                        <div class="stat-label text-muted">Total Tasks</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card text-center p-4">
                        <div class="stat-number fs-2 fw-bold text-success"><?php echo $completed; ?></div>
                        <div class="stat-label text-muted">Completed</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card text-center p-4">
                        <div class="stat-number fs-2 fw-bold text-warning"><?php echo $pending; ?></div>
                        <div class="stat-label text-muted">Pending</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card text-center p-4">
                        <div class="stat-number fs-2 fw-bold text-info"><?php echo $completion_rate; ?>%</div>
                        <div class="stat-label text-muted">Completion Rate</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="glass-card p-3">
            <h4 class="fw-bold mb-3">Tasks by Category</h4>
            <canvas id="categoryChart" height="100"></canvas>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="glass-card p-4 h-100">
            <h5 class="fw-bold mb-4">Recent Activity</h5>
            <ul class="list-unstyled">
                <?php foreach(array_slice($tasks, 0, 5) as $t): ?>
                <li class="p-3 border-bottom task-activity-item">
                    <div class="d-flex align-items-center">
                        <div class="status-badge me-3 <?php echo $t['status'] == 'completed' ? 'bg-success' : 'bg-warning'; ?>"></div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold small"><?php echo htmlspecialchars($t['title']); ?></div>
                            <div class="text-muted small"><?php echo date('M d, H:i', strtotime($t['created_at'])); ?></div>
                        </div>
                    </div>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('categoryChart').getContext('2d');
const categoryChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: <?php echo json_encode(array_keys($categories)); ?>,
        datasets: [{
            data: <?php echo json_encode(array_values($categories)); ?>,
            backgroundColor: ['#FFBB94', '#FB9590', '#DC586D', '#A33757', '#852E4E']
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
            }
        }
    }
});
</script>

<?php require_once '../includes/footer.php'; ?>


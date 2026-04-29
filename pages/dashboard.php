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
$stats = $task->getTaskStats($_SESSION['user_id']);
$overdueTasks = $task->getOverdueTasks($_SESSION['user_id']);

$pageTitle = "Dashboard";
require_once '../includes/header.php';
require_once '../includes/navbar.php';
?>

<?php if($stats['overdue'] > 0): ?>
<div class="alert alert-warning glass-card mb-4" role="alert">
    <i class="bi bi-exclamation-triangle me-2"></i>
    <strong><?php echo $stats['overdue']; ?> overdue task<?php echo $stats['overdue'] > 1 ? 's' : ''; ?></strong>
    Check your tasks immediately!
    <a href="tasks.php?status=pending" class="btn btn-warning btn-sm ms-3">View Pending</a>
</div>
<?php endif; ?>

<div class="row mb-5">
    <div class="col-12">
        <div class="glass-card p-5 text-center">
            <i class="bi bi-speedometer2 fs-1 text-primary mb-10"></i>
            <h1 class="h2 fw-bold mb-2">Dashboard Overview</h1>
            <p class="text-muted lead mb-0">Welcome back, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</p>
        </div>
    </div>
</div>

<div class="row g-4 mb-5">
    <div class="col-lg-3 col-md-6">
        <div class="stat-card-custom glass-card h-100 p-4">
            <div class="text-center">
                <i class="bi bi-list-task fs-2 text-primary mb-3"></i>
                <div class="stat-number"><?php echo $stats['total']; ?></div>
                <div class="stat-label">Total Tasks</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card-custom glass-card h-100 p-4">
            <div class="text-center">
                <i class="bi bi-check2-circle fs-2 text-success mb-3"></i>
                <div class="stat-number text-success"><?php echo $stats['completed']; ?></div>
                <div class="stat-label">Completed</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card-custom glass-card h-100 p-4">
            <div class="text-center">
                <i class="bi bi-hourglass-split fs-2 text-warning mb-3"></i>
                <div class="stat-number text-warning"><?php echo $stats['pending']; ?></div>
                <div class="stat-label">Pending</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card-custom glass-card h-100 p-4">
            <div class="text-center">
                <i class="bi bi-clock-history fs-2 text-danger mb-3"></i>
                <div class="stat-number text-danger"><?php echo $stats['overdue']; ?></div>
                <div class="stat-label">Overdue</div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="glass-card p-5">
            <h2 class="h4 fw-bold mb-4">Quick Actions</h2>
            <div class="row g-3">
                <div class="col-md-6">
                    <a href="create_task.php" class="btn btn-primary-custom btn-lg w-100 h-100 d-flex align-items-center justify-content-center">
                        <i class="bi bi-plus-circle fs-4 me-2"></i>
                        New Task
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="tasks.php" class="btn btn-outline-primary btn-lg w-100 h-100 d-flex align-items-center justify-content-center">
                        <i class="bi bi-list-ul fs-4 me-2"></i>
                        View All Tasks
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <?php if(!empty($overdueTasks)): ?>
        <div class="glass-card p-4 h-100">
            <h5 class="fw-bold mb-3">Overdue Tasks <span class="badge bg-danger"><?php echo count($overdueTasks); ?></span></h5>
            <ul class="list-unstyled">
                <?php foreach(array_slice($overdueTasks, 0, 3) as $task): ?>
                <li class="border-bottom pb-2 mb-2">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fw-semibold"><?php echo htmlspecialchars($task['title']); ?></div>
                            <small class="text-danger">Overdue</small>
                        </div>
                        <a href="manage_task.php?action=complete&id=<?php echo $task['id']; ?>" class="btn btn-sm btn-success">✓</a>
                    </div>
                </li>
                <?php endforeach; ?>
                <?php if(count($overdueTasks) > 3): ?>
                <li class="pt-2"><a href="tasks.php?status=pending" class="text-primary">View all overdue...</a></li>
                <?php endif; ?>
            </ul>
        </div>
        <?php else: ?>
        <div class="glass-card p-4 h-100 text-center">
            <i class="bi bi-check-circle fs-1 text-success mb-3"></i>
            <h6 class="text-success">No Overdue Tasks</h6>
            <p class="text-muted small mb-0">You're all caught up! 🎉</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>


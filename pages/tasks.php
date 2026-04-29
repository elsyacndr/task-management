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

$statusFilter = isset($_GET['status']) ? $_GET['status'] : '';
$tasks = $task->getTasks($_SESSION['user_id'], $statusFilter);

$message = isset($_GET['message']) ? $_GET['message'] : '';
$messageType = isset($_GET['type']) ? $_GET['type'] : '';

$pageTitle = "My Tasks";
require_once '../includes/header.php';
require_once '../includes/navbar.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <h1 class="h2">My Tasks</h1>
    <a href="create_task.php" class="btn btn-primary-custom">+ New Task</a>
</div>

<?php if($message): ?>
    <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
        <?php echo htmlspecialchars($message); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="premium-table-wrapper">
    <div class="table-header glass-card p-4 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h3 class="mb-0 fw-bold" style="color: var(--primary);">Task Management</h3>
            </div>
            <div class="col-md-6 text-end">
                <form method="GET" action="" class="d-inline">
                    <select class="form-select d-inline-block w-auto me-2" id="status" name="status" onchange="this.form.submit()" style="max-width: 200px;">
                        <option value="" <?php echo $statusFilter == '' ? 'selected' : ''; ?>>All Tasks</option>
                        <option value="pending" <?php echo $statusFilter == 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="completed" <?php echo $statusFilter == 'completed' ? 'selected' : ''; ?>>Completed</option>
                    </select>
                </form>
                <?php if($statusFilter): ?>
                <a href="tasks.php" class="btn btn-outline-light btn-sm">Clear</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <?php if($message): ?>
    <div class="alert alert-<?php echo $messageType; ?> glass-card mb-4 animate__animated animate__fadeInDown">
        <?php echo htmlspecialchars($message); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <div class="table-container glass-card mb-4">
        <div class="table-responsive">
            <table class="table table-hover premium-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($tasks as $index => $t): ?>
                        <?php $is_overdue = $task->isTaskOverdue($t['id'], $_SESSION['user_id']); ?>
                        <tr class="<?php echo $t['status'] == 'completed' ? 'completed-task' : ''; ?><?php echo $is_overdue ? ' overdue-task' : ''; ?>">
                            <td><?php echo $index + 1; ?></td>
                            <td><?php echo htmlspecialchars($t['title']); ?></td>
                            <td><?php echo htmlspecialchars(substr($t['description'], 0, 50)) . (strlen($t['description']) > 50 ? '...' : ''); ?></td>
                            <td>
                                <span class="status-badge <?php echo $t['status'] == 'completed' ? 'status-completed' : 'status-pending'; ?><?php echo $is_overdue ? ' status-overdue' : ''; ?>">
                                    <?php echo ucfirst($t['status']); ?><?php echo $is_overdue ? ' ⏰' : ''; ?>
                                </span>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($t['created_at'])); ?></td>

                            <td>
                                <div class="btn-group btn-group-sm">
                                    <?php if($t['status'] == 'pending'): ?>
                                        <a href="manage_task.php?action=complete&id=<?php echo $t['id']; ?>" class="btn btn-success-custom" title="Mark Complete">✓</a>
                                    <?php else: ?>
                                        <a href="manage_task.php?action=pending&id=<?php echo $t['id']; ?>" class="btn btn-warning" title="Mark Pending">↺</a>
                                    <?php endif; ?>
                                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editTaskModal"
                                        data-task-id="<?php echo $t['id']; ?>"
                                        data-task-title="<?php echo htmlspecialchars($t['title']); ?>"
                                        data-task-desc="<?php echo htmlspecialchars($t['description']); ?>"
                                        data-task-status="<?php echo $t['status']; ?>">
                                        ✎
                                    </button>
                                    <a href="manage_task.php?action=delete&id=<?php echo $t['id']; ?>" class="btn btn-outline-danger btn-delete" title="Delete">🗑</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($tasks)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="bi bi-inbox fs-1 text-muted mb-3"></i>
                                <p class="text-muted">No tasks yet. <a href="create_task.php">Create your first task</a></p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
        </table>
    </div>
</div>

<!-- Search/Filter Form -->
<div class="table-header glass-card p-4 mb-4">
    <div class="row align-items-center">
        <div class="col-md-4 mb-2">
            <h5 class="mb-0 fw-bold">Tasks</h5>
        </div>
        <div class="col-md-8">
            <form method="GET" class="row g-2">
                <div class="col-md-4 mb-2">
                    <input type="text" class="form-control" name="search" placeholder="Search tasks..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="status">
                        <option value="">All Status</option>
                        <option value="pending" <?php echo ($_GET['status'] ?? '') == 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="completed" <?php echo ($_GET['status'] ?? '') == 'completed' ? 'selected' : ''; ?>>Completed</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="category">
                        <option value="">All Categories</option>
                        <option value="General" <?php echo ($_GET['category'] ?? '') == 'General' ? 'selected' : ''; ?>>General</option>
                        <option value="Work" <?php echo ($_GET['category'] ?? '') == 'Work' ? 'selected' : ''; ?>>Work</option>
                        <option value="Personal" <?php echo ($_GET['category'] ?? '') == 'Personal' ? 'selected' : ''; ?> >Personal</option>
                        <option value="Urgent" <?php echo ($_GET['category'] ?? '') == 'Urgent' ? 'selected' : ''; ?>>Urgent</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary-custom w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTaskModalLabel">Edit Task</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="manage_task.php">
                <div class="modal-body">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="task_id" id="edit_task_id">
                    <div class="mb-3">
                        <label for="edit_title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="edit_title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_status" class="form-label">Status</label>
                        <select class="form-select" id="edit_status" name="status">
                            <option value="pending">Pending</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary-custom">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>


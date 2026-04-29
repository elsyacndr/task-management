<?php
session_start();
require_once '../config/database.php';
require_once '../classes/Database.php';
require_once '../classes/Task.php';
require_once '../classes/Project.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$message = '';
$messageType = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $database = new Database();
    $db = $database->connect();

    $task = new Task($db);
    $projectObj = new Project($db);
    $userProjects = $projectObj->getProjects($_SESSION['user_id']);

    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'] ?? 'General';
    $project_id = !empty($_POST['project_id']) ? $_POST['project_id'] : null;
    $status = $_POST['status'];
    $due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : null;

    if($task->createTask($_SESSION['user_id'], $project_id, $title, $description, $category, $status, $due_date)) {
        $message = "Task created successfully!";
        $messageType = "success";
    } else {
        $message = "Failed to create task.";
        $messageType = "danger";
    }
}

$pageTitle = "Create Task";
require_once '../includes/header.php';
require_once '../includes/navbar.php';
?>

<div class="glass-card p-5 mb-5">
    <div class="row align-items-center mb-4">
        <div class="col-auto">
            <i class="bi bi-plus-circle fs-1 text-primary"></i>
        </div>
        <div class="col">
            <h1 class="h3 fw-bold mb-1">Create New Task</h1>
            <p class="text-muted mb-0">Get started with your next task</p>
        </div>
        <div class="col-auto">
            <a href="tasks.php" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to Tasks
            </a>
        </div>
    </div>
</div>

<?php if($message): ?>
<div class="alert alert-<?php echo $messageType; ?> glass-card mb-5" role="alert">
    <?php echo htmlspecialchars($message); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="glass-card p-5">
            <form method="POST">
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="mb-4">
                            <label for="title" class="form-label fw-semibold mb-2">Task Title *</label>
                            <input type="text" class="form-control form-control-lg" id="title" name="title" placeholder="Enter task title" required>
                        </div>
                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold mb-2">Description *</label>
                            <textarea class="form-control" id="description" name="description" rows="4" placeholder="Describe the task details..." required></textarea>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="row g-3 h-100">
                            <div class="col-12">
                                <label for="status" class="form-label fw-semibold mb-2">Status</label>
                                <select class="form-select form-control-lg" id="status" name="status">
                                    <option value="pending" selected>⏳ Pending</option>
                                    <option value="completed">✅ Completed</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="category" class="form-label fw-semibold mb-2">Category</label>
                                <select class="form-select form-control-lg" id="category" name="category">
                                    <option value="General">📋 General</option>
                                    <option value="Work">💼 Work</option>
                                    <option value="Personal">🏠 Personal</option>
                                    <option value="Urgent">🚨 Urgent</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="project_id" class="form-label fw-semibold mb-2">Project</label>
                                <select class="form-select form-control-lg" id="project_id" name="project_id">
                                    <option value="">No Project</option>
                                    <?php foreach($userProjects as $proj): ?>
                                    <option value="<?php echo $proj['id']; ?>"><?php echo htmlspecialchars($proj['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="due_date" class="form-label fw-semibold mb-2">Due Date</label>
                                <input type="date" class="form-control form-control-lg" id="due_date" name="due_date">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-3 mt-5">
                    <a href="tasks.php" class="btn btn-outline-secondary btn-lg px-5">
                        <i class="bi bi-arrow-left me-2"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary-custom btn-lg px-5">
                        <i class="bi bi-plus-circle me-2"></i>Create Task
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>


<?php
session_start();
require_once '../config/database.php';
require_once '../classes/Database.php';
require_once '../classes/Project.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$database = new Database();
$db = $database->connect();
$project = new Project($db);

$message = '';
$messageType = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['create_project'])) {
        if($project->createProject($_SESSION['user_id'], $_POST['name'], $_POST['description'])) {
            $message = "Project created successfully!";
            $messageType = "success";
        } else {
            $message = "Failed to create project.";
            $messageType = "danger";
        }
    } elseif(isset($_POST['delete_project'])) {
        if($project->deleteProject($_POST['project_id'], $_SESSION['user_id'])) {
            $message = "Project deleted successfully!";
            $messageType = "success";
        } else {
            $message = "Failed to delete project.";
            $messageType = "danger";
        }
    } elseif(isset($_POST['update_project'])) {
        if($project->updateProject($_POST['project_id'], $_SESSION['user_id'], $_POST['name'], $_POST['description'])) {
            $message = "Project updated successfully!";
            $messageType = "success";
        } else {
            $message = "Failed to update project.";
            $messageType = "danger";
        }
    }
}

$projects = $project->getProjects($_SESSION['user_id']);

$pageTitle = "Projects";
require_once '../includes/header.php';
require_once '../includes/navbar.php';
?>

<div class="page-hero mb-5">
    <div class="glass-card p-4 d-flex align-items-center">
        <i class="bi bi-folder-fill fs-2 me-3 text-primary"></i>
        <div>
            <h2 class="mb-1 fw-bold">My Projects</h2>
            <p class="text-muted mb-0">Organize tasks by projects</p>
        </div>
    </div>
</div>

<?php if($message): ?>
<div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show glass-card mb-4" role="alert">
    <?php echo $message; ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="row g-4 mb-5">
    <div class="col-lg-4">
        <div class="glass-card p-5 text-center h-100 add-project-card" data-bs-toggle="modal" data-bs-target="#createProjectModal">
            <i class="bi bi-plus-circle fs-1 text-primary mb-3"></i>
            <h4 class="fw-bold mb-2">New Project</h4>
            <p class="text-muted">Create a new project to organize your tasks</p>
        </div>
    </div>
    <?php foreach($projects as $p): ?>
    <div class="col-lg-4 col-md-6">
        <div class="glass-card p-4 h-100 project-card" style="cursor: pointer;" onclick="window.location='tasks.php?project=<?php echo $p['id']; ?>'">
            <div class="project-header d-flex justify-content-between align-items-start mb-3">
                <h5 class="fw-bold mb-1 flex-grow-1"><?php echo htmlspecialchars($p['name']); ?></h5>
                <div class="dropdown dropstart">
                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-three-dots-vertical"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editProjectModal" data-id="<?php echo $p['id']; ?>" data-name="<?php echo htmlspecialchars($p['name']); ?>" data-desc="<?php echo htmlspecialchars($p['description']); ?>">Edit</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="#" onclick="confirmDelete(<?php echo $p['id']; ?>)">Delete</a></li>
                    </ul>
                </div>
            </div>
            <p class="text-muted small mb-3"><?php echo htmlspecialchars($p['description']); ?></p>
            <div class="project-footer">
                <small class="text-muted"><?php echo date('M d', strtotime($p['created_at'])); ?></small>
                <span class="badge bg-light text-dark ms-auto">View Tasks</span>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Create Project Modal -->
<div class="modal fade" id="createProjectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Create New Project</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="create_project" value="1">
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Project Name</label>
                        <input type="text" class="form-control" name="name" required placeholder="e.g. Website Project">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea class="form-control" name="description" rows="3" placeholder="Brief project description..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary-custom">Create Project</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Project Modal -->
<div class="modal fade" id="editProjectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Edit Project</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="">
                <div class="modal-body">
                    <input type="hidden" name="update_project" value="1">
                    <input type="hidden" name="project_id" id="edit_project_id">
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Project Name</label>
                        <input type="text" class="form-control" id="edit_project_name" name="name" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea class="form-control" id="edit_project_description" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary-custom">Update Project</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function confirmDelete(id) {
    if(confirm('Are you sure you want to delete this project? All tasks will be lost.')) {
        window.location = '?delete_project=1&project_id=' + id;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const editModal = document.getElementById('editProjectModal');
    editModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const name = button.getAttribute('data-name');
        const desc = button.getAttribute('data-desc');
        
        document.getElementById('edit_project_id').value = id;
        document.getElementById('edit_project_name').value = name;
        document.getElementById('edit_project_description').value = desc;
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>


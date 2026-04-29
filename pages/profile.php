<?php
session_start();
require_once '../config/database.php';
require_once '../classes/Database.php';
require_once '../classes/User.php';
require_once '../classes/Task.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$database = new Database();
$db = $database->connect();
$user = new User($db);
$task = new Task($db);

$message = '';
$messageType = '';

// Handle profile update first
if (isset($_POST['update_profile'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $photo_path = null;

    $upload_dir = '../assets/uploads/profiles/';
    $allowed_types = ['jpg', 'jpeg', 'png'];
    $max_size = 2 * 1024 * 1024; // 2MB

    if (!empty($_FILES['photo']['name'])) {
        $file_tmp = $_FILES['photo']['tmp_name'];
        $file_name = $_FILES['photo']['name'];
        $file_size = $_FILES['photo']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (in_array($file_ext, $allowed_types) && $file_size <= $max_size) {
            $new_filename = $_SESSION['user_id'] . '_' . time() . '.' . $file_ext;
            $photo_path = $upload_dir . $new_filename;
            if (move_uploaded_file($file_tmp, $photo_path)) {
                // Photo uploaded successfully
            } else {
                $message = 'Failed to upload photo.';
                $messageType = 'danger';
            }
        } else {
            $message = 'Invalid file type or size. Use JPG/PNG < 2MB.';
            $messageType = 'warning';
        }
    }

    if (empty($message)) {
        if ($user->updateProfile($_SESSION['user_id'], $name, $email, $photo_path)) {
            $message = 'Profile updated successfully!';
            $messageType = 'success';
        } else {
            $message = 'Failed to update profile. Email may be taken.';
            $messageType = 'danger';
        }
    }
}

// Fetch data after update
$userData = $user->getUserById($_SESSION['user_id']);
$userStats = $task->getTaskStats($_SESSION['user_id']);

$pageTitle = "Profile";
require_once '../includes/header.php';
require_once '../includes/navbar.php';
?>

<?php if (!empty($message)): ?>
<div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
    <?php echo htmlspecialchars($message); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="row g-4 mb-5">
    <div class="col-lg-4">
        <div class="glass-card text-center p-5 h-100">
            <div class="mb-4">
                <?php if ($userData['photo']): ?>
                    <img src="../assets/uploads/profiles/<?php echo htmlspecialchars($userData['photo']); ?>" alt="Profile Photo" class="avatar-custom mx-auto mb-3 profile-avatar" style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%; border: 3px solid rgba(255,255,255,0.5); box-shadow: var(--shadow-md);">
                <?php else: ?>
                    <div class="avatar-custom mx-auto mb-3" style="width: 100px; height: 100px; font-size: 2.5rem; background: linear-gradient(135deg, var(--primary), var(--primary-dark));">
                        <i class="bi bi-person-circle"></i>
                    </div>
                <?php endif; ?>
                <h2 class="h4 fw-bold mb-1"><?php echo htmlspecialchars($userData['name']); ?></h2>
                <p class="text-muted mb-0"><?php echo htmlspecialchars($userData['email']); ?></p>
            </div>
            <div class="stat-grid">
                <div class="stat-card-custom text-center">
                    <div class="stat-number"><?php echo $userStats['total']; ?></div>
                    <div class="stat-label">Total Tasks</div>
                </div>
                <div class="stat-card-custom text-center">
                    <div class="stat-number text-success"><?php echo $userStats['completed']; ?></div>
                    <div class="stat-label">Completed</div>
                </div>
                <div class="stat-card-custom text-center">
                    <div class="stat-number text-warning"><?php echo $userStats['pending']; ?></div>
                    <div class="stat-label">Pending</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="glass-card p-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h4 fw-bold mb-0">Account Information</h2>
                <button class="btn btn-primary-custom" data-bs-toggle="collapse" data-bs-target="#editProfileForm">
                    <i class="bi bi-pencil me-2"></i>Edit Profile
                </button>
            </div>

            <!-- Edit Profile Form -->
            <div class="collapse" id="editProfileForm">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="update_profile" value="1">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Full Name</label>
                                    <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($userData['name']); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Email</label>
                                    <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($userData['email']); ?>" required>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label fw-semibold">Profile Photo</label>
                                    <input type="file" class="form-control" name="photo" accept="image/jpeg,image/png">
                                    <div class="form-text">JPG or PNG, max 2MB</div>
                                    <div id="photoPreview" class="mt-2" style="max-width: 100px; max-height: 100px;"></div>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary-custom px-4">
                                    <i class="bi bi-check-lg me-2"></i>Update Profile
                                </button>
                                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#editProfileForm">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <?php if (!empty($message)): ?>
                <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show mb-4" role="alert">
                    <?php echo htmlspecialchars($message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">User ID</label>
                    <p class="fs-5 fw-bold"><?php echo $userData['id']; ?></p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Member Since</label>
                    <p class="fs-5"><?php echo date('M d, Y', strtotime($userData['created_at'])); ?></p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Full Name</label>
                    <p class="fs-5"><?php echo htmlspecialchars($userData['name']); ?></p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email</label>
                    <p class="fs-5"><?php echo htmlspecialchars($userData['email']); ?></p>
                </div>
            </div>
            <hr class="my-5">
            <div class="text-center">
                <a href="../logout.php" class="btn btn-outline-danger btn-lg px-4">
                    <i class="bi bi-box-arrow-right me-2"></i>Sign Out
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const photoInput = document.querySelector('input[name="photo"]');
    const preview = document.getElementById('photoPreview');
    
    if (photoInput) {
        photoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = '<img src="' + e.target.result + '" style="max-width: 100px; max-height: 100px; border-radius: 50%; border: 3px solid var(--primary);">';
                };
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = '';
            }
        });
    }
});
</script>

<?php require_once '../includes/footer.php'; ?>


<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!-- Top Navbar -->
<nav class="navbar navbar-expand navbar-custom">
    <div class="container-fluid">
        <button class="navbar-toggler mobile-toggle me-2" type="button" id="sidebarToggle">
            <i class="bi bi-list"></i>
        </button>
        <div class="navbar-brand page-title-custom ms-3">
            <a href="dashboard.php"><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Dashboard'; ?></a>
        </div>
        <div class="navbar-nav ms-auto user-section">
            <span class="navbar-text me-3 fw-semibold"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
            <div class="dropdown">
                <a class="avatar-custom dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="bi bi-person-fill"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-lg">
                    <li><a class="dropdown-item" href="profile.php"><i class="bi bi-person me-2"></i>Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="../logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<!-- Sidebar -->
<aside class="sidebar-custom">
    <div class="sidebar-header p-4">
        <h6 class="mb-0 fw-bold">Menu</h6>
    </div>
    <nav class="sidebar-nav">
        <a href="dashboard.php" class="sidebar-link-custom <?php echo $currentPage == 'dashboard.php' ? 'active' : ''; ?>">
            <i class="bi bi-house-door sidebar-icon"></i><span>Dashboard</span>
        </a>
        <a href="tasks.php" class="sidebar-link-custom <?php echo $currentPage == 'tasks.php' ? 'active' : ''; ?>">
            <i class="bi bi-list-task sidebar-icon"></i><span>My Tasks</span>
        </a>
        <a href="projects.php" class="sidebar-link-custom <?php echo $currentPage == 'projects.php' ? 'active' : ''; ?>">
            <i class="bi bi-diagram-3 sidebar-icon"></i><span>Projects</span>
        </a>
        <a href="create_task.php" class="sidebar-link-custom <?php echo $currentPage == 'create_task.php' ? 'active' : ''; ?>">
            <i class="bi bi-plus-circle sidebar-icon"></i><span>Create Task</span>
        </a>
        <a href="reports.php" class="sidebar-link-custom <?php echo $currentPage == 'reports.php' ? 'active' : ''; ?>">
            <i class="bi bi-bar-chart sidebar-icon"></i><span>Reports</span>
        </a>
        <a href="profile.php" class="sidebar-link-custom <?php echo $currentPage == 'profile.php' ? 'active' : ''; ?>">
            <i class="bi bi-person sidebar-icon"></i><span>Profile</span>
        </a>
    </nav>
</aside>

<!-- Main Content -->
<main class="main-content-custom">
    <div class="container-fluid">



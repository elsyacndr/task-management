<?php
session_start();
require_once 'config/database.php';
require_once 'classes/Database.php';
require_once 'classes/User.php';

if(isset($_SESSION['user_id'])) {
    header("Location: pages/dashboard.php");
    exit();
}

$message = '';
$messageType = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $database = new Database();
    $db = $database->connect();
    $user = new User($db);

    $email = $_POST['email'];
    $password = $_POST['password'];

    $loggedInUser = $user->login($email, $password);
    if($loggedInUser) {
        $_SESSION['user_id'] = $loggedInUser['id'];
        $_SESSION['user_name'] = $loggedInUser['name'];
        header("Location: pages/dashboard.php");
        exit();
    } else {
        $message = "Invalid email or password.";
        $messageType = "danger";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php $pageTitle = "Login"; ?>
    <?php include 'includes/header.php'; ?>
    <style>
        .auth-hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }
        .auth-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 100 100\"><defs><pattern id=\"grain\" width=\"100\" height=\"100\" patternUnits=\"userSpaceOnUse\"><circle cx=\"25\" cy=\"25\" r=\"1\" fill=\"%23ffffff\" opacity=\"0.1\"/><circle cx=\"75\" cy=\"75\" r=\"1.5\" fill=\"%23ffffff\" opacity=\"0.05\"/></pattern></defs><rect width=\"100\" height=\"100\" fill=\"url(%23grain)\"/></svg>');
            animation: float 20s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .auth-card-large {
            max-width: 380px;
            margin : auto;
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 16px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            animation: slideUp 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .floating-label .form-floating > label {
            color: var(--gray-600);
            font-weight: 500;
        }
        .form-floating > .form-control:focus ~ label {
            color: var(--primary);
        }
        .login-illustration {
            font-size: 6rem;
            background: linear-gradient(135deg, rgba(255,255,255,0.3), rgba(255,255,255,0.1));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            filter: drop-shadow(0 4px 12px rgba(255,255,255,0.3));
        }
    </style>
</head>
<body class="d-flex align-items-center min-vh-100 p-2">
    <div class="auth-hero w-100 position-relative">
        <div class="container">
            <div class="row align-items-center min-vh-100">
                <div class="col-lg-4 offset-lg-4 col-md-6 offset-md-3">
                    <div class="glass-card auth-card-large p-4">
                        <div class="text-center mb-5">
                            <div class="login-illustration mb-4">
                                <i class="bi bi-list-check"></i>
                            </div>
                            <h1 class="h2 fw-bold mb-2 text-dark">Welcome Back</h1>
                            <p class="lead text-muted mb-0">Sign in to continue managing your tasks</p>
                        </div>
                        
                        <?php if($message): ?>
                            <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show shadow-sm mb-4" role="alert">
                                <?php echo htmlspecialchars($message); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" class="floating-label">
                            <div class="form-floating mb-4">
                                <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="name@example.com" required>
                                <label for="email">Email address</label>
                            </div>
                            <div class="form-floating mb-4">
                                <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Password" required>
                                <label for="password">Password</label>
                            </div>
                            <button type="submit" class="btn btn-primary-custom btn-lg w-100 py-3 fw-semibold shadow-lg mb-3">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                            </button>
                        </form>
                        
                        <div class="text-center">
                            <p class="text-muted mb-0">Don't have an account? <a href="register.php" class="fw-semibold text-decoration-none">Create one now</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


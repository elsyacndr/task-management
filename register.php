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

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if($password !== $confirm_password) {
        $message = "Passwords do not match.";
        $messageType = "danger";
    } elseif($user->emailExists($email)) {
        $message = "Email already exists.";
        $messageType = "warning";
    } else {
        if($user->register($name, $email, $password)) {
            $message = "Registration successful! Please login.";
            $messageType = "success";
        } else {
            $message = "Registration failed. Please try again.";
            $messageType = "danger";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php $pageTitle = "Register"; ?>
    <?php include 'includes/header.php'; ?>
    <style>
        .auth-hero {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="%23ffffff" opacity="0.08"/><circle cx="75" cy="75" r="1" fill="%23ffffff" opacity="0.04"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            animation: float 20s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .auth-card-large {
            max-width: 380px;
            margin: auto;
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
        .register-illustration {
            font-size: 5rem;
            background: linear-gradient(135deg, rgba(255,255,255,0.4), rgba(255,255,255,0.1));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            filter: drop-shadow(0 4px 12px rgba(255,255,255,0.4));
        }
        .password-strength {
            height: 4px;
            border-radius: 2px;
            margin-top: 0.25rem;
            transition: all 0.3s ease;
        }
    </style>
</head>
<body class="d-flex align-items-center min-vh-100 p-2">
    <div class="auth-hero w-100 position-relative">
        <div class="container">
            <div class="row align-items-center min-vh-100">
                <div class="col-lg-4 offset-lg-4 col-md-6 offset-md-3">
                    <div class="glass-card auth-card-large p-3">
                        <div class="text-center mb-5">
                            <div class="register-illustration mb-4">
                                <i class="bi bi-person-plus-fill"></i>
                            </div>
                            <h1 class="h2 fw-bold mb-2 text-dark">Create Account</h1>
                            <p class="lead text-muted mb-0">Join thousands managing tasks efficiently</p>
                        </div>
                        
                        <?php if($message): ?>
                            <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show shadow-sm mb-4" role="alert">
                                <?php echo htmlspecialchars($message); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating mb-4">
                                        <input type="text" class="form-control form-control-lg" id="name" name="name" placeholder="Full Name" required>
                                        <label for="name">Full Name</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-4">
                                        <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="name@example.com" required>
                                        <label for="email">Email Address</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-floating mb-4">
                                <input type="password" class="form-control form-control-lg" id="password" name="password" minlength="6" required>
                                <label for="password">Password</label>
                                <div class="password-strength bg-light mt-1"></div>
                            </div>
                            <div class="form-floating mb-4">
                                <input type="password" class="form-control form-control-lg" id="confirm_password" name="confirm_password" minlength="6" required>
                                <label for="confirm_password">Confirm Password</label>
                            </div>
                            <button type="submit" class="btn btn-primary-custom btn-lg w-100 py-3 fw-semibold shadow-lg mb-3">
                                <i class="bi bi-person-plus me-2"></i>Create Account
                            </button>
                        </form>
                        
                        <div class="text-center pt-4 border-top">
                            <p class="text-muted mb-0">Already have an account? <a href="index.php" class="fw-semibold text-decoration-none">Sign in here</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Password strength indicator
        const passwordInput = document.getElementById('password');
        const strengthBar = document.querySelector('.password-strength');
        if (passwordInput && strengthBar) {
            passwordInput.addEventListener('input', function() {
                const val = this.value;
                let strength = 0;
                if (val.length > 6) strength++;
                if (val.match(/[a-z]/)) strength++;
                if (val.match(/[A-Z]/)) strength++;
                if (val.match(/[0-9]/)) strength++;
                if (val.match(/[^a-zA-Z0-9]/)) strength++;

                strengthBar.className = 'password-strength';
                if (strength <= 2) {
                    strengthBar.style.width = '33%';
                    strengthBar.style.background = '#ffc107';
                } else if (strength <= 4) {
                    strengthBar.style.width = '66%';
                    strengthBar.style.background = '#198754';
                } else {
                    strengthBar.style.width = '100%';
                    strengthBar.style.background = '#10b981';
                }
            });
        }
    </script>
</body>
</html>
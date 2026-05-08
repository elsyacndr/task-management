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
        $message = "Email atau password salah. Silakan coba lagi.";
        $messageType = "danger";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Task Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: #0f0c29;
            background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            position: relative;
            overflow: hidden;
        }

        /* Animated background blobs */
        body::before {
            content: '';
            position: fixed;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, transparent 70%);
            top: -200px;
            right: -200px;
            border-radius: 50%;
            animation: blobMove 8s ease-in-out infinite alternate;
        }
        body::after {
            content: '';
            position: fixed;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(168, 85, 247, 0.12) 0%, transparent 70%);
            bottom: -150px;
            left: -150px;
            border-radius: 50%;
            animation: blobMove 10s ease-in-out infinite alternate-reverse;
        }
        @keyframes blobMove {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(30px, 20px) scale(1.05); }
        }

        /* Floating particles */
        .particles {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            pointer-events: none;
            z-index: 0;
        }
        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(255,255,255,0.15);
            border-radius: 50%;
            animation: floatUp linear infinite;
        }
        @keyframes floatUp {
            0% { transform: translateY(100vh) scale(0); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(-10vh) scale(1); opacity: 0; }
        }

        /* Main wrapper */
        .auth-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 960px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 580px;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 40px 80px rgba(0,0,0,0.5), 0 0 0 1px rgba(255,255,255,0.05);
        }

        /* Left panel */
        .auth-left {
            background: linear-gradient(145deg, #6366f1 0%, #8b5cf6 50%, #a855f7 100%);
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }
        .auth-left::before {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(255,255,255,0.08);
            border-radius: 50%;
            top: -100px;
            right: -100px;
        }
        .auth-left::after {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
            bottom: -60px;
            left: -60px;
        }
        .brand-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: white;
            font-weight: 700;
            font-size: 1.1rem;
            position: relative;
            z-index: 1;
        }
        .brand-logo .logo-icon {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            backdrop-filter: blur(10px);
        }
        .left-content {
            position: relative;
            z-index: 1;
        }
        .left-content h2 {
            color: white;
            font-size: 2rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1rem;
        }
        .left-content p {
            color: rgba(255,255,255,0.75);
            font-size: 0.95rem;
            line-height: 1.7;
            margin-bottom: 2rem;
        }
        .feature-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }
        .feature-list li {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: rgba(255,255,255,0.85);
            font-size: 0.875rem;
            font-weight: 500;
        }
        .feature-list li .check {
            width: 22px;
            height: 22px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            flex-shrink: 0;
        }
        .left-footer {
            color: rgba(255,255,255,0.5);
            font-size: 0.8rem;
            position: relative;
            z-index: 1;
        }

        /* Right panel */
        .auth-right {
            background: #1a1a2e;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .auth-right h1 {
            color: #fff;
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .auth-right .subtitle {
            color: rgba(255,255,255,0.45);
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }

        /* Form styles */
        .form-group {
            margin-bottom: 1.25rem;
        }
        .form-group label {
            display: block;
            color: rgba(255,255,255,0.7);
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 0.5rem;
        }
        .input-wrapper {
            position: relative;
        }
        .input-wrapper .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,0.3);
            font-size: 1rem;
            pointer-events: none;
            transition: color 0.2s;
        }
        .input-wrapper input {
            width: 100%;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            padding: 0.875rem 1rem 0.875rem 2.75rem;
            color: #fff;
            font-size: 0.95rem;
            font-family: 'Inter', sans-serif;
            transition: all 0.2s ease;
            outline: none;
        }
        .input-wrapper input::placeholder {
            color: rgba(255,255,255,0.2);
        }
        .input-wrapper input:focus {
            background: rgba(99, 102, 241, 0.12);
            border-color: rgba(99, 102, 241, 0.6);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        }
        .input-wrapper input:focus ~ .input-icon,
        .input-wrapper:focus-within .input-icon {
            color: #6366f1;
        }

        /* Toggle password */
        .toggle-password {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: rgba(255,255,255,0.3);
            cursor: pointer;
            padding: 0;
            font-size: 1rem;
            transition: color 0.2s;
        }
        .toggle-password:hover { color: rgba(255,255,255,0.7); }

        /* Submit button */
        .btn-auth {
            width: 100%;
            padding: 0.9rem;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 0.95rem;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            letter-spacing: 0.02em;
        }
        .btn-auth:hover {
            background: linear-gradient(135deg, #5558e8, #7c3aed);
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4);
        }
        .btn-auth:active { transform: translateY(0); }

        /* Alert */
        .alert-custom {
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 10px;
            padding: 0.875rem 1rem;
            color: #fca5a5;
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 1.5rem 0;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255,255,255,0.08);
        }
        .divider span {
            color: rgba(255,255,255,0.3);
            font-size: 0.8rem;
        }

        /* Footer link */
        .auth-footer-link {
            text-align: center;
            color: rgba(255,255,255,0.4);
            font-size: 0.875rem;
            margin-top: 1.5rem;
        }
        .auth-footer-link a {
            color: #818cf8;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }
        .auth-footer-link a:hover { color: #a5b4fc; }

        /* Responsive */
        @media (max-width: 768px) {
            .auth-wrapper {
                grid-template-columns: 1fr;
                max-width: 440px;
            }
            .auth-left { display: none; }
            .auth-right { padding: 2.5rem 2rem; }
        }
    </style>
</head>
<body>

    <!-- Particles -->
    <div class="particles" id="particles"></div>

    <div class="auth-wrapper">
        <!-- Left Panel -->
        <div class="auth-left">
            <div class="brand-logo">
                <div class="logo-icon"><i class="bi bi-check2-square"></i></div>
                TaskFlow
            </div>
            <div class="left-content">
                <h2>Manage tasks<br>with ease.</h2>
                <p>Stay organized, meet deadlines, and track your progress — all in one place.</p>
                <ul class="feature-list">
                    <li><span class="check"><i class="bi bi-check"></i></span> Smart task tracking & overdue alerts</li>
                    <li><span class="check"><i class="bi bi-check"></i></span> Project-based organization</li>
                    <li><span class="check"><i class="bi bi-check"></i></span> Analytics & completion reports</li>
                    <li><span class="check"><i class="bi bi-check"></i></span> Clean, distraction-free dashboard</li>
                </ul>
            </div>
            <div class="left-footer">© 2025 TaskFlow. All rights reserved.</div>
        </div>

        <!-- Right Panel -->
        <div class="auth-right">
            <h1>Welcome back</h1>
            <p class="subtitle">Sign in to your account to continue</p>

            <?php if($message): ?>
            <div class="alert-custom">
                <i class="bi bi-exclamation-circle-fill"></i>
                <?php echo htmlspecialchars($message); ?>
            </div>
            <?php endif; ?>

            <form method="POST" autocomplete="off">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-wrapper">
                        <input type="email" id="email" name="email" placeholder="you@example.com" required
                               value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                        <i class="bi bi-envelope input-icon"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                        <i class="bi bi-lock input-icon"></i>
                        <button type="button" class="toggle-password" onclick="togglePassword('password', this)">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-auth">
                    <i class="bi bi-box-arrow-in-right"></i> Sign In
                </button>
            </form>

            <div class="divider"><span>Don't have an account?</span></div>

            <div class="auth-footer-link">
                New here? <a href="register.php">Create an account</a>
            </div>
        </div>
    </div>

    <script>
        // Generate particles
        const container = document.getElementById('particles');
        for (let i = 0; i < 20; i++) {
            const p = document.createElement('div');
            p.className = 'particle';
            p.style.left = Math.random() * 100 + '%';
            p.style.width = p.style.height = (Math.random() * 4 + 2) + 'px';
            p.style.animationDuration = (Math.random() * 15 + 10) + 's';
            p.style.animationDelay = (Math.random() * 10) + 's';
            p.style.opacity = Math.random() * 0.3;
            container.appendChild(p);
        }

        // Toggle password visibility
        function togglePassword(id, btn) {
            const input = document.getElementById(id);
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'bi bi-eye';
            }
        }
    </script>
</body>
</html>

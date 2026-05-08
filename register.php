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
        $message = "Password dan konfirmasi password tidak cocok.";
        $messageType = "danger";
    } elseif($user->emailExists($email)) {
        $message = "Email sudah terdaftar. Gunakan email lain.";
        $messageType = "warning";
    } else {
        if($user->register($name, $email, $password)) {
            $message = "Akun berhasil dibuat! Silakan login.";
            $messageType = "success";
        } else {
            $message = "Registrasi gagal. Silakan coba lagi.";
            $messageType = "danger";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — Task Management</title>
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

        body::before {
            content: '';
            position: fixed;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(168, 85, 247, 0.15) 0%, transparent 70%);
            top: -200px;
            left: -200px;
            border-radius: 50%;
            animation: blobMove 9s ease-in-out infinite alternate;
        }
        body::after {
            content: '';
            position: fixed;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.12) 0%, transparent 70%);
            bottom: -150px;
            right: -150px;
            border-radius: 50%;
            animation: blobMove 11s ease-in-out infinite alternate-reverse;
        }
        @keyframes blobMove {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(30px, 20px) scale(1.05); }
        }

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
            max-width: 1000px;
            display: grid;
            grid-template-columns: 1fr 1.2fr;
            min-height: 620px;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 40px 80px rgba(0,0,0,0.5), 0 0 0 1px rgba(255,255,255,0.05);
        }

        /* Left panel */
        .auth-left {
            background: linear-gradient(145deg, #a855f7 0%, #8b5cf6 50%, #6366f1 100%);
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
            font-size: 1.9rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1rem;
        }
        .left-content p {
            color: rgba(255,255,255,0.75);
            font-size: 0.9rem;
            line-height: 1.7;
            margin-bottom: 2rem;
        }
        .steps-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .steps-list li {
            display: flex;
            align-items: flex-start;
            gap: 0.875rem;
            color: rgba(255,255,255,0.85);
            font-size: 0.875rem;
        }
        .step-num {
            width: 26px;
            height: 26px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
            flex-shrink: 0;
            color: white;
        }
        .step-text strong {
            display: block;
            font-weight: 600;
            margin-bottom: 0.1rem;
        }
        .step-text span {
            color: rgba(255,255,255,0.6);
            font-size: 0.8rem;
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
            padding: 2.5rem 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            overflow-y: auto;
        }
        .auth-right h1 {
            color: #fff;
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: 0.4rem;
        }
        .auth-right .subtitle {
            color: rgba(255,255,255,0.4);
            font-size: 0.875rem;
            margin-bottom: 1.75rem;
        }

        /* Form */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        .form-group {
            margin-bottom: 1.1rem;
        }
        .form-group label {
            display: block;
            color: rgba(255,255,255,0.6);
            font-size: 0.78rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 0.45rem;
        }
        .input-wrapper {
            position: relative;
        }
        .input-wrapper .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,0.25);
            font-size: 0.95rem;
            pointer-events: none;
            transition: color 0.2s;
        }
        .input-wrapper input {
            width: 100%;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            padding: 0.8rem 1rem 0.8rem 2.6rem;
            color: #fff;
            font-size: 0.9rem;
            font-family: 'Inter', sans-serif;
            transition: all 0.2s ease;
            outline: none;
        }
        .input-wrapper input::placeholder { color: rgba(255,255,255,0.18); }
        .input-wrapper input:focus {
            background: rgba(99, 102, 241, 0.12);
            border-color: rgba(99, 102, 241, 0.6);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        }
        .input-wrapper:focus-within .input-icon { color: #6366f1; }

        .toggle-password {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: rgba(255,255,255,0.25);
            cursor: pointer;
            padding: 0;
            font-size: 0.95rem;
            transition: color 0.2s;
        }
        .toggle-password:hover { color: rgba(255,255,255,0.6); }

        /* Password strength */
        .strength-bar {
            height: 3px;
            border-radius: 2px;
            margin-top: 0.4rem;
            background: rgba(255,255,255,0.08);
            overflow: hidden;
        }
        .strength-fill {
            height: 100%;
            border-radius: 2px;
            width: 0%;
            transition: all 0.3s ease;
        }
        .strength-text {
            font-size: 0.72rem;
            margin-top: 0.25rem;
            color: rgba(255,255,255,0.35);
        }

        /* Alert */
        .alert-custom {
            border-radius: 10px;
            padding: 0.8rem 1rem;
            font-size: 0.85rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .alert-danger { background: rgba(239,68,68,0.12); border: 1px solid rgba(239,68,68,0.3); color: #fca5a5; }
        .alert-warning { background: rgba(245,158,11,0.12); border: 1px solid rgba(245,158,11,0.3); color: #fcd34d; }
        .alert-success { background: rgba(16,185,129,0.12); border: 1px solid rgba(16,185,129,0.3); color: #6ee7b7; }

        /* Submit button */
        .btn-auth {
            width: 100%;
            padding: 0.875rem;
            background: linear-gradient(135deg, #8b5cf6, #6366f1);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 0.95rem;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 0.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        .btn-auth:hover {
            background: linear-gradient(135deg, #7c3aed, #5558e8);
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(139, 92, 246, 0.4);
        }
        .btn-auth:active { transform: translateY(0); }

        .auth-footer-link {
            text-align: center;
            color: rgba(255,255,255,0.35);
            font-size: 0.875rem;
            margin-top: 1.25rem;
        }
        .auth-footer-link a {
            color: #818cf8;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }
        .auth-footer-link a:hover { color: #a5b4fc; }

        /* Terms */
        .terms-text {
            font-size: 0.78rem;
            color: rgba(255,255,255,0.3);
            text-align: center;
            margin-top: 0.75rem;
        }

        @media (max-width: 768px) {
            .auth-wrapper {
                grid-template-columns: 1fr;
                max-width: 460px;
            }
            .auth-left { display: none; }
            .auth-right { padding: 2.5rem 1.75rem; }
            .form-row { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <div class="particles" id="particles"></div>

    <div class="auth-wrapper">
        <!-- Left Panel -->
        <div class="auth-left">
            <div class="brand-logo">
                <div class="logo-icon"><i class="bi bi-check2-square"></i></div>
                TaskFlow
            </div>
            <div class="left-content">
                <h2>Start managing<br>smarter today.</h2>
                <p>Join and get full access to your personal task management dashboard in seconds.</p>
                <ul class="steps-list">
                    <li>
                        <div class="step-num">1</div>
                        <div class="step-text">
                            <strong>Create your account</strong>
                            <span>Fill in your details below</span>
                        </div>
                    </li>
                    <li>
                        <div class="step-num">2</div>
                        <div class="step-text">
                            <strong>Set up your projects</strong>
                            <span>Organize tasks by project</span>
                        </div>
                    </li>
                    <li>
                        <div class="step-num">3</div>
                        <div class="step-text">
                            <strong>Track your progress</strong>
                            <span>View analytics & reports</span>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="left-footer">© 2025 TaskFlow. All rights reserved.</div>
        </div>

        <!-- Right Panel -->
        <div class="auth-right">
            <h1>Create account</h1>
            <p class="subtitle">Fill in the form below to get started</p>

            <?php if($message): ?>
            <div class="alert-custom alert-<?php echo $messageType; ?>">
                <i class="bi bi-<?php echo $messageType === 'success' ? 'check-circle-fill' : ($messageType === 'warning' ? 'exclamation-triangle-fill' : 'exclamation-circle-fill'); ?>"></i>
                <?php echo htmlspecialchars($message); ?>
            </div>
            <?php endif; ?>

            <form method="POST" autocomplete="off">
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <div class="input-wrapper">
                            <input type="text" id="name" name="name" placeholder="John Doe" required
                                   value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
                            <i class="bi bi-person input-icon"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <div class="input-wrapper">
                            <input type="email" id="email" name="email" placeholder="you@example.com" required
                                   value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                            <i class="bi bi-envelope input-icon"></i>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <input type="password" id="password" name="password" placeholder="Min. 6 characters" minlength="6" required>
                        <i class="bi bi-lock input-icon"></i>
                        <button type="button" class="toggle-password" onclick="togglePassword('password', this)">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    <div class="strength-bar"><div class="strength-fill" id="strengthFill"></div></div>
                    <div class="strength-text" id="strengthText"></div>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <div class="input-wrapper">
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Repeat your password" minlength="6" required>
                        <i class="bi bi-lock-fill input-icon"></i>
                        <button type="button" class="toggle-password" onclick="togglePassword('confirm_password', this)">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    <div class="strength-text" id="matchText"></div>
                </div>

                <button type="submit" class="btn-auth">
                    <i class="bi bi-person-plus"></i> Create Account
                </button>

                <p class="terms-text">By registering, you agree to our Terms of Service.</p>
            </form>

            <div class="auth-footer-link">
                Already have an account? <a href="index.php">Sign in here</a>
            </div>
        </div>
    </div>

    <script>
        // Particles
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

        // Toggle password
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

        // Password strength
        const passwordInput = document.getElementById('password');
        const strengthFill = document.getElementById('strengthFill');
        const strengthText = document.getElementById('strengthText');

        passwordInput.addEventListener('input', function() {
            const val = this.value;
            let score = 0;
            if (val.length >= 6) score++;
            if (val.length >= 10) score++;
            if (/[A-Z]/.test(val)) score++;
            if (/[0-9]/.test(val)) score++;
            if (/[^a-zA-Z0-9]/.test(val)) score++;

            const levels = [
                { pct: '0%', color: '', label: '' },
                { pct: '25%', color: '#ef4444', label: 'Weak' },
                { pct: '50%', color: '#f59e0b', label: 'Fair' },
                { pct: '75%', color: '#3b82f6', label: 'Good' },
                { pct: '100%', color: '#10b981', label: 'Strong' },
            ];
            const level = levels[Math.min(score, 4)];
            strengthFill.style.width = level.pct;
            strengthFill.style.background = level.color;
            strengthText.textContent = level.label ? 'Password strength: ' + level.label : '';
            strengthText.style.color = level.color;
        });

        // Password match
        const confirmInput = document.getElementById('confirm_password');
        const matchText = document.getElementById('matchText');

        confirmInput.addEventListener('input', function() {
            if (this.value === '') {
                matchText.textContent = '';
                return;
            }
            if (this.value === passwordInput.value) {
                matchText.textContent = '✓ Passwords match';
                matchText.style.color = '#10b981';
            } else {
                matchText.textContent = '✗ Passwords do not match';
                matchText.style.color = '#ef4444';
            }
        });
    </script>
</body>
</html>

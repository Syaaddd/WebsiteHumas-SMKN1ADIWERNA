<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!empty($_SESSION[ADMIN_SESSION_KEY])) {
    header('Location: ' . BASE_URL . '/admin/dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        try {
            $pdo  = getPDO();
            $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ? LIMIT 1");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                session_regenerate_id(true);
                $_SESSION[ADMIN_SESSION_KEY] = ['id' => $user['id'], 'username' => $user['username']];
                header('Location: ' . BASE_URL . '/admin/dashboard.php');
                exit;
            }
        } catch (Exception $e) {
            $error = 'Koneksi database gagal. Periksa konfigurasi db.php.';
        }
    }
    if (!$error) $error = 'Username atau password salah.';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/output.css">
    <style>
        body {
            font-family: 'Segoe UI', ui-sans-serif, system-ui, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #fff7ed 0%, #fef9f0 50%, #f0fdf4 100%);
        }
        .login-card {
            background: #fff;
            border-radius: 1.5rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            padding: 2.5rem 2.25rem;
            width: 100%;
            max-width: 400px;
            border: 1px solid #fed7aa;
        }
        .input-field {
            width: 100%;
            border: 1.5px solid #e5e7eb;
            border-radius: 0.625rem;
            padding: 0.7rem 0.875rem;
            font-size: 0.9rem;
            background: #f9fafb;
            color: #1f2937;
            transition: border-color 0.15s, box-shadow 0.15s;
            box-sizing: border-box;
        }
        .input-field:focus {
            outline: none;
            border-color: #f97316;
            box-shadow: 0 0 0 3px rgba(249,115,22,0.12);
            background: #fff;
        }
        .btn-submit {
            width: 100%;
            background: #f97316;
            color: #fff;
            font-weight: 700;
            font-size: 0.95rem;
            padding: 0.75rem;
            border: none;
            border-radius: 0.625rem;
            cursor: pointer;
            transition: background 0.15s;
        }
        .btn-submit:hover { background: #ea580c; }
        label {
            display: block;
            font-size: 0.8rem;
            font-weight: 700;
            color: #374151;
            margin-bottom: 0.4rem;
        }
        .password-wrapper {
            position: relative;
        }
        .toggle-pw {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #9ca3af;
            padding: 0.25rem;
            display: flex;
            align-items: center;
            transition: color 0.15s;
        }
        .toggle-pw:hover { color: #f97316; }
    </style>
</head>
<body>
    <div class="login-card">
        <!-- Logo / Brand -->
        <div style="text-align:center; margin-bottom:2rem;">
            <div style="width:56px; height:56px; border-radius:14px; background:linear-gradient(135deg,#f97316,#ea580c); display:flex; align-items:center; justify-content:center; margin:0 auto 0.875rem; box-shadow:0 8px 20px rgba(249,115,22,0.3);">
                <svg style="width:28px;height:28px;color:#fff;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <h1 style="font-size:1.375rem; font-weight:900; color:#1f2937; margin:0 0 0.25rem;">Admin Panel</h1>
            <p style="font-size:0.8rem; color:#9ca3af; margin:0;">Masuk untuk mengelola konten</p>
        </div>

        <?php if ($error): ?>
        <div style="background:#fff7ed; border:1px solid #fed7aa; color:#c2410c; font-size:0.84rem; padding:0.75rem 1rem; border-radius:0.625rem; margin-bottom:1.25rem; display:flex; align-items:center; gap:0.5rem;">
            <span>⚠️</span> <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>

        <form method="POST" autocomplete="off">
            <!-- Username -->
            <div style="margin-bottom:1rem;">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required
                       class="input-field"
                       placeholder="Masukkan username"
                       value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                       autocomplete="username">
            </div>

            <!-- Password + Toggle -->
            <div style="margin-bottom:1.5rem;">
                <label for="password">Password</label>
                <div class="password-wrapper">
                    <input type="password" id="password" name="password" required
                           class="input-field"
                           placeholder="Masukkan password"
                           style="padding-right:2.75rem;"
                           autocomplete="current-password">
                    <button type="button" class="toggle-pw" onclick="togglePassword()" title="Tampilkan/sembunyikan password">
                        <!-- Eye icon (show) -->
                        <svg id="icon-eye" style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <!-- Eye-off icon (hide) -->
                        <svg id="icon-eye-off" style="width:18px;height:18px;display:none;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                Masuk →
            </button>
        </form>

        <p style="text-align:center; font-size:0.75rem; color:#d1d5db; margin-top:1.75rem;">
            &copy; <?= date('Y') ?> Admin Panel — Humas
        </p>
    </div>

    <script>
        function togglePassword() {
            const input   = document.getElementById('password');
            const eyeOn   = document.getElementById('icon-eye');
            const eyeOff  = document.getElementById('icon-eye-off');
            const isHidden = input.type === 'password';

            input.type      = isHidden ? 'text' : 'password';
            eyeOn.style.display  = isHidden ? 'none'  : 'block';
            eyeOff.style.display = isHidden ? 'block' : 'none';
        }
    </script>
</body>
</html>

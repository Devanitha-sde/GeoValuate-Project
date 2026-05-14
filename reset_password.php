<?php
require_once 'includes/config.php';

$token = trim($_GET['token'] ?? $_POST['token'] ?? '');
$email = trim($_GET['email'] ?? $_POST['email'] ?? '');
$showResetForm = false;
$demoResetLink = null;

if ($pdo instanceof PDO && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        set_flash('error', 'Your session expired. Please try again.');
        redirect('reset_password.php');
    }

    $action = $_POST['action'] ?? 'request';

    if ($action === 'request') {
        $email = trim($_POST['email'] ?? '');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            set_flash('error', 'Please enter a valid email address.');
            redirect('reset_password.php');
        }

        $stmt = $pdo->prepare('SELECT id, name FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user) {
            set_flash('error', 'No user account was found with that email address.');
            redirect('reset_password.php');
        }

        $pdo->prepare('DELETE FROM password_resets WHERE user_id = ? OR expires_at <= NOW()')->execute([(int) $user['id']]);

        $plainToken = generate_token(16);
        $hash = hash('sha256', $plainToken);
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $stmt = $pdo->prepare('INSERT INTO password_resets (user_id, email, token_hash, expires_at) VALUES (?, ?, ?, ?)');
        $stmt->execute([(int) $user['id'], $email, $hash, $expiresAt]);

        $demoResetLink = 'reset_password.php?token=' . urlencode($plainToken) . '&email=' . urlencode($email);
        set_flash('success', 'Reset link generated successfully. Use the demo link below to continue.');
    }

    if ($action === 'reset') {
        $email = trim($_POST['email'] ?? '');
        $token = trim($_POST['token'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        $stmt = $pdo->prepare('SELECT pr.*, u.id AS user_id, u.name FROM password_resets pr JOIN users u ON pr.user_id = u.id WHERE pr.email = ? AND pr.used_at IS NULL AND pr.expires_at > NOW() ORDER BY pr.created_at DESC LIMIT 1');
        $stmt->execute([$email]);
        $resetRecord = $stmt->fetch();

        if (!$resetRecord || !hash_equals($resetRecord['token_hash'], hash('sha256', $token))) {
            set_flash('error', 'That reset link is invalid or has expired.');
            redirect('reset_password.php');
        }

        if (strlen($password) < 6) {
            set_flash('error', 'Password must be at least 6 characters long.');
            redirect('reset_password.php?token=' . urlencode($token) . '&email=' . urlencode($email));
        }

        if ($password !== $confirmPassword) {
            set_flash('error', 'The password confirmation does not match.');
            redirect('reset_password.php?token=' . urlencode($token) . '&email=' . urlencode($email));
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $pdo->prepare('UPDATE users SET password = ? WHERE id = ?')->execute([$hashedPassword, (int) $resetRecord['user_id']]);
        $pdo->prepare('UPDATE password_resets SET used_at = NOW() WHERE id = ?')->execute([(int) $resetRecord['id']]);
        forget_remembered_user($pdo, (int) $resetRecord['user_id']);
        create_notification($pdo, (int) $resetRecord['user_id'], 'Password updated', 'Your GeoValuate password was successfully changed.', 'success');

        set_flash('success', 'Password updated successfully. You can now log in.');
        redirect('login.php');
    }
}

if ($pdo instanceof PDO && $token !== '' && $email !== '') {
    $stmt = $pdo->prepare('SELECT id, token_hash FROM password_resets WHERE email = ? AND used_at IS NULL AND expires_at > NOW() ORDER BY created_at DESC LIMIT 1');
    $stmt->execute([$email]);
    $resetRecord = $stmt->fetch();
    $showResetForm = $resetRecord && hash_equals($resetRecord['token_hash'], hash('sha256', $token));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - GeoValuate</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar glass">
        <a href="index.php" class="logo">
            <i class="fas fa-globe-americas"></i> GeoValuate
        </a>
        <div class="nav-links">
            <a href="about.php">About</a>
            <a href="help.php">Help</a>
            <a href="contact.php">Contact</a>
        </div>
    </nav>

    <main class="flex-center" style="justify-content: space-between; padding: 0 5rem;">
        <div style="width: 40%;" class="animate-fade">
            <h1 style="font-size: 3rem; margin-bottom: 2rem;">Secure access to your valuation workspace.</h1>
            <p style="font-size: 1.4rem; opacity: 0.9;">This local demo flow generates a reset link on-screen so you can safely test password recovery without email integration.</p>
        </div>

        <div class="auth-box glass animate-fade">
            <h2 class="auth-title"><?php echo $showResetForm ? 'Create a New Password' : 'Reset Your Password'; ?></h2>
            <p class="auth-subtitle"><?php echo $showResetForm ? 'Enter a strong password to finish resetting your account.' : 'Enter your account email to generate a secure demo reset link.'; ?></p>
            <?php render_flashes(); ?>

            <?php if ($demoResetLink): ?>
                <div class="alert alert-info">
                    Demo reset link:
                    <a href="<?php echo h($demoResetLink); ?>" style="color: #fff; text-decoration: underline;"><?php echo h($demoResetLink); ?></a>
                </div>
            <?php endif; ?>

            <?php if ($showResetForm): ?>
                <form action="reset_password.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo h(csrf_token()); ?>">
                    <input type="hidden" name="action" value="reset">
                    <input type="hidden" name="email" value="<?php echo h($email); ?>">
                    <input type="hidden" name="token" value="<?php echo h($token); ?>">
                    <div class="form-group">
                        <label>New Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Enter new password" required>
                    </div>
                    <div class="form-group">
                        <label>Confirm New Password</label>
                        <input type="password" name="confirm_password" class="form-control" placeholder="Confirm new password" required>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 15px;">Update Password</button>
                </form>
            <?php else: ?>
                <form action="reset_password.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo h(csrf_token()); ?>">
                    <input type="hidden" name="action" value="request">
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="Enter your registered email" required>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 15px;">Generate Reset Link</button>
                </form>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>

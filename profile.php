<?php
require_once 'includes/config.php';
require_login();

$isAdmin = is_admin_user();
$homePath = $isAdmin ? 'admin/index.php' : 'dashboard.php';
$logoLabel = $isAdmin ? 'GeoValuate Admin' : 'GeoValuate';
$logoIcon = $isAdmin ? 'fas fa-shield-alt' : 'fas fa-globe-americas';

$user = current_user($pdo);
if (!$user) {
    set_flash('error', 'Unable to load your profile right now.');
    redirect($homePath);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        set_flash('error', 'Your session expired. Please try again.');
        redirect('profile.php');
    }

    $action = $_POST['action'] ?? 'profile';

    if ($action === 'profile') {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');

        if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            set_flash('error', 'Please provide a valid name and email address.');
            redirect('profile.php');
        }

        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? AND id != ? LIMIT 1');
        $stmt->execute([$email, (int) $user['id']]);
        if ($stmt->fetch()) {
            set_flash('error', 'That email address is already in use by another account.');
            redirect('profile.php');
        }

        $stmt = $pdo->prepare('UPDATE users SET name = ?, email = ? WHERE id = ?');
        $stmt->execute([$name, $email, (int) $user['id']]);
        $_SESSION['user_name'] = $name;
        create_notification($pdo, (int) $user['id'], 'Profile updated', 'Your account profile details were updated successfully.', 'success');
        set_flash('success', 'Profile updated successfully.');
        redirect('profile.php');
    }

    if ($action === 'password') {
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (!password_verify($currentPassword, $user['password'])) {
            set_flash('error', 'Your current password is incorrect.');
            redirect('profile.php');
        }

        if (strlen($newPassword) < 6) {
            set_flash('error', 'New password must be at least 6 characters long.');
            redirect('profile.php');
        }

        if ($newPassword !== $confirmPassword) {
            set_flash('error', 'New password confirmation does not match.');
            redirect('profile.php');
        }

        $stmt = $pdo->prepare('UPDATE users SET password = ? WHERE id = ?');
        $stmt->execute([password_hash($newPassword, PASSWORD_DEFAULT), (int) $user['id']]);
        forget_remembered_user($pdo, (int) $user['id']);
        create_notification($pdo, (int) $user['id'], 'Password changed', 'Your password was updated. Please log in again on other devices if needed.', 'success');
        set_flash('success', 'Password updated successfully.');
        redirect('profile.php');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - GeoValuate</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar glass">
        <a href="<?php echo h($homePath); ?>" class="logo"><i class="<?php echo h($logoIcon); ?>"></i> <?php echo h($logoLabel); ?></a>
        <?php if ($isAdmin): ?>
            <div class="nav-links">
                <a href="admin/index.php">Overview</a>
                <a href="admin/users.php">Users</a>
                <a href="admin/reports.php">Reports</a>
                <a href="profile.php" class="active">Profile</a>
                <a href="contact.php">Contact Us</a>
            </div>
        <?php else: ?>
            <div class="nav-links">
                <a href="dashboard.php">Home</a>
                <a href="predictions.php">Predictions</a>
                <a href="notifications.php">Notifications</a>
                <a href="discover.php">Discover</a>
                <a href="contact.php">Contact Us</a>
            </div>
        <?php endif; ?>
        <div class="user-profile"><i class="fas fa-user"></i></div>
    </nav>

    <div class="page-shell">
        <div class="page-header">
            <h1>My Profile</h1>
            <p>Review your account details, keep your contact information current, and update your password safely.</p>
        </div>

        <?php render_flashes(); ?>

        <div class="grid-two">
            <section class="glass panel animate-fade">
                <div class="panel-header">
                    <h2 class="panel-title">Account details</h2>
                    <span class="pill"><i class="fas fa-id-badge"></i> <?php echo h(ucfirst($user['role'])); ?></span>
                </div>
                <form action="profile.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo h(csrf_token()); ?>">
                    <input type="hidden" name="action" value="profile">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="name" class="form-control" value="<?php echo h($user['name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" class="form-control" value="<?php echo h($user['email']); ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Profile</button>
                </form>
            </section>

            <section class="glass panel animate-fade" style="animation-delay: 0.15s;">
                <div class="panel-header">
                    <h2 class="panel-title">Security</h2>
                    <span class="pill"><i class="fas fa-shield-halved"></i> Protected</span>
                </div>
                <form action="profile.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo h(csrf_token()); ?>">
                    <input type="hidden" name="action" value="password">
                    <div class="form-group">
                        <label>Current Password</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>New Password</label>
                        <input type="password" name="new_password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Confirm New Password</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Password</button>
                </form>
            </section>
        </div>
    </div>
</body>
</html>

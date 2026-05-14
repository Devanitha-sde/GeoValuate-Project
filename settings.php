<?php
require_once 'includes/config.php';
require_login();

$isAdmin = is_admin_user();
$homePath = $isAdmin ? 'admin/index.php' : 'dashboard.php';
$logoLabel = $isAdmin ? 'GeoValuate Admin' : 'GeoValuate';
$logoIcon = $isAdmin ? 'fas fa-shield-alt' : 'fas fa-globe-americas';

$userId = (int) $_SESSION['user_id'];
$settings = get_user_setting($pdo, $userId);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        set_flash('error', 'Your session expired. Please try again.');
        redirect('settings.php');
    }

    $settings = [
        'email_notifications' => !empty($_POST['email_notifications']) ? 1 : 0,
        'valuation_updates' => !empty($_POST['valuation_updates']) ? 1 : 0,
        'market_insights' => !empty($_POST['market_insights']) ? 1 : 0,
        'preferred_property_type' => $_POST['preferred_property_type'] ?? 'house',
        'preferred_location' => $_POST['preferred_location'] ?? 'colombo'
    ];

    save_user_setting($pdo, $userId, $settings);
    create_notification($pdo, $userId, 'Settings updated', 'Your account preferences were saved successfully.', 'success');
    set_flash('success', 'Settings saved successfully.');
    redirect('settings.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - GeoValuate</title>
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
                <a href="settings.php" class="active">Settings</a>
                <a href="contact.php">Contact Us</a>
            </div>
        <?php else: ?>
            <div class="nav-links">
                <a href="dashboard.php">Home</a>
                <a href="predictions.php">Predictions</a>
                <a href="notifications.php">Notifications</a>
                <a href="discover.php">Discover</a>
                <a href="settings.php" class="active">Settings</a>
            </div>
        <?php endif; ?>
        <div class="user-profile"><i class="fas fa-user"></i></div>
    </nav>

    <div class="page-shell">
        <div class="page-header">
            <h1>Settings</h1>
            <p>Manage how GeoValuate updates you, along with the preferences that shape your default valuation experience.</p>
        </div>

        <?php render_flashes(); ?>

        <form action="settings.php" method="POST" class="grid-two">
            <input type="hidden" name="csrf_token" value="<?php echo h(csrf_token()); ?>">
            <section class="glass panel animate-fade">
                <h2 class="panel-title" style="margin-bottom: 1.2rem;">Notification preferences</h2>
                <div class="checkbox-grid">
                    <label class="check-card">
                        <span>Email notifications</span>
                        <input type="checkbox" name="email_notifications" <?php echo !empty($settings['email_notifications']) ? 'checked' : ''; ?>>
                    </label>
                    <label class="check-card">
                        <span>Valuation result updates</span>
                        <input type="checkbox" name="valuation_updates" <?php echo !empty($settings['valuation_updates']) ? 'checked' : ''; ?>>
                    </label>
                    <label class="check-card">
                        <span>Market insight reminders</span>
                        <input type="checkbox" name="market_insights" <?php echo !empty($settings['market_insights']) ? 'checked' : ''; ?>>
                    </label>
                </div>
            </section>

            <section class="glass panel animate-fade" style="animation-delay: 0.15s;">
                <h2 class="panel-title" style="margin-bottom: 1.2rem;">Default valuation preferences</h2>
                <div class="form-group">
                    <label>Preferred Property Type</label>
                    <select name="preferred_property_type" class="form-control">
                        <option value="house" <?php echo $settings['preferred_property_type'] === 'house' ? 'selected' : ''; ?>>House</option>
                        <option value="land" <?php echo $settings['preferred_property_type'] === 'land' ? 'selected' : ''; ?>>Land</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Preferred Location</label>
                    <select name="preferred_location" class="form-control">
                        <option value="colombo" <?php echo $settings['preferred_location'] === 'colombo' ? 'selected' : ''; ?>>Colombo</option>
                        <option value="kandy" <?php echo $settings['preferred_location'] === 'kandy' ? 'selected' : ''; ?>>Kandy</option>
                        <option value="galle" <?php echo $settings['preferred_location'] === 'galle' ? 'selected' : ''; ?>>Galle</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Save Settings</button>
            </section>
        </form>
    </div>
</body>
</html>

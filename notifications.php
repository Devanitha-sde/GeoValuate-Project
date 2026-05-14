<?php
require_once 'includes/config.php';
require_user_portal();

$userId = (int) $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        set_flash('error', 'Your session expired. Please try again.');
        redirect('notifications.php');
    }

    $pdo->prepare('UPDATE notifications SET is_read = 1 WHERE user_id = ?')->execute([$userId]);
    set_flash('success', 'All notifications marked as read.');
    redirect('notifications.php');
}

$stmt = $pdo->prepare('SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC');
$stmt->execute([$userId]);
$notifications = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - GeoValuate</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar glass">
        <a href="dashboard.php" class="logo"><i class="fas fa-globe-americas"></i> GeoValuate</a>
        <div class="nav-links">
            <a href="dashboard.php">Home</a>
            <a href="predictions.php">Predictions</a>
            <a href="notifications.php" class="active">Notifications</a>
            <a href="discover.php">Discover</a>
            <a href="settings.php">Settings</a>
        </div>
        <div class="user-profile"><i class="fas fa-user"></i></div>
    </nav>

    <div class="page-shell">
        <div class="page-header">
            <h1>Notifications</h1>
            <p>Track account events, valuation completions, report activity, and other updates generated inside GeoValuate.</p>
        </div>

        <?php render_flashes(); ?>

        <section class="glass panel animate-fade">
            <div class="panel-header">
                <h2 class="panel-title">Recent alerts</h2>
                <form action="notifications.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo h(csrf_token()); ?>">
                    <button type="submit" class="btn btn-outline">Mark All Read</button>
                </form>
            </div>

            <?php if (empty($notifications)): ?>
                <div class="empty-state">
                    <i class="fas fa-bell"></i>
                    <p>You do not have any notifications yet.</p>
                </div>
            <?php else: ?>
                <div class="timeline">
                    <?php foreach ($notifications as $notification): ?>
                        <div class="timeline-item">
                            <div style="display: flex; justify-content: space-between; gap: 1rem; align-items: center;">
                                <h4><?php echo h($notification['title']); ?></h4>
                                <span class="badge <?php echo !empty($notification['is_read']) ? 'badge-info' : 'badge-success'; ?>">
                                    <?php echo !empty($notification['is_read']) ? 'Read' : 'New'; ?>
                                </span>
                            </div>
                            <p><?php echo h($notification['message']); ?></p>
                            <p style="opacity: 0.7; margin-top: 0.5rem;"><?php echo date('M d, Y h:i A', strtotime($notification['created_at'])); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </div>
</body>
</html>

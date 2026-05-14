<?php
require_once 'includes/config.php';
require_user_portal();

$locationStats = $pdo->query("SELECT p.location, COUNT(*) AS total_properties, AVG(v.predicted_value) AS avg_value FROM valuations v JOIN properties p ON v.property_id = p.id GROUP BY p.location ORDER BY avg_value DESC")->fetchAll();
$recentTips = [
    'Use recent comparisons to review how location shifts affect estimated value.',
    'House valuations become more stable when building size and bedroom counts are realistic.',
    'Land valuations benefit from accurate road access and utility availability inputs.'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discover - GeoValuate</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar glass">
        <a href="dashboard.php" class="logo"><i class="fas fa-globe-americas"></i> GeoValuate</a>
        <div class="nav-links">
            <a href="dashboard.php">Home</a>
            <a href="predictions.php">Predictions</a>
            <a href="discover.php" class="active">Discover</a>
            <a href="compare.php">Compare</a>
            <a href="help.php">Help</a>
        </div>
        <div class="user-profile"><i class="fas fa-user"></i></div>
    </nav>

    <div class="page-shell">
        <div class="page-header">
            <h1>Discover</h1>
            <p>Browse valuation patterns, identify stronger-performing areas, and pick up a few practical tips for cleaner property submissions.</p>
        </div>

        <div class="grid-two">
            <section class="glass panel animate-fade">
                <h2 class="panel-title" style="margin-bottom: 1rem;">Area insights</h2>
                <?php if (empty($locationStats)): ?>
                    <div class="empty-state">
                        <i class="fas fa-map"></i>
                        <p>Area insights will appear after valuations are created.</p>
                    </div>
                <?php else: ?>
                    <div class="info-list">
                        <?php foreach ($locationStats as $stat): ?>
                            <div class="info-item">
                                <h4><?php echo h(ucfirst($stat['location'])); ?></h4>
                                <p><?php echo (int) $stat['total_properties']; ?> valuations recorded</p>
                                <p>Average estimate: <?php echo h(format_currency((float) $stat['avg_value'])); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>

            <section class="glass panel animate-fade" style="animation-delay: 0.15s;">
                <h2 class="panel-title" style="margin-bottom: 1rem;">Valuation tips</h2>
                <div class="timeline">
                    <?php foreach ($recentTips as $tip): ?>
                        <div class="timeline-item">
                            <p><?php echo h($tip); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>
    </div>
</body>
</html>

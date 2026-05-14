<?php
require_once 'includes/config.php';
require_user_portal();

$userId = (int) $_SESSION['user_id'];
$stmt = $pdo->prepare('SELECT v.*, p.type, p.location FROM valuations v JOIN properties p ON v.property_id = p.id WHERE v.user_id = ? ORDER BY v.created_at DESC');
$stmt->execute([$userId]);
$valuations = $stmt->fetchAll();

$count = count($valuations);
$totalValue = array_sum(array_map(static fn($item) => (float) $item['predicted_value'], $valuations));
$averageValue = $count ? $totalValue / $count : 0;
$avgConfidence = $count ? array_sum(array_map(static fn($item) => (float) $item['confidence_score'], $valuations)) / $count : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Predictions - GeoValuate</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar glass">
        <a href="dashboard.php" class="logo"><i class="fas fa-globe-americas"></i> GeoValuate</a>
        <div class="nav-links">
            <a href="dashboard.php">Home</a>
            <a href="predictions.php" class="active">Predictions</a>
            <a href="compare.php">Compare</a>
            <a href="reports.php">Reports</a>
            <a href="contact.php">Contact Us</a>
        </div>
        <div class="user-profile"><i class="fas fa-user"></i></div>
    </nav>

    <div class="page-shell">
        <div class="page-header">
            <h1>Prediction Overview</h1>
            <p>Review your valuation output at a glance, from total prediction volume to average estimate size and confidence.</p>
        </div>

        <div class="grid-three animate-fade">
            <div class="glass stat-card">
                <span class="stat-label">Total predictions</span>
                <span class="stat-value"><?php echo $count; ?></span>
            </div>
            <div class="glass stat-card">
                <span class="stat-label">Average estimated value</span>
                <span class="stat-value"><?php echo h(format_currency($averageValue)); ?></span>
            </div>
            <div class="glass stat-card">
                <span class="stat-label">Average confidence score</span>
                <span class="stat-value"><?php echo number_format($avgConfidence, 1); ?>%</span>
            </div>
        </div>

        <section class="glass panel animate-fade" style="margin-top: 2rem; animation-delay: 0.15s;">
            <div class="panel-header">
                <h2 class="panel-title">Recent predictions</h2>
                <a href="valuation.php" class="btn btn-primary">New Valuation</a>
            </div>

            <?php if (empty($valuations)): ?>
                <div class="empty-state">
                    <i class="fas fa-chart-line"></i>
                    <p>No predictions available yet. Start a valuation to populate this page.</p>
                </div>
            <?php else: ?>
                <table class="content-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Property Type</th>
                            <th>Location</th>
                            <th>Estimated Value</th>
                            <th>Confidence</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($valuations as $valuation): ?>
                            <tr>
                                <td><?php echo date('Y-m-d', strtotime($valuation['created_at'])); ?></td>
                                <td><?php echo h(ucfirst($valuation['type'])); ?></td>
                                <td><?php echo h(ucfirst($valuation['location'])); ?></td>
                                <td><?php echo h(format_currency((float) $valuation['predicted_value'])); ?></td>
                                <td><?php echo (int) $valuation['confidence_score']; ?>%</td>
                                <td><a href="results.php?id=<?php echo (int) $valuation['id']; ?>" class="btn btn-outline">View</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>
    </div>
</body>
</html>

<?php
require_once 'includes/config.php';
require_user_portal();

$userId = (int) $_SESSION['user_id'];
$stmt = $pdo->prepare('SELECT v.*, p.type, p.location, p.details FROM valuations v JOIN properties p ON v.property_id = p.id WHERE v.user_id = ? ORDER BY v.created_at DESC LIMIT 10');
$stmt->execute([$userId]);
$valuations = $stmt->fetchAll();

$first = $valuations[0] ?? null;
$second = $valuations[1] ?? null;

if (isset($_GET['left'], $_GET['right'])) {
    $requested = [];
    foreach ($valuations as $valuation) {
        $requested[$valuation['id']] = $valuation;
    }
    $first = $requested[(int) $_GET['left']] ?? $first;
    $second = $requested[(int) $_GET['right']] ?? $second;
}

$firstDetails = $first ? (json_decode($first['details'], true) ?: []) : [];
$secondDetails = $second ? (json_decode($second['details'], true) ?: []) : [];
$firstDetails = array_filter($firstDetails, static fn($key) => $key !== 'csrf_token', ARRAY_FILTER_USE_KEY);
$secondDetails = array_filter($secondDetails, static fn($key) => $key !== 'csrf_token', ARRAY_FILTER_USE_KEY);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compare Valuations - GeoValuate</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar glass">
        <a href="dashboard.php" class="logo"><i class="fas fa-globe-americas"></i> GeoValuate</a>
        <div class="nav-links">
            <a href="dashboard.php">Home</a>
            <a href="predictions.php">Predictions</a>
            <a href="compare.php" class="active">Compare</a>
            <a href="reports.php">Reports</a>
            <a href="help.php">Help</a>
        </div>
        <div class="user-profile"><i class="fas fa-user"></i></div>
    </nav>

    <div class="page-shell">
        <div class="page-header">
            <h1>Compare Valuations</h1>
            <p>Put two recent valuations side by side to see how the estimate, confidence, and key property characteristics differ.</p>
        </div>

        <?php if (!$first || !$second): ?>
            <div class="glass panel empty-state animate-fade">
                <i class="fas fa-code-compare"></i>
                <p>Create at least two valuations to unlock comparison mode.</p>
                <a href="valuation.php" class="btn btn-primary">Start Valuation</a>
            </div>
        <?php else: ?>
            <section class="glass panel animate-fade">
                <div class="panel-header">
                    <h2 class="panel-title">Comparison snapshot</h2>
                    <span class="pill"><i class="fas fa-code-compare"></i> Latest two valuations</span>
                </div>
                <table class="content-table">
                    <thead>
                        <tr>
                            <th>Field</th>
                            <th><?php echo h(ucfirst($first['type']) . ' - ' . ucfirst($first['location'])); ?></th>
                            <th><?php echo h(ucfirst($second['type']) . ' - ' . ucfirst($second['location'])); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Estimated Value</td>
                            <td><?php echo h(format_currency((float) $first['predicted_value'])); ?></td>
                            <td><?php echo h(format_currency((float) $second['predicted_value'])); ?></td>
                        </tr>
                        <tr>
                            <td>Confidence Score</td>
                            <td><?php echo (int) $first['confidence_score']; ?>%</td>
                            <td><?php echo (int) $second['confidence_score']; ?>%</td>
                        </tr>
                        <tr>
                            <td>Model Used</td>
                            <td><?php echo h($first['model_used']); ?></td>
                            <td><?php echo h($second['model_used']); ?></td>
                        </tr>
                        <tr>
                            <td>Key Details</td>
                            <td><?php echo h(implode(', ', array_map(static fn($key, $value) => ucfirst(str_replace('_', ' ', (string) $key)) . ': ' . $value, array_keys($firstDetails), $firstDetails))); ?></td>
                            <td><?php echo h(implode(', ', array_map(static fn($key, $value) => ucfirst(str_replace('_', ' ', (string) $key)) . ': ' . $value, array_keys($secondDetails), $secondDetails))); ?></td>
                        </tr>
                    </tbody>
                </table>
            </section>
        <?php endif; ?>
    </div>
</body>
</html>

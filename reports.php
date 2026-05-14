<?php
require_once 'includes/config.php';
require_user_portal();

$userId = (int) $_SESSION['user_id'];
$backfillStmt = $pdo->prepare('SELECT id FROM valuations WHERE user_id = ? AND id NOT IN (SELECT valuation_id FROM reports WHERE user_id = ?)');
$backfillStmt->execute([$userId, $userId]);
foreach ($backfillStmt->fetchAll() as $missingReport) {
    create_report_record($pdo, (int) $missingReport['id'], $userId);
}

$stmt = $pdo->prepare('SELECT r.id AS report_id, r.file_path, r.created_at AS report_created_at, v.id AS valuation_id, v.predicted_value, v.confidence_score, v.model_used, v.created_at AS valuation_created_at, p.type, p.location FROM reports r JOIN valuations v ON r.valuation_id = v.id JOIN properties p ON v.property_id = p.id WHERE r.user_id = ? ORDER BY r.created_at DESC');
$stmt->execute([$userId]);
$reports = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - GeoValuate</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar glass">
        <a href="dashboard.php" class="logo">
            <i class="fas fa-globe-americas"></i> GeoValuate
        </a>
        <div class="nav-links">
            <a href="dashboard.php">Home</a>
            <a href="history.php">My Property</a>
            <a href="reports.php" class="active">Reports</a>
            <a href="contact.php">Contact Us</a>
        </div>
    </nav>

    <div class="page-shell">
        <div class="page-header">
            <h1>Report Generation</h1>
            <p>Each completed valuation creates a linked report record so you can open, export, and revisit your findings from one place.</p>
        </div>

        <section class="glass panel animate-fade">
            <div class="panel-header">
                <h2 class="panel-title">Available reports</h2>
                <a href="valuation.php" class="btn btn-primary">New Valuation</a>
            </div>

            <?php if (empty($reports)): ?>
                <div class="empty-state">
                    <i class="fas fa-file-lines"></i>
                    <p>No report data is available yet. Complete a valuation to generate your first report.</p>
                </div>
            <?php else: ?>
                <div class="report-list">
                    <?php foreach ($reports as $report): ?>
                        <div class="report-item glass-card">
                            <div class="report-info">
                                <h4><?php echo h(ucfirst($report['type'])); ?> in <?php echo h(ucfirst($report['location'])); ?></h4>
                                <p>Report ID: #GV-<?php echo str_pad((string) $report['report_id'], 5, '0', STR_PAD_LEFT); ?></p>
                                <p>Valued on: <?php echo date('F d, Y', strtotime($report['valuation_created_at'])); ?> | Estimated Value: <?php echo h(format_currency((float) $report['predicted_value'])); ?> | Confidence: <?php echo (int) $report['confidence_score']; ?>%</p>
                            </div>
                            <div class="actions-row">
                                <a href="view_report.php?report_id=<?php echo (int) $report['report_id']; ?>" class="btn btn-primary">View Report</a>
                                <a href="export_excel.php?report_id=<?php echo (int) $report['report_id']; ?>" class="btn btn-outline">Export Excel</a>
                                <a href="export_pdf.php?report_id=<?php echo (int) $report['report_id']; ?>" class="btn btn-outline">Print PDF View</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </div>
</body>
</html>

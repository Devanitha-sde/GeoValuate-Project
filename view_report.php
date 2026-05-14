<?php
require_once 'includes/config.php';
require_login();

$reportId = (int) ($_GET['report_id'] ?? 0);
$valuationId = (int) ($_GET['valuation_id'] ?? $_GET['id'] ?? 0);
$isAdmin = ($_SESSION['role'] ?? '') === 'admin';

if ($reportId > 0) {
    if ($isAdmin) {
        $stmt = $pdo->prepare('SELECT r.id AS report_id, r.created_at AS report_created_at, v.id AS valuation_id, v.user_id AS valuation_user_id, v.predicted_value, v.confidence_score, v.model_used, v.created_at AS valuation_created_at, p.type, p.location, p.details, u.name AS owner_name FROM reports r JOIN valuations v ON r.valuation_id = v.id JOIN properties p ON v.property_id = p.id JOIN users u ON p.owner_id = u.id WHERE r.id = ? LIMIT 1');
        $stmt->execute([$reportId]);
    } else {
        $stmt = $pdo->prepare('SELECT r.id AS report_id, r.created_at AS report_created_at, v.id AS valuation_id, v.user_id AS valuation_user_id, v.predicted_value, v.confidence_score, v.model_used, v.created_at AS valuation_created_at, p.type, p.location, p.details, u.name AS owner_name FROM reports r JOIN valuations v ON r.valuation_id = v.id JOIN properties p ON v.property_id = p.id JOIN users u ON p.owner_id = u.id WHERE r.id = ? AND r.user_id = ? LIMIT 1');
        $stmt->execute([$reportId, (int) $_SESSION['user_id']]);
    }
} else {
    if ($isAdmin) {
        $stmt = $pdo->prepare('SELECT r.id AS report_id, r.created_at AS report_created_at, v.id AS valuation_id, v.user_id AS valuation_user_id, v.predicted_value, v.confidence_score, v.model_used, v.created_at AS valuation_created_at, p.type, p.location, p.details, u.name AS owner_name FROM valuations v JOIN properties p ON v.property_id = p.id JOIN users u ON p.owner_id = u.id LEFT JOIN reports r ON r.valuation_id = v.id WHERE v.id = ? LIMIT 1');
        $stmt->execute([$valuationId]);
    } else {
        $stmt = $pdo->prepare('SELECT r.id AS report_id, r.created_at AS report_created_at, v.id AS valuation_id, v.user_id AS valuation_user_id, v.predicted_value, v.confidence_score, v.model_used, v.created_at AS valuation_created_at, p.type, p.location, p.details, u.name AS owner_name FROM valuations v JOIN properties p ON v.property_id = p.id JOIN users u ON p.owner_id = u.id LEFT JOIN reports r ON r.valuation_id = v.id AND r.user_id = v.user_id WHERE v.id = ? AND v.user_id = ? LIMIT 1');
        $stmt->execute([$valuationId, (int) $_SESSION['user_id']]);
    }
}

$report = $stmt->fetch();

if (!$report) {
    redirect('error.php?title=' . urlencode('Report not found') . '&message=' . urlencode('The requested report could not be located or is not available to your account.') . '&back=' . urlencode('reports.php'));
}

if (empty($report['report_id'])) {
    $report['report_id'] = create_report_record($pdo, (int) $report['valuation_id'], (int) $report['valuation_user_id']);
}

$details = json_decode($report['details'], true) ?: [];
$backLink = $isAdmin ? 'admin/reports.php' : 'reports.php';
$modelLabel = trim((string) ($report['model_used'] ?? ''));
if ($modelLabel === '') {
    $modelLabel = 'GeoValuate Weighted Demo Model';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Valuation Report - <?php echo (int) $report['report_id']; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #f4f7f6; color: #333; }
        body::before { display: none; }
        .report-paper {
            max-width: 960px;
            margin: 3rem auto;
            background: #fff;
            padding: 4rem;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            border-top: 10px solid var(--primary-color);
        }
        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #eee;
            padding-bottom: 2rem;
            margin-bottom: 2rem;
        }
        .report-header .logo { color: var(--primary-color); font-size: 2rem; text-decoration: none; font-weight: 700; }
        .report-title { text-align: center; margin: 3rem 0; }
        .report-title h1 { font-size: 2.5rem; color: #2c3e50; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 3rem; }
        .info-item h5 { color: var(--primary-color); margin-bottom: 0.5rem; text-transform: uppercase; font-size: 0.9rem; }
        .info-item p { font-size: 1.1rem; font-weight: 500; }
        .valuation-summary {
            background: #fdfaf3;
            padding: 2rem;
            border-radius: 15px;
            border: 1px solid #f1e4c3;
            text-align: center;
            margin-bottom: 3rem;
        }
        .valuation-summary h2 { font-size: 3rem; color: var(--primary-color); margin: 1rem 0; }
        .details-table { width: 100%; border-collapse: collapse; }
        .details-table th, .details-table td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        .details-table th { color: #7f8c8d; font-weight: 500; width: 30%; }
        @media print {
            .no-print { display: none; }
            body { background: #fff; }
            .report-paper { box-shadow: none; margin: 0; width: 100%; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="padding: 1rem; text-align: center; background: #fff; border-bottom: 1px solid #eee;">
        <button onclick="window.print()" class="btn btn-primary"><i class="fas fa-print"></i> Print as PDF</button>
        <a href="<?php echo h($backLink); ?>" class="btn btn-outline" style="color: #333; border-color: #ccc; margin-left: 1rem;">Back to Reports</a>
        <a href="export_excel.php?report_id=<?php echo (int) $report['report_id']; ?>" class="btn btn-outline" style="color: #333; border-color: #ccc; margin-left: 1rem;">Export Excel</a>
    </div>

    <div class="report-paper">
        <div class="report-header">
            <div class="logo"><i class="fas fa-globe-americas"></i> GeoValuate</div>
            <div style="text-align: right;">
                <p><strong>Date:</strong> <?php echo date('d/m/Y'); ?></p>
                <p><strong>Report ID:</strong> #GV-<?php echo str_pad((string) $report['report_id'], 5, '0', STR_PAD_LEFT); ?></p>
            </div>
        </div>

        <div class="report-title">
            <h1>Property Valuation Certificate</h1>
            <p>Formal assessment of market value based on structured property inputs and the simulated <?php echo h($modelLabel); ?> valuation workflow.</p>
        </div>

        <div class="info-grid">
            <div class="info-item">
                <h5>Client Name</h5>
                <p><?php echo h($report['owner_name']); ?></p>
            </div>
            <div class="info-item">
                <h5>Property Type</h5>
                <p><?php echo h(ucfirst($report['type'])); ?></p>
            </div>
            <div class="info-item">
                <h5>Location</h5>
                <p><?php echo h(ucfirst($report['location'])); ?></p>
            </div>
            <div class="info-item">
                <h5>Valuation Model</h5>
                <p><?php echo h($modelLabel); ?></p>
            </div>
        </div>

        <div class="valuation-summary">
            <h5>Estimated Market Value</h5>
            <h2><?php echo h(format_currency((float) $report['predicted_value'])); ?></h2>
            <p>Confidence Level: <strong><?php echo (int) $report['confidence_score']; ?>%</strong></p>
        </div>

        <div class="property-details">
            <h4 style="margin-bottom: 1rem; border-left: 5px solid var(--primary-color); padding-left: 1rem;">Property Characteristics</h4>
            <table class="details-table">
                <?php foreach ($details as $key => $value): ?>
                    <?php if (in_array($key, ['csrf_token', 'type', 'location'], true)) { continue; } ?>
                    <tr>
                        <th><?php echo h(ucfirst(str_replace('_', ' ', (string) $key))); ?></th>
                        <td><?php echo h(is_array($value) ? implode(', ', $value) : (string) $value); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <div style="margin-top: 5rem; border-top: 1px solid #eee; padding-top: 2rem; display: flex; justify-content: space-between;">
            <div>
                <p style="font-size: 0.8rem; color: #7f8c8d;">This report was generated automatically by the GeoValuate system.</p>
                <p style="font-size: 0.8rem; color: #7f8c8d;">© 2026 GeoValuate Real Estate Solutions.</p>
            </div>
            <div style="text-align: center;">
                <div style="width: 150px; border-bottom: 1px solid #333; margin-bottom: 0.5rem;"></div>
                <p style="font-size: 0.9rem; font-weight: 600;">Authorized Signature</p>
            </div>
        </div>
    </div>
</body>
</html>

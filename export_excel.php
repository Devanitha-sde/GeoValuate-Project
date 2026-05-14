<?php
require_once 'includes/config.php';
require_login();

$reportId = (int) ($_GET['report_id'] ?? 0);
$valuationId = (int) ($_GET['valuation_id'] ?? 0);
$isAdmin = ($_SESSION['role'] ?? '') === 'admin';

if ($reportId > 0) {
    if ($isAdmin) {
        $stmt = $pdo->prepare('SELECT r.id AS report_id, v.id AS valuation_id, v.predicted_value, v.confidence_score, v.model_used, v.created_at AS valuation_created_at, p.type, p.location, p.details, u.name AS owner_name, u.email AS owner_email FROM reports r JOIN valuations v ON r.valuation_id = v.id JOIN properties p ON v.property_id = p.id JOIN users u ON p.owner_id = u.id WHERE r.id = ? LIMIT 1');
        $stmt->execute([$reportId]);
    } else {
        $stmt = $pdo->prepare('SELECT r.id AS report_id, v.id AS valuation_id, v.predicted_value, v.confidence_score, v.model_used, v.created_at AS valuation_created_at, p.type, p.location, p.details, u.name AS owner_name, u.email AS owner_email FROM reports r JOIN valuations v ON r.valuation_id = v.id JOIN properties p ON v.property_id = p.id JOIN users u ON p.owner_id = u.id WHERE r.id = ? AND r.user_id = ? LIMIT 1');
        $stmt->execute([$reportId, (int) $_SESSION['user_id']]);
    }
} else {
    if ($isAdmin) {
        $stmt = $pdo->prepare('SELECT r.id AS report_id, v.id AS valuation_id, v.predicted_value, v.confidence_score, v.model_used, v.created_at AS valuation_created_at, p.type, p.location, p.details, u.name AS owner_name, u.email AS owner_email FROM valuations v JOIN properties p ON v.property_id = p.id JOIN users u ON p.owner_id = u.id LEFT JOIN reports r ON r.valuation_id = v.id WHERE v.id = ? LIMIT 1');
        $stmt->execute([$valuationId]);
    } else {
        $stmt = $pdo->prepare('SELECT r.id AS report_id, v.id AS valuation_id, v.predicted_value, v.confidence_score, v.model_used, v.created_at AS valuation_created_at, p.type, p.location, p.details, u.name AS owner_name, u.email AS owner_email FROM valuations v JOIN properties p ON v.property_id = p.id JOIN users u ON p.owner_id = u.id LEFT JOIN reports r ON r.valuation_id = v.id AND r.user_id = v.user_id WHERE v.id = ? AND v.user_id = ? LIMIT 1');
        $stmt->execute([$valuationId, (int) $_SESSION['user_id']]);
    }
}

$report = $stmt->fetch();
if (!$report) {
    redirect('error.php?title=' . urlencode('Export unavailable') . '&message=' . urlencode('The requested report could not be exported.') . '&back=' . urlencode('reports.php'));
}

$details = json_decode($report['details'], true) ?: [];
$filename = 'geovaluate-report-' . ((int) ($report['report_id'] ?: $report['valuation_id'])) . '.xls';

header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$output = fopen('php://output', 'w');
fputcsv($output, ['Report ID', 'Client Name', 'Client Email', 'Property Type', 'Location', 'Estimated Value', 'Confidence Score', 'Model Used', 'Valuation Date'], "\t");
fputcsv($output, [
    'GV-' . str_pad((string) ((int) ($report['report_id'] ?: $report['valuation_id'])), 5, '0', STR_PAD_LEFT),
    $report['owner_name'],
    $report['owner_email'],
    ucfirst($report['type']),
    ucfirst($report['location']),
    (string) $report['predicted_value'],
    (string) $report['confidence_score'],
    $report['model_used'],
    $report['valuation_created_at']
], "\t");
fputcsv($output, [], "\t");
fputcsv($output, ['Property Detail', 'Value'], "\t");

foreach ($details as $key => $value) {
    if (in_array($key, ['csrf_token'], true)) {
        continue;
    }
    fputcsv($output, [ucfirst(str_replace('_', ' ', (string) $key)), is_array($value) ? implode(', ', $value) : (string) $value], "\t");
}

fclose($output);
exit();
?>

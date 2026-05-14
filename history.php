<?php 
require_once 'includes/config.php'; 
require_user_portal();

$stmt = $pdo->prepare("SELECT v.*, p.type, p.location, r.id AS report_id FROM valuations v JOIN properties p ON v.property_id = p.id LEFT JOIN reports r ON r.valuation_id = v.id AND r.user_id = v.user_id WHERE v.user_id = ? ORDER BY v.created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$valuations = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Valuation History - GeoValuate</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .history-container {
            padding: 2rem 4rem;
        }
        .history-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 1rem;
            margin-top: 2rem;
        }
        .history-table th {
            padding: 1.5rem;
            text-align: left;
            font-size: 1.1rem;
            color: var(--primary-color);
        }
        .history-table tr {
            transition: var(--transition);
        }
        .history-table td {
            padding: 1.5rem;
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(5px);
        }
        .history-table td:first-child {
            border-radius: 20px 0 0 20px;
        }
        .history-table td:last-child {
            border-radius: 0 20px 20px 0;
        }
        .history-table tr:hover td {
            background: rgba(255,255,255,0.1);
        }
    </style>
</head>
<body>
    <nav class="navbar glass">
        <a href="dashboard.php" class="logo">
            <i class="fas fa-globe-americas"></i> GeoValuate
        </a>
        <div class="nav-links">
            <a href="dashboard.php">Home</a>
            <a href="history.php" class="active">My Property</a>
            <a href="help.php">Help</a>
            <a href="contact.php">Contact Us</a>
        </div>
        <div class="user-profile">
            <i class="fas fa-user"></i>
        </div>
    </nav>

    <div class="history-container">
        <h1 style="font-size: 3rem; margin-top: 2rem;">Valuation History</h1>

        <table class="history-table animate-fade">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Location</th>
                    <th>Predicted Value</th>
                    <th>Model Used</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($valuations as $v): ?>
                <tr>
                    <td><?php echo date('Y-m-d', strtotime($v['created_at'])); ?></td>
                    <td><?php echo ucfirst($v['type']); ?></td>
                    <td><?php echo ucfirst($v['location']); ?></td>
                    <td style="color: var(--primary-color); font-weight: 600;">Rs. <?php echo number_format($v['predicted_value']); ?></td>
                    <td><?php echo $v['model_used']; ?></td>
                    <td>
                        <a href="view_report.php?<?php echo !empty($v['report_id']) ? 'report_id=' . (int) $v['report_id'] : 'valuation_id=' . (int) $v['id']; ?>" class="btn btn-primary" style="padding: 8px 20px; font-size: 0.9rem;">View Report</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($valuations)): ?>
                <tr>
                    <td colspan="6" style="text-align: center; padding: 3rem;">No valuations found. <a href="valuation.php" style="color: var(--primary-color);">Start one now!</a></td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

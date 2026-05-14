<?php
require_once 'includes/config.php';
require_user_portal();

function numeric_value($value): float
{
    return is_numeric($value) ? (float) $value : 0.0;
}

function valuation_location_config(string $location): array
{
    $defaults = [
        'label' => ucfirst($location),
        'house_base' => 6000000,
        'land_rate' => 650000,
        'build_rate' => 15000,
        'premium' => 1.0,
        'confidence' => 84
    ];

    $catalog = [
        'colombo' => [
            'label' => 'Colombo',
            'house_base' => 8500000,
            'land_rate' => 950000,
            'build_rate' => 19000,
            'premium' => 1.14,
            'confidence' => 92
        ],
        'kandy' => [
            'label' => 'Kandy',
            'house_base' => 6400000,
            'land_rate' => 680000,
            'build_rate' => 15500,
            'premium' => 1.02,
            'confidence' => 88
        ],
        'galle' => [
            'label' => 'Galle',
            'house_base' => 5900000,
            'land_rate' => 590000,
            'build_rate' => 14500,
            'premium' => 0.98,
            'confidence' => 86
        ]
    ];

    return $catalog[$location] ?? $defaults;
}

function select_demo_model_name(): string
{
    $demoModels = [
        'GeoValuate Random Forest Model',
        'GeoValuate XGBoost Model',
        'GeoValuate Linear Regression Model',
        'GeoValuate Decision Tree Model'
    ];

    return $demoModels[random_int(0, count($demoModels) - 1)];
}

function calculate_house_valuation(array $data, array $locationConfig): array
{
    $locationSize = max(1, numeric_value($data['location_size'] ?? 0));
    $buildingSize = max(1, numeric_value($data['building_size'] ?? 0));
    $bedrooms = max(0, (int) ($data['bedrooms'] ?? 0));
    $bathrooms = max(0, (int) ($data['bathrooms'] ?? 0));
    $yearBuilt = max(1950, (int) ($data['year_built'] ?? date('Y')));
    $age = max(0, (int) date('Y') - $yearBuilt);

    $landComponent = $locationSize * ($locationConfig['land_rate'] * 0.45);
    $buildingComponent = $buildingSize * $locationConfig['build_rate'];
    $bedroomComponent = $bedrooms * 475000;
    $bathroomComponent = $bathrooms * 325000;
    $ageFactor = max(0.72, 1 - ($age * 0.006));

    $value = ($locationConfig['house_base'] + $landComponent + $buildingComponent + $bedroomComponent + $bathroomComponent) * $locationConfig['premium'] * $ageFactor;
    $value = max($value, 4500000);

    $confidence = min(95, max(78, (int) round($locationConfig['confidence'] + min(3, $bedrooms) + min(2, $bathrooms) - min(6, $age / 10))));

    $contributions = [
        'Land footprint' => $landComponent,
        'Building size' => $buildingComponent,
        'Location premium' => $locationConfig['house_base'] * ($locationConfig['premium'] - 0.9),
        'Bedrooms' => $bedroomComponent,
        'Bathrooms' => $bathroomComponent
    ];

    $explanations = [
        'Larger usable land area increased the estimate.',
        'Building size contributed strongly to the final price.',
        'The selected location carried a measurable market premium.'
    ];

    if ($age > 20) {
        $explanations[] = 'The age of the property moderated the value slightly.';
    } else {
        $explanations[] = 'The relatively newer build year supported the estimate.';
    }

    return [
        'predicted_value' => round($value, 2),
        'confidence_score' => $confidence,
        'contributions' => $contributions,
        'explanations' => $explanations
    ];
}

function calculate_land_valuation(array $data, array $locationConfig): array
{
    $landSize = max(1, numeric_value($data['land_size'] ?? 0));
    $landType = $data['land_type'] ?? 'residential';
    $roadAccess = $data['road_access'] ?? 'good';
    $utilities = $data['utilities'] ?? 'partial';
    $distance = max(0, numeric_value(preg_replace('/[^0-9.]/', '', (string) ($data['distance'] ?? '0'))));

    $landTypeMultiplier = [
        'residential' => 1.00,
        'commercial' => 1.24,
        'agricultural' => 0.82
    ][$landType] ?? 1.0;

    $roadMultiplier = [
        'excellent' => 1.12,
        'good' => 1.05,
        'fair' => 0.96
    ][$roadAccess] ?? 1.0;

    $utilityMultiplier = [
        'all' => 1.06,
        'partial' => 1.02,
        'none' => 0.93
    ][$utilities] ?? 1.0;

    $distancePenalty = min(0.12, $distance * 0.01);
    $baseLand = $landSize * $locationConfig['land_rate'];
    $value = ($baseLand * $landTypeMultiplier * $roadMultiplier * $utilityMultiplier * (1 - $distancePenalty)) + ($locationConfig['house_base'] * 0.15);
    $value = max($value, 2500000);

    $confidence = min(94, max(76, (int) round($locationConfig['confidence'] + ($roadMultiplier * 3) + ($utilityMultiplier * 2) - ($distancePenalty * 20))));

    $contributions = [
        'Land size' => $baseLand,
        'Land type' => $baseLand * ($landTypeMultiplier - 0.85),
        'Road access' => $baseLand * ($roadMultiplier - 0.9),
        'Utilities' => $baseLand * ($utilityMultiplier - 0.9),
        'Location demand' => $locationConfig['house_base'] * 0.12
    ];

    $explanations = [
        'The parcel size was the strongest driver in the estimate.',
        'Road access and utility availability shifted the marketability of the land.',
        'Location demand influenced the underlying rate applied to the site.'
    ];

    if ($distance > 0) {
        $explanations[] = 'Distance from key amenities introduced a modest adjustment.';
    }

    return [
        'predicted_value' => round($value, 2),
        'confidence_score' => $confidence,
        'contributions' => $contributions,
        'explanations' => $explanations
    ];
}

function chart_payload(array $contributions, int $confidence): array
{
    $filtered = [];
    foreach ($contributions as $label => $value) {
        $filtered[$label] = abs((float) $value);
    }

    arsort($filtered);
    $top = array_slice($filtered, 0, 5, true);
    $sum = array_sum($top) ?: 1;

    $barLabels = array_keys($top);
    $barData = array_map(static fn($value) => round(($value / $sum) * 100, 1), array_values($top));

    $probability = round($confidence / 100, 2);
    $lineData = [
        round(max(0.2, $probability - 0.35), 2),
        round(max(0.28, $probability - 0.24), 2),
        round(max(0.36, $probability - 0.14), 2),
        round(max(0.48, $probability - 0.07), 2),
        round(max(0.58, $probability - 0.02), 2),
        $probability
    ];

    return [
        'bar_labels' => $barLabels,
        'bar_data' => $barData,
        'line_data' => $lineData
    ];
}

$valuation = null;
$details = [];
$reportId = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        set_flash('error', 'Your session expired. Please start the valuation again.');
        redirect('valuation.php');
    }

    $type = $_POST['type'] ?? 'house';
    $location = $_POST['location'] ?? 'colombo';
    $locationConfig = valuation_location_config($location);
    $details = $_POST;
    unset($details['csrf_token']);

    if ($type === 'land') {
        $result = calculate_land_valuation($_POST, $locationConfig);
    } else {
        $type = 'house';
        $result = calculate_house_valuation($_POST, $locationConfig);
    }

    $result['model_used'] = select_demo_model_name();

    $details['type'] = $type;
    $details['location'] = $location;

    $stmt = $pdo->prepare('INSERT INTO properties (owner_id, type, location, details, status) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([
        (int) $_SESSION['user_id'],
        $type,
        $location,
        json_encode($details, JSON_UNESCAPED_UNICODE),
        'valued'
    ]);
    $propertyId = (int) $pdo->lastInsertId();

    $stmt = $pdo->prepare('INSERT INTO valuations (property_id, user_id, predicted_value, confidence_score, model_used) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([
        $propertyId,
        (int) $_SESSION['user_id'],
        $result['predicted_value'],
        $result['confidence_score'],
        $result['model_used']
    ]);
    $valuationId = (int) $pdo->lastInsertId();

    $reportId = create_report_record($pdo, $valuationId, (int) $_SESSION['user_id']);
    create_notification($pdo, (int) $_SESSION['user_id'], 'Valuation completed', 'A new ' . ucfirst($type) . ' valuation for ' . ucfirst($location) . ' has been saved.', 'success');
    create_notification($pdo, (int) $_SESSION['user_id'], 'Report ready', 'Your valuation report is now available to view or export.', 'info');

    redirect('results.php?id=' . $valuationId . '&report_id=' . $reportId);
}

$valuationId = (int) ($_GET['id'] ?? $_GET['valuation_id'] ?? 0);
if ($valuationId <= 0) {
    set_flash('error', 'No valuation result was selected.');
    redirect('history.php');
}

$stmt = $pdo->prepare('SELECT v.*, p.type, p.location, p.details, r.id AS report_id FROM valuations v JOIN properties p ON v.property_id = p.id LEFT JOIN reports r ON r.valuation_id = v.id AND r.user_id = v.user_id WHERE v.id = ? AND v.user_id = ? LIMIT 1');
$stmt->execute([$valuationId, (int) $_SESSION['user_id']]);
$valuation = $stmt->fetch();

if (!$valuation) {
    redirect('error.php?title=' . urlencode('Valuation not found') . '&message=' . urlencode('The selected valuation could not be located for your account.') . '&back=' . urlencode('history.php'));
}

$details = json_decode($valuation['details'], true) ?: [];
$locationConfig = valuation_location_config($valuation['location']);
$recomputed = $valuation['type'] === 'land'
    ? calculate_land_valuation($details, $locationConfig)
    : calculate_house_valuation($details, $locationConfig);
$charts = chart_payload($recomputed['contributions'], (int) $valuation['confidence_score']);
$locationMeta = location_coordinates($valuation['location']);
$reportId = (int) ($valuation['report_id'] ?? ($_GET['report_id'] ?? 0));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Valuation Results - GeoValuate</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <style>
        .results-container {
            padding: 2rem 4rem;
            text-align: center;
        }
        .results-grid {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-top: 3rem;
            flex-wrap: wrap;
        }
        .result-card {
            width: 320px;
            padding: 2.5rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .result-label {
            font-size: 1.3rem;
            font-weight: 500;
        }
        .result-value {
            font-size: 2rem;
            font-weight: 600;
            color: #ff6b6b;
        }
        .xai-container {
            margin-top: 4rem;
            max-width: 1100px;
            margin-left: auto;
            margin-right: auto;
        }
        .xai-header {
            background: var(--primary-color);
            color: #000;
            border-radius: 20px;
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            display: inline-block;
            padding: 10px 60px;
        }
        .xai-content {
            background: #0f172a;
            border-radius: 20px;
            padding: 2rem;
            text-align: left;
            border: 1px solid rgba(255,255,255,0.1);
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

    <div class="results-container">
        <h1 style="font-size: 3.5rem; margin-top: 2rem;">Precision in Every Estimate</h1>

        <div class="actions-row" style="justify-content: center; margin-top: 1.5rem;">
            <?php if ($reportId > 0): ?>
                <a href="view_report.php?report_id=<?php echo $reportId; ?>" class="btn btn-primary">Open Report</a>
                <a href="export_excel.php?report_id=<?php echo $reportId; ?>" class="btn btn-outline">Export Excel</a>
                <a href="export_pdf.php?report_id=<?php echo $reportId; ?>" class="btn btn-outline">Print PDF View</a>
            <?php endif; ?>
        </div>

        <div class="results-grid animate-fade">
            <div class="result-card glass">
                <span class="result-label">Predicted Property Value</span>
                <span class="result-value"><?php echo h(format_currency((float) $valuation['predicted_value'])); ?></span>
            </div>
            <div class="result-card glass">
                <span class="result-label">Confidence Score</span>
                <span class="result-value"><?php echo (int) $valuation['confidence_score']; ?>%</span>
            </div>
            <div class="result-card glass">
                <span class="result-label">Model Used</span>
                <span class="result-value" style="font-size: 1.4rem;"><?php echo h($valuation['model_used']); ?></span>
            </div>
        </div>

        <div class="xai-container animate-fade" style="animation-delay: 0.25s;">
            <div class="xai-header">Property Location (GIS)</div>
            <div class="xai-content" style="height: 400px; padding: 0;">
                <div id="map" style="width: 100%; height: 100%; border-radius: 20px;"></div>
            </div>
        </div>

        <div class="xai-container animate-fade" style="animation-delay: 0.45s;">
            <div class="xai-header">Model Insight</div>
            <div class="xai-content">
                <div style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 2rem;">
                    <div>
                        <h4 style="margin-bottom: 1rem;">Confidence Progression</h4>
                        <div id="chart-line"></div>
                    </div>
                    <div>
                        <h4 style="margin-bottom: 1rem;">Top Contributing Features</h4>
                        <div id="chart-bar"></div>
                    </div>
                </div>

                <div class="glass-card" style="margin-top: 2rem; padding: 1.5rem; background: rgba(255,255,255,0.05);">
                    <h5 style="color: var(--primary-color); margin-bottom: 1rem;">Explanation for Current Prediction</h5>
                    <ul style="list-style: none; display: flex; flex-direction: column; gap: 10px;">
                        <?php foreach ($recomputed['explanations'] as $explanation): ?>
                            <li><span style="color: #4ade80;">•</span> <?php echo h($explanation); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        const lineOptions = {
            series: [{
                name: 'Confidence',
                data: <?php echo json_encode($charts['line_data']); ?>
            }],
            chart: { height: 250, type: 'line', toolbar: { show: false }, background: 'transparent' },
            colors: ['#ff6b6b'],
            stroke: { curve: 'smooth', width: 3 },
            theme: { mode: 'dark' },
            xaxis: { categories: ['Base', 'Location', 'Property', 'Condition', 'Market', 'Final'] }
        };
        new ApexCharts(document.querySelector('#chart-line'), lineOptions).render();

        const barOptions = {
            series: [{
                name: 'Impact',
                data: <?php echo json_encode($charts['bar_data']); ?>
            }],
            chart: { height: 250, type: 'bar', toolbar: { show: false } },
            plotOptions: { bar: { horizontal: true, borderRadius: 10 } },
            colors: ['#ff6b6b', '#f59e0b', '#3b82f6', '#10b981', '#6366f1'],
            distributed: true,
            theme: { mode: 'dark' },
            xaxis: { categories: <?php echo json_encode($charts['bar_labels']); ?> }
        };
        new ApexCharts(document.querySelector('#chart-bar'), barOptions).render();

        const map = L.map('map').setView([<?php echo $locationMeta['lat']; ?>, <?php echo $locationMeta['lng']; ?>], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);
        L.marker([<?php echo $locationMeta['lat']; ?>, <?php echo $locationMeta['lng']; ?>]).addTo(map)
            .bindPopup('Property Location at <?php echo h($locationMeta['label']); ?>')
            .openPopup();
    </script>
</body>
</html>

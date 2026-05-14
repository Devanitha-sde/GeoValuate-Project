<?php 
require_once 'includes/config.php'; 
require_user_portal();

$type = $_GET['type'] ?? 'house';
$location = $_GET['location'] ?? '';
$range = $_GET['range'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Valuation Details - GeoValuate</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .valuation-container {
            padding: 2rem 4rem;
            text-align: center;
        }
        .details-box {
            width: 600px;
            margin: 3rem auto;
            padding: 3rem;
            text-align: center;
        }
        .details-title {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            font-weight: 500;
        }
        .feature-input {
            background: rgba(255,255,255,0.2);
            border-radius: 50px;
            padding: 15px 25px;
            margin-bottom: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 1.2rem;
        }
        .feature-input span {
            font-weight: 500;
        }
        .feature-input div {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .feature-input i {
            font-size: 0.8rem;
            cursor: pointer;
            opacity: 0.7;
        }
        .feature-input i:hover {
            opacity: 1;
        }
        .feature-input input, .feature-input select {
            background: transparent;
            border: none;
            color: #fff;
            text-align: right;
            outline: none;
            font-size: 1.1rem;
        }
        .feature-input select option {
            background: #333;
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

    <div class="valuation-container">
        <h1 style="font-size: 3.5rem; margin-top: 2rem;">Precision in Every Estimate</h1>

        <div class="details-box glass animate-fade">
            <h3 class="details-title">Additional Features</h3>
            
            <form action="results.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo h(csrf_token()); ?>">
                <input type="hidden" name="type" value="<?php echo htmlspecialchars($type); ?>">
                <input type="hidden" name="location" value="<?php echo htmlspecialchars($location); ?>">

                <?php if ($type == 'house'): ?>
                    <div class="feature-input">
                        <span>Location Size</span>
                        <input type="number" name="location_size" placeholder="Enter size" required>
                    </div>
                    <div class="feature-input">
                        <span>Building Size</span>
                        <input type="number" name="building_size" placeholder="Enter size" required>
                    </div>
                    <div class="feature-input">
                        <span>No. of Bedrooms</span>
                        <input type="number" name="bedrooms" placeholder="Enter count" required>
                    </div>
                    <div class="feature-input">
                        <span>No. of Bathrooms</span>
                        <input type="number" name="bathrooms" placeholder="Enter count" required>
                    </div>
                    <div class="feature-input">
                        <span>Year Built</span>
                        <input type="number" name="year_built" placeholder="Enter year" required>
                    </div>
                <?php else: ?>
                    <div class="feature-input">
                        <span>Land Size</span>
                        <input type="number" name="land_size" placeholder="Enter size" required>
                    </div>
                    <div class="feature-input">
                        <span>Land Type</span>
                        <select name="land_type">
                            <option value="residential">Residential</option>
                            <option value="commercial">Commercial</option>
                            <option value="agricultural">Agricultural</option>
                        </select>
                    </div>
                    <div class="feature-input">
                        <span>Road Access</span>
                        <select name="road_access">
                            <option value="excellent">Excellent</option>
                            <option value="good">Good</option>
                            <option value="fair">Fair</option>
                        </select>
                    </div>
                    <div class="feature-input">
                        <span>Distance by places</span>
                        <input type="text" name="distance" placeholder="Enter distance" required>
                    </div>
                    <div class="feature-input">
                        <span>Utilities</span>
                        <select name="utilities">
                            <option value="all">All Available</option>
                            <option value="partial">Partial</option>
                            <option value="none">None</option>
                        </select>
                    </div>
                <?php endif; ?>

                <button type="submit" class="btn btn-primary" style="margin-top: 2rem; width: 60%; padding: 15px; font-size: 1.3rem;">Predict Price</button>
            </form>
        </div>
    </div>
</body>
</html>

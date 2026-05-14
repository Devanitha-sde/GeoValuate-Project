<?php 
require_once 'includes/config.php'; 
require_user_portal();

$type = $_GET['type'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Valuation - GeoValuate</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .valuation-container {
            padding: 2rem 4rem;
            text-align: center;
        }
        .search-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 3rem;
            margin-top: 3rem;
            gap: 2rem;
        }
        .search-item {
            flex: 1;
            text-align: left;
        }
        .search-item label {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.1rem;
            font-weight: 500;
            background: var(--primary-color);
            color: #000;
            padding: 10px 20px;
            border-radius: 50px;
            margin-bottom: 10px;
            width: fit-content;
        }
        .search-item select {
            width: 100%;
            background: transparent;
            border: none;
            color: #fff;
            font-size: 1.2rem;
            outline: none;
            cursor: pointer;
        }
        .search-item select option {
            background: #333;
        }
        .search-btn {
            background: var(--primary-color);
            color: #000;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            cursor: pointer;
            border: none;
            transition: var(--transition);
        }
        .search-btn:hover {
            transform: scale(1.1);
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

        <form action="valuation_details.php" method="GET" class="search-bar glass">
            <div class="search-item">
                <label><i class="fas fa-home"></i> Type of property</label>
                <select name="type" required>
                    <option value="" disabled <?php echo empty($type) ? 'selected' : ''; ?>>Select your wish</option>
                    <option value="land" <?php echo $type == 'land' ? 'selected' : ''; ?>>Land</option>
                    <option value="house" <?php echo $type == 'house' ? 'selected' : ''; ?>>House</option>
                </select>
            </div>

            <div class="search-item">
                <label><i class="fas fa-map-marker-alt"></i> Location</label>
                <select name="location" required>
                    <option value="" disabled selected>Select a location</option>
                    <option value="colombo">Colombo</option>
                    <option value="kandy">Kandy</option>
                    <option value="galle">Galle</option>
                </select>
            </div>

            <div class="search-item">
                <label><i class="fas fa-tag"></i> Predicted Price Range</label>
                <select name="range" required>
                    <option value="" disabled selected>Select the range</option>
                    <option value="1">Rs. 1,000,000 - 5,000,000</option>
                    <option value="2">Rs. 5,000,000 - 10,000,000</option>
                    <option value="3">Rs. 10,000,000+</option>
                </select>
            </div>

            <button type="submit" class="search-btn">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>
</body>
</html>

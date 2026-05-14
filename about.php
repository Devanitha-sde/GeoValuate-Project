<?php require_once 'includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About GeoValuate</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar glass">
        <a href="index.php" class="logo"><i class="fas fa-globe-americas"></i> GeoValuate</a>
        <div class="nav-links">
            <a href="about.php" class="active">About</a>
            <a href="help.php">Help</a>
            <a href="contact.php">Contact</a>
        </div>
        <div style="display: flex; gap: 1rem;">
            <a href="login.php" class="btn btn-outline">Login</a>
            <a href="register.php" class="btn btn-primary">Register</a>
        </div>
    </nav>

    <div class="page-shell">
        <div class="page-header">
            <h1>About GeoValuate</h1>
            <p>GeoValuate is a guided real estate valuation platform built for faster decision-making, cleaner reporting, and a smoother property review workflow.</p>
        </div>

        <div class="grid-two">
            <section class="glass panel text-content animate-fade">
                <h2 class="panel-title">What the platform does</h2>
                <p>The application combines user-friendly property intake screens, structured valuation logic, GIS-backed map context, and downloadable reporting in a single PHP/MySQL workflow.</p>
                <p>It is designed to help users move from raw property details to a practical estimate with traceable supporting information and a consistent report trail.</p>
            </section>

            <section class="glass panel text-content animate-fade" style="animation-delay: 0.15s;">
                <h2 class="panel-title">Why it matters</h2>
                <p>Real estate teams often need quick first-pass pricing, especially when reviewing multiple homes or land parcels. GeoValuate gives them a repeatable process instead of ad-hoc guesswork.</p>
                <p>The current version uses a structured demo model rather than a production ML service, which keeps it fast, testable, and easy to extend later.</p>
            </section>
        </div>
    </div>
</body>
</html>

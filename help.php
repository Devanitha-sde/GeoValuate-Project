<?php require_once 'includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help Center - GeoValuate</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar glass">
        <a href="index.php" class="logo"><i class="fas fa-globe-americas"></i> GeoValuate</a>
        <div class="nav-links">
            <a href="about.php">About</a>
            <a href="help.php" class="active">Help</a>
            <a href="contact.php">Contact</a>
        </div>
        <div style="display: flex; gap: 1rem;">
            <a href="login.php" class="btn btn-outline">Login</a>
            <a href="register.php" class="btn btn-primary">Register</a>
        </div>
    </nav>

    <div class="page-shell">
        <div class="page-header">
            <h1>Help Center</h1>
            <p>Find answers about valuations, reports, account access, and the best way to use GeoValuate for day-to-day property analysis.</p>
        </div>

        <div class="split-layout">
            <section class="glass panel text-content animate-fade">
                <h2 class="panel-title">Frequently asked questions</h2>
                <div class="info-list">
                    <div class="info-item">
                        <h4>How does the valuation work?</h4>
                        <p>GeoValuate uses a structured demo model that weighs property type, location, land size, building size, bedrooms, bathrooms, age, and site access to estimate a realistic market range.</p>
                    </div>
                    <div class="info-item">
                        <h4>Can I compare old valuations?</h4>
                        <p>Yes. After signing in, visit the compare page to review your recent valuation history side by side and spot pricing differences across locations and property profiles.</p>
                    </div>
                    <div class="info-item">
                        <h4>Are reports downloadable?</h4>
                        <p>Yes. Reports can be viewed online, exported into an Excel-friendly file, or opened in a print-friendly format for PDF generation.</p>
                    </div>
                    <div class="info-item">
                        <h4>What if I forget my password?</h4>
                        <p>Use the password reset page to generate a local demo reset link, then choose a new password securely from that link.</p>
                    </div>
                </div>
            </section>

            <aside class="glass panel animate-fade" style="animation-delay: 0.15s;">
                <h2 class="panel-title">Quick guidance</h2>
                <div class="timeline">
                    <div class="timeline-item">
                        <h4>1. Start a valuation</h4>
                        <p>Choose the property type and location, then continue into the detailed feature form.</p>
                    </div>
                    <div class="timeline-item">
                        <h4>2. Review the result</h4>
                        <p>Check the estimated value, confidence score, location map, and supporting feature chart.</p>
                    </div>
                    <div class="timeline-item">
                        <h4>3. Save and export</h4>
                        <p>Your valuation is stored in history, linked to a report record, and can be opened or exported later.</p>
                    </div>
                </div>
                <div class="actions-row" style="margin-top: 1.5rem;">
                    <a href="contact.php" class="btn btn-primary">Contact Support</a>
                    <a href="faq.php" class="btn btn-outline">FAQ Shortcut</a>
                </div>
            </aside>
        </div>
    </div>
</body>
</html>

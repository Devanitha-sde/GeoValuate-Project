<?php require_once 'includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GeoValuate - Smart Real Estate Valuations</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="landing-page">
    <nav class="navbar glass">
        <a href="index.php" class="logo">
            <i class="fas fa-globe-americas"></i> GeoValuate
        </a>

        <div class="nav-links">
            <a href="#why">Why GeoValuate</a>
            <a href="#workflow">How It Works</a>
            <a href="#insights">Insights</a>
            <a href="about.php">About</a>
            <a href="contact.php">Contact</a>
        </div>

        <div style="display: flex; gap: 1rem; align-items: center;">
            <a href="login.php" class="btn btn-outline">Login</a>
            <a href="register.php" class="btn btn-primary">Get Started</a>
        </div>
    </nav>

    <main class="landing-shell">
        <section class="landing-hero">
            <div class="hero-copy glass animate-fade">
                <span class="eyebrow">Modern property intelligence for confident decisions</span>
                <h1>Real estate valuation that feels faster, clearer, and far more professional.</h1>
                <p>
                    GeoValuate brings together guided property intake, location-aware pricing logic, visual reporting,
                    and export-ready outputs so agents, valuers, investors, and property teams can assess value with
                    more structure and less guesswork.
                </p>

                <div class="actions-row" style="margin-top: 2rem;">
                    <a href="register.php" class="btn btn-primary">Start Free Valuation</a>
                    <a href="login.php" class="btn btn-outline">Open Dashboard</a>
                </div>

                <div class="hero-metrics">
                    <div class="metric-card">
                        <strong>Location-Aware</strong>
                        <span>Colombo, Kandy, and Galle pricing logic built in.</span>
                    </div>
                    <div class="metric-card">
                        <strong>Instant Reports</strong>
                        <span>Generate printable valuation reports with history tracking.</span>
                    </div>
                    <div class="metric-card">
                        <strong>Decision Focused</strong>
                        <span>Confidence scores, maps, exports, and comparison views.</span>
                    </div>
                </div>
            </div>

            <div class="hero-visual animate-fade" style="animation-delay: 0.15s;">
                <div class="feature-media-card primary-media glass">
                    <img src="https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=1200&q=80" alt="Modern luxury home exterior">
                    <div class="media-caption">
                        <span class="pill"><i class="fas fa-house"></i> Featured Property</span>
                        <h3>Luxury residential review with market-ready presentation.</h3>
                    </div>
                </div>

                <div class="hero-floating-card glass">
                    <span class="pill"><i class="fas fa-map-location-dot"></i> GIS-backed context</span>
                    <p>Combine property features with location context and pricing signals in a single valuation flow.</p>
                </div>
            </div>
        </section>

        <section class="trust-strip animate-fade" style="animation-delay: 0.25s;">
            <div class="glass stat-card">
                <span class="stat-label">Property types covered</span>
                <span class="stat-value">Land + House</span>
            </div>
            <div class="glass stat-card">
                <span class="stat-label">Built-in outputs</span>
                <span class="stat-value">History, Reports, Exports</span>
            </div>
            <div class="glass stat-card">
                <span class="stat-label">Professional workflow</span>
                <span class="stat-value">Input to Report in Minutes</span>
            </div>
        </section>

        <section id="why" class="landing-section">
            <div class="section-heading">
                <span class="eyebrow">Why teams choose GeoValuate</span>
                <h2>A complete first-pass valuation workspace, not just a single estimate screen.</h2>
                <p>Designed to make every stage of the property review process feel more polished, informed, and client-ready.</p>
            </div>

            <div class="grid-three">
                <article class="glass feature-card animate-fade">
                    <i class="fas fa-chart-line"></i>
                    <h3>Structured valuation logic</h3>
                    <p>Estimate values using property type, location, size, bedrooms, bathrooms, road access, utilities, and other meaningful inputs.</p>
                </article>
                <article class="glass feature-card animate-fade" style="animation-delay: 0.1s;">
                    <i class="fas fa-file-export"></i>
                    <h3>Presentation-ready reporting</h3>
                    <p>Turn each valuation into a report record with printable views and spreadsheet export support for internal or client-facing use.</p>
                </article>
                <article class="glass feature-card animate-fade" style="animation-delay: 0.2s;">
                    <i class="fas fa-users-gear"></i>
                    <h3>Built for real workflows</h3>
                    <p>Support admins, customers, and operational teams with dashboards, account settings, notifications, and valuation history.</p>
                </article>
            </div>
        </section>

        <section id="insights" class="landing-section showcase-section">
            <div class="showcase-copy glass panel animate-fade">
                <span class="eyebrow">Visual intelligence</span>
                <h2>See value from multiple angles.</h2>
                <p>GeoValuate pairs price estimation with polished visuals and supportive context so users understand not only the number, but the story behind it.</p>

                <div class="info-list" style="margin-top: 1.5rem;">
                    <div class="info-item">
                        <h4>Map context</h4>
                        <p>Plot property location visually with a GIS-inspired map block for clearer geographic understanding.</p>
                    </div>
                    <div class="info-item">
                        <h4>Contribution charts</h4>
                        <p>Show the strongest valuation drivers using clear chart components for more confident review.</p>
                    </div>
                    <div class="info-item">
                        <h4>History and comparison</h4>
                        <p>Track past valuations and compare recent estimates side by side to reveal trends and trade-offs.</p>
                    </div>
                </div>
            </div>

            <div class="showcase-grid">
                <div class="feature-media-card glass animate-fade" style="animation-delay: 0.1s;">
                    <img src="https://images.unsplash.com/photo-1479839672679-a46483c0e7c8?auto=format&fit=crop&w=1200&q=80" alt="Modern city skyline">
                    <div class="media-caption">
                        <span class="pill"><i class="fas fa-city"></i> Market Coverage</span>
                        <h3>Location-sensitive insights for high-demand urban environments.</h3>
                    </div>
                </div>
                <div class="feature-media-card glass animate-fade" style="animation-delay: 0.2s;">
                    <img src="https://images.unsplash.com/photo-1582407947304-fd86f028f716?auto=format&fit=crop&w=1200&q=80" alt="Professional team working in a modern office">
                    <div class="media-caption">
                        <span class="pill"><i class="fas fa-briefcase"></i> Team Ready</span>
                        <h3>Useful for agencies, valuers, internal property teams, and consultants.</h3>
                    </div>
                </div>
                <div class="feature-media-card glass animate-fade" style="animation-delay: 0.3s;">
                    <img src="https://images.unsplash.com/photo-1600047509807-ba8f99d2cdde?auto=format&fit=crop&w=1200&q=80" alt="Modern interior living room">
                    <div class="media-caption">
                        <span class="pill"><i class="fas fa-couch"></i> Property Detail</span>
                        <h3>Designed to capture meaningful residential details in a guided way.</h3>
                    </div>
                </div>
            </div>
        </section>

        <section id="workflow" class="landing-section">
            <div class="section-heading">
                <span class="eyebrow">Simple workflow</span>
                <h2>From first input to finished report in four guided steps.</h2>
                <p>The homepage, dashboard, and valuation pages work together as one continuous professional experience.</p>
            </div>

            <div class="workflow-grid">
                <div class="glass workflow-card animate-fade">
                    <span class="workflow-index">01</span>
                    <h3>Select property type</h3>
                    <p>Start with land or house, choose the area, and set a rough pricing range to frame the estimate.</p>
                </div>
                <div class="glass workflow-card animate-fade" style="animation-delay: 0.1s;">
                    <span class="workflow-index">02</span>
                    <h3>Enter property details</h3>
                    <p>Capture the features that matter, from land size and road access to bedrooms, bathrooms, and year built.</p>
                </div>
                <div class="glass workflow-card animate-fade" style="animation-delay: 0.2s;">
                    <span class="workflow-index">03</span>
                    <h3>Review the estimate</h3>
                    <p>See the calculated property value, confidence score, map visualization, and feature contribution charts.</p>
                </div>
                <div class="glass workflow-card animate-fade" style="animation-delay: 0.3s;">
                    <span class="workflow-index">04</span>
                    <h3>Save, compare, and export</h3>
                    <p>Keep a record in your history, open reports later, compare valuations, and export when needed.</p>
                </div>
            </div>
        </section>

        <section class="landing-section audience-section">
            <div class="section-heading">
                <span class="eyebrow">Who it serves</span>
                <h2>Useful for professionals, growing agencies, and serious property buyers.</h2>
            </div>

            <div class="grid-three">
                <article class="glass audience-card animate-fade">
                    <h3>Real estate agencies</h3>
                    <p>Create faster preliminary valuations, save property histories, and present estimates with more credibility.</p>
                </article>
                <article class="glass audience-card animate-fade" style="animation-delay: 0.1s;">
                    <h3>Property consultants</h3>
                    <p>Organize repeat valuations, compare similar opportunities, and keep printable records for review meetings.</p>
                </article>
                <article class="glass audience-card animate-fade" style="animation-delay: 0.2s;">
                    <h3>Investors and buyers</h3>
                    <p>Explore value signals more systematically before deeper due diligence or negotiation begins.</p>
                </article>
            </div>
        </section>

        <section class="landing-cta glass animate-fade">
            <div>
                <span class="eyebrow">Ready to explore the platform?</span>
                <h2>Bring more clarity to every property decision.</h2>
                <p>Create an account to start valuations, track reports, and experience the full GeoValuate workflow.</p>
            </div>
            <div class="actions-row">
                <a href="register.php" class="btn btn-primary">Create Account</a>
                <a href="help.php" class="btn btn-outline">Learn More</a>
            </div>
        </section>
    </main>

    <footer class="landing-footer">
        <p>&copy; 2026 GeoValuate. Real estate valuation with clarity, speed, and presentation-ready output.</p>
        <div class="footer-links">
            <a href="privacy.php">Privacy</a>
            <a href="terms.php">Terms</a>
            <a href="help.php">FAQ</a>
            <a href="contact.php">Contact</a>
        </div>
        <p class="image-credit">Imagery sourced from Unsplash.</p>
    </footer>
</body>
</html>

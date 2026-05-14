<?php 
require_once 'includes/config.php'; 
require_user_portal();

$viewer = current_user($pdo);
$userName = $viewer['name'] ?? ($_SESSION['user_name'] ?? 'GeoValuate User');
$userEmail = $viewer['email'] ?? 'account@geovaluate.local';
$initialParts = preg_split('/\s+/', trim($userName)) ?: [];
$userInitials = '';
foreach (array_slice($initialParts, 0, 2) as $part) {
    $userInitials .= strtoupper(substr($part, 0, 1));
}
$userInitials = $userInitials !== '' ? $userInitials : 'GV';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - GeoValuate</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .dashboard-container {
            display: flex;
            gap: 2rem;
            padding: 2rem;
            height: calc(100vh - 150px);
        }
        .sidebar {
            width: 250px;
            padding: 2rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .sidebar-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 15px 20px;
            border-radius: 15px;
            color: #fff;
            text-decoration: none;
            transition: var(--transition);
            background: rgba(255,255,255,0.05);
            font-size: 1.1rem;
        }
        .sidebar-item:hover, .sidebar-item.active {
            background: var(--primary-color);
            color: #000;
        }
        .sidebar-item i {
            width: 25px;
            text-align: center;
        }
        .main-content {
            flex: 1;
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-template-rows: 1fr 1fr;
            gap: 2rem;
        }
        .dashboard-card {
            padding: 2rem;
            text-align: center;
        }
        .card-title {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            font-weight: 500;
        }
        .card-actions {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .logout-btn {
            margin-top: auto;
            background: rgba(176, 141, 68, 0.3);
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
        }
    </style>
</head>
<body>
    <nav class="navbar glass">
        <a href="dashboard.php" class="logo">
            <i class="fas fa-globe-americas"></i> GeoValuate
        </a>
        <div class="nav-links">
            <a href="dashboard.php" class="active">Home</a>
            <a href="history.php">My Property</a>
            <a href="help.php">Help</a>
            <a href="contact.php">Contact Us</a>
        </div>
        <div class="profile-menu" data-profile-menu>
            <button type="button" class="user-profile profile-trigger" data-profile-toggle aria-expanded="false" aria-haspopup="true" aria-label="Open profile menu">
                <span class="profile-avatar-text"><?php echo h($userInitials); ?></span>
            </button>

            <div class="profile-dropdown" data-profile-dropdown>
                <div class="profile-summary">
                    <div class="profile-summary-avatar"><?php echo h($userInitials); ?></div>
                    <div class="profile-summary-copy">
                        <strong><?php echo h($userName); ?></strong>
                        <span><?php echo h($userEmail); ?></span>
                    </div>
                </div>

                <div class="profile-menu-list">
                    <a href="profile.php" class="profile-menu-link">
                        <span><i class="fas fa-user-gear"></i> Profile Settings</span>
                        <i class="fas fa-chevron-right"></i>
                    </a>
                    <a href="settings.php" class="profile-menu-link">
                        <span><i class="fas fa-sliders"></i> Account Settings</span>
                        <i class="fas fa-chevron-right"></i>
                    </a>
                    <a href="help.php" class="profile-menu-link">
                        <span><i class="fas fa-life-ring"></i> Help &amp; Support</span>
                        <i class="fas fa-chevron-right"></i>
                    </a>
                    <a href="logout.php" class="profile-menu-link logout-link">
                        <span><i class="fas fa-sign-out-alt"></i> Logout</span>
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div style="padding: 0 2rem;">
        <?php render_flashes(); ?>
    </div>

    <div class="dashboard-container">
        <aside class="sidebar glass">
            <div class="user-profile" style="width: 80px; height: 80px; margin: 0 auto 2rem;">
                <i class="fas fa-user" style="font-size: 2rem;"></i>
            </div>
            <a href="dashboard.php" class="sidebar-item active"><i class="fas fa-th-large"></i> Dashboard</a>
            <a href="predictions.php" class="sidebar-item"><i class="fas fa-chart-line"></i> Predictions</a>
            <a href="notifications.php" class="sidebar-item"><i class="fas fa-bell"></i> Notifications</a>
            <a href="discover.php" class="sidebar-item"><i class="fas fa-compass"></i> Discover</a>
            <a href="settings.php" class="sidebar-item"><i class="fas fa-cog"></i> Settings</a>
            <a href="help.php" class="sidebar-item"><i class="fas fa-question-circle"></i> Help</a>
            <a href="logout.php" class="sidebar-item logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </aside>

        <main class="main-content">
            <div class="dashboard-card glass">
                <h3 class="card-title">Type of Property</h3>
                <div class="card-actions">
                    <a href="valuation.php?type=land" class="btn btn-primary">Land</a>
                    <a href="valuation.php?type=house" class="btn btn-primary">House</a>
                    <div style="margin-top: 1rem;">
                        <a href="valuation.php" class="btn btn-primary" style="background: var(--primary-color); opacity: 0.8;">New Property Valuation</a>
                    </div>
                </div>
            </div>

            <div class="dashboard-card glass">
                <h3 class="card-title">Valuation History</h3>
                <div class="card-actions">
                    <a href="history.php" class="btn btn-primary">Past Valuation</a>
                    <a href="compare.php" class="btn btn-primary">Compare Results</a>
                    <a href="predictions.php" class="btn btn-primary">Valuation Result</a>
                </div>
            </div>

            <div class="dashboard-card glass">
                <h3 class="card-title">Report Generation</h3>
                <div class="card-actions">
                    <div style="display: flex; gap: 1rem;">
                        <a href="reports.php" class="btn btn-primary" style="flex: 1;">View Report</a>
                        <a href="predictions.php" class="btn btn-primary" style="flex: 1;">Review Valuations</a>
                    </div>
                    <a href="reports.php" class="btn btn-primary">Export / Download</a>
                    <a href="reports.php" class="btn btn-primary" style="color: #b08d44; background: transparent; border: 1px solid #b08d44;">Generate</a>
                </div>
            </div>

            <div class="dashboard-card glass">
                <h3 class="card-title">Profile Settings</h3>
                <div class="card-actions">
                    <a href="profile.php" class="btn btn-primary">Update Personal Information</a>
                    <a href="settings.php" class="btn btn-primary">Notification Settings</a>
                    <a href="settings.php" class="btn btn-primary">Account Preference</a>
                </div>
            </div>
        </main>
    </div>

    <script>
        (() => {
            const menu = document.querySelector('[data-profile-menu]');
            if (!menu) return;

            const toggle = menu.querySelector('[data-profile-toggle]');

            const setOpen = (open) => {
                menu.classList.toggle('is-open', open);
                toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            };

            toggle.addEventListener('click', (event) => {
                event.stopPropagation();
                setOpen(!menu.classList.contains('is-open'));
            });

            document.addEventListener('click', (event) => {
                if (!menu.contains(event.target)) {
                    setOpen(false);
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    setOpen(false);
                }
            });
        })();
    </script>
</body>
</html>

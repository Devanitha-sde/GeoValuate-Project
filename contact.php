<?php
require_once 'includes/config.php';

if ($pdo instanceof PDO && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        set_flash('error', 'Your session expired. Please try again.');
        redirect('contact.php');
    }

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === '' || $email === '' || $subject === '' || $message === '') {
        set_flash('error', 'Please complete every field before sending your message.');
        redirect('contact.php');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        set_flash('error', 'Please enter a valid email address.');
        redirect('contact.php');
    }

    $stmt = $pdo->prepare('INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)');
    $stmt->execute([$name, $email, $subject, $message]);

    $admins = $pdo->query("SELECT id FROM users WHERE role = 'admin'")->fetchAll();
    foreach ($admins as $admin) {
        create_notification($pdo, (int) $admin['id'], 'New contact enquiry', 'A new contact message was submitted by ' . $name . '.', 'info');
    }

    set_flash('success', 'Your message has been saved successfully. We will review it soon.');
    redirect('contact.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - GeoValuate</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar glass">
        <a href="index.php" class="logo"><i class="fas fa-globe-americas"></i> GeoValuate</a>
        <div class="nav-links">
            <a href="about.php">About</a>
            <a href="help.php">Help</a>
            <a href="contact.php" class="active">Contact</a>
        </div>
        <div style="display: flex; gap: 1rem;">
            <a href="login.php" class="btn btn-outline">Login</a>
            <a href="register.php" class="btn btn-primary">Register</a>
        </div>
    </nav>

    <div class="page-shell">
        <div class="page-header">
            <h1>Contact GeoValuate</h1>
            <p>Need product help, demo support, or valuation guidance? Send a message and keep the conversation tied to your local project data.</p>
        </div>

        <div class="split-layout">
            <section class="glass panel animate-fade">
                <h2 class="panel-title">Send a message</h2>
                <?php render_flashes(); ?>
                <form action="contact.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo h(csrf_token()); ?>">
                    <div class="field-row">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Subject</label>
                        <input type="text" name="subject" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Message</label>
                        <textarea name="message" class="form-control" rows="6" style="border-radius: 24px; min-height: 180px;" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </form>
            </section>

            <aside class="glass panel text-content animate-fade" style="animation-delay: 0.15s;">
                <h2 class="panel-title">Other ways to navigate</h2>
                <div class="info-list">
                    <div class="info-item">
                        <h4>Need account help?</h4>
                        <p>Visit the help center for reset guidance, report questions, and valuation workflow answers.</p>
                    </div>
                    <div class="info-item">
                        <h4>Need legal information?</h4>
                        <p>See the privacy and terms pages to understand how demo data is handled inside the local application.</p>
                    </div>
                    <div class="info-item">
                        <h4>Need platform context?</h4>
                        <p>The about page explains the role of GeoValuate and how its guided valuation experience is designed.</p>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</body>
</html>

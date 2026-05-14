<?php 
require_once 'includes/config.php'; 
if (isset($_SESSION['user_id'])) {
    redirect_to_home();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create an Account - GeoValuate</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar glass">
        <a href="index.php" class="logo">
            <i class="fas fa-globe-americas"></i> GeoValuate
        </a>
    </nav>

    <main class="flex-center" style="justify-content: space-between; padding: 0 5rem;">
        <div style="width: 40%;" class="animate-fade">
            <h1 style="font-size: 3rem; margin-bottom: 2rem;">Build your home. Live your story.</h1>
            <p style="font-size: 1.5rem; opacity: 0.9;">Discover, analyze, and optimize properties with <br><strong>"GeoValuate"</strong><br> your smart real estate companion</p>
        </div>

        <div class="auth-box glass animate-fade">
            <h2 class="auth-title">Create an Account</h2>
            <p class="auth-subtitle">Already have an account? <a href="login.php" style="color: var(--primary-color); text-decoration: none;">Log In</a></p>
            <?php render_flashes(); ?>
            
            <form action="auth_process.php" method="POST">
                <input type="hidden" name="action" value="register">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" placeholder="Enter your full name" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Enter your email address" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Create a password" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1rem; padding: 15px;">Create Account</button>
            </form>
        </div>
    </main>
</body>
</html>

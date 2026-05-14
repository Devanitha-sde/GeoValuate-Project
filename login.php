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
    <title>Log In - GeoValuate</title>
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
            <h2 class="auth-title">Welcome Back !</h2>
            <p class="auth-subtitle">No account ? <a href="register.php" style="color: var(--primary-color); text-decoration: none;">Create one</a></p>
            <?php render_flashes(); ?>
            
            <form action="auth_process.php" method="POST">
                <input type="hidden" name="action" value="login">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Enter your email address" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <div style="position: relative;">
                        <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                        <i class="fas fa-eye-slash" style="position: absolute; right: 20px; top: 18px; color: rgba(255,255,255,0.5); cursor: pointer;"></i>
                    </div>
                </div>
                
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <label style="display: flex; align-items: center; gap: 10px; font-size: 1rem; cursor: pointer;">
                        <input type="checkbox" name="remember" style="width: 18px; height: 18px;"> Remember me
                    </label>
                    <a href="reset_password.php" style="color: #fff; text-decoration: none; font-size: 1rem;">Forgot password?</a>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 15px;">Log in</button>
            </form>
        </div>
    </main>
</body>
</html>

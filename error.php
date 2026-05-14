<?php
require_once 'includes/config.php';
$title = trim($_GET['title'] ?? 'Something went wrong');
$message = trim($_GET['message'] ?? 'The page you requested could not be completed.');
$back = trim($_GET['back'] ?? 'index.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo h($title); ?> - GeoValuate</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="page-shell" style="padding-top: 5rem;">
        <div class="glass panel empty-state animate-fade">
            <i class="fas fa-circle-exclamation"></i>
            <h1 style="margin-bottom: 1rem;"><?php echo h($title); ?></h1>
            <p style="max-width: 620px; margin: 0 auto 1.5rem;"><?php echo h($message); ?></p>
            <a href="<?php echo h($back); ?>" class="btn btn-primary">Go Back</a>
        </div>
    </div>
</body>
</html>

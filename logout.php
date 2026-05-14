<?php
require_once 'includes/config.php';

if ($pdo instanceof PDO && !empty($_SESSION['user_id'])) {
    forget_remembered_user($pdo, (int) $_SESSION['user_id']);
}

$_SESSION = [];
session_destroy();
redirect('login.php');
?>

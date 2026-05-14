<?php
require_once 'includes/config.php';

if (!$pdo instanceof PDO || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    set_flash('error', 'Unable to process that request right now.');
    redirect('login.php');
}

$action = $_POST['action'] ?? '';

if ($action === 'register') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($name === '' || $email === '' || $password === '') {
        set_flash('error', 'Please complete all required registration fields.');
        redirect('register.php');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        set_flash('error', 'Please enter a valid email address.');
        redirect('register.php');
    }

    if (strlen($password) < 6) {
        set_flash('error', 'Password must be at least 6 characters long.');
        redirect('register.php');
    }

    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        set_flash('error', 'An account already exists with that email address.');
        redirect('register.php');
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$name, $email, $hashedPassword, 'customer', 'active']);

    $userId = (int) $pdo->lastInsertId();
    sync_user_session([
        'id' => $userId,
        'name' => $name,
        'role' => 'customer',
        'status' => 'active'
    ]);

    save_user_setting($pdo, $userId, get_user_setting($pdo, $userId));
    create_notification($pdo, $userId, 'Welcome to GeoValuate', 'Your account is ready. Start your first valuation whenever you are ready.', 'success');
    set_flash('success', 'Account created successfully.');
    redirect_to_home();
}

if ($action === 'login') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = !empty($_POST['remember']);

    if ($email === '' || $password === '') {
        set_flash('error', 'Please enter both email and password.');
        redirect('login.php');
    }

    $stmt = $pdo->prepare('SELECT id, name, email, password, role, status FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password'])) {
        set_flash('error', 'Invalid email or password.');
        redirect('login.php');
    }

    if (($user['status'] ?? 'active') !== 'active') {
        set_flash('error', 'This account is currently inactive. Please contact support.');
        redirect('login.php');
    }

    sync_user_session($user);

    if ($remember) {
        remember_user($pdo, (int) $user['id']);
    } else {
        forget_remembered_user($pdo, (int) $user['id']);
    }

    set_flash('success', 'Welcome back, ' . $user['name'] . '.');
    redirect_to_home();
}

set_flash('error', 'Unsupported authentication action.');
redirect('login.php');
?>

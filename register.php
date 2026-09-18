<?php

require_once 'config/database.php';
require_once 'includes/auth.php';

redirectIfLoggedIn();

$error = '';
$name = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
        $error = 'Invalid request. Please try again.';
    } else {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if ($name === '' || $email === '' || $password === '') {
            $error = 'Please fill in all required fields.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid email address.';
        } elseif (strlen($password) < 6) {
            $error = 'Password must contain at least 6 characters.';
        } elseif ($password !== $confirmPassword) {
            $error = 'Passwords do not match.';
        } else {

            $check = $pdo->prepare(
                'SELECT id FROM users WHERE email = ? LIMIT 1'
            );
            $check->execute([$email]);

            if ($check->fetch()) {
                $error = 'An account with this email already exists.';
            } else {

                $hashedPassword = password_hash(
                    $password,
                    PASSWORD_DEFAULT
                );

                $stmt = $pdo->prepare(
                    'INSERT INTO users (name, email, password)
                     VALUES (?, ?, ?)'
                );

                $stmt->execute([
                    $name,
                    $email,
                    $hashedPassword
                ]);

                header('Location: login.php?registered=1');
                exit;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Create Account | Student Management</title>

    <link rel="stylesheet" href="css/style.css">
</head>

<body class="auth-page">

<div class="auth-container">

    <div class="auth-card">

        <div class="brand">
            <div class="brand-icon">S</div>

            <div>
                <h1>Student Management</h1>
                <p>Create your administrator account</p>
            </div>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST">

            <input
                type="hidden"
                name="csrf_token"
                value="<?= htmlspecialchars(csrfToken()) ?>"
            >

            <div class="form-group">
                <label>Full Name</label>

                <input
                    type="text"
                    name="name"
                    value="<?= htmlspecialchars($name) ?>"
                    placeholder="Enter your full name"
                    required
                >
            </div>

            <div class="form-group">
                <label>Email Address</label>

                <input
                    type="email"
                    name="email"
                    value="<?= htmlspecialchars($email) ?>"
                    placeholder="Enter your email"
                    required
                >
            </div>

            <div class="form-group">
                <label>Password</label>

                <input
                    type="password"
                    name="password"
                    placeholder="Minimum 6 characters"
                    required
                >
            </div>

            <div class="form-group">
                <label>Confirm Password</label>

                <input
                    type="password"
                    name="confirm_password"
                    placeholder="Enter password again"
                    required
                >
            </div>

            <button class="btn btn-primary btn-full" type="submit">
                Create Account
            </button>

        </form>

        <p class="auth-link">
            Already have an account?
            <a href="login.php">Login</a>
        </p>

    </div>

</div>

</body>
</html>
<?php

require_once 'config/database.php';
require_once 'includes/auth.php';

redirectIfLoggedIn();

$error = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
        $error = 'Invalid request. Please try again.';
    } else {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {
            $error = 'Please enter your email and password.';
        } else {

            $stmt = $pdo->prepare(
                'SELECT * FROM users WHERE email = ? LIMIT 1'
            );

            $stmt->execute([$email]);

            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {

                session_regenerate_id(true);

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];

                header('Location: dashboard.php');
                exit;

            } else {
                $error = 'Incorrect email or password.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Login | Student Management</title>

    <link rel="stylesheet" href="css/style.css">
</head>

<body class="auth-page">

<div class="auth-container">

    <div class="auth-card">

        <div class="brand">
            <div class="brand-icon">S</div>

            <div>
                <h1>Welcome Back</h1>
                <p>Login to manage your students</p>
            </div>
        </div>

        <?php if (isset($_GET['registered'])): ?>

            <div class="alert alert-success">
                Account created successfully. You can now login.
            </div>

        <?php endif; ?>

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
                    placeholder="Enter your password"
                    required
                >

            </div>

            <button
                type="submit"
                class="btn btn-primary btn-full"
            >
                Login
            </button>

        </form>

        <p class="auth-link">
            Don't have an account?
            <a href="register.php">Create Account</a>
        </p>

    </div>

</div>

</body>
</html>
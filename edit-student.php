<?php

require_once 'config/database.php';
require_once 'includes/auth.php';

requireLogin();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: students.php');
    exit;
}

$stmt = $pdo->prepare(
    'SELECT * FROM students WHERE id = ?'
);

$stmt->execute([$id]);

$student = $stmt->fetch();

if (!$student) {
    header('Location: students.php');
    exit;
}

$error = '';

$name = $student['student_name'];
$email = $student['email'];
$phone = $student['phone'] ?? '';
$course = $student['course'];
$city = $student['city'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {

        $error = 'Invalid request. Please try again.';

    } else {

        $name = trim($_POST['student_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $course = trim($_POST['course'] ?? '');
        $city = trim($_POST['city'] ?? '');

        if ($name === '' || $email === '' || $course === '') {

            $error = 'Name, email and course are required.';

        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            $error = 'Please enter a valid email address.';

        } else {

            $check = $pdo->prepare(
                'SELECT id
                 FROM students
                 WHERE email = ?
                 AND id != ?'
            );

            $check->execute([$email, $id]);

            if ($check->fetch()) {

                $error = 'Another student already uses this email.';

            } else {

                $update = $pdo->prepare(
                    'UPDATE students
                     SET student_name = ?,
                         email = ?,
                         phone = ?,
                         course = ?,
                         city = ?
                     WHERE id = ?'
                );

                $update->execute([
                    $name,
                    $email,
                    $phone ?: null,
                    $course,
                    $city ?: null,
                    $id
                ]);

                header('Location: students.php?updated=1');
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

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Update Student</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<header class="topbar">

    <a href="dashboard.php" class="logo">
        <span>S</span>
        Student Management
    </a>

    <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="students.php">Students</a>
        <a href="logout.php" class="logout-link">Logout</a>
    </nav>

</header>

<main class="container small-container">

    <section class="page-header">

        <div>
            <p class="eyebrow">UPDATE RECORD</p>
            <h1>Update Student</h1>

            <p>
                Edit the student's information and save your changes.
            </p>
        </div>

    </section>

    <section class="panel form-panel">

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

            <div class="form-grid">

                <div class="form-group">

                    <label>Student Name *</label>

                    <input
                        type="text"
                        name="student_name"
                        value="<?= htmlspecialchars($name) ?>"
                        required
                    >

                </div>

                <div class="form-group">

                    <label>Email *</label>

                    <input
                        type="email"
                        name="email"
                        value="<?= htmlspecialchars($email) ?>"
                        required
                    >

                </div>

                <div class="form-group">

                    <label>Phone</label>

                    <input
                        type="text"
                        name="phone"
                        value="<?= htmlspecialchars($phone) ?>"
                    >

                </div>

                <div class="form-group">

                    <label>Course *</label>

                    <input
                        type="text"
                        name="course"
                        value="<?= htmlspecialchars($course) ?>"
                        required
                    >

                </div>

                <div class="form-group full-width">

                    <label>City</label>

                    <input
                        type="text"
                        name="city"
                        value="<?= htmlspecialchars($city) ?>"
                    >

                </div>

            </div>

            <div class="form-actions">

                <a href="students.php" class="btn btn-secondary">
                    Cancel
                </a>

                <button type="submit" class="btn btn-primary">
                    Save Changes
                </button>

            </div>

        </form>

    </section>

</main>

</body>
</html>
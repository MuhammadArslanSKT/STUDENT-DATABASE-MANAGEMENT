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
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Student Details</title>

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
            <p class="eyebrow">STUDENT PROFILE</p>

            <h1>
                <?= htmlspecialchars($student['student_name']) ?>
            </h1>

            <p>
                Complete information for this student.
            </p>
        </div>

        <a
            href="edit-student.php?id=<?= (int) $student['id'] ?>"
            class="btn btn-primary"
        >
            Update Student
        </a>

    </section>

    <section class="panel">

        <div class="details-grid">

            <div class="detail">
                <span>Student ID</span>
                <strong>#<?= (int) $student['id'] ?></strong>
            </div>

            <div class="detail">
                <span>Full Name</span>

                <strong>
                    <?= htmlspecialchars($student['student_name']) ?>
                </strong>
            </div>

            <div class="detail">
                <span>Email</span>

                <strong>
                    <?= htmlspecialchars($student['email']) ?>
                </strong>
            </div>

            <div class="detail">
                <span>Phone</span>

                <strong>
                    <?= htmlspecialchars($student['phone'] ?: 'Not provided') ?>
                </strong>
            </div>

            <div class="detail">
                <span>Course</span>

                <strong>
                    <?= htmlspecialchars($student['course']) ?>
                </strong>
            </div>

            <div class="detail">
                <span>City</span>

                <strong>
                    <?= htmlspecialchars($student['city'] ?: 'Not provided') ?>
                </strong>
            </div>

        </div>

        <div class="form-actions">

            <a href="students.php" class="btn btn-secondary">
                ← Back to Students
            </a>

        </div>

    </section>

</main>

</body>
</html>
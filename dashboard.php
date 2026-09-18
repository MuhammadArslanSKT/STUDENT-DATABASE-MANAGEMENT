<?php

require_once 'config/database.php';
require_once 'includes/auth.php';

requireLogin();

$totalStudents = $pdo
    ->query('SELECT COUNT(*) FROM students')
    ->fetchColumn();

$totalCourses = $pdo
    ->query('SELECT COUNT(DISTINCT course) FROM students')
    ->fetchColumn();

$recentStudents = $pdo
    ->query(
        'SELECT * FROM students
         ORDER BY id DESC
         LIMIT 5'
    )
    ->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Dashboard | Student Management</title>

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
        <a href="add-student.php">Add Student</a>
        <a href="logout.php" class="logout-link">Logout</a>
    </nav>

</header>

<main class="container">

    <section class="page-header">

        <div>
            <p class="eyebrow">ADMIN DASHBOARD</p>

            <h1>
                Welcome,
                <?= htmlspecialchars($_SESSION['user_name']) ?>
            </h1>

            <p>
                Manage your student records from one place.
            </p>
        </div>

        <a href="add-student.php" class="btn btn-primary">
            + Add Student
        </a>

    </section>

    <section class="stats-grid">

        <div class="stat-card">
            <p>Total Students</p>
            <h2><?= (int) $totalStudents ?></h2>
        </div>

        <div class="stat-card">
            <p>Total Courses</p>
            <h2><?= (int) $totalCourses ?></h2>
        </div>

        <div class="stat-card">
            <p>Account</p>
            <h2>Active</h2>
        </div>

    </section>

    <section class="panel">

        <div class="panel-header">

            <div>
                <h2>Recent Students</h2>
                <p>Latest student records added to the system.</p>
            </div>

            <a href="students.php" class="btn btn-secondary">
                View All
            </a>

        </div>

        <?php if (!$recentStudents): ?>

            <div class="empty-state">
                No students have been added yet.
            </div>

        <?php else: ?>

            <div class="table-wrapper">

                <table>

                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Student</th>
                        <th>Course</th>
                        <th>City</th>
                    </tr>
                    </thead>

                    <tbody>

                    <?php foreach ($recentStudents as $student): ?>

                        <tr>

                            <td>
                                #<?= (int) $student['id'] ?>
                            </td>

                            <td>
                                <strong>
                                    <?= htmlspecialchars($student['student_name']) ?>
                                </strong>

                                <small>
                                    <?= htmlspecialchars($student['email']) ?>
                                </small>
                            </td>

                            <td>
                                <?= htmlspecialchars($student['course']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($student['city'] ?: '-') ?>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        <?php endif; ?>

    </section>

</main>

</body>
</html>
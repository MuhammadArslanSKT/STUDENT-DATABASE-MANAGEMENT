<?php

require_once 'config/database.php';
require_once 'includes/auth.php';

requireLogin();

$students = $pdo
    ->query('SELECT * FROM students ORDER BY id DESC')
    ->fetchAll();

$message = '';

if (isset($_GET['added'])) {
    $message = 'Student added successfully.';
}

if (isset($_GET['updated'])) {
    $message = 'Student updated successfully.';
}

if (isset($_GET['deleted'])) {
    $message = 'Student deleted successfully.';
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

    <title>Students | Student Management</title>

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
            <p class="eyebrow">STUDENT DATABASE</p>
            <h1>Students</h1>

            <p>
                Add, read, update and delete student records.
            </p>
        </div>

        <a href="add-student.php" class="btn btn-primary">
            + Add Student
        </a>

    </section>

    <?php if ($message): ?>

        <div class="alert alert-success">
            <?= htmlspecialchars($message) ?>
        </div>

    <?php endif; ?>

    <section class="panel">

        <?php if (!$students): ?>

            <div class="empty-state">
                <h3>No Students Found</h3>
                <p>Add your first student to begin.</p>
            </div>

        <?php else: ?>

            <div class="table-wrapper">

                <table>

                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Student</th>
                        <th>Phone</th>
                        <th>Course</th>
                        <th>City</th>
                        <th>Actions</th>
                    </tr>
                    </thead>

                    <tbody>

                    <?php foreach ($students as $student): ?>

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
                                <?= htmlspecialchars($student['phone'] ?: '-') ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($student['course']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($student['city'] ?: '-') ?>
                            </td>

                            <td>

                                <div class="action-buttons">

                                    <a
                                        class="btn btn-view"
                                        href="view-student.php?id=<?= (int) $student['id'] ?>"
                                    >
                                        Read
                                    </a>

                                    <a
                                        class="btn btn-edit"
                                        href="edit-student.php?id=<?= (int) $student['id'] ?>"
                                    >
                                        Update
                                    </a>

                                    <form
                                        action="delete-student.php"
                                        method="POST"
                                        class="inline-form"
                                        onsubmit="return confirm('Delete this student?');"
                                    >

                                        <input
                                            type="hidden"
                                            name="csrf_token"
                                            value="<?= htmlspecialchars(csrfToken()) ?>"
                                        >

                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?= (int) $student['id'] ?>"
                                        >

                                        <button
                                            type="submit"
                                            class="btn btn-delete"
                                        >
                                            Delete
                                        </button>

                                    </form>

                                </div>

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
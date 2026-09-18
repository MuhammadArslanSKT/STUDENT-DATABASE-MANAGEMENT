<?php

require_once 'config/database.php';
require_once 'includes/auth.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: students.php');
    exit;
}

if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
    exit('Invalid request.');
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: students.php');
    exit;
}

$stmt = $pdo->prepare(
    'DELETE FROM students WHERE id = ?'
);

$stmt->execute([$id]);

header('Location: students.php?deleted=1');
exit;
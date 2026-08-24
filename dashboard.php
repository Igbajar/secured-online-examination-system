<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->query("SELECT * FROM exams");
$exams = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head><title>Dashboard</title></head>
<body>
    <h2>Welcome, <?= htmlspecialchars($_SESSION['username']) ?></h2>
    <a href="logout.php">Logout</a>
    <h3>Available Exams</h3>
    <ul>
        <?php foreach ($exams as $exam): ?>
            <li>
                <strong><?= htmlspecialchars($exam['title']) ?></strong> (<?= $exam['duration_minutes'] ?> Mins) 
                - <a href="take_exam.php?id=<?= $exam['id'] ?>">Start Exam</a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
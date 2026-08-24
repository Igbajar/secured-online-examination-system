<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit;
}

$student_id = $_SESSION['user_id'];
$exam_id = (int)$_POST['exam_id'];
$user_answers = $_POST['answers'] ?? [];

// Fetch correct answers
$stmt = $pdo->prepare("SELECT id, correct_option FROM questions WHERE exam_id = ?");
$stmt->execute([$exam_id]);
$questions = $stmt->fetchAll();

$total = count($questions);
$score = 0;

foreach ($questions as $q) {
    $q_id = $q['id'];
    if (isset($user_answers[$q_id]) && $user_answers[$q_id] === $q['correct_option']) {
        $score++;
    }
}

// Store result securely
$stmt = $pdo->prepare("INSERT INTO results (student_id, exam_id, score, total) VALUES (?, ?, ?, ?)");
$stmt->execute([$student_id, $exam_id, $score, $total]);
?>
<!DOCTYPE html>
<html>
<head><title>Exam Results</title></head>
<body>
    <h2>Exam Submitted Successfully</h2>
    <p>Your Score: <strong><?= $score ?> / <?= $total ?></strong></p>
    <a href="dashboard.php">Return to Dashboard</a>
</body>
</html>
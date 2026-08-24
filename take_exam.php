<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit;
}

$exam_id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM exams WHERE id = ?");
$stmt->execute([$exam_id]);
$exam = $stmt->fetch();

if (!$exam) {
    die("Exam not found.");
}

$stmt = $pdo->prepare("SELECT id, question_text, option_a, option_b, option_c, option_d FROM questions WHERE exam_id = ?");
$stmt->execute([$exam_id]);
$questions = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($exam['title']) ?></title>
    <style>
        body { user-select: none; -webkit-user-select: none; } /* Disable Text Selection */
    </style>
</head>
<body oncontextmenu="return false;" oncopy="return false;" oncut="return false;" onpaste="return false;">

    <h2>Exam: <?= htmlspecialchars($exam['title']) ?></h2>
    <div>Time Remaining: <span id="timer"><?= $exam['duration_minutes'] * 60 ?></span> seconds</div>
    <hr>

    <form id="examForm" action="submit_exam.php" method="POST">
        <input type="hidden" name="exam_id" value="<?= $exam_id ?>">
        
        <?php foreach ($questions as $index => $q): ?>
            <div>
                <p><strong>Q<?= $index + 1 ?>: <?= htmlspecialchars($q['question_text']) ?></strong></p>
                <label><input type="radio" name="answers[<?= $q['id'] ?>]" value="A" required> <?= htmlspecialchars($q['option_a']) ?></label><br>
                <label><input type="radio" name="answers[<?= $q['id'] ?>]" value="B"> <?= htmlspecialchars($q['option_b']) ?></label><br>
                <label><input type="radio" name="answers[<?= $q['id'] ?>]" value="C"> <?= htmlspecialchars($q['option_c']) ?></label><br>
                <label><input type="radio" name="answers[<?= $q['id'] ?>]" value="D"> <?= htmlspecialchars($q['option_d']) ?></label><br>
            </div>
            <hr>
        <?php endforeach; ?>
        
        <button type="submit">Submit Exam</button>
    </form>

    <script>
        // Anti-Cheat: Tab Switch Detection
        let warnings = 0;
        document.addEventListener("visibilitychange", function() {
            if (document.hidden) {
                warnings++;
                alert("Warning " + warnings + "/3: Tab switching is prohibited!");
                if (warnings >= 3) {
                    alert("Maximum warnings reached. Submitting exam automatically.");
                    document.getElementById("examForm").submit();
                }
            }
        });

        // Timer Script
        let timeLeft = <?= $exam['duration_minutes'] * 60 ?>;
        const timerDisplay = document.getElementById('timer');
        const timerInterval = setInterval(() => {
            timeLeft--;
            timerDisplay.textContent = timeLeft;
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                alert("Time is up!");
                document.getElementById("examForm").submit();
            }
        }, 1000);
    </script>
</body>
</html>
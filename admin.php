<?php
session_start();
require 'db.php';

// Access Control: Strict check for logged-in Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$message = '';

// Handle Exam Creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_exam') {
    $title = trim($_POST['title']);
    $duration = (int)$_POST['duration'];

    if (!empty($title) && $duration > 0) {
        $stmt = $pdo->prepare("INSERT INTO exams (title, duration_minutes) VALUES (?, ?)");
        $stmt->execute([$title, $duration]);
        $message = "Exam created successfully!";
    }
}

// Handle Question Addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_question') {
    $exam_id = (int)$_POST['exam_id'];
    $question_text = trim($_POST['question_text']);
    $opt_a = trim($_POST['option_a']);
    $opt_b = trim($_POST['option_b']);
    $opt_c = trim($_POST['option_c']);
    $opt_d = trim($_POST['option_d']);
    $correct = $_POST['correct_option'];

    if ($exam_id && $question_text && $opt_a && $opt_b && $opt_c && $opt_d && $correct) {
        $stmt = $pdo->prepare("INSERT INTO questions (exam_id, question_text, option_a, option_b, option_c, option_d, correct_option) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$exam_id, $question_text, $opt_a, $opt_b, $opt_c, $opt_d, $correct]);
        $message = "Question added successfully!";
    }
}

// Fetch all exams for the dropdown and list
$exams = $pdo->query("SELECT * FROM exams ORDER BY id DESC")->fetchAll();

// Fetch student exam results
$results_stmt = $pdo->query("
    SELECT r.id, u.username, e.title, r.score, r.total, r.submitted_at 
    FROM results r
    JOIN users u ON r.student_id = u.id
    JOIN exams e ON r.exam_id = e.id
    ORDER BY r.submitted_at DESC
");
$results = $results_stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Secured Exam System</title>
</head>
<body>
    <h2>Admin Panel</h2>
    <p>Logged in as: <strong><?= htmlspecialchars($_SESSION['username']) ?></strong> | <a href="logout.php">Logout</a></p>
    <hr>

    <?php if ($message): ?>
        <p style="color: green; font-weight: bold;"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <!-- Section 1: Create New Exam -->
    <h3>1. Create New Exam</h3>
    <form method="POST">
        <input type="hidden" name="action" value="add_exam">
        <label>Exam Title:</label><br>
        <input type="text" name="title" required><br><br>
        
        <label>Duration (Minutes):</label><br>
        <input type="number" name="duration" min="1" required><br><br>
        
        <button type="submit">Create Exam</button>
    </form>
    <hr>

    <!-- Section 2: Add Question to Exam -->
    <h3>2. Add Question to Exam</h3>
    <?php if (empty($exams)): ?>
        <p><em>Create an exam first before adding questions.</em></p>
    <?php else: ?>
        <form method="POST">
            <input type="hidden" name="action" value="add_question">
            
            <label>Select Exam:</label><br>
            <select name="exam_id" required>
                <?php foreach ($exams as $exam): ?>
                    <option value="<?= $exam['id'] ?>"><?= htmlspecialchars($exam['title']) ?></option>
                <?php endforeach; ?>
            </select><br><br>

            <label>Question Text:</label><br>
            <textarea name="question_text" rows="3" cols="50" required></textarea><br><br>

            <label>Option A:</label> <input type="text" name="option_a" required><br>
            <label>Option B:</label> <input type="text" name="option_b" required><br>
            <label>Option C:</label> <input type="text" name="option_c" required><br>
            <label>Option D:</label> <input type="text" name="option_d" required><br><br>

            <label>Correct Option:</label>
            <select name="correct_option" required>
                <option value="A">Option A</option>
                <option value="B">Option B</option>
                <option value="C">Option C</option>
                <option value="D">Option D</option>
            </select><br><br>

            <button type="submit">Add Question</button>
        </form>
    <?php endif; ?>
    <hr>

    <!-- Section 3: Student Results -->
    <h3>3. Student Results</h3>
    <?php if (empty($results)): ?>
        <p>No exam submissions recorded yet.</p>
    <?php else: ?>
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Exam Title</th>
                    <th>Score</th>
                    <th>Percentage</th>
                    <th>Submitted At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $res): ?>
                    <?php $percentage = round(($res['score'] / $res['total']) * 100, 2); ?>
                    <tr>
                        <td><?= htmlspecialchars($res['username']) ?></td>
                        <td><?= htmlspecialchars($res['title']) ?></td>
                        <td><?= $res['score'] ?> / <?= $res['total'] ?></td>
                        <td><?= $percentage ?>%</td>
                        <td><?= $res['submitted_at'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

</body>
</html>
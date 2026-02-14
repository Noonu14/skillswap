<?php
require 'db.php';
session_start();

$error = '';

if (!isset($_SESSION['attempts'])) {
    $_SESSION['attempts'] = 0;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $unique_id = trim($_POST['unique_id']);
    $ans_chocolate = trim($_POST['ans_chocolate']);
    $ans_cousin = trim($_POST['ans_cousin']);
    $ans_place = trim($_POST['ans_place']);

    if (empty($unique_id) || empty($ans_chocolate) || empty($ans_cousin) || empty($ans_place)) {
        $error = "Please fill in all fields.";
    } else {
        // Find User
        $stmt = $pdo->prepare("SELECT id FROM users WHERE unique_id = ?");
        $stmt->execute([$unique_id]);
        $user = $stmt->fetch();

        if ($user) {
            // Check Answers
            $stmt = $pdo->prepare("SELECT * FROM que_ans WHERE user_id = ?");
            $stmt->execute([$user['id']]);
            $answers = $stmt->fetch();

            if ($answers && 
                strtolower($answers['ans_chocolate']) === strtolower($ans_chocolate) &&
                strtolower($answers['ans_cousin']) === strtolower($ans_cousin) &&
                strtolower($answers['ans_place']) === strtolower($ans_place)
            ) {
                // Success
                $_SESSION['attempts'] = 0;
                $_SESSION['reset_user_id'] = $user['id'];
                header("Location: change_password.php");
                exit;
            } else {
                // Wrong Answer
                $_SESSION['attempts']++;
                $remaining = 3 - $_SESSION['attempts'];
                if ($remaining <= 0) {
                    $_SESSION['attempts'] = 0;
                    $error = "Too many failed attempts. Redirecting to login...";
                    header("Refresh: 2; url=login.php");
                } else {
                    $error = "Wrong answer. Remaining attempts: $remaining";
                }
            }
        } else {
            // User not found (be vague or count attempt)
            $_SESSION['attempts']++;
            $error = "Invalid details. Remaining attempts: " . (3 - $_SESSION['attempts']);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password - SkillSwap</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Recover Password</h1>
        <p class="subtitle">Answer your security questions.</p>
        
        <?php if ($error): ?>
            <div style="background: rgba(255,0,0,0.3); padding: 10px; border-radius: 10px; margin-bottom: 20px;">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if ($_SESSION['attempts'] < 3): ?>
        <form method="POST" action="">
            <div class="form-group">
                <label>User ID</label>
                <input type="text" name="unique_id" required>
            </div>
            
            <h3>Security Questions</h3>
            <div class="form-group">
                <label>Favourite Chocolate?</label>
                <input type="text" name="ans_chocolate" required>
            </div>
            <div class="form-group">
                <label>Favourite Cousin?</label>
                <input type="text" name="ans_cousin" required>
            </div>
            <div class="form-group">
                <label>Favourite Place?</label>
                <input type="text" name="ans_place" required>
            </div>
            
            <button type="submit">Verify Answers</button>
        </form>
        <p style="margin-top: 1rem;"><a href="login.php" style="color: white;">Back to Login</a></p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
require 'db.php';
session_start();

// Ensure user passed the check
if (!isset($_SESSION['reset_user_id'])) {
    header("Location: login.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pass = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if ($pass !== $confirm) {
        $error = "Passwords do not match.";
    } elseif (strlen($pass) < 4) { // Minimal length check
        $error = "Password must be at least 4 characters.";
    } else {
        // Updates Password
        $hashed = password_hash($pass, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([$hashed, $_SESSION['reset_user_id']]);

        // Clear Session
        session_destroy();
        $success = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password - SkillSwap</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Reset Password</h1>
        
        <?php if ($error): ?>
            <div style="background: rgba(255,0,0,0.3); padding: 10px; border-radius: 10px; margin-bottom: 20px;">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div style="background: rgba(0,255,0,0.3); padding: 10px; border-radius: 10px; margin-bottom: 20px;">
                Password successfully updated! <br>
                Redirecting to login...
            </div>
            <script>setTimeout(function(){ window.location.href = 'login.php'; }, 2000);</script>
        <?php else: ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="password" required>
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" required>
                </div>
                <button type="submit">Update Password</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>

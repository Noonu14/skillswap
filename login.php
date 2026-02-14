<?php
require 'db.php';
session_start();

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $unique_id = trim($_POST['unique_id']);
    $password = $_POST['password'];

    if (empty($unique_id) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE unique_id = ?");
        $stmt->execute([$unique_id]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Login Success
            $_SESSION['user_id'] = $user['id']; // Internal ID
            $_SESSION['username'] = $user['username'];
            
            // Trigger matching engine
            require_once 'match_engine.php';
            findMatches($pdo, $user['id']);
            
            header("Location: home_page.php");
            exit;
        } else {
            $error = "Invalid User ID or Password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - SkillSwap Circle</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Welcome Back</h1>
        <p class="subtitle">Login to your SkillSwap account.</p>
        
        <?php if ($error): ?>
            <div style="background: rgba(255,0,0,0.3); padding: 10px; border-radius: 10px; margin-bottom: 20px;">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>User ID</label>
                <input type="text" name="unique_id" required placeholder="Enter your Unique ID">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
        <div style="margin-top: 1rem;">
            <a href="register.php" style="color: white; margin-right: 15px;">Sign Up</a>
            <a href="forgot_password.php" style="color: white;">Forgot Password?</a>
        </div>
    </div>
</body>
</html>

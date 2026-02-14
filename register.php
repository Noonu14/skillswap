<?php
require 'db.php';
session_start();

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $unique_id = trim($_POST['unique_id']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $offer = trim($_POST['offer']);
    $need = trim($_POST['need']);
    
    // Security Answers
    $ans_chocolate = trim($_POST['ans_chocolate']);
    $ans_cousin = trim($_POST['ans_cousin']);
    $ans_place = trim($_POST['ans_place']);

    if (empty($unique_id) || empty($username) || empty($password) || empty($ans_chocolate)) {
        $error = "Please fill in all required fields.";
    } else {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Normalize
        $offer = strtolower($offer);
        $need = strtolower($need);

        try {
            $pdo->beginTransaction();

            // 1. Insert User
            $stmt = $pdo->prepare("INSERT INTO users (unique_id, username, password, skill_offer, skill_need) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$unique_id, $username, $hashed_password, $offer, $need]);
            $user_id = $pdo->lastInsertId();

            // 2. Insert Security Answers
            $stmt = $pdo->prepare("INSERT INTO que_ans (user_id, ans_chocolate, ans_cousin, ans_place) VALUES (?, ?, ?, ?)");
            $stmt->execute([$user_id, $ans_chocolate, $ans_cousin, $ans_place]);

            $pdo->commit();
            
            $success = "Registration successful! <a href='login.php'>Login here</a>";
        } catch (PDOException $e) {
            $pdo->rollBack();
            if ($e->getCode() == 23000) {
                // Duplicate entry check
                $error = "Please enter a unique user_id"; 
                // We will also use JS to clear the input as requested
                echo "<script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const input = document.querySelector('input[name=\"unique_id\"]');
                            input.value = '';
                            input.focus();
                        });
                      </script>";
            } else {
                $error = "Error: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - SkillSwap Circle</title>
    <link rel="stylesheet" href="style.css">
    <script>
        // Optional Frontend check (The requirement "automatically clear" is handled in PHP error block above for post-submit, 
        // but we can also add visual feedback if needed)
    </script>
</head>
<body>
    <div class="container" style="max-width: 700px;">
        <h1>Join the Circle</h1>
        <p class="subtitle">Create your account with a Unique ID.</p>
        
        <?php if ($error): ?>
            <div style="background: rgba(255,0,0,0.3); padding: 10px; border-radius: 10px; margin-bottom: 20px;">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div style="background: rgba(0,255,0,0.3); padding: 10px; border-radius: 10px; margin-bottom: 20px;">
                <?= $success ?>
            </div>
        <?php else: ?>

        <form method="POST" action="">
            <div class="dashboard-grid">
                <div>
                    <h3>Profile Info</h3>
                    <div class="form-group">
                        <label>Unique User ID</label>
                        <input type="text" name="unique_id" required placeholder="e.g. alice_123">
                    </div>
                    <div class="form-group">
                        <label>Display Name</label>
                        <input type="text" name="username" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" required>
                    </div>
                     <div class="form-group">
                        <label>Skill You Offer</label>
                        <input type="text" name="offer" required>
                    </div>
                    <div class="form-group">
                        <label>Skill You Want</label>
                        <input type="text" name="need" required>
                    </div>
                </div>
                <div>
                    <h3>Security Questions</h3>
                    <p style="font-size: 0.8rem; opacity: 0.8; margin-bottom: 10px;">Used for password recovery.</p>
                    
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
                </div>
            </div>
            
            <button type="submit" style="margin-top: 20px;">Sign Up</button>
        </form>
        <p style="margin-top: 1rem;"><a href="login.php" style="color: white;">Already have an account? Login</a></p>
        <?php endif; ?>
    </div>
</body>
</html>

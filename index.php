<?php
require 'auth.php';

// If already logged in, go to dashboard
if (isLoggedIn()) {
    header("Location: home_page.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillSwap Circle</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>SkillSwap Circle</h1>
        <p class="subtitle">Exchange Skills. Empower Each Other.</p>
        
        <div style="margin-top: 2rem;">
            <p>Join our community of mentors and learners.</p>
            
            <a href="register.php" class="btn-main">Get Started</a>
            <br><br>
            <a href="login.php" style="color: white; text-decoration: underline;">Login</a>
        </div>
        
        <div style="margin-top: 3rem; text-align: left; opacity: 0.8; font-size: 0.9rem;">
            <h3>How it works:</h3>
            <ul>
                <li>Create a profile with what you offer and what you need.</li>
                <li>We automatically match you with complementary users.</li>
                <li>Chat, learn, and grow together!</li>
            </ul>
        </div>
    </div>
</body>
</html>

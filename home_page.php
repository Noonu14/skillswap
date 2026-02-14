<?php
require 'db.php';
require 'auth.php';
require_once 'match_engine.php';

requireLogin();

$user_id = $_SESSION['user_id'];

// Trigger matching on load (optimistic approach for MVP)
findMatches($pdo, $user_id);

// Fetch User Profile
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Fetch Matches
// We need to join with users table to get the OTHER person's name
$matches = [];
$stmt = $pdo->prepare("
    SELECT m.id as match_id, 
           u.username as partner_name, 
           u.unique_id as partner_unique_id,
           CASE 
               WHEN m.user_id_1 = ? THEN m.role_1 
               ELSE m.role_2 
           END as my_role
    FROM matches m
    JOIN users u ON (m.user_id_1 = u.id OR m.user_id_2 = u.id)
    WHERE (m.user_id_1 = ? OR m.user_id_2 = ?) AND u.id != ?
");
$stmt->execute([$user_id, $user_id, $user_id, $user_id]);
$matches = $stmt->fetchAll();

// Fetch Notifications
$stmt = $pdo->prepare("SELECT * FROM notifications WHERE user_id = ? AND is_read = 0 ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$notifications = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - SkillSwap Circle</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .role-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: bold;
            color: white;
            display: inline-block;
            margin-top: 5px;
        }
        .role-mentor { background-color: #6a11cb; }
        .role-learner { background-color: #00c853; }
        
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            text-align: left;
        }
        @media (max-width: 600px) {
            .dashboard-grid { grid-template-columns: 1fr; }
        }
        
        .section-card {
            background: rgba(255,255,255,0.1);
            padding: 1.5rem;
            border-radius: 15px;
        }
        
        .notification-item {
            background: rgba(255,255,255,0.2);
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 5px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container" style="max-width: 900px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h1>Hi, <?= htmlspecialchars($user['username']) ?> 👋</h1>
            <a href="logout.php" style="color: white; text-decoration: underline;">Logout</a>
        </div>

        <div class="dashboard-grid">
            <!-- Profile Column -->
            <div class="section-card">
                <h2>Your Profile</h2>
                <div class="form-group">
                    <label>Skill Offered</label>
                    <div style="font-size: 1.2rem; font-weight: bold;"><?= htmlspecialchars(ucfirst($user['skill_offer'])) ?: 'None' ?></div>
                </div>
                <div class="form-group">
                    <label>Skill Wanted</label>
                    <div style="font-size: 1.2rem; font-weight: bold;"><?= htmlspecialchars(ucfirst($user['skill_need'])) ?: 'None' ?></div>
                </div>
            </div>

            <!-- Notifications Column -->
            <div class="section-card">
                <h2>🔔 Notifications</h2>
                <?php if (count($notifications) > 0): ?>
                    <?php foreach ($notifications as $notif): ?>
                        <div class="notification-item">
                            <?= htmlspecialchars($notif['message']) ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="opacity: 0.7;">No new notifications.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Matches Section -->
        <h2 style="text-align: left; margin-top: 2rem;">Your Matches</h2>
        <?php if (count($matches) > 0): ?>
            <div class="dashboard-grid" style="margin-top: 1rem;">
                <?php foreach ($matches as $match): ?>
                    <div class="match-card" style="margin-top: 0; text-align: left;">
                        <h3><?= htmlspecialchars($match['partner_name']) ?></h3>
                        <div>
                            You are: 
                            <span class="role-badge role-<?= $match['my_role'] ?>">
                                <?= ucfirst($match['my_role']) ?>
                            </span>
                        </div>
                        <a href="chat.php?match_id=<?= $match['match_id'] ?>" class="btn-chat" style="width: auto; display: inline-block;">Chat Now</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="waiting-message">
                <h3>No matches yet...</h3>
                <p>We are searching for someone who connects with your skills.</p>
            </div>
        <?php endif; ?>

    </div>
</body>
</html>

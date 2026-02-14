<?php
// Function to find matches for a specific user
function findMatches($pdo, $user_id) {
    // 1. Get current user's skills
    $stmt = $pdo->prepare("SELECT skill_offer, skill_need FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $me = $stmt->fetch();

    if (!$me) return;

    $my_offer = $me['skill_offer'];
    $my_need = $me['skill_need'];

    // 2. Find potential matches
    // Scenario A: I am Mentor (My Offer == Their Need)
    if ($my_offer) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE skill_need = ? AND id != ?");
        $stmt->execute([$my_offer, $user_id]);
        $learners = $stmt->fetchAll();

        foreach ($learners as $learner) {
            createMatch($pdo, $user_id, $learner['id'], 'mentor', 'learner');
        }
    }

    // Scenario B: I am Learner (My Need == Their Offer)
    if ($my_need) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE skill_offer = ? AND id != ?");
        $stmt->execute([$my_need, $user_id]);
        $mentors = $stmt->fetchAll();

        foreach ($mentors as $mentor) {
            createMatch($pdo, $mentor['id'], $user_id, 'mentor', 'learner');
        }
    }
}

function createMatch($pdo, $u1, $u2, $r1, $r2) {
    // Ensure consistent ordering to prevent duplicates based on ID if we were doing peer-to-peer
    // But here logic dictates roles. However, we must ensure we don't insert if pair exists.
    // The DB has UNIQUE(user_id_1, user_id_2) but we need to check both directions just in case 
    // or rely on a standard order. 
    // Let's check if a match exists between these two regardless of direction.
    
    $stmt = $pdo->prepare("SELECT id FROM matches WHERE (user_id_1 = ? AND user_id_2 = ?) OR (user_id_1 = ? AND user_id_2 = ?)");
    $stmt->execute([$u1, $u2, $u2, $u1]);
    
    if ($stmt->rowCount() == 0) {
        // Create Match
        $stmt = $pdo->prepare("INSERT INTO matches (user_id_1, user_id_2, role_1, role_2) VALUES (?, ?, ?, ?)");
        $stmt->execute([$u1, $u2, $r1, $r2]);
        
        // Create Notifications
        $notif1 = "You have a new match! You are a $r1.";
        $notif2 = "You have a new match! You are a $r2.";
        
        $pdo->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)")->execute([$u1, $notif1]);
        $pdo->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)")->execute([$u2, $notif2]);
    }
}
?>

<?php
require 'db.php';
require 'auth.php';

if (!isLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id']; // This is the logged-in user's internal ID
$match_id = $_GET['match_id'] ?? null;

if ($match_id) {
    // Verify user belongs to match
    $stmt = $pdo->prepare("SELECT id FROM matches WHERE id = ? AND (user_id_1 = ? OR user_id_2 = ?)");
    $stmt->execute([$match_id, $user_id, $user_id]);

    if ($stmt->rowCount() == 0) {
        echo json_encode(['status' => 'error', 'message' => 'Access denied']);
        exit;
    }

    try {
        // Fetch messages with explicit sender_id
        $stmt = $pdo->prepare("SELECT sender_id, message, created_at FROM messages WHERE match_id = ? ORDER BY created_at ASC");
        $stmt->execute([$match_id]);
        $messages = $stmt->fetchAll();

        // Add 'alignment' metadata to help frontend if needed, 
        // though strictly standard is to return raw data and let JS decide.
        // But let's return clean JSON.
        
        $response = [];
        foreach($messages as $msg) {
            $response[] = [
                'sender_id' => (int)$msg['sender_id'],
                'message' => $msg['message'],
                'is_me' => ($msg['sender_id'] == $user_id) 
            ];
        }

        echo json_encode(['status' => 'success', 'messages' => $response]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Missing match_id']);
}
?>

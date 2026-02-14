<?php
require 'db.php';
require 'auth.php';

// Check auth but return JSON error if not logged in
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['match_id'], $data['message'])) {
    $match_id = $data['match_id'];
    $message = trim($data['message']);

    if (empty($message)) {
        echo json_encode(['status' => 'error', 'message' => 'Empty message']);
        exit;
    }

    // Verify user belongs to match
    $stmt = $pdo->prepare("SELECT id FROM matches WHERE id = ? AND (user_id_1 = ? OR user_id_2 = ?)");
    $stmt->execute([$match_id, $user_id, $user_id]);
    
    if ($stmt->rowCount() == 0) {
        http_response_code(403);
        echo json_encode(['status' => 'error', 'message' => 'Access denied to this match']);
        exit;
    }

    // Insert Message
    try {
        $stmt = $pdo->prepare("INSERT INTO messages (match_id, sender_id, message) VALUES (?, ?, ?)");
        $stmt->execute([$match_id, $user_id, $message]);
        echo json_encode(['status' => 'success']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
}
?>

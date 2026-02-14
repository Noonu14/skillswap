<?php
require 'db.php';
require 'auth.php';

requireLogin();

$user_id = $_SESSION['user_id'];
$match_id = isset($_GET['match_id']) ? (int)$_GET['match_id'] : 0;

if ($match_id <= 0) {
    header("Location: home_page.php");
    exit;
}


if (!$match_id) {
    header("Location: home_page.php");
    exit;
}

// 1. Verify user is part of this match
$stmt = $pdo->prepare("
    SELECT m.id, 
           m.user_id_1, 
           m.user_id_2,
           u1.username as user1_name,
           u2.username as user2_name
    FROM matches m
    JOIN users u1 ON m.user_id_1 = u1.id
    JOIN users u2 ON m.user_id_2 = u2.id
    WHERE m.id = ? AND (m.user_id_1 = ? OR m.user_id_2 = ?)
");
$stmt->execute([$match_id, $user_id, $user_id]);
$match_data = $stmt->fetch();

if (!$match_data) {
    die("Match not found or access denied.");
}

// Determine Partner ID and Name
if ($match_data['user_id_1'] == $user_id) {
    $partner_id = $match_data['user_id_2'];
    $partner_name = $match_data['user2_name'];
} else {
    $partner_id = $match_data['user_id_1'];
    $partner_name = $match_data['user1_name'];
}

// 2. Initial Message Fetch
$messages = [];
try {
    $stmt = $pdo->prepare("SELECT sender_id, message FROM messages WHERE match_id = ? ORDER BY created_at ASC");
    $stmt->execute([$match_id]);
    $messages = $stmt->fetchAll();
} catch (PDOException $e) {
    // Handle error quietly
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chat with <?= htmlspecialchars($partner_name) ?></title>
    <link rel="stylesheet" href="style.css">
    <style>
    .chat-header-info {
        font-size: 0.9rem;
        opacity: 0.8;
        margin-bottom: 1rem;
        background: rgba(0,0,0,0.2);
        padding: 5px 10px;
        border-radius: 5px;
        display: inline-block;
    }

    .chat-container {
        height: 400px;
        overflow-y: auto;
        padding: 15px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        background: #d0d0d0;
        border-radius: 10px;
    }

    .message {
        max-width: 60%;
        padding: 10px 14px;
        border-radius: 15px;
        word-wrap: break-word;
    }

    .message-right {
        align-self: flex-end;
        background-color: #4caf50;
        color: white;
        border-bottom-right-radius: 5px;
    }

    .message-left {
        align-self: flex-start;
        background-color: #333;
        color: white;
        border-bottom-left-radius: 5px;
    }
</style>

</head>
<body>
    <div class="container">
        <a href="home_page.php" style="color: white; text-decoration: underline; float: left;">&larr; Dashboard</a>
        
        <h1>Chat</h1>
        <div class="subtitle">
            With <strong><?= htmlspecialchars($partner_name) ?></strong>
        </div>
        
        <div class="chat-header-info">
            You are: <strong>User <?= htmlspecialchars($user_id) ?></strong> <br>
            Chatting with: <strong>User <?= htmlspecialchars($partner_id) ?></strong>
        </div>

        <!-- PHP Generated Chat Loop -->
        <div class="chat-container" id="chat-box">
            <?php foreach ($messages as $msg): ?>
                <?php 
                    // Strict Comparison: DB sender_id vs Session user_id
                    $sender_id = (int)$msg['sender_id'];
                    $current_user_id = (int)$user_id;

                    if ($sender_id === $current_user_id) {
                        $cssClass = 'message-right'; // My Message
                    } else {
                        $cssClass = 'message-left';  // Their Message
                    }
                ?>
                <div class="message <?= $cssClass ?>">
                    <?= htmlspecialchars($msg['message']) ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="chat-input-area">
            <input type="text" id="message-input" placeholder="Type a message...">
            <button onclick="sendMessage()">Send</button>
        </div>
    </div>

    <script>
        const matchId = <?= json_encode($match_id) ?>;
        const userId = <?= json_encode((int)$user_id) ?>; 
    </script>
    <script>
        function sendMessage() {
            const input = document.getElementById('message-input');
            const message = input.value.trim();

            if (!message) return;

            fetch('send_message.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    match_id: matchId,
                    message: message
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    input.value = '';
                    fetchMessages(); // Refresh immediately
                } else {
                    alert('Error: ' + data.message);
                }
            });
        }

        function fetchMessages() {
            fetch(`fetch_messages.php?match_id=${matchId}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const chatBox = document.getElementById('chat-box');
                    chatBox.innerHTML = '';
                    
                    data.messages.forEach(msg => {
                        const div = document.createElement('div');
                        div.classList.add('message');
                        
                        // Strict Comparison in JS
                        // msg.sender_id must be compared as int (handled by PHP casting or JS loose eq, but let's be strict)
                        if (parseInt(msg.sender_id) === userId) {
                            div.classList.add('message-right');
                        } else {
                            div.classList.add('message-left');
                        }
                        
                        div.textContent = msg.message;
                        chatBox.appendChild(div);
                    });
                    
                    // Note: In a real app, only scroll if already at bottom or on first load
                    // For now, simple auto-scroll
                    chatBox.scrollTop = chatBox.scrollHeight;
                }
            });
        }

        setInterval(fetchMessages, 3000); // Poll every 3 seconds
        
        // Scroll to bottom on load
        const chatBox = document.getElementById('chat-box');
        chatBox.scrollTop = chatBox.scrollHeight;
        
        document.getElementById('message-input').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') sendMessage();
        });
    </script>
</body>
</html>
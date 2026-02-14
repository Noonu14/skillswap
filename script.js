function sendMessage() {
    const input = document.getElementById('message-input');
    const message = input.value.trim();

    if (!message) return;

    fetch('send_message.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            sender_id: userId,
            receiver_id: matchId,
            message: message
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            input.value = '';
            fetchMessages(); // Refresh immediately
        } else {
            alert('Error sending message');
        }
    })
    .catch(error => console.error('Error:', error));
}

function fetchMessages() {
    fetch(`fetch_messages.php?user_id=${userId}&match_id=${matchId}`)
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            const chatBox = document.getElementById('chat-box');
            chatBox.innerHTML = ''; // Clear current messages
            
            if (data.messages.length === 0) {
                 chatBox.innerHTML = '<div style="text-align: center; color: #ccc;">No messages yet. Say hi!</div>';
                 return;
            }

            data.messages.forEach(msg => {
                const div = document.createElement('div');
                div.classList.add('message');
                div.classList.add(msg.sender_id == userId ? 'sent' : 'received');
                div.textContent = msg.message;
                chatBox.appendChild(div);
            });

            // Scroll to bottom (optional, basic implementation)
            // chatBox.scrollTop = chatBox.scrollHeight; 
        }
    })
    .catch(error => console.error('Error fetching messages:', error));
}

// Poll every 2 seconds
setInterval(fetchMessages, 2000);

// Initial load
fetchMessages();

// Allow Enter key to send
document.getElementById('message-input').addEventListener('keypress', function (e) {
    if (e.key === 'Enter') {
        sendMessage();
    }
});

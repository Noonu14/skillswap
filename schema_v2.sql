-- Drop old tables if they exist (Clean slate for V2)
DROP TABLE IF EXISTS messages;
DROP TABLE IF EXISTS skill_users; 
DROP TABLE IF EXISTS matches;
DROP TABLE IF EXISTS notifications;
DROP TABLE IF EXISTS users;

-- Users Table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    skill_offer VARCHAR(100),
    skill_need VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Matches Table
CREATE TABLE matches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id_1 INT NOT NULL,
    user_id_2 INT NOT NULL,
    role_1 ENUM('mentor', 'learner', 'peer') NOT NULL,
    role_2 ENUM('mentor', 'learner', 'peer') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_match (user_id_1, user_id_2),
    FOREIGN KEY (user_id_1) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id_2) REFERENCES users(id) ON DELETE CASCADE
);

-- Messages Table (Updated)
CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    match_id INT NOT NULL,
    sender_id INT NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (match_id) REFERENCES matches(id) ON DELETE CASCADE,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Notifications Table
CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

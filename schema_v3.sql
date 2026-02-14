-- Drop old tables if they exist (Clean slate for V3)
DROP TABLE IF EXISTS messages;
DROP TABLE IF EXISTS matches;
DROP TABLE IF EXISTS notifications;
DROP TABLE IF EXISTS que_ans;
DROP TABLE IF EXISTS users;

-- Users Table (Updated: unique_id instead of email)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    unique_id VARCHAR(50) NOT NULL UNIQUE,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    skill_offer VARCHAR(100),
    skill_need VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Security Questions Table
CREATE TABLE que_ans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    ans_chocolate VARCHAR(255) NOT NULL,
    ans_cousin VARCHAR(255) NOT NULL,
    ans_place VARCHAR(255) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
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

-- Messages Table
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

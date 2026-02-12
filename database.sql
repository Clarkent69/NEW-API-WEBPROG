CREATE DATABASE IF NOT EXISTS user_management;
USE user_management;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (username) VALUES 
('Japhet_user'),
('ImissHer_user'),
('SanaMissNyarinAko_user'),
('KamustaNaKayaSya_user'),
('ItsNotHerItsMe_user');

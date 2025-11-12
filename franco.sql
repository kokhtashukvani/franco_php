CREATE DATABASE IF NOT EXISTS franco;

USE franco;

CREATE TABLE IF NOT EXISTS announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    release_date DATE,
    is_show TINYINT(1) NOT NULL DEFAULT 1,
    display_order INT
);

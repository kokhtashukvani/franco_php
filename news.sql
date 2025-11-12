USE franco;

CREATE TABLE IF NOT EXISTS news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    is_show TINYINT(1) NOT NULL DEFAULT 1,
    display_order INT
);

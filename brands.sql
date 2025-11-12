USE franco;

CREATE TABLE IF NOT EXISTS brands (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    latin_title VARCHAR(255),
    logo_image VARCHAR(255),
    is_show TINYINT(1) NOT NULL DEFAULT 1,
    display_order INT
);

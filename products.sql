USE franco;

CREATE TABLE IF NOT EXISTS product_groups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(255),
    title VARCHAR(255) NOT NULL,
    is_show TINYINT(1) NOT NULL DEFAULT 1,
    display_order INT
);

CREATE TABLE IF NOT EXISTS sub_products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(255),
    title VARCHAR(255) NOT NULL,
    brand_id INT,
    product_group_id INT,
    stock_status TINYINT(1) NOT NULL DEFAULT 1,
    count_in_bag INT,
    cach_price DECIMAL(15, 2),
    no_cach_price DECIMAL(15, 2),
    is_show TINYINT(1) NOT NULL DEFAULT 1,
    display_order INT,
    FOREIGN KEY (brand_id) REFERENCES brands(id) ON DELETE SET NULL,
    FOREIGN KEY (product_group_id) REFERENCES product_groups(id) ON DELETE CASCADE
);

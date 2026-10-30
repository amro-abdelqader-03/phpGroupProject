CREATE DATABASE IF NOT EXISTS wedding_store;

USE wedding_store;

CREATE TABLE IF NOT EXISTS users(
    id INT PRIMARY KEY AUTO_INCREMENT,
    full_name varchar(255) NOT NULL,
    email varchar(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

);

CREATE TABLE IF NOT EXISTS admin(
    id INT PRIMARY KEY AUTO_INCREMENT,
    full_name varchar(255) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS address(
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    country VARCHAR(255) NOT NULL,
    city VARCHAR(255) NOT NULL,
    street VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    building VARCHAR(255),
     FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS categories(
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    status ENUM('active', 'inactive') DEFAULT 'active'
);

CREATE TABLE IF NOT EXISTS subcategories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL ,
    category_id INT NOT NULL ,
    description VARCHAR(255),


    FOREIGN KEY (category_id)
        REFERENCES categories(id)
        ON DELETE CASCADE
);
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subcategory_id INT NOT NULL,
    product_name VARCHAR(150) NOT NULL,
    description TEXT,
    cost_price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    price DECIMAL(10,2) NOT NULL,
    image_url VARCHAR(500),
    stock_quantity INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,


    FOREIGN KEY (subcategory_id)
        REFERENCES subcategories(id)
        ON DELETE RESTRICT
);

CREATE TABLE product_variants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    size VARCHAR(30),
    color VARCHAR(50),
    stock_quantity INT NOT NULL DEFAULT 0,

    FOREIGN KEY (product_id)
        REFERENCES products(id)
        ON DELETE CASCADE
);

CREATE TABLE product_images (
    image_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image_url VARCHAR(500) NOT NULL,
    is_primary BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (product_id)
        REFERENCES products(id)
        ON DELETE CASCADE
);

CREATE TABLE carts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
);

CREATE TABLE cart_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cart_id INT NOT NULL,
    product_id INT NOT NULL,
    variant_id INT NULL,
    quantity INT NOT NULL DEFAULT 1,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (cart_id)
        REFERENCES carts(id)
        ON DELETE CASCADE,

    FOREIGN KEY (product_id)
        REFERENCES products(id)
        ON DELETE CASCADE,

    FOREIGN KEY (variant_id)
        REFERENCES product_variants(id)
        ON DELETE SET NULL
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    order_status ENUM(
        'Pending',
        'Confirmed',
        'Shipped',
        'Delivered',
        'Cancelled'
    ) DEFAULT 'Pending',

    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    country VARCHAR(100) NOT NULL DEFAULT 'Jordan',
    city VARCHAR(100) NOT NULL,
    area VARCHAR(100),
    shipping_address VARCHAR(255) NOT NULL,
    building_details VARCHAR(255),
    shipping_cost DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    revenue DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    

    FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE RESTRICT
);

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    variant_id INT NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,

    FOREIGN KEY (order_id)
        REFERENCES orders(id)
        ON DELETE CASCADE,

    FOREIGN KEY (product_id)
        REFERENCES products(id)
        ON DELETE RESTRICT,

    FOREIGN KEY (variant_id)
        REFERENCES product_variants(id)
        ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS wishlist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE,

    FOREIGN KEY (product_id)
        REFERENCES products(id)
        ON DELETE CASCADE,

    UNIQUE (user_id, product_id)
);
CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    payment_method ENUM(
        'Cash on Delivery',
        'Credit Card'
    ) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_status ENUM(
        'Pending',
        'Paid',
        'Failed',
        'Refunded'
    ) DEFAULT 'Pending',
    transaction_reference VARCHAR(255),
    paid_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (order_id)
        REFERENCES orders(id)
        ON DELETE RESTRICT
);

INSERT INTO categories (id, name, description, status) VALUES
(1, 'Dresses', 'Bridal, evening, party and formal dresses for the bride and guests.', 'active'),
(2, 'Accessories', 'Bridal accessories including veils, gloves, jewelry, tiaras and crowns.', 'active'),
(3, 'Men''s Suits', 'Wedding and occasion suits and tuxedos for the groom and guests.', 'active'),
(4, 'Men''s Shoes', 'Formal shoes to complete the groom and guests'' looks.', 'active'),
(5, 'Women''s Heels', 'Bridal and occasion heels for the bride and guests.', 'active'),
(6, 'Men''s Accessories', 'Bow ties, neckties and pocket squares for the groom and guests.', 'active'),
(7, 'Women''s Shoes', 'Bridal sandals and flats for the bride and guests.', 'active')
ON DUPLICATE KEY UPDATE name=VALUES(name), description=VALUES(description), status=VALUES(status);



INSERT INTO subcategories (id, name, category_id, description) VALUES
(1, 'A Line', 1, 'A Line products for weddings and special occasions.'),
(2, 'Mermaid', 1, 'Mermaid products for weddings and special occasions.'),
(3, 'Princess', 1, 'Princess products for weddings and special occasions.'),
(4, 'Royal', 1, 'Royal products for weddings and special occasions.'),
(5, 'Sheath', 1, 'Sheath products for weddings and special occasions.'),
(6, 'Gloves', 2, 'Gloves products for weddings and special occasions.'),
(7, 'Jewlery', 2, 'Jewlery products for weddings and special occasions.'),
(8, 'Taras And Crown', 2, 'Taras And Crown products for weddings and special occasions.'),
(9, 'Veils', 2, 'Veils products for weddings and special occasions.'),
(10, '3 Piece Suit', 3, '3 Piece Suit products for weddings and special occasions.'),
(11, 'Double Breasted Suit', 3, 'Double Breasted Suit products for weddings and special occasions.'),
(12, 'Regular Fit', 3, 'Regular Fit products for weddings and special occasions.'),
(13, 'Slim Fit', 3, 'Slim Fit products for weddings and special occasions.'),
(14, 'Tuxedo', 3, 'Tuxedo products for weddings and special occasions.'),
(15, 'Derby', 4, 'Derby products for weddings and special occasions.'),
(16, 'Loarfers', 4, 'Loarfers products for weddings and special occasions.'),
(17, 'Monk Strap', 4, 'Monk Strap products for weddings and special occasions.'),
(18, 'Oxford', 4, 'Oxford products for weddings and special occasions.'),
(19, 'Bloch Heels', 5, 'Bloch Heels products for weddings and special occasions.'),
(20, 'High Heels', 5, 'High Heels products for weddings and special occasions.'),
(21, 'Bow Ties', 6, 'Bow Ties products for weddings and special occasions.'),
(22, 'Neck Ties', 6, 'Neck Ties products for weddings and special occasions.'),
(23, 'Pocket Squares', 6, 'Pocket Squares products for weddings and special occasions.'),
(24, 'Bridal Sandal', 7, 'Bridal Sandal products for weddings and special occasions.'),
(25, 'Flats', 7, 'Flats products for weddings and special occasions.')
ON DUPLICATE KEY UPDATE name=VALUES(name), category_id=VALUES(category_id), description=VALUES(description);


INSERT INTO products (id, subcategory_id, product_name, description, cost_price, price, image_url, stock_quantity) VALUES
(1, 1, 'A Line Bridal Wedding Dress', 'A Line bridal gown for weddings and formal occasions.', 180.00, 299.00, '/Data/women/dress/a_line/primary.png', 5),
(2, 2, 'Mermaid Bridal Wedding Dress', 'Mermaid bridal gown for weddings and formal occasions.', 180.00, 299.00, '/Data/women/dress/mermaid/primary.png', 5),
(3, 3, 'Princess Bridal Wedding Dress', 'Princess bridal gown for weddings and formal occasions.', 180.00, 299.00, '/Data/women/dress/princess/primary.png', 5),
(4, 4, 'Royal Bridal Wedding Dress', 'Royal bridal gown for weddings and formal occasions.', 180.00, 299.00, '/Data/women/dress/royal/primary.png', 5),
(5, 5, 'Sheath Bridal Wedding Dress', 'Sheath bridal gown for weddings and formal occasions.', 180.00, 299.00, '/Data/women/dress/sheath/primary.png', 5),
(6, 10, '3 Piece Suit Suit 01', '3 Piece Suit men''s wedding suit for the groom and special occasions.', 95.00, 169.00, '/Data/men/suits/3%20piece%20suit/01/primary.webp', 8),
(7, 10, '3 Piece Suit Suit 02', '3 Piece Suit men''s wedding suit for the groom and special occasions.', 95.00, 169.00, '/Data/men/suits/3%20piece%20suit/02/primary.webp', 8),
(8, 11, 'Double Breasted Suit Suit 01', 'Double Breasted Suit men''s wedding suit for the groom and special occasions.', 95.00, 169.00, '/Data/men/suits/double%20breasted%20suit/01/primary.png', 8),
(9, 12, 'Regular Fit Suit 01', 'Regular Fit men''s wedding suit for the groom and special occasions.', 95.00, 169.00, '/Data/men/suits/regular%20fit/01/primary.png', 8),
(10, 13, 'Slim Fit Suit 01', 'Slim Fit men''s wedding suit for the groom and special occasions.', 95.00, 169.00, '/Data/men/suits/slim%20fit/01/primary.png', 8),
(11, 14, 'Tuxedo Suit 01', 'Tuxedo men''s wedding suit for the groom and special occasions.', 95.00, 169.00, '/Data/men/suits/tuxedo/01/primary.jpg', 8),
(12, 21, 'Bow Ties Black', 'Bow Ties accessory for weddings and special occasions.', 8.00, 19.00, '/Data/men/accessories/bow%20ties/black.jpg', 15),
(13, 21, 'Bow Ties Blue', 'Bow Ties accessory for weddings and special occasions.', 8.00, 19.00, '/Data/men/accessories/bow%20ties/blue.png', 15),
(14, 21, 'Bow Ties Gray', 'Bow Ties accessory for weddings and special occasions.', 8.00, 19.00, '/Data/men/accessories/bow%20ties/gray.png', 15),
(15, 21, 'Bow Ties Red', 'Bow Ties accessory for weddings and special occasions.', 8.00, 19.00, '/Data/men/accessories/bow%20ties/red.png', 15),
(16, 22, 'Neck Ties Green Tie', 'Neck Ties accessory for weddings and special occasions.', 8.00, 19.00, '/Data/men/accessories/neck%20ties/green_tie.jpg', 15),
(17, 22, 'Neck Ties 1', 'Neck Ties accessory for weddings and special occasions.', 8.00, 19.00, '/Data/men/accessories/neck%20ties/Screenshot%202026-10-29%20135339.png', 15),
(18, 22, 'Neck Ties 2', 'Neck Ties accessory for weddings and special occasions.', 8.00, 19.00, '/Data/men/accessories/neck%20ties/Screenshot%202026-10-29%20135424.png', 15),
(19, 23, 'Pocket Squares 1', 'Pocket Squares accessory for weddings and special occasions.', 8.00, 19.00, '/Data/men/accessories/pocket%20squares/Screenshot%202026-10-29%20135623.png', 15),
(20, 23, 'Pocket Squares 2', 'Pocket Squares accessory for weddings and special occasions.', 8.00, 19.00, '/Data/men/accessories/pocket%20squares/Screenshot%202026-10-29%20135648.png', 15),
(21, 23, 'Pocket Squares 3', 'Pocket Squares accessory for weddings and special occasions.', 8.00, 19.00, '/Data/men/accessories/pocket%20squares/shopping%20(1).jpg', 15),
(22, 15, 'Derby 1', 'Derby footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/men/shoes/derby/Screenshot%202026-10-29%20150136.png', 12),
(23, 15, 'Derby 2', 'Derby footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/men/shoes/derby/Screenshot%202026-10-29%20150144.png', 12),
(24, 15, 'Derby 3', 'Derby footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/men/shoes/derby/Screenshot%202026-10-29%20150445.png', 12),
(25, 16, 'Loarfers 1', 'Loarfers footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/men/shoes/loarfers/Screenshot%202026-10-29%20150610.png', 12),
(26, 16, 'Loarfers 2', 'Loarfers footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/men/shoes/loarfers/Screenshot%202026-10-29%20150617.png', 12),
(27, 16, 'Loarfers 3', 'Loarfers footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/men/shoes/loarfers/Screenshot%202026-10-29%20150623.png', 12),
(28, 17, 'Monk Strap 1', 'Monk Strap footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/men/shoes/monk%20strap/Screenshot%202026-10-29%20150915.png', 12),
(29, 17, 'Monk Strap 2', 'Monk Strap footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/men/shoes/monk%20strap/Screenshot%202026-10-29%20150925.png', 12),
(30, 17, 'Monk Strap 3', 'Monk Strap footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/men/shoes/monk%20strap/Screenshot%202026-10-29%20150936.png', 12),
(31, 18, 'Oxford 1', 'Oxford footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/men/shoes/oxford/Screenshot%202026-10-29%20151018.png', 12),
(32, 18, 'Oxford 2', 'Oxford footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/men/shoes/oxford/Screenshot%202026-10-29%20151025.png', 12),
(33, 18, 'Oxford 3', 'Oxford footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/men/shoes/oxford/Screenshot%202026-10-29%20151033.png', 12),
(34, 6, 'Gloves 1', 'Gloves accessory for weddings and special occasions.', 8.00, 19.00, '/Data/women/accessories/gloves/Screenshot%202026-10-29%20144001.png', 15),
(35, 6, 'Gloves 2', 'Gloves accessory for weddings and special occasions.', 8.00, 19.00, '/Data/women/accessories/gloves/Screenshot%202026-10-29%20144018.png', 15),
(36, 6, 'Gloves 3', 'Gloves accessory for weddings and special occasions.', 8.00, 19.00, '/Data/women/accessories/gloves/Screenshot%202026-10-29%20144033.png', 15),
(37, 7, 'Jewlery 1', 'Jewlery accessory for weddings and special occasions.', 8.00, 19.00, '/Data/women/accessories/jewlery/Screenshot%202026-10-29%20144210.png', 15),
(38, 7, 'Jewlery 2', 'Jewlery accessory for weddings and special occasions.', 8.00, 19.00, '/Data/women/accessories/jewlery/Screenshot%202026-10-29%20144221.png', 15),
(39, 7, 'Jewlery 3', 'Jewlery accessory for weddings and special occasions.', 8.00, 19.00, '/Data/women/accessories/jewlery/Screenshot%202026-10-29%20144250.png', 15),
(40, 8, 'Taras And Crown 1', 'Taras And Crown accessory for weddings and special occasions.', 8.00, 19.00, '/Data/women/accessories/taras%20and%20crown/Screenshot%202026-10-29%20144427.png', 15),
(41, 8, 'Taras And Crown 2', 'Taras And Crown accessory for weddings and special occasions.', 8.00, 19.00, '/Data/women/accessories/taras%20and%20crown/Screenshot%202026-10-29%20144443.png', 15),
(42, 8, 'Taras And Crown 3', 'Taras And Crown accessory for weddings and special occasions.', 8.00, 19.00, '/Data/women/accessories/taras%20and%20crown/Screenshot%202026-10-29%20144451.png', 15),
(43, 9, 'Veils 1', 'Veils accessory for weddings and special occasions.', 8.00, 19.00, '/Data/women/accessories/veils/Screenshot%202026-10-29%20144546.png', 15),
(44, 9, 'Veils 2', 'Veils accessory for weddings and special occasions.', 8.00, 19.00, '/Data/women/accessories/veils/Screenshot%202026-10-29%20144600.png', 15),
(45, 9, 'Veils 3', 'Veils accessory for weddings and special occasions.', 8.00, 19.00, '/Data/women/accessories/veils/Screenshot%202026-10-29%20144610.png', 15),
(46, 19, 'Bloch Heels 1', 'Bloch Heels footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/women/shoes/bloch%20heels/Screenshot%202026-10-29%20145357.png', 12),
(47, 19, 'Bloch Heels 2', 'Bloch Heels footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/women/shoes/bloch%20heels/Screenshot%202026-10-29%20145406.png', 12),
(48, 19, 'Bloch Heels 3', 'Bloch Heels footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/women/shoes/bloch%20heels/Screenshot%202026-10-29%20145413.png', 12),
(49, 24, 'Bridal Sandal 1', 'Bridal Sandal footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/women/shoes/bridal%20sandal/Screenshot%202026-10-29%20145528.png', 12),
(50, 24, 'Bridal Sandal 2', 'Bridal Sandal footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/women/shoes/bridal%20sandal/Screenshot%202026-10-29%20145539.png', 12),
(51, 24, 'Bridal Sandal 3', 'Bridal Sandal footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/women/shoes/bridal%20sandal/Screenshot%202026-10-29%20145546.png', 12),
(52, 25, 'Flats 1', 'Flats footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/women/shoes/flats/Screenshot%202026-10-29%20145821.png', 12),
(53, 25, 'Flats 2', 'Flats footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/women/shoes/flats/Screenshot%202026-10-29%20145855.png', 12),
(54, 20, 'High Heels 1', 'High Heels footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/women/shoes/high%20heels/Screenshot%202026-10-29%20145954.png', 12),
(55, 20, 'High Heels 2', 'High Heels footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/women/shoes/high%20heels/Screenshot%202026-10-29%20150010.png', 12),
(56, 20, 'High Heels 3', 'High Heels footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/women/shoes/high%20heels/Screenshot%202026-10-29%20150021.png', 12);


INSERT INTO products (id, subcategory_id, product_name, description, cost_price, price, image_url, stock_quantity) VALUES
(1, 1, 'A Line Bridal Wedding Dress', 'A Line bridal gown for weddings and formal occasions.', 180.00, 299.00, '/Data/women/dress/a_line/primary.png', 5),
(2, 2, 'Mermaid Bridal Wedding Dress', 'Mermaid bridal gown for weddings and formal occasions.', 180.00, 299.00, '/Data/women/dress/mermaid/primary.png', 5),
(3, 3, 'Princess Bridal Wedding Dress', 'Princess bridal gown for weddings and formal occasions.', 180.00, 299.00, '/Data/women/dress/princess/primary.png', 5),
(4, 4, 'Royal Bridal Wedding Dress', 'Royal bridal gown for weddings and formal occasions.', 180.00, 299.00, '/Data/women/dress/royal/primary.png', 5),
(5, 5, 'Sheath Bridal Wedding Dress', 'Sheath bridal gown for weddings and formal occasions.', 180.00, 299.00, '/Data/women/dress/sheath/primary.png', 5),
(6, 10, '3 Piece Suit Suit 01', '3 Piece Suit men''s wedding suit for the groom and special occasions.', 95.00, 169.00, '/Data/men/suits/3%20piece%20suit/01/primary.webp', 8),
(7, 10, '3 Piece Suit Suit 02', '3 Piece Suit men''s wedding suit for the groom and special occasions.', 95.00, 169.00, '/Data/men/suits/3%20piece%20suit/02/primary.webp', 8),
(8, 11, 'Double Breasted Suit Suit 01', 'Double Breasted Suit men''s wedding suit for the groom and special occasions.', 95.00, 169.00, '/Data/men/suits/double%20breasted%20suit/01/primary.png', 8),
(9, 12, 'Regular Fit Suit 01', 'Regular Fit men''s wedding suit for the groom and special occasions.', 95.00, 169.00, '/Data/men/suits/regular%20fit/01/primary.png', 8),
(10, 13, 'Slim Fit Suit 01', 'Slim Fit men''s wedding suit for the groom and special occasions.', 95.00, 169.00, '/Data/men/suits/slim%20fit/01/primary.png', 8),
(11, 14, 'Tuxedo Suit 01', 'Tuxedo men''s wedding suit for the groom and special occasions.', 95.00, 169.00, '/Data/men/suits/tuxedo/01/primary.jpg', 8),
(12, 21, 'Bow Ties Black', 'Bow Ties accessory for weddings and special occasions.', 8.00, 19.00, '/Data/men/accessories/bow%20ties/black.jpg', 15),
(13, 21, 'Bow Ties Blue', 'Bow Ties accessory for weddings and special occasions.', 8.00, 19.00, '/Data/men/accessories/bow%20ties/blue.png', 15),
(14, 21, 'Bow Ties Gray', 'Bow Ties accessory for weddings and special occasions.', 8.00, 19.00, '/Data/men/accessories/bow%20ties/gray.png', 15),
(15, 21, 'Bow Ties Red', 'Bow Ties accessory for weddings and special occasions.', 8.00, 19.00, '/Data/men/accessories/bow%20ties/red.png', 15),
(16, 22, 'Neck Ties Green Tie', 'Neck Ties accessory for weddings and special occasions.', 8.00, 19.00, '/Data/men/accessories/neck%20ties/green_tie.jpg', 15),
(17, 22, 'Neck Ties 1', 'Neck Ties accessory for weddings and special occasions.', 8.00, 19.00, '/Data/men/accessories/neck%20ties/Screenshot%202026-10-29%20135339.png', 15),
(18, 22, 'Neck Ties 2', 'Neck Ties accessory for weddings and special occasions.', 8.00, 19.00, '/Data/men/accessories/neck%20ties/Screenshot%202026-10-29%20135424.png', 15),
(19, 23, 'Pocket Squares 1', 'Pocket Squares accessory for weddings and special occasions.', 8.00, 19.00, '/Data/men/accessories/pocket%20squares/Screenshot%202026-10-29%20135623.png', 15),
(20, 23, 'Pocket Squares 2', 'Pocket Squares accessory for weddings and special occasions.', 8.00, 19.00, '/Data/men/accessories/pocket%20squares/Screenshot%202026-10-29%20135648.png', 15),
(21, 23, 'Pocket Squares 3', 'Pocket Squares accessory for weddings and special occasions.', 8.00, 19.00, '/Data/men/accessories/pocket%20squares/shopping%20(1).jpg', 15),
(22, 15, 'Derby 1', 'Derby footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/men/shoes/derby/Screenshot%202026-10-29%20150136.png', 12),
(23, 15, 'Derby 2', 'Derby footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/men/shoes/derby/Screenshot%202026-10-29%20150144.png', 12),
(24, 15, 'Derby 3', 'Derby footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/men/shoes/derby/Screenshot%202026-10-29%20150445.png', 12),
(25, 16, 'Loarfers 1', 'Loarfers footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/men/shoes/loarfers/Screenshot%202026-10-29%20150610.png', 12),
(26, 16, 'Loarfers 2', 'Loarfers footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/men/shoes/loarfers/Screenshot%202026-10-29%20150617.png', 12),
(27, 16, 'Loarfers 3', 'Loarfers footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/men/shoes/loarfers/Screenshot%202026-10-29%20150623.png', 12),
(28, 17, 'Monk Strap 1', 'Monk Strap footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/men/shoes/monk%20strap/Screenshot%202026-10-29%20150915.png', 12),
(29, 17, 'Monk Strap 2', 'Monk Strap footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/men/shoes/monk%20strap/Screenshot%202026-10-29%20150925.png', 12),
(30, 17, 'Monk Strap 3', 'Monk Strap footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/men/shoes/monk%20strap/Screenshot%202026-10-29%20150936.png', 12),
(31, 18, 'Oxford 1', 'Oxford footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/men/shoes/oxford/Screenshot%202026-10-29%20151018.png', 12),
(32, 18, 'Oxford 2', 'Oxford footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/men/shoes/oxford/Screenshot%202026-10-29%20151025.png', 12),
(33, 18, 'Oxford 3', 'Oxford footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/men/shoes/oxford/Screenshot%202026-10-29%20151033.png', 12),
(34, 6, 'Gloves 1', 'Gloves accessory for weddings and special occasions.', 8.00, 19.00, '/Data/women/accessories/gloves/Screenshot%202026-10-29%20144001.png', 15),
(35, 6, 'Gloves 2', 'Gloves accessory for weddings and special occasions.', 8.00, 19.00, '/Data/women/accessories/gloves/Screenshot%202026-10-29%20144018.png', 15),
(36, 6, 'Gloves 3', 'Gloves accessory for weddings and special occasions.', 8.00, 19.00, '/Data/women/accessories/gloves/Screenshot%202026-10-29%20144033.png', 15),
(37, 7, 'Jewlery 1', 'Jewlery accessory for weddings and special occasions.', 8.00, 19.00, '/Data/women/accessories/jewlery/Screenshot%202026-10-29%20144210.png', 15),
(38, 7, 'Jewlery 2', 'Jewlery accessory for weddings and special occasions.', 8.00, 19.00, '/Data/women/accessories/jewlery/Screenshot%202026-10-29%20144221.png', 15),
(39, 7, 'Jewlery 3', 'Jewlery accessory for weddings and special occasions.', 8.00, 19.00, '/Data/women/accessories/jewlery/Screenshot%202026-10-29%20144250.png', 15),
(40, 8, 'Taras And Crown 1', 'Taras And Crown accessory for weddings and special occasions.', 8.00, 19.00, '/Data/women/accessories/taras%20and%20crown/Screenshot%202026-10-29%20144427.png', 15),
(41, 8, 'Taras And Crown 2', 'Taras And Crown accessory for weddings and special occasions.', 8.00, 19.00, '/Data/women/accessories/taras%20and%20crown/Screenshot%202026-10-29%20144443.png', 15),
(42, 8, 'Taras And Crown 3', 'Taras And Crown accessory for weddings and special occasions.', 8.00, 19.00, '/Data/women/accessories/taras%20and%20crown/Screenshot%202026-10-29%20144451.png', 15),
(43, 9, 'Veils 1', 'Veils accessory for weddings and special occasions.', 8.00, 19.00, '/Data/women/accessories/veils/Screenshot%202026-10-29%20144546.png', 15),
(44, 9, 'Veils 2', 'Veils accessory for weddings and special occasions.', 8.00, 19.00, '/Data/women/accessories/veils/Screenshot%202026-10-29%20144600.png', 15),
(45, 9, 'Veils 3', 'Veils accessory for weddings and special occasions.', 8.00, 19.00, '/Data/women/accessories/veils/Screenshot%202026-10-29%20144610.png', 15),
(46, 19, 'Bloch Heels 1', 'Bloch Heels footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/women/shoes/bloch%20heels/Screenshot%202026-10-29%20145357.png', 12),
(47, 19, 'Bloch Heels 2', 'Bloch Heels footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/women/shoes/bloch%20heels/Screenshot%202026-10-29%20145406.png', 12),
(48, 19, 'Bloch Heels 3', 'Bloch Heels footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/women/shoes/bloch%20heels/Screenshot%202026-10-29%20145413.png', 12),
(49, 24, 'Bridal Sandal 1', 'Bridal Sandal footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/women/shoes/bridal%20sandal/Screenshot%202026-10-29%20145528.png', 12),
(50, 24, 'Bridal Sandal 2', 'Bridal Sandal footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/women/shoes/bridal%20sandal/Screenshot%202026-10-29%20145539.png', 12),
(51, 24, 'Bridal Sandal 3', 'Bridal Sandal footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/women/shoes/bridal%20sandal/Screenshot%202026-10-29%20145546.png', 12),
(52, 25, 'Flats 1', 'Flats footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/women/shoes/flats/Screenshot%202026-10-29%20145821.png', 12),
(53, 25, 'Flats 2', 'Flats footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/women/shoes/flats/Screenshot%202026-10-29%20145855.png', 12),
(54, 20, 'High Heels 1', 'High Heels footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/women/shoes/high%20heels/Screenshot%202026-10-29%20145954.png', 12),
(55, 20, 'High Heels 2', 'High Heels footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/women/shoes/high%20heels/Screenshot%202026-10-29%20150010.png', 12),
(56, 20, 'High Heels 3', 'High Heels footwear suitable for weddings and formal occasions.', 25.00, 49.00, '/Data/women/shoes/high%20heels/Screenshot%202026-10-29%20150021.png', 12);


INSERT INTO admin (full_name, email, password_hash)
VALUES (
    'Wedding Store Admin',
    'admin@weddingstore.com',
   $2y$10$XtKP/UFjYQ9AXeGZsP6e1u09QJoL93YH72p19kCRWACLQs.UGJaTq;
);


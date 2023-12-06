CREATE DATABASE projet_eco_php;
USE projet_eco_php;

-- Création des tables
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    user_type ENUM('admin', 'client') NOT NULL
);

CREATE TABLE commands (
    command_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    total_price DECIMAL(10, 2) NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE clothes (
    clothes_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    size VARCHAR(10) NOT NULL,
    description TEXT
);

CREATE TABLE command_items (
    command_item_id INT PRIMARY KEY AUTO_INCREMENT,
    command_id INT,
    clothes_id INT,
    quantity INT NOT NULL,
    FOREIGN KEY (command_id) REFERENCES commands(command_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (clothes_id) REFERENCES clothes(clothes_id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE photos (
    photo_id INT PRIMARY KEY AUTO_INCREMENT,
    clothes_id INT,
    file_path VARCHAR(255) NOT NULL,
    FOREIGN KEY (clothes_id) REFERENCES clothes(clothes_id) ON DELETE CASCADE ON UPDATE CASCADE
);

-- Insertion des données

-- Utilisateurs
INSERT INTO users (username, password, email, user_type) VALUES
('admin1', 'hashed_admin_password1', 'admin1@example.com', 'admin'),
('client1', 'hashed_client_password1', 'client1@example.com', 'client'),
('client2', 'hashed_client_password2', 'client2@example.com', 'client'),
('client3', 'hashed_client_password3', 'client3@example.com', 'client');

-- Commandes
INSERT INTO commands (user_id, total_price) VALUES
(2, 150.00),
(3, 80.00),
(4, 200.00),
(2, 120.00);

-- Vêtements
INSERT INTO clothes (name, price, size, description) VALUES
('T-shirt', 25.00, 'M', 'Cotton T-shirt'),
('Jeans', 50.00, '32', 'Slim-fit Jeans'),
('Hoodie', 30.00, 'L', 'Fleece Hoodie'),
('Dress Shirt', 45.00, 'M', 'Formal Dress Shirt');

-- Command Items (Associations entre commandes et vêtements)
INSERT INTO command_items (command_id, clothes_id, quantity) VALUES
(1, 1, 2),
(1, 2, 1),
(2, 3, 3),
(3, 4, 2),
(4, 1, 1),
(4, 3, 2);

-- Photos
INSERT INTO photos (clothes_id, file_path) VALUES
(1, '/images/tshirt.jpg'),
(2, '/images/jeans.jpg'),
(3, '/images/hoodie.jpg'),
(4, '/images/dressshirt.jpg');

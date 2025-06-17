CREATE DATABASE MiTiendaOnline;
USE MiTiendaOnline;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    phone VARCHAR(15) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    address VARCHAR(255),
    email VARCHAR(100),
    state VARCHAR(50),
    city VARCHAR(50),
    points INT DEFAULT 0,
    is_admin TINYINT(1) DEFAULT 0
);

CREATE TABLE prizes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    points_required INT NOT NULL
);

CREATE TABLE benefits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_name VARCHAR(100) NOT NULL,
    description TEXT,
    discount VARCHAR(50)
);

CREATE TABLE point_transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    points INT NOT NULL,
    transaction_type ENUM('add', 'redeem') NOT NULL,
    description VARCHAR(255),
    transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
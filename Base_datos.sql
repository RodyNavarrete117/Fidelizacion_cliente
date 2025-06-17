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
USE loyalty_program;

-- Insertar usuarios de ejemplo (clientes y un administrador)
INSERT INTO users (phone, password, name, last_name, address, email, state, city, points, is_admin) VALUES
('5512345678', '123456', 'Juan', 'Pérez García', 'Calle Falsa 123, Col. Centro', 'juan.perez@example.com', 'Ciudad de México', 'CDMX', 150, 0),
('5523456789', '123456', 'María', 'López Hernández', 'Av. Siempre Viva 456', 'maria.lopez@example.com', 'Jalisco', 'Guadalajara', 200, 0),
('5534567890', '123456', 'Carlos', 'Gómez Ramírez', 'Calle Luna 789, Col. Estrella', 'carlos.gomez@example.com', 'Nuevo León', 'Monterrey', 50, 0),
('5545678901', '123456', 'Ana', 'Martínez Torres', 'Blvd. Independencia 101', 'ana.martinez@example.com', 'Querétaro', 'Querétaro', 300, 0),
('5556789012', 'admin123', 'Admin', 'Sistema', 'Oficina Central', 'admin@example.com', 'Ciudad de México', 'CDMX', 0, 1);

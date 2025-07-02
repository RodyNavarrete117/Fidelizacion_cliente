CREATE DATABASE MiTiendaOnline;
USE MiTiendaOnline;

-- Tabla de usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    telefono VARCHAR(15) UNIQUE NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    apellidos VARCHAR(50) NOT NULL,
    direccion VARCHAR(255),
    correo VARCHAR(100),
    estado VARCHAR(50),
    ciudad VARCHAR(50),
    puntos INT DEFAULT 0,
    es_admin TINYINT(1) DEFAULT 0
);

-- Tabla de premios
CREATE TABLE premios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    puntos_requeridos INT NOT NULL
);

-- Tabla de beneficios
CREATE TABLE beneficios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_empresa VARCHAR(100) NOT NULL,
    descripcion TEXT,
    descuento VARCHAR(50)
);

-- Tabla de transacciones de puntos
CREATE TABLE transacciones_puntos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    puntos INT NOT NULL,
    tipo_transaccion ENUM('agregar', 'canjear') NOT NULL,
    descripcion VARCHAR(255),
    fecha_transaccion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
);

-- Insertar usuarios de ejemplo
INSERT INTO usuarios (telefono, contrasena, nombre, apellidos, direccion, correo, estado, ciudad, puntos, es_admin) VALUES
('5512345678', '123456', 'Juan', 'Pérez García', 'Calle Falsa 123, Col. Centro', 'juan.perez@ejemplo.com', 'Ciudad de México', 'CDMX', 150, 0),
('5523456789', '123456', 'María', 'López Hernández', 'Av. Siempre Viva 456', 'maria.lopez@ejemplo.com', 'Jalisco', 'Guadalajara', 200, 0),
('5534567890', '123456', 'Carlos', 'Gómez Ramírez', 'Calle Luna 789, Col. Estrella', 'carlos.gomez@ejemplo.com', 'Nuevo León', 'Monterrey', 50, 0),
('5545678901', '123456', 'Ana', 'Martínez Torres', 'Blvd. Independencia 101', 'ana.martinez@ejemplo.com', 'Querétaro', 'Querétaro', 300, 0),
('5556789012', 'admin123', 'Admin', 'Sistema', 'Oficina Central', 'admin@ejemplo.com', 'Ciudad de México', 'CDMX', 0, 1);

DROP DATABASE IF EXISTS pastillas_codigo;

CREATE DATABASE IF NOT EXISTS pastillas_codigo DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;
USE pastillas_codigo;

CREATE TABLE IF NOT EXISTS clientes_listado (
    id INT AUTO_INCREMENT,
    correo VARCHAR(255) NOT NULL,
    nombre VARCHAR(255) NOT NULL,
    ciudad VARCHAR(255) NOT NULL,
    telefono VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=INNODB;

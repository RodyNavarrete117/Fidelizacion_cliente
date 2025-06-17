<?php
// Configuración de conexión a la base de datos
$servidor = "localhost";
$usuario = "root";
$clave = "";
$base_datos = "Fidelizacion_cliente";
$puerto = "3309";

// Conectar a la base de datos usando objeto mysqli para prepare
$conn = new mysqli($servidor, $usuario, $clave, $base_datos, $puerto);

// Verificar la conexión
if ($conn->connect_error) {
    die("No se pudo conectar a la base de datos: " . $conn->connect_error);
}

// Configurar el juego de caracteres
$conn->set_charset("utf8");

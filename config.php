<?php
    // Configuración de conexión a la base de datos
    $servidor = "localhost";
    $usuario = "root";
    $clave = "";
    $base_datos = "Fidelizacion_cliente";
    $puerto = "3309";

    // Conectar a la base de datos
    $conexion = mysqli_connect($servidor, $usuario, $clave, $base_datos, $puerto);

    // Verificar la conexión
    if (mysqli_connect_errno()) {
        echo "No se pudo conectar a la base de datos";
        exit();
    }

    // Configurar el juego de caracteres
    mysqli_set_charset($conexion, "utf8");
?>
<?php
require_once('../includes/Cliente.class.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar que todos los campos estén presentes
    if (isset($_POST['correo'], $_POST['nombre'], $_POST['ciudad'], $_POST['telefono'])) {
        // Sanitizar datos
        $correo = filter_var(trim($_POST['correo']), FILTER_SANITIZE_EMAIL);
        $nombre = htmlspecialchars(trim($_POST['nombre']));
        $ciudad = htmlspecialchars(trim($_POST['ciudad']));
        $telefono = htmlspecialchars(trim($_POST['telefono']));

        // Validaciones adicionales si quieres
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            echo "Correo inválido.";
            exit;
        }

        // Llamar al método para crear cliente
        if (Cliente::crear_cliente($correo, $nombre, $ciudad, $telefono)) {
            echo "✅ Cliente registrado correctamente.";
        } else {
            echo "❌ Error al registrar cliente.";
        }
    } else {
        echo "Todos los campos son obligatorios.";
    }
} else {
    echo "Acceso no permitido.";
}
?>

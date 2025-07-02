<?php
require_once('../includes/Cliente.class.php');

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Obtener el contenido en bruto de la solicitud
    parse_str(file_get_contents("php://input"), $datos);

    // Verificar si el ID fue enviado
    if (isset($datos['id'])) {
        $id = (int)$datos['id']; // Cast explícito a entero por seguridad

        if ($id > 0) {
            if (Cliente::eliminar_cliente_por_id($id)) {
                echo "✅ Cliente eliminado correctamente.";
            } else {
                echo "❌ No se pudo eliminar el cliente.";
            }
        } else {
            echo "❌ ID inválido.";
        }
    } else {
        echo "❌ Falta el ID del cliente.";
    }
} else {
    echo "❌ Método no permitido.";
}
?>

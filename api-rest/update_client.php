<?php
require_once('../includes/Cliente.class.php');


if ($_SERVER['REQUEST_METHOD'] === 'PUT' &&
    isset($_GET['id'], $_GET['email'], $_GET['nombre'], $_GET['ciudad'], $_GET['telefono']) &&
    !empty($_GET['id']) && !empty($_GET['email']) && !empty($_GET['nombre']) && !empty($_GET['ciudad']) && !empty($_GET['telefono'])) {

    $id = $_GET['id'];
    $email = $_GET['email'];
    $nombre = $_GET['nombre'];
    $ciudad = $_GET['ciudad'];
    $telefono = $_GET['telefono'];

    // Llamar al método para actualizar
    Cliente::actualizar_cliente($id, $email, $nombre, $ciudad, $telefono);

} else {
    // Respuesta en caso de error
    http_response_code(400);
    echo json_encode([
        "estado" => "error",
        "mensaje" => "Solicitud incorrecta o parámetros faltantes"
    ]);
}
?>

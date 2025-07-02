<?php
require_once('../includes/Cliente.class.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    Cliente::obtener_todos_los_clientes();
}
?>

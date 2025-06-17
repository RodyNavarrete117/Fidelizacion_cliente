<?php
$host = 'localhost';
$db = 'loyalty_program';
$user = 'root';
$pass = '';
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}
?>
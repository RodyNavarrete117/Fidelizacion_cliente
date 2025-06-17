<?php
session_start();
require_once 'config.php'; // Archivo de conexión a la base de datos

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$is_admin = $_SESSION['is_admin'];

$stmt = $conn->prepare("SELECT name, last_name, points FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Programa de Fidelización</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">Bienvenido, <?php echo htmlspecialchars($user['name'] . ' ' . $user['last_name']); ?></h1>
        <a href="logout.php" class="text-red-500 hover:underline">Cerrar Sesión</a>

        <?php if ($is_admin) { ?>
            <div class="mt-6">
                <h2 class="text-2xl font-bold mb-4">Panel de Administrador</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="clients.php" class="bg-blue-500 text-white p-4 rounded-md text-center hover:bg-blue-600">Gestionar Clientes</a>
                    <a href="prizes.php" class="bg-blue-500 text-white p-4 rounded-md text-center hover:bg-blue-600">Gestionar Premios</a>
                    <a href="benefits.php" class="bg-blue-500 text-white p-4 rounded-md text-center hover:bg-blue-600">Gestionar Beneficios</a>
                </div>
            </div>
        <?php } else { ?>
            <div class="mt-6">
                <h2 class="text-2xl font-bold mb-4">Tus Puntos: <?php echo $user['points']; ?></h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="user_prizes.php" class="bg-blue-500 text-white p-4 rounded-md text-center hover:bg-blue-600">Ver Premios</a>
                    <a href="user_benefits.php" class="bg-blue-500 text-white p-4 rounded-md text-center hover:bg-blue-600">Ver Beneficios</a>
                    <a href="digital_card.php" class="bg-blue-500 text-white p-4 rounded-md text-center hover:bg-blue-600">Ver Tarjeta Digital</a>
                </div>
            </div>
        <?php } ?>
    </div>
</body>
</html>
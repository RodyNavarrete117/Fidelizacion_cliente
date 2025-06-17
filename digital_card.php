<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT phone, name, last_name FROM users WHERE id = ?");
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
    <title>Tarjeta Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md text-center">
        <h1 class="text-2xl font-bold mb-4">Tarjeta Digital</h1>
        <p class="text-lg">Nombre: <?php echo htmlspecialchars($user['name'] . ' ' . $user['last_name']); ?></p>
        <p class="text-lg">Teléfono: <?php echo htmlspecialchars($user['phone']); ?></p>
        <div class="mt-4">
            <canvas id="qrCode" class="mx-auto"></canvas>
        </div>
        <a href="dashboard.php" class="text-blue-500 hover:underline mt-4 inline-block">Volver al Dashboard</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.0/build/qrcode.min.js"></script>
    <script>
        QRCode.toCanvas(document.getElementById('qrCode'), '<?php echo $user['phone']; ?>', { width: 200 }, function (error) {
            if (error) console.error(error);
        });
    </script>
</body>
</html>
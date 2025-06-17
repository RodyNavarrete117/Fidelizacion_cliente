<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$result = $conn->query("SELECT * FROM benefits");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beneficios</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">Tus Beneficios</h1>
        <a href="dashboard.php" class="text-blue-500 hover:underline mb-4 inline-block">Volver al Dashboard</a>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <?php while ($benefit = $result->fetch_assoc()) { ?>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-xl font-bold"><?php echo htmlspecialchars($benefit['company_name']); ?></h2>
                    <p><?php echo htmlspecialchars($benefit['description']); ?></p>
                    <p class="text-lg font-semibold">Descuento: <?php echo htmlspecialchars($benefit['discount']); ?></p>
                </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>
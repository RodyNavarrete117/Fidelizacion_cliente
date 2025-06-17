<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: index.php");
    exit();
}

// Manejo de alta, baja y modificación de premios
if (isset($_POST['add_prize'])) {
    $name = sanitize($_POST['name']);
    $description = sanitize($_POST['description']);
    $points_required = intval($_POST['points_required']);

    $stmt = $conn->prepare("INSERT INTO prizes (name, description, points_required) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $name, $description, $points_required);
    $stmt->execute();
    $stmt->close();
}

$result = $conn->query("SELECT * FROM prizes");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Premios</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">Gestionar Premios</h1>
        <a href="dashboard.php" class="text-blue-500 hover:underline mb-4 inline-block">Volver al Dashboard</a>

        <!-- Formulario de alta de premio -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-xl font-bold mb-4">Agregar Premio</h2>
            <form method="POST" action="">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nombre del Premio</label>
                    <input type="text" id="name" name="name" class="mt-1 p-2 w-full border rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
                    <textarea id="description" name="description" class="mt-1 p-2 w-full border rounded-md"></textarea>
                </div>
                <div class="mb-4">
                    <label for="points_required" class="block text-sm font-medium text-gray-700">Puntos Requeridos</label>
                    <input type="number" id="points_required" name="points_required" class="mt-1 p-2 w-full border rounded-md" required>
                </div>
                <button type="submit" name="add_prize" class="bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600">Agregar Premio</button>
            </form>
        </div>

        <!-- Listado de premios -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-bold mb-4">Lista de Premios</h2>
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border p-2">Nombre</th>
                        <th class="border p-2">Descripción</th>
                        <th class="border p-2">Puntos Requeridos</th>
                        <th class="border p-2">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($prize = $result->fetch_assoc()) { ?>
                        <tr>
                            <td class="border p-2"><?php echo htmlspecialchars($prize['name']); ?></td>
                            <td class="border p-2"><?php echo htmlspecialchars($prize['description']); ?></td>
                            <td class="border p-2"><?php echo $prize['points_required']; ?></td>
                            <td class="border p-2">
                                <a href="edit_prize.php?id=<?php echo $prize['id']; ?>" class="text-blue-500 hover:underline">Editar</a>
                                <a href="delete_prize.php?id=<?php echo $prize['id']; ?>" class="text-red-500 hover:underline" onclick="return confirm('¿Estás seguro?')">Eliminar</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
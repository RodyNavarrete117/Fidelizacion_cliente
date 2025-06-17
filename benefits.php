<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: index.php");
    exit();
}

// Manejo de alta, baja y modificación de beneficios
if (isset($_POST['add_benefit'])) {
    $company_name = sanitize($_POST['company_name']);
    $description = sanitize($_POST['description']);
    $discount = sanitize($_POST['discount']);

    $stmt = $conn->prepare("INSERT INTO benefits (company_name, description, discount) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $company_name, $description, $discount);
    $stmt->execute();
    $stmt->close();
}

$result = $conn->query("SELECT * FROM benefits");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Beneficios</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">Gestionar Beneficios</h1>
        <a href="dashboard.php" class="text-blue-500 hover:underline mb-4 inline-block">Volver al Dashboard</a>

        <!-- Formulario de alta de beneficio -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-xl font-bold mb-4">Agregar Beneficio</h2>
            <form method="POST" action="">
                <div class="mb-4">
                    <label for="company_name" class="block text-sm font-medium text-gray-700">Nombre de la Empresa</label>
                    <input type="text" id="company_name" name="company_name" class="mt-1 p-2 w-full border rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
                    <textarea id="description" name="description" class="mt-1 p-2 w-full border rounded-md"></textarea>
                </div>
                <div class="mb-4">
                    <label for="discount" class="block text-sm font-medium text-gray-700">Descuento</label>
                    <input type="text" id="discount" name="discount" class="mt-1 p-2 w-full border rounded-md" required>
                </div>
                <button type="submit" name="add_benefit" class="bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600">Agregar Beneficio</button>
            </form>
        </div>

        <!-- Listado de beneficios -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-bold mb-4">Lista de Beneficios</h2>
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border p-2">Empresa</th>
                        <th class="border p-2">Descripción</th>
                        <th class="border p-2">Descuento</th>
                        <th class="border p-2">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($benefit = $result->fetch_assoc()) { ?>
                        <tr>
                            <td class="border p-2"><?php echo htmlspecialchars($benefit['company_name']); ?></td>
                            <td class="border p-2"><?php echo htmlspecialchars($benefit['description']); ?></td>
                            <td class="border p-2"><?php echo htmlspecialchars($benefit['discount']); ?></td>
                            <td class="border p-2">
                                <a href="edit_benefit.php?id=<?php echo $benefit['id']; ?>" class="text-blue-500 hover:underline">Editar</a>
                                <a href="delete_benefit.php?id=<?php echo $benefit['id']; ?>" class="text-red-500 hover:underline" onclick="return confirm('¿Estás seguro?')">Eliminar</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
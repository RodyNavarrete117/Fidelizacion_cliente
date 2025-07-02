<?php
session_start();
require_once 'config.php';

// Verificar que el usuario sea administrador
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: index.php");
    exit();
}

// Alta de beneficio
if (isset($_POST['agregar_beneficio'])) {
    $nombre_empresa = sanitize($_POST['nombre_empresa']);
    $descripcion = sanitize($_POST['descripcion']);
    $descuento = sanitize($_POST['descuento']);

    $stmt = $conn->prepare("INSERT INTO benefits (company_name, description, discount) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nombre_empresa, $descripcion, $descuento);
    $stmt->execute();
    $stmt->close();
}

$resultado = $conn->query("SELECT * FROM benefits");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Beneficios</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">Administrar Beneficios</h1>
        <a href="dashboard.php" class="text-blue-500 hover:underline mb-4 inline-block">Volver al Panel</a>

        <!-- Formulario para agregar nuevo beneficio -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-xl font-bold mb-4">Agregar Nuevo Beneficio</h2>
            <form method="POST" action="">
                <div class="mb-4">
                    <label for="nombre_empresa" class="block text-sm font-medium text-gray-700">Nombre de la Empresa</label>
                    <input type="text" id="nombre_empresa" name="nombre_empresa" class="mt-1 p-2 w-full border rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                    <textarea id="descripcion" name="descripcion" class="mt-1 p-2 w-full border rounded-md"></textarea>
                </div>
                <div class="mb-4">
                    <label for="descuento" class="block text-sm font-medium text-gray-700">Descuento</label>
                    <input type="text" id="descuento" name="descuento" class="mt-1 p-2 w-full border rounded-md" required>
                </div>
                <button type="submit" name="agregar_beneficio" class="bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600">Agregar Beneficio</button>
            </form>
        </div>

        <!-- Listado de beneficios -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-bold mb-4">Listado de Beneficios</h2>
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
                    <?php while ($beneficio = $resultado->fetch_assoc()) { ?>
                        <tr>
                            <td class="border p-2"><?php echo htmlspecialchars($beneficio['company_name']); ?></td>
                            <td class="border p-2"><?php echo htmlspecialchars($beneficio['description']); ?></td>
                            <td class="border p-2"><?php echo htmlspecialchars($beneficio['discount']); ?></td>
                            <td class="border p-2">
                                <a href="edit_benefit.php?id=<?php echo $beneficio['id']; ?>" class="text-blue-500 hover:underline">Editar</a>
                                <a href="delete_benefit.php?id=<?php echo $beneficio['id']; ?>" class="text-red-500 hover:underline" onclick="return confirm('¿Estás seguro que deseas eliminar este beneficio?')">Eliminar</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

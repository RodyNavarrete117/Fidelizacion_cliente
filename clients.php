<?php
session_start();
require_once 'config.php';

// Verificar que el usuario sea administrador
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: index.php");
    exit();
}

// Registro de nuevo cliente
if (isset($_POST['agregar_cliente'])) {
    $telefono = $_POST['telefono'];
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $direccion = $_POST['direccion'];
    $correo = $_POST['correo'];
    $estado = $_POST['estado'];
    $ciudad = $_POST['ciudad'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (phone, password, name, last_name, address, email, state, city) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $telefono, $contrasena, $nombre, $apellidos, $direccion, $correo, $estado, $ciudad);
    $stmt->execute();
    $stmt->close();
}

// Agregar puntos al cliente
if (isset($_POST['agregar_puntos'])) {
    $id_cliente = $_POST['id_cliente'];
    $monto = floatval($_POST['monto_compra']);
    $puntos = floor($monto / 100) * 5;

    $stmt = $conn->prepare("UPDATE users SET points = points + ? WHERE id = ?");
    $stmt->bind_param("ii", $puntos, $id_cliente);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("INSERT INTO point_transactions (user_id, points, transaction_type, description) VALUES (?, ?, 'add', ?)");
    $descripcion = "Puntos por compra de $monto pesos";
    $stmt->bind_param("iis", $id_cliente, $puntos, $descripcion);
    $stmt->execute();
    $stmt->close();
}

// Consultar clientes
$resultado = $conn->query("SELECT * FROM users WHERE is_admin = 0");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">Administrar Clientes</h1>
        <a href="dashboard.php" class="text-blue-500 hover:underline mb-4 inline-block">Volver al Panel</a>

        <!-- Formulario para registrar cliente -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-xl font-bold mb-4">Agregar Cliente</h2>
            <form method="POST" action="">
                <div class="mb-4">
                    <label for="telefono" class="block text-sm font-medium text-gray-700">Teléfono</label>
                    <input type="text" id="telefono" name="telefono" class="mt-1 p-2 w-full border rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="contrasena" class="block text-sm font-medium text-gray-700">Contraseña</label>
                    <input type="password" id="contrasena" name="contrasena" class="mt-1 p-2 w-full border rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input type="text" id="nombre" name="nombre" class="mt-1 p-2 w-full border rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="apellidos" class="block text-sm font-medium text-gray-700">Apellidos</label>
                    <input type="text" id="apellidos" name="apellidos" class="mt-1 p-2 w-full border rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="direccion" class="block text-sm font-medium text-gray-700">Dirección</label>
                    <input type="text" id="direccion" name="direccion" class="mt-1 p-2 w-full border rounded-md">
                </div>
                <div class="mb-4">
                    <label for="correo" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                    <input type="email" id="correo" name="correo" class="mt-1 p-2 w-full border rounded-md">
                </div>
                <div class="mb-4">
                    <label for="estado" class="block text-sm font-medium text-gray-700">Estado</label>
                    <input type="text" id="estado" name="estado" class="mt-1 p-2 w-full border rounded-md">
                </div>
                <div class="mb-4">
                    <label for="ciudad" class="block text-sm font-medium text-gray-700">Ciudad</label>
                    <input type="text" id="ciudad" name="ciudad" class="mt-1 p-2 w-full border rounded-md">
                </div>
                <button type="submit" name="agregar_cliente" class="bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600">Agregar Cliente</button>
            </form>
        </div>

        <!-- Formulario para agregar puntos -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-xl font-bold mb-4">Agregar Puntos</h2>
            <form method="POST" action="">
                <div class="mb-4">
                    <label for="id_cliente" class="block text-sm font-medium text-gray-700">Seleccionar Cliente</label>
                    <select id="id_cliente" name="id_cliente" class="mt-1 p-2 w-full border rounded-md" required>
                        <?php while ($cliente = $resultado->fetch_assoc()) { ?>
                            <option value="<?php echo $cliente['id']; ?>"><?php echo htmlspecialchars($cliente['name'] . ' ' . $cliente['last_name']); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="monto_compra" class="block text-sm font-medium text-gray-700">Monto de Compra</label>
                    <input type="number" id="monto_compra" name="monto_compra" class="mt-1 p-2 w-full border rounded-md" required>
                </div>
                <button type="submit" name="agregar_puntos" class="bg-green-500 text-white p-2 rounded-md hover:bg-green-600">Agregar Puntos</button>
            </form>
        </div>

        <!-- Listado de clientes -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-bold mb-4">Lista de Clientes</h2>
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border p-2">Teléfono</th>
                        <th class="border p-2">Nombre</th>
                        <th class="border p-2">Apellidos</th>
                        <th class="border p-2">Puntos</th>
                        <th class="border p-2">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $resultado->data_seek(0);
                    while ($cliente = $resultado->fetch_assoc()) { ?>
                        <tr>
                            <td class="border p-2"><?php echo htmlspecialchars($cliente['phone']); ?></td>
                            <td class="border p-2"><?php echo htmlspecialchars($cliente['name']); ?></td>
                            <td class="border p-2"><?php echo htmlspecialchars($cliente['last_name']); ?></td>
                            <td class="border p-2"><?php echo $cliente['points']; ?></td>
                            <td class="border p-2">
                                <a href="edit_client.php?id=<?php echo $cliente['id']; ?>" class="text-blue-500 hover:underline">Editar</a>
                                <a href="delete_client.php?id=<?php echo $cliente['id']; ?>" class="text-red-500 hover:underline" onclick="return confirm('¿Estás seguro de eliminar este cliente?')">Eliminar</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

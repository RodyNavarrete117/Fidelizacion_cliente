<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: index.php");
    exit();
}

// Manejo de alta, baja, modificación y consulta de clientes
if (isset($_POST['add_client'])) {
    $phone = sanitize($_POST['phone']);
    $name = sanitize($_POST['name']);
    $last_name = sanitize($_POST['last_name']);
    $address = sanitize($_POST['address']);
    $email = sanitize($_POST['email']);
    $state = sanitize($_POST['state']);
    $city = sanitize($_POST['city']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (phone, password, name, last_name, address, email, state, city) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $phone, $password, $name, $last_name, $address, $email, $state, $city);
    $stmt->execute();
    $stmt->close();
}

// Alta de puntos
if (isset($_POST['add_points'])) {
    $user_id = $_POST['user_id'];
    $purchase_amount = floatval($_POST['purchase_amount']);
    $points = floor($purchase_amount / 100) * 5;

    $stmt = $conn->prepare("UPDATE users SET points = points + ? WHERE id = ?");
    $stmt->bind_param("ii", $points, $user_id);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("INSERT INTO point_transactions (user_id, points, transaction_type, description) VALUES (?, ?, 'add', ?)");
    $description = "Puntos por compra de $purchase_amount pesos";
    $stmt->bind_param("iis", $user_id, $points, $description);
    $stmt->execute();
    $stmt->close();
}

// Listar clientes
$result = $conn->query("SELECT * FROM users WHERE is_admin = 0");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">Gestionar Clientes</h1>
        <a href="dashboard.php" class="text-blue-500 hover:underline mb-4 inline-block">Volver al Dashboard</a>

        <!-- Formulario de alta de cliente -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-xl font-bold mb-4">Agregar Cliente</h2>
            <form method="POST" action="">
                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium text-gray-700">Teléfono</label>
                    <input type="text" id="phone" name="phone" class="mt-1 p-2 w-full border rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                    <input type="password" id="password" name="password" class="mt-1 p-2 w-full border rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input type="text" id="name" name="name" class="mt-1 p-2 w-full border rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="last_name" class="block text-sm font-medium text-gray-700">Apellidos</label>
                    <input type="text" id="last_name" name="last_name" class="mt-1 p-2 w-full border rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="address" class="block text-sm font-medium text-gray-700">Dirección</label>
                    <input type="text" id="address" name="address" class="mt-1 p-2 w-full border rounded-md">
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                    <input type="email" id="email" name="email" class="mt-1 p-2 w-full border rounded-md">
                </div>
                <div class="mb-4">
                    <label for="state" class="block text-sm font-medium text-gray-700">Estado</label>
                    <input type="text" id="state" name="state" class="mt-1 p-2 w-full border rounded-md">
                </div>
                <div class="mb-4">
                    <label for="city" class="block text-sm font-medium text-gray-700">Ciudad</label>
                    <input type="text" id="city" name="city" class="mt-1 p-2 w-full border rounded-md">
                </div>
                <button type="submit" name="add_client" class="bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600">Agregar Cliente</button>
            </form>
        </div>

        <!-- Formulario de alta de puntos -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-xl font-bold mb-4">Agregar Puntos</h2>
            <form method="POST" action="">
                <div class="mb-4">
                    <label for="user_id" class="block text-sm font-medium text-gray-700">Seleccionar Cliente</label>
                    <select id="user_id" name="user_id" class="mt-1 p-2 w-full border rounded-md" required>
                        <?php while ($client = $result->fetch_assoc()) { ?>
                            <option value="<?php echo $client['id']; ?>"><?php echo htmlspecialchars($client['name'] . ' ' . $client['last_name']); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="purchase_amount" class="block text-sm font-medium text-gray-700">Monto de Compra</label>
                    <input type="number" id="purchase_amount" name="purchase_amount" class="mt-1 p-2 w-full border rounded-md" required>
                </div>
                <button type="submit" name="add_points" class="bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600">Agregar Puntos</button>
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
                    $result->data_seek(0);
                    while ($client = $result->fetch_assoc()) { ?>
                        <tr>
                            <td class="border p-2"><?php echo htmlspecialchars($client['phone']); ?></td>
                            <td class="border p-2"><?php echo htmlspecialchars($client['name']); ?></td>
                            <td class="border p-2"><?php echo htmlspecialchars($client['last_name']); ?></td>
                            <td class="border p-2"><?php echo $client['points']; ?></td>
                            <td class="border p-2">
                                <a href="edit_client.php?id=<?php echo $client['id']; ?>" class="text-blue-500 hover:underline">Editar</a>
                                <a href="delete_client.php?id=<?php echo $client['id']; ?>" class="text-red-500 hover:underline" onclick="return confirm('¿Estás seguro?')">Eliminar</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
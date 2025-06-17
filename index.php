<?php

// Función para sanitizar entradas
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Manejo del login
if (isset($_POST['login'])) {
    $phone = sanitize($_POST['phone']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password, is_admin FROM users WHERE phone = ?");
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['is_admin'] = $user['is_admin'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Contraseña incorrecta";
        }
    } else {
        $error = "Usuario no encontrado";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Programa de Fidelización</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold text-center mb-6">Iniciar Sesión</h2>
        <?php if (isset($error)) { ?>
            <p class="text-red2 text-center"><?php echo $error; ?></p>
        <?php } ?>
        <form method="POST" action="">
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-gray-700">Número Telefónico</label>
                <input type="text" id="phone" name="phone" class="mt-1 p-2 w-full border rounded-md" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                <input type="password" id="password" name="password" class="mt-1 p-2 w-full border rounded-md" required>
            </div>
            <button type="submit" name="login" class="w-full bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600">Iniciar Sesión</button>
        </form>
    </div>
</body>
</html>
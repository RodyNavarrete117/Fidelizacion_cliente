<?php
session_start();

$servidor = "localhost";
$usuario = "root";
$clave = "";
$base_datos = "Fidelizacion_cliente";
$puerto = "3309";

// Conectar a la base de datos
$conexion = mysqli_connect($servidor, $usuario, $clave, $base_datos, $puerto);

// Verificar la conexión
if (mysqli_connect_errno()) {
    echo "No se pudo conectar a la base de datos";
    exit();
}

// Configurar el juego de caracteres
mysqli_set_charset($conexion, "utf8");

// Función para sanitizar entradas
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Manejo del login
if (isset($_POST['login'])) {
    $phone = sanitize($_POST['phone']);
    $password = $_POST['password'];

    $stmt = mysqli_prepare($conexion, "SELECT id, password, is_admin FROM users WHERE phone = ?");
    mysqli_stmt_bind_param($stmt, "s", $phone);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // Comparación directa de contraseña en texto plano (NO recomendado para producción)
        if ($password === $user['password']) {
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

    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login - Programa de Fidelización</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold text-center mb-6">Iniciar Sesión</h2>
        <?php if (isset($error)) { ?>
            <p class="text-red-500 text-center mb-4"><?php echo $error; ?></p>
        <?php } ?>
        <form method="POST" action="">
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-gray-700">Número Telefónico</label>
                <input type="text" id="phone" name="phone" class="mt-1 p-2 w-full border rounded-md" required />
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                <input type="password" id="password" name="password" class="mt-1 p-2 w-full border rounded-md" required />
            </div>
            <button type="submit" name="login" class="w-full bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600">Iniciar Sesión</button>
        </form>
    </div>
</body>
</html>

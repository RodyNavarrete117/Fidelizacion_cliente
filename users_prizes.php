<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT points FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Canje de premios
if (isset($_POST['redeem_prize'])) {
    $prize_id = $_POST['prize_id'];
    $stmt = $conn->prepare("SELECT points_required FROM prizes WHERE id = ?");
    $stmt->bind_param("i", $prize_id);
    $stmt->execute();
    $prize = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($user['points'] >= $prize['points_required']) {
        $new_points = $user['points'] - $prize['points_required'];
        $stmt = $conn->prepare("UPDATE users SET points = ? WHERE id = ?");
        $stmt->bind_param("ii", $new_points, $user_id);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("INSERT INTO point_transactions (user_id, points, transaction_type, description) VALUES (?, ?, 'redeem', ?)");
        $points = -$prize['points_required'];
        $description = "Canje de premio ID: $prize_id";
        $stmt->bind_param("iis", $user_id, $points, $description);
        $stmt->execute();
        $stmt->close();

        header("Location: user_prizes.php");
        exit();
    } else {
        $error = "No tienes suficientes puntos para canjear este premio.";
    }
}

$result = $conn->query("SELECT * FROM prizes");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premios</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">Tus Premios</h1>
        <p class="text-lg mb-4">Puntos Disponibles: <?php echo $user['points']; ?></p>
        <a href="dashboard.php" class="text-blue-500 hover:underline mb-4 inline-block">Volver al Dashboard</a>
        <?php if (isset($error)) { ?>
            <p class="text-red-500 text-center"><?php echo $error; ?></p>
        <?php } ?>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <?php while ($prize = $result->fetch_assoc()) { ?>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-xl font-bold"><?php echo htmlspecialchars($prize['name']); ?></h2>
                    <p><?php echo htmlspecialchars($prize['description']); ?></p>
                    <p class="text-lg font-semibold">Puntos Requeridos: <?php echo $prize['points_required']; ?></p>
                    <form method="POST" action="">
                        <input type="hidden" name="prize_id" value="<?php echo $prize['id']; ?>">
                        <button type="submit" name="redeem_prize" class="bg-blue-500 text-white p-2 rounded-md hover:bg-blue-600 mt-4">Canjear</button>
                    </form>
                </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>
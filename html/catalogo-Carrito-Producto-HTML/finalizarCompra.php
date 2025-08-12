<?php
session_start();
$_SESSION['usuario_id'] = 1;

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    die("Error: el usuario no está autenticado.");
}

$usuario_id = $_SESSION['usuario_id'];

// Verificar que haya productos en el carrito
if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    header("Location: carrito.php");
    exit;
}

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "suplementos_dynamite");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Medio de pago fijo de prueba (deberías permitir elegir más adelante)
if (!isset($_POST['medio_pago_id'])) {
    die("Error: medio de pago no seleccionado.");
}
$medio_pago_id = (int) $_POST['medio_pago_id'];
$total = 0;

// Calcular total
foreach ($_SESSION['carrito'] as $item) {
    $total += $item['precio'] * $item['cantidad'];
}

// Insertar en tabla pedidos
$stmt = $conexion->prepare("INSERT INTO pedidos (usuario_id, medio_pago_id, total) VALUES (?, ?, ?)");
$stmt->bind_param("iid", $usuario_id, $medio_pago_id, $total);
$stmt->execute();
$pedido_id = $stmt->insert_id;
$stmt->close();

// Insertar en tabla detalle_pedido
$stmt = $conexion->prepare("INSERT INTO detalle_pedido (pedido_id, producto_id, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");
foreach ($_SESSION['carrito'] as $item) {
    $producto_id = $item['producto_id'];
    $cantidad = $item['cantidad'];
    $precio_unitario = $item['precio'];
    $stmt->bind_param("iiid", $pedido_id, $producto_id, $cantidad, $precio_unitario);
    $stmt->execute();
}
$stmt->close();

// Guardar resumen antes de vaciar el carrito
$resumen = $_SESSION['carrito'];
$_SESSION['carrito'] = [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Compra Finalizada</title>
    <link rel="stylesheet" href="/web2025/trabajoFinalPHP/css/Catalogo-Carrito-Css/carrito.css">
    <style>
        .mensaje-confirmacion {
            max-width: 800px;
            margin: 80px auto;
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
            text-align: center;
        }
        .mensaje-confirmacion h2 {
            color: green;
            margin-bottom: 20px;
        }
        .mensaje-confirmacion table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        .mensaje-confirmacion th, .mensaje-confirmacion td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        .mensaje-confirmacion th {
            background-color: #457b9d;
            color: white;
        }
        .mensaje-confirmacion a {
            display: inline-block;
            margin-top: 25px;
            background-color: #2a9d8f;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 6px;
        }
        .mensaje-confirmacion a:hover {
            background-color: #21867a;
        }
    </style>
</head>
<body>
    <div class="mensaje-confirmacion">
        <h2>✅ ¡Gracias por tu compra!</h2>
        <p>Tu pedido fue registrado exitosamente.</p>

        <h3>🧾 Resumen de productos:</h3>
        <table>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
            </tr>
            <?php foreach ($resumen as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['nombre']) ?></td>
                <td><?= $item['cantidad'] ?></td>
                <td>$<?= number_format($item['precio'] * $item['cantidad'], 2) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>

        <a href="catalogo.php">Volver al catálogo</a>
    </div>
</body>
</html>

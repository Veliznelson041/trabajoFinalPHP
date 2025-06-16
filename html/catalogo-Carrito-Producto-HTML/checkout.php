<?php
session_start();

if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    header("Location: carrito.php");
    exit;
}

$total = 0;
foreach ($_SESSION['carrito'] as $item) {
    $total += $item['precio'] * $item['cantidad'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link rel="stylesheet" href="/web2025/trabajoFinalPHP/css/Catalogo-Carrito-Css/carrito.css">
    <style>
        .checkout-container {
            max-width: 500px;
            margin: 100px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
        }
        .checkout-container h2 {
            margin-bottom: 20px;
            text-align: center;
        }
        .checkout-container select, .checkout-container button {
            width: 100%;
            padding: 12px;
            margin-top: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        .checkout-container button {
            background-color: #2a9d8f;
            color: white;
            border: none;
            cursor: pointer;
        }
        .checkout-container button:hover {
            background-color: #21867a;
        }
    </style>
</head>
<body>
    <div class="checkout-container">
        <h2>Confirmar compra</h2>
        <p><strong>Total:</strong> $<?= number_format($total, 2) ?></p>
        <form action="finalizarCompra.php" method="POST">
            <label for="medio_pago">Seleccioná medio de pago:</label>
            <select name="medio_pago_id" id="medio_pago" required>
                <option value="">-- Elegí una opción --</option>
                <option value="1">Efectivo</option>
                <option value="2">Transferencia</option>
                <option value="3">Tarjeta</option>
            </select>
            <button type="submit">Finalizar compra</button>
        </form>
    </div>
</body>
</html>

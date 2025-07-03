<?php
session_start();

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

if (isset($_GET['eliminar'])) {
    $index = $_GET['eliminar'];
    unset($_SESSION['carrito'][$index]);
    $_SESSION['carrito'] = array_values($_SESSION['carrito']);
}

if (isset($_GET['vaciar'])) {
    $_SESSION['carrito'] = [];
}
?>

// Versión integrada por Maciel


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="/web2025/trabajoFinalPHP/css/Catalogo-Carrito-Css/carrito.css">
</head>
<body>
    <h1>🛒 Carrito de Compras</h1>

    <?php if (empty($_SESSION['carrito'])): ?>
        <p class="cart-summary">El carrito está vacío.</p>
    <?php else: ?>
        <table class="cart-table">
            <tr>
                <th>Producto</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
                <th>Acción</th>
            </tr>
            <?php
            $total = 0;
            foreach ($_SESSION['carrito'] as $index => $item):
                $subtotal = $item['precio'] * $item['cantidad'];
                $total += $subtotal;
            ?>
            <tr>
                <td><?= htmlspecialchars($item['nombre']) ?></td>
                <td>$<?= number_format($item['precio'], 2) ?></td>
                <td><?= $item['cantidad'] ?></td>
                <td>$<?= number_format($subtotal, 2) ?></td>
                <td>
                    <a href="?eliminar=<?= $index ?>">
                        <button class="btn-remove">Eliminar</button>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

        <div class="cart-summary">
            <p>Total: $<?= number_format($total, 2) ?></p>
            <a href="?vaciar=1"><button class="btn-remove">Vaciar Carrito</button></a>
            <a href="checkout.php"><button class="btn-checkout">Ir al Checkout</button></a>
        </div>
    <?php endif; ?>
</body>
</html>

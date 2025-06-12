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

<!DOCTYPE html>
<html lang="es-AR">
<head>
    <meta charset="UTF-8">
    <title>Carrito de Compras - Suplementos Dynamite</title>
    <link rel="stylesheet" href="../../css/Catalogo-Carrito-Css/carrito.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<header>
    <div class="header-container">
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRt_eZYMvS26mdHNwVQw-zHWqDRdSz5XzAVHQ&s" alt="Logo de Suplementos Dynamite" class="logo">
        <h1>Suplementos Dynamite</h1>
    </div>
    <br>
    <p id="date-time"></p>
    <nav class="nav-bar">
        <ul>
            <li><a href="/html/index.php">Inicio</a></li>
            <li><a href="catalogo.php">Catálogo</a></li>
            <li><a href="/html/contactoHtml/contacto.php">Contacto</a></li>
            <li><a href="/html/loginHtml/login.php" class="btn-login"><i class="fas fa-user"></i> Iniciar Sesión</a></li>
        </ul>
    </nav>
</header>

<nav class="ruta">
    <a href="../index.php">Inicio</a> &gt; <a href="carrito.php">Carrito</a>
</nav>

<main>
    <h2>Carrito de Compras</h2>

    <?php if (empty($_SESSION['carrito'])): ?>
        <p class="cart-summary">El carrito está vacío.</p>
    <?php else: ?>
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
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
                        <a href="?eliminar=<?= $index ?>" class="btn-remove">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="cart-summary">
            <p>Total a pagar: <span id="total-amount">$<?= number_format($total, 2) ?></span></p>
            <a href="?vaciar=1"><button class="btn-remove">Vaciar Carrito</button></a>
            <a href="checkout.php" class="finalizar-btn">Finalizar Compra</a>
        </div>
    <?php endif; ?>
</main>

<footer>
    <p>Dirección: Av. Pres. Arturo Illia 902, Catamarca, Argentina | Email: contacto@suplementosdynamite.com | Tel: (123) 456-7890</p>
    <div class="social-icons">
        <a href="https://wa.me/1234567890" target="_blank"><i class="fab fa-whatsapp"></i></a>
        <a href="https://www.instagram.com/suplementosdynamite" target="_blank"><i class="fab fa-instagram"></i></a>
    </div>
    <p>&copy; 2024 Suplementos Dynamite. Todos los derechos reservados.</p>
</footer>

</body>
</html>

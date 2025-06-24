<?php
require_once '../../../controlSesion.php';
require_once '../../../conexion.php';

$sql = "SELECT p.*, s.nombre AS subcategoria, c.nombre AS categoria 
        FROM productos p
        JOIN subcategorias s ON p.subcategoria_id = s.subcategoria_id
        JOIN categorias c ON s.categoria_id = c.categoria_id
        WHERE p.stock < 10
        ORDER BY p.stock ASC";
$productos = $conexion->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Stock Crítico</title>
    <link rel="stylesheet" href="../../../css/Empleadocss/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .stock-critico { color: red; font-weight: bold; }
        .stock-bajo { color: orange; }
    </style>
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
                <li><a href="../../empleado.php">Inicio</a></li>                             
                <li><a href="../../perfilempleado.php" class="btn-login"><i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['nombre']) ?></a></li>
            </ul>
        </nav>
    </header>
    <br>

    <nav class="ruta">
        <a href="../../empleado.php">Inicio</a> > <a href="#">Reportes</a> > <a href="#">Stock Crítico</a>
    </nav>

    <main>
        <h2>Productos con Stock Crítico (< 10 unidades)</h2>
        
        <table class="table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Categoría</th>
                    <th>Subcategoría</th>
                    <th>Stock</th>
                    <th>Precio</th>
                </tr>
            </thead>
            <tbody>
                <?php while($producto = $productos->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($producto['nombre']) ?></td>
                    <td><?= htmlspecialchars($producto['categoria']) ?></td>
                    <td><?= htmlspecialchars($producto['subcategoria']) ?></td>
                    <td class="<?= $producto['stock'] < 5 ? 'stock-critico' : 'stock-bajo' ?>">
                        <?= $producto['stock'] ?> unidades
                    </td>
                    <td>$<?= number_format($producto['precio'], 2) ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>
    <script>
        function updateDateTime() {
            const now = new Date();
            const dateTime = now.toLocaleString();
            document.getElementById('date-time').textContent = dateTime;
        }
        setInterval(updateDateTime, 1000);
        updateDateTime();
    </script>
</body>
</html>
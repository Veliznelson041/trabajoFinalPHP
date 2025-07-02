<?php
require_once '../../../controlSesion.php';
require_once '../../../conexion.php';

// Obtener todos los productos
$sql = "SELECT p.*, s.nombre AS subcategoria, c.nombre AS categoria 
        FROM productos p
        JOIN subcategorias s ON p.subcategoria_id = s.subcategoria_id
        JOIN categorias c ON s.categoria_id = c.categoria_id";
$productos = $conexion->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Productos</title>
    <link rel="stylesheet" href="../../../css/Empleadocss/styles-productos.css">
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
                <li><a href="../../empleado.php">Inicio</a></li>                              
                <li><a href="../../perfilempleado.php" class="btn-login"><i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['nombre']) ?></a></li>
            </ul>
        </nav>
    </header>

    <div>
        <nav class="nav-empleado">
            <ul>
                <li><a href="../consultas/buzon.php">Buzón de Consultas</a></li>
                <li><a href="../proveedores.php">Proveedores</a></li>
                <li><a href="../pedidos/listado.php">Seguimiento de Paquetes</a></li>
            </ul>
        </nav>
    </div>
    <br>
    
    <?php if(isset($_GET['success'])): ?>
        <div class="success">¡Operación realizada con éxito!</div>
    <?php endif; ?>
    
    <h1>Gestión de Productos</h1>
        
    <div class="actions">
        <a class="btn-agregar" href="agregar.php">Agregar Nuevo Producto</a>
    </div>

    <table class="productos-table">
        <thead>
            <tr>
                <th>Imagen</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Categoría</th>
                <th>Subcategoría</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while($producto = $productos->fetch_assoc()): ?>
            <tr>
                <td>
                    <?php if($producto['imagen_url']): ?>
                        <img src="<?= htmlspecialchars($producto['imagen_url']) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>" class="producto-img">
                    <?php else: ?>
                        <span>Sin imagen</span>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($producto['nombre']) ?></td>
                <td>$<?= number_format($producto['precio'], 2) ?></td>
                <td><?= $producto['stock'] ?> unidades</td>
                <td><?= htmlspecialchars($producto['categoria']) ?></td>
                <td><?= htmlspecialchars($producto['subcategoria']) ?></td>
                <td>
                    <a href="ver.php?id=<?= $producto['producto_id'] ?>" class="btn-ver">Ver/Editar</a>
                    <a href="eliminar.php?id=<?= $producto['producto_id'] ?>" class="btn-eliminar" onclick="return confirm('¿Está seguro de eliminar este producto?')">Eliminar</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
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
<?php
require_once("../../conexion.php");

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$stmt = $conexion->prepare("
    SELECT p.nombre, p.descripcion, p.precio, p.imagen_url, p.stock
    FROM productos p
    WHERE p.producto_id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$producto = $resultado->fetch_assoc();

if (!$producto) {
    echo "<p>Producto no encontrado.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($producto['nombre']); ?> - Suplementos Dynamite</title>
    <link rel="stylesheet" href="../../css/Catalogo-Carrito-Css/producto.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .detalle-producto {
            max-width: 1000px;
            margin: 50px auto;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            padding: 30px;
            display: flex;
            gap: 40px;
        }

        .detalle-producto img {
            width: 400px;
            height: auto;
            border-radius: 8px;
            object-fit: contain;
            border: 1px solid #ccc;
        }

        .info {
            flex: 1;
        }

        .info h2 {
            color: var(--color-principal);
            margin-bottom: 15px;
        }

        .info p {
            margin-bottom: 10px;
            line-height: 1.6;
        }

        .precio {
            font-size: 24px;
            font-weight: bold;
            margin: 15px 0;
        }

        .volver {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: var(--color-principal);
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .volver:hover {
            background-color: var(--color-hover);
        }
    </style>
</head>
<body>

    <header>
        <div class="header-container">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRt_eZYMvS26mdHNwVQw-zHWqDRdSz5XzAVHQ&s" alt="Logo" class="logo">
            <h1>Suplementos Dynamite</h1>
        </div>
        <nav class="nav-bar">
            <ul>
                <li><a href="../index.php">Inicio</a></li>
                <li><a href="catalogo.php">Catálogo</a></li>
                <li><a href="carrito.php">Carrito</a></li>
                <li><a href="../../html/contactoHtml/contacto.php">Contacto</a></li>                                
                <li><a href="../../html/loginHtml/login.php" class="btn-login"><i class="fas fa-user"></i> Iniciar Sesión</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="detalle-producto">
            <img src="<?php echo htmlspecialchars($producto['imagen_url']); ?>" alt="Imagen de <?php echo htmlspecialchars($producto['nombre']); ?>">

            <div class="info">
                <h2><?php echo htmlspecialchars($producto['nombre']); ?></h2>
                <p><?php echo nl2br(htmlspecialchars($producto['descripcion'])); ?></p>
                <p class="precio">$<?php echo number_format($producto['precio'], 2, ',', '.'); ?></p>
                <p><strong>Stock:</strong> <?php echo (int)$producto['stock']; ?></p>

                <a href="catalogo.php" class="volver">← Volver al catálogo</a>
            </div>
        </div>
    </main>

</body>
</html>
<?php
session_start();
require_once("../../conexion.php");

// Inicializar carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Agregar producto al carrito
if (isset($_GET['agregar'])) {
    $id = (int) $_GET['agregar'];
    $consulta = $conexion->prepare("SELECT producto_id, nombre, precio FROM productos WHERE producto_id = ? AND stock > 0");
    $consulta->bind_param("i", $id);
    $consulta->execute();
    $resultado = $consulta->get_result();
    if ($producto = $resultado->fetch_assoc()) {
        $ya_existe = false;
        foreach ($_SESSION['carrito'] as &$item) {
            if ($item['producto_id'] == $id) {
                $item['cantidad']++;
                $ya_existe = true;
                break;
            }
        }
        if (!$ya_existe) {
            $_SESSION['carrito'][] = [
                'producto_id' => $producto['producto_id'],
                'nombre' => $producto['nombre'],
                'precio' => $producto['precio'],
                'cantidad' => 1
            ];
        }
    }
    header("Location: catalogo.php");
    exit;
}

$categoria = isset($_GET['categoria']) ? trim($_GET['categoria']) : '';
$busqueda = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';

if (!empty($categoria)) {
    $stmt = $conexion->prepare("
        SELECT p.producto_id, p.nombre, p.descripcion, p.precio, p.imagen_url
        FROM productos p
        JOIN subcategorias s ON p.subcategoria_id = s.subcategoria_id
        JOIN categorias c ON s.categoria_id = c.categoria_id
        WHERE c.nombre = ? AND p.nombre LIKE ? AND p.stock > 0
    ");
    $likeBusqueda = '%' . $busqueda . '%';
    $stmt->bind_param("ss", $categoria, $likeBusqueda);
    $stmt->execute();
    $resultado = $stmt->get_result();
} else {
    $stmt = $conexion->prepare("
        SELECT p.producto_id, p.nombre, p.descripcion, p.precio, p.imagen_url
        FROM productos p
        WHERE p.nombre LIKE ? AND p.stock > 0
    ");
    $likeBusqueda = '%' . $busqueda . '%';
    $stmt->bind_param("s", $likeBusqueda);
    $stmt->execute();
    $resultado = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="es-AR">
<head>
    <meta charset="UTF-8">
    <title>Catálogo - Suplementos Dynamite</title>
    <link rel="stylesheet" href="../../css/Catalogo-Carrito-Css/catalogo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <div class="header-container">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRt_eZYMvS26mdHNwVQw-zHWqDRdSz5XzAVHQ&s" alt="Logo de Suplementos Dynamite" class="logo">
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
        <h2>Catálogo de Productos</h2>
        <div class="filters">
            <form method="get" action="catalogo.php" class="filters">
                <input type="text" name="buscar" placeholder="Buscar en catálogo..." value="<?php echo htmlspecialchars($busqueda); ?>">
                <select name="categoria" onchange="this.form.submit()">
                    <?php
                    $categorias = ['Proteínas', 'Creatinas', 'Pre-entrenos', 'Vitaminas'];
                    echo '<option value=""' . ($categoria === '' ? ' selected' : '') . '>Todas las categorías</option>';
                    foreach ($categorias as $cat) {
                        $selected = ($categoria === $cat) ? ' selected' : '';
                        echo "<option value=\"$cat\"$selected>$cat</option>";
                    }
                    ?>
                </select>
                <button type="submit">Buscar</button>
            </form>
        </div>

        <div class="catalogo-grid">
            <?php while ($row = $resultado->fetch_assoc()): ?>
                <div class="product-card">
                    <img src="<?= htmlspecialchars($row['imagen_url']) ?>" alt="<?= htmlspecialchars($row['nombre']) ?>">
                    <h3><?= htmlspecialchars($row['nombre']) ?></h3>
                    <p><?= htmlspecialchars($row['descripcion']) ?></p>
                    <p>Precio: $<?= number_format($row['precio'], 2) ?></p>
                    <a href="catalogo.php?agregar=<?= $row['producto_id'] ?>"><button class="btn-add">Agregar al carrito</button></a>
                </div>
            <?php endwhile; ?>
        </div>
    </main>
</body>
</html>

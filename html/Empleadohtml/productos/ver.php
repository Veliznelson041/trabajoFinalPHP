<?php
require_once '../../../controlSesion.php';
require_once '../../../conexion.php';

if (!isset($_GET['id'])) {
    header('Location: listado.php');
    exit;
}

$id = $_GET['id'];
$sql = "SELECT p.*, s.nombre AS subcategoria, s.categoria_id, c.nombre AS categoria 
        FROM productos p
        JOIN subcategorias s ON p.subcategoria_id = s.subcategoria_id
        JOIN categorias c ON s.categoria_id = c.categoria_id
        WHERE p.producto_id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$producto = $stmt->get_result()->fetch_assoc();

if (!$producto) {
    header('Location: listado.php');
    exit;
}

// Obtener categorías y subcategorías
$categorias = $conexion->query("SELECT * FROM categorias");
$subcategorias = $conexion->query("SELECT * FROM subcategorias WHERE categoria_id = {$producto['categoria_id']}");

// Procesar actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = floatval($_POST['precio']);
    $stock = intval($_POST['stock']);
    $subcategoria_id = intval($_POST['subcategoria_id']);
    $imagen_url = $_POST['imagen_url'];
    $modificado_por = $_SESSION['email'];

    $sql = "UPDATE productos SET 
            nombre = ?,
            descripcion = ?,
            precio = ?,
            stock = ?,
            subcategoria_id = ?,
            imagen_url = ?,
            modificado_por = ?,
            fecha_modificacion = NOW()
            WHERE producto_id = ?";
    
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssdiissi", $nombre, $descripcion, $precio, $stock, $subcategoria_id, $imagen_url, $modificado_por, $id);
    
    if ($stmt->execute()) {
        header('Location: listado.php?success=1');
        exit;
    } else {
        $error = "Error al actualizar el producto: " . $conexion->error;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
    <link rel="stylesheet" href="../../../css/Empleadocss/styles-productos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script>
        function cargarSubcategorias() {
            const categoriaId = document.getElementById('categoria_id').value;
            if (!categoriaId) return;
            
            fetch('../../get_subcategorias.php?categoria_id=' + categoriaId)
                .then(response => response.json())
                .then(data => {
                    const select = document.getElementById('subcategoria_id');
                    select.innerHTML = '<option value="">Seleccione subcategoría</option>';
                    data.forEach(sub => {
                        const option = document.createElement('option');
                        option.value = sub.subcategoria_id;
                        option.textContent = sub.nombre;
                        select.appendChild(option);
                    });
                    // Seleccionar la subcategoría actual
                    select.value = <?= $producto['subcategoria_id'] ?>;
                });
        }
    </script>
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
        <a href="../../empleado.php">Inicio</a> > <a href="listado.php">Productos</a> > <a href="#"><?= htmlspecialchars($producto['nombre']) ?></a>
    </nav>

    <main>
        <div class="product-detail">
            <img src="<?= htmlspecialchars($producto['imagen_url']) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>" class="product-image">
            
            <div class="product-info">
                <h2>Editar Producto</h2>
                
                <?php if(isset($error)): ?>
                    <div class="error"><?= $error ?></div>
                <?php endif; ?>
                
                <form method="POST">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($producto['nombre']) ?>" class="input-text" required>
                    
                    <label for="descripcion">Descripción:</label>
                    <textarea id="descripcion" name="descripcion" class="input-description" required><?= htmlspecialchars($producto['descripcion']) ?></textarea>
                    
                    <label for="precio">Precio:</label>
                    <input type="number" step="0.01" min="0" id="precio" name="precio" value="<?= $producto['precio'] ?>" class="input-price" required>
                    
                    <label for="stock">Stock:</label>
                    <input type="number" min="0" id="stock" name="stock" value="<?= $producto['stock'] ?>" class="input-text" required>
                    
                    <label for="categoria_id">Categoría:</label>
                    <select id="categoria_id" class="input-select" onchange="cargarSubcategorias()" required>
                        <option value="">Seleccione categoría</option>
                        <?php while($cat = $categorias->fetch_assoc()): ?>
                            <option value="<?= $cat['categoria_id'] ?>" <?= $cat['categoria_id'] == $producto['categoria_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['nombre']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                    
                    <label for="subcategoria_id">Subcategoría:</label>
                    <select id="subcategoria_id" name="subcategoria_id" class="input-select" required>
                        <option value="">Seleccione subcategoría</option>
                        <?php while($sub = $subcategorias->fetch_assoc()): ?>
                            <option value="<?= $sub['subcategoria_id'] ?>" <?= $sub['subcategoria_id'] == $producto['subcategoria_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($sub['nombre']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                    
                    <label for="imagen_url">URL de la imagen:</label>
                    <input type="text" id="imagen_url" name="imagen_url" value="<?= htmlspecialchars($producto['imagen_url']) ?>" class="input-text" required>
                    
                    <br>
                    <button type="submit" class="btn-save">Guardar Cambios</button>
                    <a href="listado.php" class="btn-cancel">Cancelar</a>
                </form>
            </div>
        </div>
    </main>
    <script>
        // Inicializar subcategorías al cargar
        document.addEventListener('DOMContentLoaded', function() {
            if(document.getElementById('categoria_id').value) {
                cargarSubcategorias();
            }
        });
        
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
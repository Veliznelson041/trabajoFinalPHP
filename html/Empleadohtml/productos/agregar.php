<?php
require_once '../../../controlSesion.php';
require_once '../../../conexion.php';

// Obtener categorías
$categorias = $conexion->query("SELECT * FROM categorias");

// Inicializar variables para mantener los valores del formulario en caso de error
$nombre_val = '';
$descripcion_val = '';
$precio_val = '';
$stock_val = '';
$imagen_url_val = '';
$categoria_id_val = '';
$subcategoria_id_val = '';

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger datos del formulario
    $nombre_val = $_POST['nombre'];
    $descripcion_val = $_POST['descripcion'];
    $precio_val = floatval($_POST['precio']);
    $stock_val = intval($_POST['stock']);
    $imagen_url_val = $_POST['imagen_url'];
    $categoria_id_val = isset($_POST['categoria_id']) ? $_POST['categoria_id'] : '';
    $subcategoria_id_val = isset($_POST['subcategoria_id']) ? $_POST['subcategoria_id'] : '';

    // Validar que se haya seleccionado una subcategoría
    if (empty($subcategoria_id_val)) {
        $error = "Debe seleccionar una subcategoría";
    } else {
        $subcategoria_id = intval($subcategoria_id_val);
        $modificado_por = $_SESSION['email'];

        $sql = "INSERT INTO productos (
            nombre, descripcion, precio, stock, 
            subcategoria_id, imagen_url, modificado_por, fecha_modificacion
        ) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ssdiiss", $nombre_val, $descripcion_val, $precio_val, $stock_val, $subcategoria_id, $imagen_url_val, $modificado_por);
        
        if ($stmt->execute()) {
            header('Location: listado.php?success=1');
            exit;
        } else {
            $error = "Error al crear el producto: " . $conexion->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Producto</title>
    <link rel="stylesheet" href="../../../css/Empleadocss/styles-productos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script>
        function cargarSubcategorias() {
            const categoriaId = document.getElementById('categoria_id').value;
            if (!categoriaId) {
                // Si no hay categoría seleccionada, deshabilitar y resetear subcategoría
                document.getElementById('subcategoria_id').disabled = true;
                document.getElementById('subcategoria_id').innerHTML = '<option value="">Primero seleccione categoría</option>';
                return;
            }
            
            fetch('../../../get_subcategorias.php?categoria_id=' + categoriaId)
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
                    // Habilitar el select
                    select.disabled = false;
                    
                    // Si hay un valor previamente seleccionado (por un error), seleccionarlo
                    <?php if (!empty($subcategoria_id_val)): ?>
                        select.value = <?= $subcategoria_id_val ?>;
                    <?php endif; ?>
                })
                .catch(error => console.error('Error:', error));
        }
        
        // Al cargar la página, si ya se había seleccionado una categoría, cargar sus subcategorías
        document.addEventListener('DOMContentLoaded', function() {
            <?php if (!empty($categoria_id_val)): ?>
                document.getElementById('categoria_id').value = <?= $categoria_id_val ?>;
                cargarSubcategorias();
            <?php endif; ?>
        });
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
                <li><a href="#">Sobre Nosotros</a></li>
                <li><a href="#">Contacto</a></li>                                
                <li><a href="../../perfilempleado.php" class="btn-login"><i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['nombre']) ?></a></li>
            </ul>
        </nav>
    </header>
    <br>

    <nav class="ruta">
        <a href="../../empleado.php">Inicio</a> > <a href="listado.php">Productos</a> > <a href="#">Agregar</a>
    </nav>

    <main>
        <div class="form-container">
            <h2>Agregar Nuevo Producto</h2>
            
            <?php if(isset($error)): ?>
                <div class="error"><?= $error ?></div>
            <?php endif; ?>
            
            <form action="agregar.php" method="post" class="product-form">
                <!-- Imagen del producto -->
                <label for="imagen_url">URL de la imagen:</label>
                <input type="text" id="imagen_url" name="imagen_url" class="input-text" placeholder="URL de la imagen" required value="<?= htmlspecialchars($imagen_url_val) ?>">

                <!-- Nombre del producto -->
                <label for="nombre">Nombre del producto:</label>
                <input type="text" id="nombre" name="nombre" class="input-text" placeholder="Nombre del producto" required value="<?= htmlspecialchars($nombre_val) ?>">

                <!-- Descripción del producto -->
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="descripcion" class="input-description" placeholder="Descripción del producto" required><?= htmlspecialchars($descripcion_val) ?></textarea>

                <!-- Precio del producto -->
                <label for="precio">Precio:</label>
                <input type="number" step="0.01" min="0" id="precio" name="precio" class="input-text" placeholder="Precio del producto" required value="<?= htmlspecialchars($precio_val) ?>">

                <!-- Stock disponible -->
                <label for="stock">Stock disponible:</label>
                <input type="number" min="0" id="stock" name="stock" class="input-text" placeholder="Unidades disponibles" required value="<?= htmlspecialchars($stock_val) ?>">

                <!-- Categoría y Subcategoría -->
                <label for="categoria_id">Categoría:</label>
                <select id="categoria_id" name="categoria_id" class="input-select" onchange="cargarSubcategorias()" required>
                    <option value="">Seleccione categoría</option>
                    <?php 
                    // Reiniciar el puntero del resultado
                    $categorias->data_seek(0);
                    while($cat = $categorias->fetch_assoc()): 
                    ?>
                        <option value="<?= $cat['categoria_id'] ?>" <?= $categoria_id_val == $cat['categoria_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['nombre']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <label for="subcategoria_id">Subcategoría:</label>
                <select id="subcategoria_id" name="subcategoria_id" class="input-select" required>
                    <option value="">Primero seleccione categoría</option>
                    <?php 
                    // Si hay un error y ya se había seleccionado una subcategoría, mostrar opciones?
                    // Esto se manejará principalmente por JavaScript
                    ?>
                </select>

                <!-- Botón de agregar -->
                <button type="submit" class="btn-submit">Agregar Producto</button>
                <a href="listado.php" class="btn-cancel">Cancelar</a>
            </form>
        </div>
    </main>
    <script>
        // Actualizar fecha y hora
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
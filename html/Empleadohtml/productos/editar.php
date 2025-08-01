<?php
require_once '../../../controlSesion.php';
require_once '../../../conexion.php';

// Verificar ID de producto
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: listado.php?error=ID inválido');
    exit;
}

$producto_id = intval($_GET['id']);

// Inicializar valores con array vacío
$valores = [
    'nombre' => '',
    'descripcion' => '',
    'precio' => '',
    'stock' => '',
    'imagen_url' => '',
    'categoria_id' => '',
    'subcategoria_id' => ''
];

$error = '';

// Obtener categorías
$categorias = $conexion->query("SELECT * FROM categorias");

// Obtener datos actuales del producto
$sql_producto = "SELECT p.*, s.categoria_id 
                 FROM productos p
                 JOIN subcategorias s ON p.subcategoria_id = s.subcategoria_id
                 WHERE p.producto_id = ?";
$stmt = $conexion->prepare($sql_producto);
$stmt->bind_param("i", $producto_id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    header('Location: listado.php?error=Producto no encontrado');
    exit;
}

$producto = $resultado->fetch_assoc();

// Actualizar valores con datos del producto
$valores = [
    'nombre' => $producto['nombre'],
    'descripcion' => $producto['descripcion'],
    'precio' => $producto['precio'],
    'stock' => $producto['stock'],
    'imagen_url' => $producto['imagen_url'],
    'categoria_id' => $producto['categoria_id'],
    'subcategoria_id' => $producto['subcategoria_id']
];

// Procesar actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger y validar datos
    $valores = [
        'nombre' => trim($_POST['nombre']),
        'descripcion' => trim($_POST['descripcion']),
        'precio' => floatval($_POST['precio']),
        'stock' => intval($_POST['stock']),
        'imagen_url' => trim($_POST['imagen_url']),
        'categoria_id' => isset($_POST['categoria_id']) ? intval($_POST['categoria_id']) : '',
        'subcategoria_id' => isset($_POST['subcategoria_id']) ? intval($_POST['subcategoria_id']) : ''
    ];

    // Validaciones
    if (empty($valores['nombre'])) {
        $error = "El nombre es obligatorio";
    } elseif ($valores['precio'] <= 0) {
        $error = "Precio debe ser mayor a 0";
    } elseif ($valores['stock'] < 0) {
        $error = "Stock no puede ser negativo";
    } elseif (empty($valores['subcategoria_id'])) {
        $error = "Seleccione una subcategoría";
    } else {
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
        $stmt->bind_param("ssdiissi", 
            $valores['nombre'],
            $valores['descripcion'],
            $valores['precio'],
            $valores['stock'],
            $valores['subcategoria_id'],
            $valores['imagen_url'],
            $modificado_por,
            $producto_id
        );
        
        if ($stmt->execute()) {
            header('Location: listado.php?success=1');
            exit;
        } else {
            $error = "Error al actualizar: " . $conexion->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto - Suplementos Dynamite</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        :root {
            --primary: #2c3e50;
            --secondary: #3498db;
            --accent: #e74c3c;
            --light: #ecf0f1;
            --dark: #2c3e50;
            --success: #27ae60;
            --warning: #f39c12;
            --gray: #95a5a6;
        }
        
        body {
            background-color: #f5f7fa;
            color: var(--dark);
            line-height: 1.6;
        }
        
        /* Header */
        header {
            background: linear-gradient(135deg, var(--primary), #1a2530);
            color: white;
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            padding: 0 20px;
        }
        
        .logo {
            height: 50px;
            margin-right: 15px;
            border-radius: 50%;
        }
        
        .header-container h1 {
            font-size: 1.8rem;
            font-weight: 600;
        }
        
        #date-time {
            text-align: center;
            padding: 5px 0;
            background: rgba(0,0,0,0.1);
            font-size: 0.9rem;
        }
        
        .nav-bar {
            background-color: rgba(0,0,0,0.15);
            padding: 10px 0;
        }
        
        .nav-bar ul {
            display: flex;
            justify-content: center;
            list-style: none;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .nav-bar li {
            margin: 0 15px;
        }
        
        .nav-bar a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            padding: 8px 15px;
            border-radius: 4px;
            transition: all 0.3s ease;
        }
        
        .nav-bar a:hover {
            background: rgba(255,255,255,0.1);
        }
        
        .btn-login {
            background: var(--secondary);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-login:hover {
            background: #2980b9;
        }
        
        /* Ruta de navegación */
        .ruta {
            max-width: 1200px;
            margin: 20px auto 10px;
            padding: 0 20px;
            font-size: 0.9rem;
            color: var(--gray);
        }
        
        .ruta a {
            color: var(--secondary);
            text-decoration: none;
        }
        
        .ruta a:hover {
            text-decoration: underline;
        }
        
        /* Contenedor principal */
        main {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
        }
        
        /* Formulario */
        .form-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            overflow: hidden;
            margin-bottom: 30px;
        }
        
        .form-header {
            background: linear-gradient(to right, var(--secondary), var(--primary));
            color: white;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .form-header i {
            font-size: 1.5rem;
        }
        
        .form-content {
            padding: 30px;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--dark);
        }
        
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            border-color: var(--secondary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }
        
        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }
        
        .required::after {
            content: " *";
            color: var(--accent);
        }
        
        .image-preview {
            grid-column: 1 / 3;
            text-align: center;
            margin: 15px 0;
        }
        
        .image-preview img {
            max-width: 100%;
            max-height: 250px;
            border-radius: 8px;
            border: 1px solid #eee;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        }
        
        .form-actions {
            grid-column: 1 / 3;
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        
        .btn-submit {
            background: var(--success);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-submit:hover {
            background: #219653;
            transform: translateY(-2px);
        }
        
        .btn-cancel {
            background: var(--gray);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-cancel:hover {
            background: #7f8c8d;
            transform: translateY(-2px);
        }
        
        .required-note {
            grid-column: 1 / 3;
            color: var(--gray);
            font-size: 0.9rem;
            margin-top: -10px;
            font-style: italic;
        }
        
        /* Mensajes de error */
        .error {
            background: #fee;
            color: var(--accent);
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 25px;
            border-left: 4px solid var(--accent);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .error i {
            font-size: 1.2rem;
        }
        
        /* Footer */
        footer {
            background: var(--primary);
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 30px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .image-preview,
            .form-actions,
            .required-note {
                grid-column: 1;
            }
            
            .nav-bar ul {
                flex-direction: column;
                align-items: center;
                gap: 10px;
            }
        }
    </style>
    <script>
        function cargarSubcategorias() {
            const categoriaId = document.getElementById('categoria_id').value;
            const subcatSelect = document.getElementById('subcategoria_id');
            
            if (!categoriaId) {
                subcatSelect.disabled = true;
                subcatSelect.innerHTML = '<option value="">Seleccione categoría primero</option>';
                return;
            }
            
            fetch('../../../get_subcategorias.php?categoria_id=' + categoriaId)
                .then(response => response.json())
                .then(data => {
                    subcatSelect.innerHTML = '<option value="">Seleccione subcategoría</option>';
                    data.forEach(sub => {
                        const option = document.createElement('option');
                        option.value = sub.subcategoria_id;
                        option.textContent = sub.nombre;
                        subcatSelect.appendChild(option);
                    });
                    subcatSelect.disabled = false;
                    
                    // Seleccionar la subcategoría actual
                    <?php if (!empty($valores['subcategoria_id'])): ?>
                        subcatSelect.value = <?= $valores['subcategoria_id'] ?>;
                    <?php endif; ?>
                })
                .catch(error => {
                    console.error('Error:', error);
                    subcatSelect.innerHTML = '<option value="">Error al cargar</option>';
                });
        }
        
        // Al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            // Establecer categoría actual
            const categoriaId = <?= !empty($valores['categoria_id']) ? $valores['categoria_id'] : 'null' ?>;
            if (categoriaId) {
                document.getElementById('categoria_id').value = categoriaId;
            }
            
            // Cargar subcategorías para la categoría actual
            cargarSubcategorias();
            
            // Previsualización de imagen
            const imageUrlInput = document.getElementById('imagen_url');
            const previewImage = document.getElementById('preview');
            
            function updatePreview() {
                if (imageUrlInput.value) {
                    previewImage.src = imageUrlInput.value;
                    previewImage.style.display = 'block';
                } else {
                    previewImage.style.display = 'none';
                }
            }
            
            imageUrlInput.addEventListener('input', updatePreview);
            updatePreview();
        });
    </script>
</head>
<body>
    <header>
        <div class="header-container">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRt_eZYMvS26mdHNwVQw-zHWqDRdSz5XzAVHQ&s" alt="Logo" class="logo">
            <h1>Suplementos Dynamite</h1>
        </div>
        <br>
        <p id="date-time"></p>
        <nav class="nav-bar">
            <ul>
                <li><a href="../empleado.php">Inicio</a></li>                              
                <li><a href="../perfilempleado.php" class="btn-login"><i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['nombre']) ?></a></li>
            </ul>
        </nav>
    </header>
    
    <nav class="ruta">
        <a href="../empleado.php">Inicio</a> > <a href="listado.php">Productos</a> > <a href="#">Editar</a>
    </nav>

    <main>
        <div class="form-container">
            <div class="form-header">
                <i class="fas fa-edit"></i>
                <h2>Editar Producto</h2>
            </div>
            
            <div class="form-content">
                <?php if(!empty($error)): ?>
                    <div class="error">
                        <i class="fas fa-exclamation-circle"></i>
                        <span><?= $error ?></span>
                    </div>
                <?php endif; ?>
                
                <form action="editar.php?id=<?= $producto_id ?>" method="post" class="product-form">
                    <div class="form-grid">
                        <!-- Imagen -->
                        <div class="form-group">
                            <label for="imagen_url" class="required">URL Imagen</label>
                            <input type="text" id="imagen_url" name="imagen_url" 
                                   value="<?= htmlspecialchars($valores['imagen_url'] ?? '') ?>" required>
                        </div>
                        
                        <!-- Previsualización de imagen -->
                        <div class="image-preview">
                            <?php if(!empty($valores['imagen_url'])): ?>
                                <img id="preview" src="<?= htmlspecialchars($valores['imagen_url']) ?>" alt="Previsualización de imagen">
                            <?php else: ?>
                                <img id="preview" src="" alt="Previsualización de imagen" style="display:none;">
                            <?php endif; ?>
                        </div>
                        
                        <!-- Nombre -->
                        <div class="form-group">
                            <label for="nombre" class="required">Nombre del Producto</label>
                            <input type="text" id="nombre" name="nombre" 
                                   value="<?= htmlspecialchars($valores['nombre'] ?? '') ?>" required>
                        </div>
                        
                        <!-- Descripción -->
                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea id="descripcion" name="descripcion"><?= htmlspecialchars($valores['descripcion'] ?? '') ?></textarea>
                        </div>
                        
                        <!-- Precio -->
                        <div class="form-group">
                            <label for="precio" class="required">Precio ($)</label>
                            <input type="number" step="0.01" min="0.01" id="precio" name="precio" 
                                   value="<?= htmlspecialchars($valores['precio'] ?? '') ?>" required>
                        </div>
                        
                        <!-- Stock -->
                        <div class="form-group">
                            <label for="stock" class="required">Stock Disponible</label>
                            <input type="number" min="0" id="stock" name="stock" 
                                   value="<?= htmlspecialchars($valores['stock'] ?? '') ?>" required>
                        </div>
                        
                        <!-- Categoría -->
                        <div class="form-group">
                            <label for="categoria_id" class="required">Categoría</label>
                            <select id="categoria_id" name="categoria_id" onchange="cargarSubcategorias()" required>
                                <option value="">Seleccione categoría</option>
                                <?php 
                                $categorias->data_seek(0); // Reiniciar puntero
                                while($cat = $categorias->fetch_assoc()): 
                                ?>
                                    <option value="<?= $cat['categoria_id'] ?>" 
                                        <?= ($valores['categoria_id'] ?? '') == $cat['categoria_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat['nombre']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        
                        <!-- Subcategoría -->
                        <div class="form-group">
                            <label for="subcategoria_id" class="required">Subcategoría</label>
                            <select id="subcategoria_id" name="subcategoria_id" required>
                                <option value="">Cargando...</option>
                            </select>
                        </div>
                        
                        <p class="required-note">Los campos marcados con asterisco (*) son obligatorios</p>
                        
                        <div class="form-actions">
                            <a href="listado.php" class="btn-cancel">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                            <button type="submit" class="btn-submit">
                                <i class="fas fa-save"></i> Actualizar Producto
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
    
    <footer>
        <p>Suplementos Dynamite &copy; <?= date('Y') ?> - Sistema de Gestión de Productos</p>
    </footer>
    
    <script>
        function updateDateTime() {
            const now = new Date();
            const options = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            };
            document.getElementById('date-time').textContent = now.toLocaleDateString('es-ES', options);
        }
        setInterval(updateDateTime, 1000);
        updateDateTime();
    </script>
</body>
</html>
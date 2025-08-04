<?php
require_once '../../../controlSesion.php';
require_once '../../../conexion.php';

// Obtener categorías
$categorias = $conexion->query("SELECT * FROM categorias");

// Inicializar variables para mantener los valores del formulario
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

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger y sanitizar datos
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
        $error = "El nombre del producto es obligatorio";
    } elseif (empty($valores['precio']) || $valores['precio'] <= 0) {
        $error = "El precio debe ser mayor que 0";
    } elseif ($valores['stock'] < 0) {
        $error = "El stock no puede ser negativo";
    } elseif (empty($valores['subcategoria_id'])) {
        $error = "Debe seleccionar una subcategoría";
    } else {
        $modificado_por = $_SESSION['email'];
        $sql = "INSERT INTO productos (
                    nombre, descripcion, precio, stock, 
                    subcategoria_id, imagen_url, modificado_por, fecha_modificacion
                ) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ssdiiss", 
            $valores['nombre'],
            $valores['descripcion'],
            $valores['precio'],
            $valores['stock'],
            $valores['subcategoria_id'],
            $valores['imagen_url'],
            $modificado_por
        );
        
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
    <title>Agregar Producto | Suplementos Dynamite</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #3498db;
            --success: #27ae60;
            --danger: #e74c3c;
            --warning: #f39c12;
            --light: #ecf0f1;
            --dark: #34495e;
            --gray: #95a5a6;
            --light-gray: #f8f9fa;
            --border: #e0e0e0;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --radius: 8px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Header Styles */
        header {
            background: linear-gradient(135deg, var(--primary), var(--dark));
            color: white;
            padding: 15px 0;
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .header-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .logo-container {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .logo {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid white;
        }
        
        .brand {
            display: flex;
            flex-direction: column;
        }
        
        .brand h1 {
            font-size: 1.8rem;
            margin-bottom: 5px;
        }
        
        .brand p {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.1);
            padding: 8px 15px;
            border-radius: 30px;
            transition: all 0.3s ease;
        }
        
        .user-profile:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        
        .user-profile i {
            font-size: 1.2rem;
        }
        
        .user-profile span {
            font-weight: 600;
        }
        
        /* Navigation */
        .nav-empleado {
            background: white;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            margin: 20px 0;
            padding: 0 15px;
        }
        
        .nav-empleado ul {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .nav-empleado li {
            flex: 1;
            text-align: center;
        }
        
        .nav-empleado a {
            display: block;
            padding: 15px 10px;
            text-decoration: none;
            color: var(--dark);
            font-weight: 600;
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
        }
        
        .nav-empleado a:hover, 
        .nav-empleado a.active {
            color: var(--secondary);
            border-bottom: 3px solid var(--secondary);
        }
        
        /* Breadcrumb */
        .breadcrumb {
            padding: 15px 0;
            font-size: 0.95rem;
            color: var(--gray);
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
        }
        
        .breadcrumb a {
            text-decoration: none;
            color: var(--secondary);
            transition: all 0.2s ease;
        }
        
        .breadcrumb a:hover {
            color: var(--primary);
            text-decoration: underline;
        }
        
        .breadcrumb span {
            color: var(--dark);
            font-weight: 500;
        }
        
        /* Main Content */
        .main-content {
            background: white;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 30px;
            margin-bottom: 30px;
        }
        
        .page-header {
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border);
        }
        
        .page-title {
            font-size: 1.8rem;
            color: var(--primary);
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .page-subtitle {
            color: var(--gray);
            font-size: 1.1rem;
            font-weight: 400;
        }
        
        /* Form Styles */
        .form-container {
            max-width: 900px;
            margin: 0 auto;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
        }
        
        .form-section {
            margin-bottom: 30px;
        }
        
        .form-section-title {
            font-size: 1.3rem;
            color: var(--primary);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--light);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .form-section-title i {
            color: var(--secondary);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--dark);
        }
        
        .required::after {
            content: " *";
            color: var(--danger);
        }
        
        .input-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--border);
            border-radius: 6px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: var(--light-gray);
        }
        
        .input-control:focus {
            outline: none;
            border-color: var(--secondary);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
            background: white;
        }
        
        textarea.input-control {
            min-height: 120px;
            resize: vertical;
        }
        
        .input-select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--border);
            border-radius: 6px;
            font-size: 1rem;
            background: var(--light-gray);
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2334495e' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 16px;
        }
        
        .input-select:focus {
            outline: none;
            border-color: var(--secondary);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
            background-color: white;
        }
        
        /* Image Preview */
        .image-preview {
            width: 100%;
            max-width: 300px;
            margin-top: 15px;
            border-radius: var(--radius);
            overflow: hidden;
            border: 1px solid var(--border);
            background: var(--light-gray);
            display: flex;
            align-items: center;
            justify-content: center;
            aspect-ratio: 4/3;
        }
        
        .image-preview img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        
        .image-preview-placeholder {
            color: var(--gray);
            text-align: center;
            padding: 20px;
        }
        
        .image-preview-placeholder i {
            font-size: 3rem;
            margin-bottom: 10px;
            display: block;
            color: var(--light);
        }
        
        /* Buttons */
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 30px;
            padding-top: 25px;
            border-top: 1px solid var(--border);
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 25px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            font-size: 1rem;
        }
        
        .btn-submit {
            background: var(--success);
            color: white;
        }
        
        .btn-submit:hover {
            background: #219653;
            transform: translateY(-2px);
        }
        
        .btn-cancel {
            background: var(--light-gray);
            color: var(--dark);
        }
        
        .btn-cancel:hover {
            background: #e0e0e0;
        }
        
        /* Messages */
        .message {
            padding: 15px;
            border-radius: var(--radius);
            margin: 15px 0 30px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .error {
            background: #fdecea;
            color: var(--danger);
            border-left: 4px solid var(--danger);
        }
        
        .message i {
            font-size: 1.2rem;
        }
        
        .required-note {
            font-size: 0.9rem;
            color: var(--danger);
            margin-top: 20px;
            font-style: italic;
        }
        
        /* Footer */
        footer {
            background: var(--primary);
            color: white;
            padding: 20px 0;
            text-align: center;
            margin-top: 30px;
        }
        
        .footer-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }
        
        .footer-logo {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid white;
        }
        
        .copyright {
            font-size: 0.9rem;
            opacity: 0.8;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .nav-empleado ul {
                flex-wrap: wrap;
            }
            
            .nav-empleado li {
                flex: 1 0 33%;
            }
        }
        
        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .user-info {
                align-self: flex-end;
            }
            
            .nav-empleado li {
                flex: 1 0 50%;
            }
            
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
        }
        
        @media (max-width: 576px) {
            .nav-empleado li {
                flex: 1 0 100%;
            }
            
            .brand h1 {
                font-size: 1.5rem;
            }
            
            .page-title {
                font-size: 1.5rem;
            }
            
            .main-content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container header-container">
            <div class="logo-container">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRt_eZYMvS26mdHNwVQw-zHWqDRdSz5XzAVHQ&s" alt="Logo de Suplementos Dynamite" class="logo">
                <div class="brand">
                    <h1>Suplementos Dynamite</h1>
                    <p id="date-time"></p>
                </div>
            </div>
            
            <nav class="nav-bar">
                <ul>
                    <li><a href="../empleado.php">Inicio</a></li>
                </ul>
            </nav>
            
            <div class="user-info">
                <a href="../perfilempleado.php" class="user-profile">
                    <i class="fas fa-user-circle"></i>
                    <span><?= htmlspecialchars($_SESSION['nombre']) ?></span>
                </a>
            </div>
        </div>
    </header>

    <div class="container">
        <nav class="nav-empleado">
            <ul>
                <li><a href="../consultas/buzon.php">Buzón de Consultas</a></li>
                <li><a href="../proveedores.php">Proveedores</a></li>
                <li><a href="../pedidos/listado.php">Seguimiento de Paquetes</a></li>
                <li><a href="listado.php" class="active">Gestión de Productos</a></li>
            </ul>
        </nav>
        
        <div class="breadcrumb">
            <a href="../empleado.php">Inicio</a> <i class="fas fa-chevron-right"></i>
            <a href="listado.php">Productos</a> <i class="fas fa-chevron-right"></i>
            <span>Agregar Producto</span>
        </div>
        
        <div class="main-content">
            <div class="page-header">
                <h1 class="page-title">Agregar Nuevo Producto</h1>
                <p class="page-subtitle">Complete el formulario para añadir un nuevo producto al catálogo</p>
            </div>
            
            <?php if($error): ?>
                <div class="message error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?= $error ?></span>
                </div>
            <?php endif; ?>
            
            <div class="form-container">
                <form action="agregar.php" method="post" class="product-form">
                    <div class="form-grid">
                        <div class="form-section">
                            <h3 class="form-section-title"><i class="fas fa-image"></i> Imagen del Producto</h3>
                            
                            <div class="form-group">
                                <label for="imagen_url" class="required">URL de la imagen:</label>
                                <input type="text" id="imagen_url" name="imagen_url" class="input-control" 
                                       placeholder="https://ejemplo.com/imagen-producto.jpg" 
                                       value="<?= htmlspecialchars($valores['imagen_url']) ?>">
                            </div>
                            
                            <div class="image-preview" id="imagePreview">
                                <?php if(!empty($valores['imagen_url'])): ?>
                                    <img src="<?= htmlspecialchars($valores['imagen_url']) ?>" alt="Vista previa de la imagen">
                                <?php else: ?>
                                    <div class="image-preview-placeholder">
                                        <i class="fas fa-image"></i>
                                        <div>Vista previa de la imagen</div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="form-section">
                            <h3 class="form-section-title"><i class="fas fa-info-circle"></i> Información Básica</h3>
                            
                            <div class="form-group">
                                <label for="nombre" class="required">Nombre del producto:</label>
                                <input type="text" id="nombre" name="nombre" class="input-control" 
                                       placeholder="Ej: Proteína Whey 2kg" required 
                                       value="<?= htmlspecialchars($valores['nombre']) ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="descripcion">Descripción:</label>
                                <textarea id="descripcion" name="descripcion" class="input-control" 
                                          placeholder="Describa las características del producto..."><?= htmlspecialchars($valores['descripcion']) ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="precio" class="required">Precio:</label>
                                <input type="number" step="0.01" min="0.01" id="precio" name="precio" 
                                       class="input-control" placeholder="Ej: 29.99" required 
                                       value="<?= htmlspecialchars($valores['precio']) ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="stock" class="required">Stock disponible:</label>
                                <input type="number" min="0" id="stock" name="stock" class="input-control" 
                                       placeholder="Ej: 50" required 
                                       value="<?= htmlspecialchars($valores['stock']) ?>">
                            </div>
                        </div>
                        
                        <div class="form-section">
                            <h3 class="form-section-title"><i class="fas fa-tags"></i> Categorización</h3>
                            
                            <div class="form-group">
                                <label for="categoria_id" class="required">Categoría:</label>
                                <select id="categoria_id" name="categoria_id" class="input-select" 
                                        onchange="cargarSubcategorias()" required>
                                    <option value="">Seleccione categoría</option>
                                    <?php 
                                    // Reiniciar el puntero del resultado
                                    $categorias->data_seek(0);
                                    while($cat = $categorias->fetch_assoc()): 
                                    ?>
                                        <option value="<?= $cat['categoria_id'] ?>" 
                                            <?= $valores['categoria_id'] == $cat['categoria_id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cat['nombre']) ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="subcategoria_id" class="required">Subcategoría:</label>
                                <select id="subcategoria_id" name="subcategoria_id" class="input-select" required disabled>
                                    <option value="">Primero seleccione categoría</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <p class="required-note">* Campos obligatorios</p>
                    
                    <div class="form-actions">
                        <a href="listado.php" class="btn btn-cancel">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-submit">
                            <i class="fas fa-plus-circle"></i> Agregar Producto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <footer>
        <div class="container footer-content">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRt_eZYMvS26mdHNwVQw-zHWqDRdSz5XzAVHQ&s" alt="Logo de Suplementos Dynamite" class="footer-logo">
            <p class="copyright">Suplementos Dynamite &copy; <?= date('Y') ?> - Todos los derechos reservados</p>
        </div>
    </footer>
    
    <script>
        // Función para cargar subcategorías
        function cargarSubcategorias() {
            const categoriaId = document.getElementById('categoria_id').value;
            const subcatSelect = document.getElementById('subcategoria_id');
            
            if (!categoriaId) {
                subcatSelect.disabled = true;
                subcatSelect.innerHTML = '<option value="">Primero seleccione categoría</option>';
                return;
            }
            
            fetch('../../../get_subcategorias.php?categoria_id=' + categoriaId)
                .then(response => {
                    if (!response.ok) throw new Error('Error en la respuesta');
                    return response.json();
                })
                .then(data => {
                    subcatSelect.innerHTML = '<option value="">Seleccione subcategoría</option>';
                    data.forEach(sub => {
                        const option = document.createElement('option');
                        option.value = sub.subcategoria_id;
                        option.textContent = sub.nombre;
                        subcatSelect.appendChild(option);
                    });
                    subcatSelect.disabled = false;
                    
                    // Si hay un valor previamente seleccionado, seleccionarlo
                    <?php if (!empty($valores['subcategoria_id'])): ?>
                        if (<?= $valores['subcategoria_id'] ?>) {
                            subcatSelect.value = <?= $valores['subcategoria_id'] ?>;
                        }
                    <?php endif; ?>
                })
                .catch(error => {
                    console.error('Error:', error);
                    subcatSelect.innerHTML = '<option value="">Error al cargar subcategorías</option>';
                });
        }
        
        // Vista previa de imagen
        const imageUrlInput = document.getElementById('imagen_url');
        const imagePreview = document.getElementById('imagePreview');
        
        imageUrlInput.addEventListener('input', function() {
            const imageUrl = this.value.trim();
            if (imageUrl) {
                imagePreview.innerHTML = `<img src="${imageUrl}" alt="Vista previa de la imagen" onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'image-preview-placeholder\'><i class=\'fas fa-exclamation-triangle\'></i><div>Imagen no disponible</div></div>';" />`;
            } else {
                imagePreview.innerHTML = '<div class="image-preview-placeholder"><i class="fas fa-image"></i><div>Vista previa de la imagen</div></div>';
            }
        });
        
        // Al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            // Actualizar fecha y hora
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
                const dateTime = now.toLocaleDateString('es-ES', options);
                document.getElementById('date-time').textContent = dateTime;
            }
            setInterval(updateDateTime, 1000);
            updateDateTime();
            
            // Cargar subcategorías si es necesario
            <?php if (!empty($valores['categoria_id'])): ?>
                document.getElementById('categoria_id').value = <?= $valores['categoria_id'] ?>;
                cargarSubcategorias();
            <?php endif; ?>
        });
    </script>
</body>
</html>
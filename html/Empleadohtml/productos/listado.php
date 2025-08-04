<?php
require_once '../../../controlSesion.php';
require_once '../../../conexion.php';

// Manejo de errores
$error = '';
if(isset($_GET['error'])) {
    $error = urldecode($_GET['error']);
}

// Obtener todos los productos
$sql = "SELECT p.*, s.nombre AS subcategoria, c.nombre AS categoria 
        FROM productos p
        JOIN subcategorias s ON p.subcategoria_id = s.subcategoria_id
        JOIN categorias c ON s.categoria_id = c.categoria_id
        ORDER BY p.fecha_modificacion DESC";

$productos = $conexion->query($sql);

// Verificar si hay error en la consulta
if(!$productos) {
    die("Error en la consulta: " . $conexion->error);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Productos | Suplementos Dynamite</title>
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
        
        .user-profile img {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .user-profile span {
            font-weight: 600;
        }
        
        /* Navigation */
        .nav-empleado {
            background: white;
            border-radius: 8px;
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
        
        /* Main Content */
        .main-content {
            background: white;
            border-radius: 10px;
            box-shadow: var(--shadow);
            padding: 25px;
            margin-bottom: 30px;
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border);
        }
        
        .page-title {
            font-size: 1.8rem;
            color: var(--primary);
            font-weight: 700;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            font-size: 0.95rem;
        }
        
        .btn i {
            font-size: 0.9rem;
        }
        
        .btn-agregar {
            background: var(--success);
            color: white;
        }
        
        .btn-agregar:hover {
            background: #219653;
            transform: translateY(-2px);
        }
        
        /* Messages */
        .message {
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .success {
            background: #e6f7ee;
            color: var(--success);
            border-left: 4px solid var(--success);
        }
        
        .error {
            background: #fdecea;
            color: var(--danger);
            border-left: 4px solid var(--danger);
        }
        
        .message i {
            font-size: 1.2rem;
        }
        
        /* Products Table */
        .products-container {
            overflow-x: auto;
        }
        
        .products-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: var(--shadow);
            background: white;
        }
        
        .products-table thead {
            background: linear-gradient(135deg, var(--primary), var(--dark));
            color: white;
        }
        
        .products-table th {
            padding: 16px 15px;
            text-align: left;
            font-weight: 600;
        }
        
        .products-table tbody tr {
            transition: background 0.2s ease;
        }
        
        .products-table tbody tr:nth-child(even) {
            background: var(--light-gray);
        }
        
        .products-table tbody tr:hover {
            background: #e3f2fd;
        }
        
        .products-table td {
            padding: 14px 15px;
            border-bottom: 1px solid var(--border);
        }
        
        .product-img-container {
            width: 70px;
            height: 70px;
            border-radius: 8px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            border: 1px solid var(--border);
        }
        
        .product-img {
            max-width: 100%;
            max-height: 100%;
            object-fit: cover;
        }
        
        .no-img {
            color: var(--gray);
            font-size: 0.85rem;
        }
        
        .product-name {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 4px;
        }
        
        .product-category {
            font-size: 0.85rem;
            color: var(--gray);
        }
        
        .product-price {
            font-weight: 700;
            color: var(--primary);
        }
        
        .product-stock {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 30px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .stock-high {
            background: #e6f7ee;
            color: var(--success);
        }
        
        .stock-medium {
            background: #fef9e7;
            color: var(--warning);
        }
        
        .stock-low {
            background: #fdecea;
            color: var(--danger);
        }
        
        .actions {
            display: flex;
            gap: 8px;
        }
        
        .btn-action {
            padding: 8px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
        }
        
        .btn-edit {
            background: #e3f2fd;
            color: var(--secondary);
        }
        
        .btn-edit:hover {
            background: #bbdefb;
        }
        
        .btn-delete {
            background: #fdecea;
            color: var(--danger);
        }
        
        .btn-delete:hover {
            background: #f9cbc4;
        }
        
        /* Empty State */
        .no-products {
            text-align: center;
            padding: 50px 20px;
            background: white;
            border-radius: 8px;
            box-shadow: var(--shadow);
            margin: 20px 0;
        }
        
        .no-products i {
            font-size: 3rem;
            color: var(--gray);
            margin-bottom: 15px;
        }
        
        .no-products h3 {
            color: var(--primary);
            margin-bottom: 10px;
        }
        
        .no-products p {
            color: var(--gray);
            max-width: 500px;
            margin: 0 auto 20px;
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
            
            .products-table th, 
            .products-table td {
                padding: 12px 10px;
                font-size: 0.9rem;
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
                <a href="../../perfilempleado.php" class="user-profile">
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
                <li><a href="#" class="active">Gestión de Productos</a></li>
            </ul>
        </nav>
        
        <div class="main-content">
            <div class="page-header">
                <h1 class="page-title">Gestión de Productos</h1>
                <a class="btn btn-agregar" href="agregar.php">
                    <i class="fas fa-plus-circle"></i> Agregar Nuevo Producto
                </a>
            </div>
            
            <?php if(isset($_GET['success'])): ?>
                <div class="message success">
                    <i class="fas fa-check-circle"></i>
                    <span>¡Operación realizada con éxito!</span>
                </div>
            <?php endif; ?>
            
            <?php if($error): ?>
                <div class="message error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?= htmlspecialchars($error) ?></span>
                </div>
            <?php endif; ?>
            
            <?php if($productos->num_rows > 0): ?>
                <div class="products-container">
                    <table class="products-table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Precio</th>
                                <th>Stock</th>
                                <th>Categoría</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($producto = $productos->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 15px;">
                                        <div class="product-img-container">
                                            <?php if($producto['imagen_url']): ?>
                                                <img src="<?= htmlspecialchars($producto['imagen_url']) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>" class="product-img">
                                            <?php else: ?>
                                                <div class="no-img"><i class="fas fa-image"></i></div>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <div class="product-name"><?= htmlspecialchars($producto['nombre']) ?></div>
                                            <div class="product-category"><?= htmlspecialchars($producto['subcategoria']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="product-price">$<?= number_format($producto['precio'], 2) ?></td>
                                <td>
                                    <?php 
                                    $stockClass = 'stock-high';
                                    if ($producto['stock'] < 10) {
                                        $stockClass = 'stock-low';
                                    } elseif ($producto['stock'] < 30) {
                                        $stockClass = 'stock-medium';
                                    }
                                    ?>
                                    <span class="product-stock <?= $stockClass ?>">
                                        <?= $producto['stock'] ?> unidades
                                    </span>
                                </td>
                                <td>
                                    <div class="product-category"><?= htmlspecialchars($producto['categoria']) ?></div>
                                </td>
                                <td>
                                    <div class="actions">
                                        <a href="editar.php?id=<?= $producto['producto_id'] ?>" class="btn-action btn-edit">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        <a href="eliminar.php?id=<?= $producto['producto_id'] ?>" class="btn-action btn-delete" onclick="return confirm('¿Está seguro de eliminar este producto?')">
                                            <i class="fas fa-trash-alt"></i> Eliminar
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="no-products">
                    <i class="fas fa-box-open"></i>
                    <h3>No se encontraron productos</h3>
                    <p>Actualmente no hay productos registrados en el sistema. Puedes empezar agregando uno nuevo.</p>
                    <a class="btn btn-agregar" href="agregar.php">
                        <i class="fas fa-plus-circle"></i> Agregar Primer Producto
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <footer>
        <div class="container footer-content">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRt_eZYMvS26mdHNwVQw-zHWqDRdSz5XzAVHQ&s" alt="Logo de Suplementos Dynamite" class="footer-logo">
            <p class="copyright">Suplementos Dynamite &copy; <?= date('Y') ?> - Todos los derechos reservados</p>
        </div>
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
            const dateTime = now.toLocaleDateString('es-ES', options);
            document.getElementById('date-time').textContent = dateTime;
        }
        setInterval(updateDateTime, 1000);
        updateDateTime();
    </script>
</body>
</html>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --critical-color: #e74c3c;
            --low-color: #f39c12;
            --normal-color: #2ecc71;
            --light-bg: #f8f9fa;
            --card-bg: #ffffff;
            --border-color: #e0e0e0;
            --text-dark: #333;
            --text-light: #777;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: var(--light-bg);
            color: var(--text-dark);
            line-height: 1.6;
        }
        
        /* Header Styles */
        .main-header {
            background: linear-gradient(135deg, var(--secondary-color), #1a2530);
            color: white;
            padding: 1rem 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .header-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        
        .logo-container {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .logo {
            height: 50px;
            width: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid white;
        }
        
        .brand-text h1 {
            font-size: 1.5rem;
            font-weight: 600;
        }
        
        .brand-text p {
            font-size: 0.85rem;
            opacity: 0.9;
            margin-top: 3px;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.1);
            padding: 8px 15px;
            border-radius: 30px;
            transition: all 0.3s ease;
        }
        
        .user-info:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        
        .user-info i {
            font-size: 1.2rem;
        }
        
        .user-info span {
            font-weight: 500;
        }
        
        .nav-bar {
            background-color: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            padding: 0.5rem;
            margin-top: 1rem;
        }
        
        .nav-links {
            display: flex;
            list-style: none;
            gap: 20px;
        }
        
        .nav-links a {
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 5px;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .nav-links a:hover {
            background-color: rgba(255, 255, 255, 0.15);
        }
        
        .nav-links a.active {
            background-color: var(--primary-color);
        }
        
        /* Breadcrumb */
        .breadcrumb {
            padding: 1rem 2rem;
            background-color: var(--card-bg);
            border-bottom: 1px solid var(--border-color);
            font-size: 0.9rem;
        }
        
        .breadcrumb a {
            color: var(--primary-color);
            text-decoration: none;
        }
        
        .breadcrumb a:hover {
            text-decoration: underline;
        }
        
        .breadcrumb span {
            color: var(--text-light);
        }
        
        /* Main Content */
        .main-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1.5rem;
        }
        
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .dashboard-title {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--secondary-color);
        }
        
        .report-summary {
            background: var(--card-bg);
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border-left: 4px solid var(--critical-color);
        }
        
        .summary-content {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .summary-icon {
            background: rgba(231, 76, 60, 0.1);
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .summary-icon i {
            font-size: 2rem;
            color: var(--critical-color);
        }
        
        .summary-text h3 {
            font-size: 1.5rem;
            margin-bottom: 5px;
            color: var(--critical-color);
        }
        
        .summary-text p {
            color: var(--text-light);
        }
        
        /* Table Styles */
        .table-container {
            background-color: var(--card-bg);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }
        
        .table-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }
        
        .table-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--secondary-color);
        }
        
        .table-responsive {
            overflow-x: auto;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }
        
        .table th {
            background-color: #f5f7fa;
            color: var(--secondary-color);
            font-weight: 600;
            text-align: left;
            padding: 1rem 1.5rem;
            border-bottom: 2px solid var(--border-color);
        }
        
        .table td {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-dark);
        }
        
        .table tbody tr {
            transition: background-color 0.2s ease;
        }
        
        .table tbody tr:hover {
            background-color: #f8fafd;
        }
        
        /* Stock Indicator */
        .stock-indicator {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .stock-bar {
            height: 8px;
            border-radius: 4px;
            background-color: #e0e0e0;
            flex-grow: 1;
            max-width: 150px;
            overflow: hidden;
        }
        
        .stock-fill {
            height: 100%;
            border-radius: 4px;
        }
        
        .stock-critical .stock-fill {
            background-color: var(--critical-color);
            width: 10%; /* Muy bajo */
        }
        
        .stock-low .stock-fill {
            background-color: var(--low-color);
            width: 25%; /* Bajo */
        }
        
        .stock-value {
            font-weight: 600;
            min-width: 100px;
        }
        
        .stock-critical .stock-value {
            color: var(--critical-color);
        }
        
        .stock-low .stock-value {
            color: var(--low-color);
        }
        
        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .status-critical {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--critical-color);
        }
        
        .status-low {
            background-color: rgba(243, 156, 18, 0.1);
            color: var(--low-color);
        }
        
        /* Priority Icons */
        .priority-icon {
            font-size: 1.2rem;
            margin-right: 8px;
        }
        
        /* Price Styling */
        .price {
            font-weight: 600;
            color: var(--secondary-color);
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: var(--text-light);
        }
        
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.3;
            color: var(--normal-color);
        }
        
        .empty-state h3 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: var(--secondary-color);
        }
        
        /* Footer */
        .main-footer {
            text-align: center;
            padding: 1.5rem;
            color: var(--text-light);
            font-size: 0.9rem;
            border-top: 1px solid var(--border-color);
            margin-top: 2rem;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .user-info {
                align-self: flex-end;
            }
            
            .dashboard-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .summary-content {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <header class="main-header">
        <div class="header-container">
            <div class="logo-container">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRt_eZYMvS26mdHNwVQw-zHWqDRdSz5XzAVHQ&s" alt="Logo de Suplementos Dynamite" class="logo">
                <div class="brand-text">
                    <h1>Suplementos Dynamite</h1>
                    <p id="date-time"></p>
                </div>
            </div>
            
            <nav class="nav-bar">
                <ul class="nav-links">
                    <li><a href="../empleado.php">Inicio</a></li>
                    <li><a href="../perfilempleado.php" class="user-info">
                        <i class="fas fa-user"></i> 
                        <span><?= htmlspecialchars($_SESSION['nombre']) ?></span>
                    </a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <div class="breadcrumb">
        <a href="../empleado.php">Inicio</a> > <a href="#">Reportes</a> > <span>Stock Crítico</span>
    </div>
    
    <div class="main-container">
        <div class="dashboard-header">
            <h2 class="dashboard-title">Reporte de Stock Crítico</h2>
        </div>
        
        <div class="report-summary">
            <div class="summary-content">
                <div class="summary-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="summary-text">
                    <h3><?= $productos->num_rows ?> Productos con Stock Crítico</h3>
                    <p>Productos con menos de 10 unidades en stock que requieren atención inmediata</p>
                </div>
            </div>
        </div>
        
        <div class="table-container">
            <div class="table-header">
                <h3 class="table-title">Detalle de Productos</h3>
            </div>
            
            <div class="table-responsive">
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
                        <?php if ($productos->num_rows > 0): ?>
                            <?php while($producto = $productos->fetch_assoc()): 
                                $isCritical = $producto['stock'] < 5;
                                $stockClass = $isCritical ? 'stock-critical' : 'stock-low';
                                $statusClass = $isCritical ? 'status-critical' : 'status-low';
                                $statusText = $isCritical ? 'Crítico' : 'Bajo';
                            ?>
                                <tr>
                                    <td>
                                        <div>
                                            <i class="fas fa-<?= $isCritical ? 'fire' : 'exclamation-circle' ?> priority-icon" 
                                               style="color: <?= $isCritical ? 'var(--critical-color)' : 'var(--low-color)' ?>"></i>
                                            <?= htmlspecialchars($producto['nombre']) ?>
                                        </div>
                                        <div class="status-badge <?= $statusClass ?>"><?= $statusText ?></div>
                                    </td>
                                    <td><?= htmlspecialchars($producto['categoria']) ?></td>
                                    <td><?= htmlspecialchars($producto['subcategoria']) ?></td>
                                    <td class="<?= $stockClass ?>">
                                        <div class="stock-indicator">
                                            <div class="stock-value"><?= $producto['stock'] ?> unidades</div>
                                            <div class="stock-bar">
                                                <div class="stock-fill"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="price">$<?= number_format($producto['precio'], 2) ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <i class="fas fa-check-circle"></i>
                                        <h3>No hay productos con stock crítico</h3>
                                        <p>Todos los productos tienen suficiente stock. ¡Buen trabajo!</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <footer class="main-footer">
        <p>Suplementos Dynamite &copy; <?= date('Y') ?> - Todos los derechos reservados</p>
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
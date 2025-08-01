<?php
require_once '../../../controlSesion.php';
require_once '../../../conexion.php';

// Verificar ID de pedido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: listado.php?error=ID inválido');
    exit;
}

$pedido_id = intval($_GET['id']);

// Obtener información del pedido
$sql_pedido = "SELECT p.*, u.nombre AS nombre_usuario, u.apellido, u.email, u.telefono, u.direccion, 
                      u.localidad, u.provincia
               FROM pedidos p
               JOIN usuarios u ON p.usuario_id = u.usuario_id
               WHERE p.pedido_id = ?";
$stmt = $conexion->prepare($sql_pedido);
$stmt->bind_param("i", $pedido_id);
$stmt->execute();
$resultado_pedido = $stmt->get_result();

if ($resultado_pedido->num_rows === 0) {
    header('Location: listado.php?error=Pedido no encontrado');
    exit;
}

$pedido = $resultado_pedido->fetch_assoc();

// Obtener detalle del pedido
$sql_detalle = "SELECT dp.*, pr.nombre AS producto_nombre, pr.imagen_url
                FROM detalle_pedido dp
                JOIN productos pr ON dp.producto_id = pr.producto_id
                WHERE dp.pedido_id = ?";
$stmt_detalle = $conexion->prepare($sql_detalle);
$stmt_detalle->bind_param("i", $pedido_id);
$stmt_detalle->execute();
$detalle = $stmt_detalle->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Pedido</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Variables de colores */
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --success: #4cc9f0;
            --danger: #f72585;
            --warning: #f8961e;
            --info: #4895ef;
            --light: #f8f9fa;
            --dark: #212529;
            --gray: #6c757d;
            --light-gray: #e9ecef;
            --border: #dee2e6;
            --card-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fb;
            color: #333;
            line-height: 1.6;
        }
        
        /* Header */
        .header-container {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .logo {
            height: 50px;
            margin-right: 15px;
            border-radius: 8px;
        }
        
        .header-container h1 {
            font-size: 1.8rem;
            font-weight: 600;
        }
        
        #date-time {
            background-color: rgba(0,0,0,0.1);
            padding: 8px 15px;
            font-size: 0.9rem;
            text-align: center;
            color: white;
        }
        
        .nav-bar {
            background-color: rgba(255,255,255,0.15);
            padding: 0.5rem 2rem;
        }
        
        .nav-bar ul {
            display: flex;
            list-style: none;
            justify-content: space-between;
        }
        
        .nav-bar li a {
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 4px;
            transition: background 0.3s;
            font-weight: 500;
        }
        
        .nav-bar li a:hover {
            background: rgba(255,255,255,0.2);
        }
        
        .btn-login {
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        /* Navegación empleado */
        .nav-empleado {
            background: white;
            padding: 0.8rem 2rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        .nav-empleado ul {
            display: flex;
            list-style: none;
            gap: 1.5rem;
        }
        
        .nav-empleado li a {
            color: var(--dark);
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 6px;
            transition: all 0.3s;
            font-weight: 500;
            position: relative;
        }
        
        .nav-empleado li a:hover {
            color: var(--primary);
        }
        
        .nav-empleado li a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary);
            transition: width 0.3s;
        }
        
        .nav-empleado li a:hover::after {
            width: 100%;
        }
        
        /* Breadcrumb */
        .breadcrumb {
            padding: 15px 2rem;
            background: white;
            font-size: 0.9rem;
            border-bottom: 1px solid var(--light-gray);
            margin-bottom: 2rem;
        }
        
        .breadcrumb a {
            color: var(--primary);
            text-decoration: none;
            transition: all 0.2s;
        }
        
        .breadcrumb a:hover {
            text-decoration: underline;
        }
        
        .breadcrumb span {
            color: var(--gray);
        }
        
        /* Contenido principal */
        .main-container {
            max-width: 1200px;
            margin: 0 auto 3rem;
            padding: 0 2rem;
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        .page-title {
            font-size: 1.8rem;
            color: var(--primary);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .page-title i {
            background: rgba(67, 97, 238, 0.1);
            padding: 10px;
            border-radius: 50%;
            color: var(--primary);
        }
        
        /* Tarjetas de información */
        .cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .info-card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
        }
        
        .info-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: var(--primary);
        }
        
        .card-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 1.5rem;
            color: var(--primary);
        }
        
        .card-header i {
            font-size: 1.2rem;
        }
        
        .card-header h3 {
            font-size: 1.2rem;
            font-weight: 600;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--light-gray);
        }
        
        .info-row:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }
        
        .info-label {
            flex: 1;
            font-weight: 600;
            color: var(--dark);
        }
        
        .info-value {
            flex: 2;
            color: var(--gray);
        }
        
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            text-transform: capitalize;
        }
        
        .status-pendiente {
            background-color: #fffbeb;
            color: #f59e0b;
        }
        
        .status-procesando {
            background-color: #dbeafe;
            color: #3b82f6;
        }
        
        .status-enviado {
            background-color: #d1fae5;
            color: #10b981;
        }
        
        .status-completado {
            background-color: #dcfce7;
            color: #16a34a;
        }
        
        .status-cancelado {
            background-color: #fee2e2;
            color: #ef4444;
        }
        
        /* Tabla de productos */
        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            margin-bottom: 2rem;
        }
        
        .productos-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        
        .productos-table thead {
            background: linear-gradient(to right, var(--primary), var(--info));
            color: white;
        }
        
        .productos-table th {
            padding: 16px 20px;
            text-align: left;
            font-weight: 600;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .productos-table tbody tr {
            transition: background 0.2s;
        }
        
        .productos-table tbody tr:nth-child(even) {
            background-color: rgba(67, 97, 238, 0.03);
        }
        
        .productos-table tbody tr:hover {
            background-color: rgba(67, 97, 238, 0.08);
        }
        
        .productos-table td {
            padding: 14px 20px;
            border-bottom: 1px solid var(--light-gray);
            color: var(--dark);
        }
        
        .producto-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .producto-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid var(--light-gray);
        }
        
        .resumen-pedido {
            display: flex;
            justify-content: flex-end;
            margin-top: 1rem;
        }
        
        .resumen-card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            padding: 1.5rem;
            width: 300px;
        }
        
        .resumen-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--light-gray);
        }
        
        .resumen-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
            margin-bottom: 0;
            font-weight: 600;
            font-size: 1.1rem;
            color: var(--primary);
        }
        
        /* Botones */
        .acciones {
            display: flex;
            justify-content: center;
            margin-top: 2rem;
        }
        
        .btn-volver {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background-color: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.25);
        }
        
        .btn-volver:hover {
            background-color: var(--secondary);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(67, 97, 238, 0.35);
        }
        
        /* Footer */
        footer {
            background: white;
            padding: 1.5rem 2rem;
            text-align: center;
            color: var(--gray);
            font-size: 0.9rem;
            border-top: 1px solid var(--light-gray);
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .productos-table {
                display: block;
                overflow-x: auto;
            }
            
            .resumen-pedido {
                justify-content: center;
            }
        }
        
        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                text-align: center;
                padding: 1rem;
            }
            
            .logo {
                margin-bottom: 10px;
            }
            
            .nav-bar ul, .nav-empleado ul {
                flex-wrap: wrap;
            }
            
            .cards-container {
                grid-template-columns: 1fr;
            }
            
            .resumen-card {
                width: 100%;
            }
        }
    </style>
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

    <div>
        <nav class="nav-empleado">
            <ul>
                <li><a href="../consultas/buzon.php">Buzón de Consultas</a></li>
                <li><a href="../proveedores.php">Proveedores</a></li>
                <li><a href="../pedidos/listado.php">Seguimiento de Paquetes</a></li>
            </ul>
        </nav>
    </div>
    
    <div class="breadcrumb">
        <a href="../empleado.php">Inicio</a> > 
        <a href="listado.php">Pedidos</a> > 
        <span>Detalle #<?= $pedido['pedido_id'] ?></span>
    </div>
    
    <div class="main-container">
        <div class="page-header">
            <h1 class="page-title">
                <i class="fas fa-file-invoice"></i>
                Detalle del Pedido #<?= $pedido['pedido_id'] ?>
            </h1>
        </div>
        
        <div class="cards-container">
            <div class="info-card">
                <div class="card-header">
                    <i class="fas fa-info-circle"></i>
                    <h3>Información del Pedido</h3>
                </div>
                <div class="info-row">
                    <div class="info-label">Fecha:</div>
                    <div class="info-value"><?= date('d/m/Y H:i', strtotime($pedido['fecha_pedido'])) ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Estado:</div>
                    <div class="info-value">
                        <?php 
                            $statusClass = 'status-' . strtolower($pedido['estado']);
                        ?>
                        <span class="status-badge <?= $statusClass ?>">
                            <?= htmlspecialchars($pedido['estado']) ?>
                        </span>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Total:</div>
                    <div class="info-value">$<?= number_format($pedido['total'], 2) ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Método de Pago:</div>
                    <div class="info-value"><?= htmlspecialchars($pedido['metodo_pago'] ?? 'No especificado') ?></div>
                </div>
            </div>
            
            <div class="info-card">
                <div class="card-header">
                    <i class="fas fa-user"></i>
                    <h3>Datos del Cliente</h3>
                </div>
                <div class="info-row">
                    <div class="info-label">Nombre:</div>
                    <div class="info-value"><?= htmlspecialchars($pedido['nombre_usuario'] . ' ' . $pedido['apellido']) ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Email:</div>
                    <div class="info-value"><?= htmlspecialchars($pedido['email']) ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Teléfono:</div>
                    <div class="info-value"><?= htmlspecialchars($pedido['telefono']) ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Dirección:</div>
                    <div class="info-value">
                        <?= htmlspecialchars($pedido['direccion']) ?>, 
                        <?= htmlspecialchars($pedido['localidad']) ?>, 
                        <?= htmlspecialchars($pedido['provincia']) ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="table-container">
            <table class="productos-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio Unitario</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($item = $detalle->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <div class="producto-info">
                                <?php if($item['imagen_url']): ?>
                                    <img src="<?= htmlspecialchars($item['imagen_url']) ?>" alt="<?= htmlspecialchars($item['producto_nombre']) ?>" class="producto-img">
                                <?php else: ?>
                                    <div class="no-image">
                                        <i class="fas fa-box-open" style="font-size: 1.5rem; color: #ccc;"></i>
                                    </div>
                                <?php endif; ?>
                                <span><?= htmlspecialchars($item['producto_nombre']) ?></span>
                            </div>
                        </td>
                        <td>$<?= number_format($item['precio_unitario'], 2) ?></td>
                        <td><?= $item['cantidad'] ?></td>
                        <td>$<?= number_format($item['precio_unitario'] * $item['cantidad'], 2) ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            
            <div class="resumen-pedido">
                <div class="resumen-card">
                    <div class="resumen-row">
                        <span>Subtotal:</span>
                        <span>$<?= number_format($pedido['total'] * 0.82, 2) ?></span>
                    </div>
                    <div class="resumen-row">
                        <span>IVA (18%):</span>
                        <span>$<?= number_format($pedido['total'] * 0.18, 2) ?></span>
                    </div>
                    <div class="resumen-row">
                        <span>Envío:</span>
                        <span>$0.00</span>
                    </div>
                    <div class="resumen-row">
                        <span>Total:</span>
                        <span>$<?= number_format($pedido['total'], 2) ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="acciones">
            <a href="listado.php" class="btn-volver">
                <i class="fas fa-arrow-left"></i> Volver al Listado
            </a>
        </div>
    </div>
    
    <footer>
        <p>Sistema de Gestión de Pedidos - Suplementos Dynamite &copy; <?= date('Y') ?></p>
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
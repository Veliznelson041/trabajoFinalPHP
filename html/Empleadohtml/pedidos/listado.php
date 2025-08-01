<?php
require_once '../../../controlSesion.php';
require_once '../../../conexion.php';

// Obtener pedidos con información de usuario
$sql = "SELECT p.*, u.nombre AS nombre_usuario, u.apellido 
        FROM pedidos p
        JOIN usuarios u ON p.usuario_id = u.usuario_id
        ORDER BY p.fecha_pedido DESC";

$pedidos = $conexion->query($sql);

// Manejo de errores
$error = '';
if(isset($_GET['error'])) {
    $error = urldecode($_GET['error']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seguimiento de Pedidos</title>
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
        
        /* Contenido principal */
        .main-container {
            max-width: 1400px;
            margin: 2rem auto;
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
        }
        
        .search-filter-container {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .search-box {
            flex: 1;
            position: relative;
        }
        
        .search-box input {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s;
        }
        
        .search-box input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
        }
        
        .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
        }
        
        .filter-select {
            padding: 12px 15px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: white;
            min-width: 180px;
        }
        
        /* Mensajes */
        .alert {
            padding: 15px;
            margin-bottom: 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .success {
            background-color: #d1fae5;
            color: #065f46;
            border-left: 4px solid #10b981;
        }
        
        .error {
            background-color: #fee2e2;
            color: #b91c1c;
            border-left: 4px solid #ef4444;
        }
        
        /* Tabla de pedidos */
        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
        }
        
        .pedidos-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        
        .pedidos-table thead {
            background: linear-gradient(to right, var(--primary), var(--info));
            color: white;
        }
        
        .pedidos-table th {
            padding: 16px 20px;
            text-align: left;
            font-weight: 600;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .pedidos-table tbody tr {
            transition: background 0.2s;
        }
        
        .pedidos-table tbody tr:nth-child(even) {
            background-color: rgba(67, 97, 238, 0.03);
        }
        
        .pedidos-table tbody tr:hover {
            background-color: rgba(67, 97, 238, 0.08);
        }
        
        .pedidos-table td {
            padding: 14px 20px;
            border-bottom: 1px solid var(--light-gray);
            color: var(--dark);
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
        
        /* Botones */
        .btn-ver {
            display: inline-block;
            padding: 8px 16px;
            background-color: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .btn-ver:hover {
            background-color: var(--secondary);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(67, 97, 238, 0.25);
        }
        
        .btn-ver i {
            font-size: 0.8rem;
        }
        
        /* Sin pedidos */
        .no-pedidos {
            padding: 3rem 2rem;
            text-align: center;
            background: white;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
        }
        
        .no-pedidos i {
            font-size: 3rem;
            color: var(--light-gray);
            margin-bottom: 1rem;
        }
        
        .no-pedidos p {
            font-size: 1.2rem;
            color: var(--gray);
            margin-bottom: 1.5rem;
        }
        
        /* Footer */
        footer {
            background: white;
            padding: 1.5rem 2rem;
            text-align: center;
            color: var(--gray);
            font-size: 0.9rem;
            margin-top: 3rem;
            border-top: 1px solid var(--light-gray);
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .pedidos-table {
                display: block;
                overflow-x: auto;
            }
            
            .search-filter-container {
                flex-direction: column;
            }
            
            .nav-bar ul, .nav-empleado ul {
                flex-wrap: wrap;
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
            
            .nav-bar ul {
                flex-direction: column;
                gap: 5px;
            }
            
            .nav-empleado ul {
                flex-direction: column;
                gap: 5px;
            }
            
            .page-header {
                flex-direction: column;
                gap: 1rem;
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
    
    <div class="main-container">
        <div class="page-header">
            <h1 class="page-title">Seguimiento de Pedidos</h1>
        </div>
        
        <div class="search-filter-container">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Buscar pedidos...">
            </div>
            <select class="filter-select">
                <option>Todos los estados</option>
                <option>Pendiente</option>
                <option>Procesando</option>
                <option>Enviado</option>
                <option>Completado</option>
                <option>Cancelado</option>
            </select>
        </div>
        
        <?php if(isset($_GET['success'])): ?>
            <div class="alert success">
                <i class="fas fa-check-circle"></i>
                <span>¡Operación realizada con éxito!</span>
            </div>
        <?php endif; ?>
        
        <?php if($error): ?>
            <div class="alert error">
                <i class="fas fa-exclamation-circle"></i>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
        <?php endif; ?>
        
        <?php if($pedidos->num_rows > 0): ?>
            <div class="table-container">
                <table class="pedidos-table">
                    <thead>
                        <tr>
                            <th>ID Pedido</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($pedido = $pedidos->fetch_assoc()): 
                            // Determinar clase CSS según el estado
                            $statusClass = 'status-' . strtolower($pedido['estado']);
                        ?>
                        <tr>
                            <td><?= $pedido['pedido_id'] ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($pedido['fecha_pedido'])) ?></td>
                            <td><?= htmlspecialchars($pedido['nombre_usuario'] . ' ' . $pedido['apellido']) ?></td>
                            <td>$<?= number_format($pedido['total'], 2) ?></td>
                            <td>
                                <span class="status-badge <?= $statusClass ?>">
                                    <?= htmlspecialchars($pedido['estado']) ?>
                                </span>
                            </td>
                            <td>
                                <a href="detalle.php?id=<?= $pedido['pedido_id'] ?>" class="btn-ver">
                                    <i class="fas fa-eye"></i> Ver Detalle
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="no-pedidos">
                <i class="fas fa-box-open"></i>
                <p>No se encontraron pedidos pendientes</p>
            </div>
        <?php endif; ?>
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
        
        // Función para filtrar la tabla
        document.querySelector('.search-box input').addEventListener('input', function(e) {
            const filter = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('.pedidos-table tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });
        
        // Función para filtrar por estado
        document.querySelector('.filter-select').addEventListener('change', function(e) {
            const filter = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('.pedidos-table tbody tr');
            
            if (filter === 'todos los estados') {
                rows.forEach(row => row.style.display = '');
                return;
            }
            
            rows.forEach(row => {
                const status = row.querySelector('.status-badge').textContent.toLowerCase();
                row.style.display = status.includes(filter) ? '' : 'none';
            });
        });
    </script>
</body>
</html>
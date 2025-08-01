<?php
require_once '../../../controlSesion.php';
require_once '../../../conexion.php';

$sql = "SELECT * FROM consultas WHERE estado = 'no respondida' ORDER BY fecha DESC";
$consultas = $conexion->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buzón de Consultas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --accent-color: #e74c3c;
            --light-bg: #f8f9fa;
            --card-bg: #ffffff;
            --border-color: #e0e0e0;
            --success-color: #2ecc71;
            --warning-color: #f39c12;
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
        
        .stats-summary {
            display: flex;
            gap: 15px;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }
        
        .stat-card {
            background: var(--card-bg);
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            flex: 1;
            min-width: 200px;
            border-left: 4px solid var(--primary-color);
        }
        
        .stat-card.warning {
            border-left-color: var(--warning-color);
        }
        
        .stat-card .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--secondary-color);
            margin: 10px 0;
        }
        
        .stat-card .stat-label {
            font-size: 0.9rem;
            color: var(--text-light);
            display: flex;
            align-items: center;
            gap: 8px;
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
        
        .table tbody tr.unread {
            background-color: rgba(52, 152, 219, 0.05);
            font-weight: 500;
        }
        
        .table tbody tr.unread:hover {
            background-color: rgba(52, 152, 219, 0.08);
        }
        
        /* Button Styles */
        .btn-responder {
            display: inline-block;
            background-color: var(--primary-color);
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 0.9rem;
        }
        
        .btn-responder:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(52, 152, 219, 0.3);
        }
        
        .btn-responder i {
            margin-right: 5px;
        }
        
        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .status-new {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--accent-color);
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
            
            .stats-summary {
                flex-direction: column;
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
        <a href="../empleado.php">Inicio</a> > <span>Buzón de Consultas</span>
    </div>
    
    <div class="main-container">
        <div class="dashboard-header">
            <h2 class="dashboard-title">Buzón de Consultas</h2>
        </div>
        
        <div class="stats-summary">
            <div class="stat-card">
                <div class="stat-label">
                    <i class="fas fa-inbox"></i>
                    <span>Consultas totales</span>
                </div>
                <div class="stat-value"><?= $consultas->num_rows ?></div>
            </div>
            
            <div class="stat-card warning">
                <div class="stat-label">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>Sin responder</span>
                </div>
                <div class="stat-value"><?= $consultas->num_rows ?></div>
            </div>
        </div>
        
        <div class="table-container">
            <div class="table-header">
                <h3 class="table-title">Consultas sin responder</h3>
            </div>
            
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Asunto</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($consultas->num_rows > 0): ?>
                            <?php while($consulta = $consultas->fetch_assoc()): ?>
                                <tr class="unread">
                                    <td>
                                        <div><?= date('d/m/Y', strtotime($consulta['fecha'])) ?></div>
                                        <div style="font-size: 0.85rem; color: #777;"><?= date('H:i', strtotime($consulta['fecha'])) ?></div>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($consulta['nombre']) ?>
                                        <div class="status-badge status-new">Nuevo</div>
                                    </td>
                                    <td><?= htmlspecialchars($consulta['email']) ?></td>
                                    <td><?= htmlspecialchars($consulta['asunto']) ?></td>
                                    <td>
                                        <a href="responder.php?id=<?= $consulta['consulta_id'] ?>" class="btn-responder">
                                            <i class="fas fa-reply"></i> Responder
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <i class="fas fa-inbox"></i>
                                        <h3>No hay consultas pendientes</h3>
                                        <p>El buzón de consultas está vacío. ¡Buen trabajo!</p>
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
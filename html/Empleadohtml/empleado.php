<?php
require_once '../../controlSesion.php';
require_once '../../conexion.php';

// Obtener estadísticas rápidas
$sql_productos = "SELECT COUNT(*) AS total FROM productos";
$productos = $conexion->query($sql_productos)->fetch_assoc();

$sql_pedidos = "SELECT COUNT(*) AS total FROM pedidos WHERE estado = 'pendiente'";
$pedidos_pendientes = $conexion->query($sql_pedidos)->fetch_assoc();

$sql_consultas = "SELECT COUNT(*) AS total FROM consultas WHERE estado = 'no respondida'";
$consultas_nuevas = $conexion->query($sql_consultas)->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Empleado - Suplementos Dynamite</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #3498db;
            --accent: #e74c3c;
            --success: #27ae60;
            --warning: #f39c12;
            --light: #ecf0f1;
            --dark: #34495e;
            --gray: #95a5a6;
            --light-gray: #f5f7fa;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: var(--light-gray);
            color: #333;
            line-height: 1.6;
        }
        
        /* Header Styles */
        header {
            background: linear-gradient(135deg, var(--primary), var(--dark));
            color: white;
            padding: 1rem 2rem;
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
        }
        
        .logo-container {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .logo {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid rgba(255, 255, 255, 0.2);
        }
        
        .brand {
            display: flex;
            flex-direction: column;
        }
        
        .brand h1 {
            font-size: 1.8rem;
            font-weight: 600;
            margin: 0;
        }
        
        .brand p {
            font-size: 0.9rem;
            opacity: 0.8;
            margin-top: 0.2rem;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(45deg, var(--secondary), var(--success));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
        }
        
        .user-details {
            text-align: right;
        }
        
        .user-details .name {
            font-weight: 600;
            font-size: 1.1rem;
        }
        
        .user-details .role {
            font-size: 0.85rem;
            opacity: 0.9;
        }
        
        .date-time {
            background: rgba(0, 0, 0, 0.15);
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            display: inline-block;
            margin-top: 0.5rem;
        }
        
        .nav-bar {
            margin-top: 1rem;
        }
        
        .nav-bar ul {
            display: flex;
            list-style: none;
            gap: 1rem; /* Reducido para mejor ajuste */
        }
        
        .nav-bar a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 0.8rem;
            position: relative;
            font-weight: 500;
            transition: var(--transition);
            border-radius: 4px;
        }
        
        .nav-bar a:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.1);
        }
        
        .nav-bar a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: white;
            transition: var(--transition);
        }
        
        .nav-bar a:hover::after {
            width: 100%;
        }
        
        /* Botones específicos */
        .btn-home-public {
            background-color: rgba(46, 204, 113, 0.2);
        }
        
        .btn-logout {
            background-color: rgba(231, 76, 60, 0.2);
        }
        
        .btn-home-public:hover {
            background-color: rgba(46, 204, 113, 0.3);
        }
        
        .btn-logout:hover {
            background-color: rgba(231, 76, 60, 0.3);
        }
        
        /* Main Content */
        .container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 1.5rem;
        }
        
        .welcome-section {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow);
            text-align: center;
            background: linear-gradient(135deg, #ffffff, #f8f9fa);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .welcome-section h2 {
            color: var(--primary);
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        
        .welcome-section p {
            color: var(--gray);
            font-size: 1.1rem;
        }
        
        .welcome-section .role-badge {
            display: inline-block;
            background: var(--secondary);
            color: white;
            padding: 0.3rem 1rem;
            border-radius: 20px;
            margin-top: 1rem;
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        /* Dashboard Cards */
        .dashboard {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .card {
            background: white;
            border-radius: 12px;
            padding: 1.8rem;
            box-shadow: var(--shadow);
            transition: var(--transition);
            display: flex;
            flex-direction: column;
            height: 100%;
            border-top: 4px solid var(--secondary);
            position: relative;
            overflow: hidden;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .card:nth-child(2) {
            border-top-color: var(--warning);
        }
        
        .card:nth-child(3) {
            border-top-color: var(--success);
        }
        
        .card-icon {
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
            color: var(--secondary);
            background: rgba(52, 152, 219, 0.1);
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .card:nth-child(2) .card-icon {
            color: var(--warning);
            background: rgba(243, 156, 18, 0.1);
        }
        
        .card:nth-child(3) .card-icon {
            color: var(--success);
            background: rgba(39, 174, 96, 0.1);
        }
        
        .card h3 {
            font-size: 1.3rem;
            margin-bottom: 0.8rem;
            color: var(--dark);
        }
        
        .card .stat {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: var(--dark);
        }
        
        .card a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--secondary);
            color: white;
            text-decoration: none;
            padding: 0.8rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: var(--transition);
            margin-top: auto;
            width: fit-content;
        }
        
        .card a:hover {
            background: var(--primary);
            transform: translateY(-2px);
        }
        
        .card a i {
            margin-right: 0.5rem;
        }
        
        /* Quick Actions */
        .quick-actions {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
        }
        
        .quick-actions h3 {
            font-size: 1.5rem;
            color: var(--dark);
            margin-bottom: 1.5rem;
            padding-bottom: 0.8rem;
            border-bottom: 2px solid var(--light);
        }
        
        .actions-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }
        
        .btn-action {
            display: flex;
            align-items: center;
            background: white;
            color: var(--dark);
            text-decoration: none;
            padding: 1.5rem;
            border-radius: 10px;
            font-weight: 500;
            transition: var(--transition);
            box-shadow: var(--shadow);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .btn-action:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
            background: var(--primary);
            color: white;
        }
        
        .btn-action i {
            font-size: 1.8rem;
            margin-right: 1rem;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(52, 152, 219, 0.1);
            color: var(--secondary);
        }
        
        .btn-action:nth-child(2) i {
            background: rgba(231, 76, 60, 0.1);
            color: var(--accent);
        }
        
        .btn-action:hover i {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }
        
        .action-text {
            display: flex;
            flex-direction: column;
        }
        
        .action-text span:first-child {
            font-weight: 600;
            font-size: 1.2rem;
        }
        
        .action-text span:last-child {
            font-size: 0.9rem;
            opacity: 0.8;
            margin-top: 0.3rem;
        }
        
        /* Footer */
        footer {
            background: var(--primary);
            color: white;
            padding: 2rem;
            text-align: center;
            margin-top: 2rem;
        }
        
        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .footer-logo {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .footer-links {
            display: flex;
            gap: 1.5rem;
            margin: 1.5rem 0;
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .footer-links a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: var(--transition);
        }
        
        .footer-links a:hover {
            color: white;
        }
        
        .copyright {
            font-size: 0.9rem;
            opacity: 0.7;
            margin-top: 1rem;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .user-info {
                margin-top: 1rem;
                align-self: flex-end;
            }
            
            .nav-bar ul {
                flex-wrap: wrap;
            }
            
            .dashboard {
                grid-template-columns: 1fr;
            }
            
            .welcome-section h2 {
                font-size: 1.6rem;
            }
        }
        
        @media (max-width: 480px) {
            .header-container {
                padding: 1rem;
            }
            
            .user-info {
                align-self: stretch;
                justify-content: flex-end;
            }
            
            .nav-bar ul {
                gap: 0.5rem;
            }
            
            .nav-bar a {
                padding: 0.5rem;
                font-size: 0.9rem;
            }
            
            .welcome-section {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo-container">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRt_eZYMvS26mdHNwVQw-zHWqDRdSz5XzAVHQ&s" alt="Logo de Suplementos Dynamite" class="logo">
                <div class="brand">
                    <h1>Suplementos Dynamite</h1>
                    <p>Panel de administración</p>
                </div>
            </div>
            
            <div class="user-info">
                <div class="user-avatar">
                    <?= strtoupper(substr(htmlspecialchars($_SESSION['nombre']), 0, 1)) ?>
                </div>
                <div class="user-details">
                    <div class="name"><?= htmlspecialchars($_SESSION['nombre']) ?> <?= htmlspecialchars($_SESSION['apellido']) ?></div>
                    <div class="role"><?= ucfirst($_SESSION['rol']) ?></div>
                </div>
            </div>
        </div>
        
        <p id="date-time" class="date-time"></p>
        
        <nav class="nav-bar">
            <ul>
                <li><a href="empleado.php"><i class="fas fa-home"></i> Inicio</a></li>
                <!-- <li><a href="#"><i class="fas fa-info-circle"></i> Sobre Nosotros</a></li>
                <li><a href="#"><i class="fas fa-envelope"></i> Contacto</a></li>
                 --><!-- Nuevos botones agregados -->
                <li><a href="../index.php" class="btn-home-public"><i class="fas fa-globe"></i> Home Público</a></li>
                <li><a href="perfilempleado.php" class="btn-login"><i class="fas fa-user-cog"></i> Mi Perfil</a></li>
                <li><a href="../loginHtml/logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <section class="welcome-section">
            <h2>Bienvenido, <?= htmlspecialchars($_SESSION['nombre']) ?> <?= htmlspecialchars($_SESSION['apellido']) ?></h2>
            <p>Estas son las operaciones disponibles para tu rol</p>
            <div class="role-badge"><?= ucfirst($_SESSION['rol']) ?></div>
        </section>

        <div class="dashboard">
            <div class="card">
                <div class="card-icon">
                    <i class="fas fa-box"></i>
                </div>
                <h3>Productos</h3>
                <div class="stat"><?= $productos['total'] ?></div>
                <p>productos registrados</p>
                <a href="productos/listado.php"><i class="fas fa-cog"></i> Gestionar</a>
            </div>
            
            <div class="card">
                <div class="card-icon">
                    <i class="fas fa-truck"></i>
                </div>
                <h3>Pedidos Pendientes</h3>
                <div class="stat"><?= $pedidos_pendientes['total'] ?></div>
                <p>por procesar</p>
                <a href="pedidos/listado.php"><i class="fas fa-eye"></i> Ver pedidos</a>
            </div>
            
            <div class="card">
                <div class="card-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <h3>Consultas Nuevas</h3>
                <div class="stat"><?= $consultas_nuevas['total'] ?></div>
                <p>sin responder</p>
                <a href="consultas/buzon.php"><i class="fas fa-reply"></i> Responder</a>
            </div>
        </div>

        <section class="quick-actions">
            <h3>Acciones Rápidas</h3>
            <div class="actions-container">
                <a href="productos/agregar.php" class="btn-action">
                    <i class="fas fa-plus"></i>
                    <div class="action-text">
                        <span>Agregar Producto</span>
                        <span>Crear un nuevo producto en el catálogo</span>
                    </div>
                </a>
                
                <a href="reportes/stock.php" class="btn-action">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div class="action-text">
                        <span>Ver Stock Crítico</span>
                        <span>Productos con bajo inventario</span>
                    </div>
                </a>
            </div>
        </section>
    </div>

    <footer>
        <div class="footer-content">
            <div class="footer-logo">Suplementos Dynamite</div>
            <p>Potenciando tu rendimiento desde 2010</p>
            
            <div class="footer-links">
                <a href="#">Términos de uso</a>
                <a href="#">Política de privacidad</a>
                <a href="#">Soporte técnico</a>
                <a href="#">Contáctanos</a>
            </div>
            
            <div class="copyright">
                &copy; 2023 Suplementos Dynamite. Todos los derechos reservados.
            </div>
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
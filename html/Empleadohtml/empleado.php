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
    <title>Panel de Empleado</title>
    <link rel="stylesheet" href="../../css/Empleadocss/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
                <li><a href="empleado.php">Inicio</a></li>
                <li><a href="#">Sobre Nosotros</a></li>
                <li><a href="#">Contacto</a></li>                                
                <li><a href="perfilempleado.php" class="btn-login"><i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['nombre']) ?></a></li>
            </ul>
        </nav>
    </header>

    <div class="welcome-message">
        <h2>Bienvenido, <?= htmlspecialchars($_SESSION['nombre']) ?> <?= htmlspecialchars($_SESSION['apellido']) ?></h2>
        <p>Rol: <?= ucfirst($_SESSION['rol']) ?></p>
    </div>

    <div class="dashboard">
        <div class="card">
            <h3><i class="fas fa-box"></i> Productos</h3>
            <p><?= $productos['total'] ?> registrados</p>
            <a href="productos/listado.php">Gestionar</a>
        </div>
        
        <div class="card">
            <h3><i class="fas fa-truck"></i> Pedidos Pendientes</h3>
            <p><?= $pedidos_pendientes['total'] ?> por procesar</p>
            <a href="pedidos/listado.php">Ver pedidos</a>
        </div>
        
        <div class="card">
            <h3><i class="fas fa-envelope"></i> Consultas Nuevas</h3>
            <p><?= $consultas_nuevas['total'] ?> sin responder</p>
            <a href="consultas/buzon.php">Responder</a>
        </div>
    </div>

    <div class="quick-actions">
        <h3>Acciones Rápidas</h3>
        <div>
            <a href="productos/agregar.php" class="btn-action"><i class="fas fa-plus"></i> Agregar Producto</a>
            <a href="reportes/stock.php" class="btn-action"><i class="fas fa-exclamation-triangle"></i> Ver Stock Crítico</a>
        </div>
    </div>

    <script>
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
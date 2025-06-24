<?php
require_once '../../../controlSesion.php';
require_once '../../../conexion.php';

$sql = "SELECT p.*, u.nombre, u.apellido 
        FROM pedidos p
        JOIN usuarios u ON p.usuario_id = u.usuario_id
        ORDER BY p.fecha_pedido DESC";
$pedidos = $conexion->query($sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Pedidos</title>
    <link rel="stylesheet" href="../../../css/Empleadocss/styles.css">
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
                <li><a href="../../empleado.php">Inicio</a></li>                             
                <li><a href="../../perfilempleado.php" class="btn-login"><i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['nombre']) ?></a></li>
            </ul>
        </nav>
    </header>
    <br>

    <nav class="ruta">
        <a href="../../empleado.php">Inicio</a> > <a href="#">Pedidos</a>
    </nav>

    <main>
        <h2>Listado de Pedidos</h2>
        
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while($pedido = $pedidos->fetch_assoc()): ?>
                <tr>
                    <td><?= $pedido['pedido_id'] ?></td>
                    <td><?= date('d/m/Y', strtotime($pedido['fecha_pedido'])) ?></td>
                    <td><?= htmlspecialchars($pedido['nombre']) ?> <?= htmlspecialchars($pedido['apellido']) ?></td>
                    <td>$<?= number_format($pedido['total'], 2) ?></td>
                    <td>
                        <span class="estado-<?= $pedido['estado'] ?>"><?= ucfirst($pedido['estado']) ?></span>
                    </td>
                    <td>
                        <a href="detalle.php?id=<?= $pedido['pedido_id'] ?>" class="btn-ver">Ver Detalle</a>
                        <?php if($pedido['estado'] == 'pendiente'): ?>
                            <a href="actualizar.php?id=<?= $pedido['pedido_id'] ?>&estado=enviado" class="btn-enviar">Marcar como Enviado</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>
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
<?php
require_once '../../../controlSesion.php';
require_once '../../../conexion.php';

if (!isset($_GET['id'])) {
    header('Location: listado.php');
    exit;
}

$id = $_GET['id'];
$sql_pedido = "SELECT p.*, u.nombre, u.apellido, u.email, u.telefono, u.direccion, u.localidad, u.provincia 
               FROM pedidos p
               JOIN usuarios u ON p.usuario_id = u.usuario_id
               WHERE p.pedido_id = ?";
$stmt_pedido = $conexion->prepare($sql_pedido);
$stmt_pedido->bind_param("i", $id);
$stmt_pedido->execute();
$pedido = $stmt_pedido->get_result()->fetch_assoc();

if (!$pedido) {
    header('Location: listado.php');
    exit;
}

$sql_detalle = "SELECT d.*, pr.nombre, pr.precio, pr.imagen_url 
                FROM detalle_pedido d
                JOIN productos pr ON d.producto_id = pr.producto_id
                WHERE d.pedido_id = ?";
$stmt_detalle = $conexion->prepare($sql_detalle);
$stmt_detalle->bind_param("i", $id);
$stmt_detalle->execute();
$detalles = $stmt_detalle->get_result();

$sql_medio_pago = "SELECT nombre FROM medios_pago WHERE medio_pago_id = ?";
$stmt_medio = $conexion->prepare($sql_medio_pago);
$stmt_medio->bind_param("i", $pedido['medio_pago_id']);
$stmt_medio->execute();
$medio_pago = $stmt_medio->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Pedido</title>
    <link rel="stylesheet" href="../../../css/Empleadocss/styles-empleado.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .producto-img {
            max-width: 80px;
            max-height: 80px;
            object-fit: contain;
        }
    </style>
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
        <a href="../../empleado.php">Inicio</a> > <a href="listado.php">Pedidos</a> > <a href="#">Detalle</a>
    </nav>

    <main>
        <h2>Detalle del Pedido #<?= $pedido['pedido_id'] ?></h2>
        
        <div class="pedido-info">
            <div class="info-group">
                <h3>Información del Cliente</h3>
                <p><strong>Nombre:</strong> <?= htmlspecialchars($pedido['nombre']) ?> <?= htmlspecialchars($pedido['apellido']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($pedido['email']) ?></p>
                <p><strong>Teléfono:</strong> <?= htmlspecialchars($pedido['telefono']) ?></p>
                <p><strong>Dirección:</strong> <?= htmlspecialchars($pedido['direccion']) ?>, <?= htmlspecialchars($pedido['localidad']) ?>, <?= htmlspecialchars($pedido['provincia']) ?></p>
            </div>
            
            <div class="info-group">
                <h3>Información del Pedido</h3>
                <p><strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($pedido['fecha_pedido'])) ?></p>
                <p><strong>Estado:</strong> <span class="estado-<?= $pedido['estado'] ?>"><?= ucfirst($pedido['estado']) ?></span></p>
                <p><strong>Medio de Pago:</strong> <?= htmlspecialchars($medio_pago['nombre']) ?></p>
                <p><strong>Total:</strong> $<?= number_format($pedido['total'], 2) ?></p>
            </div>
        </div>
        
        <h3>Productos</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Imagen</th>
                    <th>Producto</th>
                    <th>Precio Unitario</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php while($item = $detalles->fetch_assoc()): ?>
                <tr>
                    <td>
                        <?php if($item['imagen_url']): ?>
                            <img src="<?= htmlspecialchars($item['imagen_url']) ?>" alt="<?= htmlspecialchars($item['nombre']) ?>" class="producto-img">
                        <?php else: ?>
                            <span>Sin imagen</span>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($item['nombre']) ?></td>
                    <td>$<?= number_format($item['precio'], 2) ?></td>
                    <td><?= $item['cantidad'] ?></td>
                    <td>$<?= number_format($item['precio'] * $item['cantidad'], 2) ?></td>
                </tr>
                <?php endwhile; ?>
                <tr class="total-row">
                    <td colspan="4" class="text-right"><strong>Total:</strong></td>
                    <td><strong>$<?= number_format($pedido['total'], 2) ?></strong></td>
                </tr>
            </tbody>
        </table>
        
        <div class="pedido-acciones">
            <?php if($pedido['estado'] == 'pendiente'): ?>
                <a href="actualizar.php?id=<?= $pedido['pedido_id'] ?>&estado=enviado" class="btn-enviar">Marcar como Enviado</a>
            <?php endif; ?>
            <a href="listado.php" class="btn-volver">Volver al Listado</a>
        </div>
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
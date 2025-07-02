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
        <a href="../../empleado.php">Inicio</a> > <a href="#">Buzón de Consultas</a>
    </nav>

    <main>
        <h2>Consultas sin responder</h2>
        
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
                <?php while($consulta = $consultas->fetch_assoc()): ?>
                <tr>
                    <td><?= date('d/m/Y H:i', strtotime($consulta['fecha'])) ?></td>
                    <td><?= htmlspecialchars($consulta['nombre']) ?></td>
                    <td><?= htmlspecialchars($consulta['email']) ?></td>
                    <td><?= htmlspecialchars($consulta['asunto']) ?></td>
                    <td>
                        <a href="responder.php?id=<?= $consulta['consulta_id'] ?>" class="btn-responder">Responder</a>
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
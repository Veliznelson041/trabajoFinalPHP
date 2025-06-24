<?php
require_once '../../controlSesion.php';
require_once '../../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_SESSION['usuario_id'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $provincia = $_POST['provincia'];
    $localidad = $_POST['localidad'];
    $direccion = $_POST['direccion'];
    
    $sql = "UPDATE usuarios SET 
            nombre = ?, 
            apellido = ?, 
            email = ?, 
            telefono = ?, 
            provincia = ?, 
            localidad = ?, 
            direccion = ? 
            WHERE usuario_id = ?";
    
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssssssi", $nombre, $apellido, $email, $telefono, $provincia, $localidad, $direccion, $usuario_id);
    
    if ($stmt->execute()) {
        $_SESSION['nombre'] = $nombre;
        $_SESSION['apellido'] = $apellido;
        $_SESSION['email'] = $email;
        $success = "Perfil actualizado correctamente";
    } else {
        $error = "Error al actualizar el perfil: " . $conexion->error;
    }
}

$sql = "SELECT * FROM usuarios WHERE usuario_id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $_SESSION['usuario_id']);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Empleado</title>
    <link rel="stylesheet" href="../../css/Empleadocss/styles-empleado.css">
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
                <li><a href="perfilempleado.php" class="btn-login"><i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['nombre']) ?></a></li>
            </ul>
        </nav>
    </header>
    <br>

    <nav class="ruta">
        <a href="empleado.php">Inicio</a> > <a href="#">Perfil</a>
    </nav>

    <main>
        <div class="profile-container">
            <h2>Mi Perfil</h2>
            
            <?php if(isset($success)): ?>
                <div class="success"><?= $success ?></div>
            <?php endif; ?>
            
            <?php if(isset($error)): ?>
                <div class="error"><?= $error ?></div>
            <?php endif; ?>
            
            <form method="POST" class="profile-form">
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="apellido">Apellido:</label>
                    <input type="text" id="apellido" name="apellido" value="<?= htmlspecialchars($usuario['apellido']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="telefono">Teléfono:</label>
                    <input type="text" id="telefono" name="telefono" value="<?= htmlspecialchars($usuario['telefono']) ?>">
                </div>
                
                <div class="form-group">
                    <label for="provincia">Provincia:</label>
                    <input type="text" id="provincia" name="provincia" value="<?= htmlspecialchars($usuario['provincia']) ?>">
                </div>
                
                <div class="form-group">
                    <label for="localidad">Localidad:</label>
                    <input type="text" id="localidad" name="localidad" value="<?= htmlspecialchars($usuario['localidad']) ?>">
                </div>
                
                <div class="form-group">
                    <label for="direccion">Dirección:</label>
                    <input type="text" id="direccion" name="direccion" value="<?= htmlspecialchars($usuario['direccion']) ?>">
                </div>
                
                <button type="submit" class="btn-save">Guardar Cambios</button>
            </form>
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
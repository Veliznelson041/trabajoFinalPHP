<?php
session_start();
include ("../../conexion.php");
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
     header("Location: ../index.php");
    exit();
}


$errores = [];
$mensaje = '';
$empleado = null;
$redirect_to = 'admin.php';

// Obtener redirect_to desde GET si existe
if (isset($_GET['redirect_to'])) {
    $redirect_to = $_GET['redirect_to'];
}


// Obtener datos del empleado a editar
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM usuarios WHERE usuario_id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $empleado = $result->fetch_assoc();
}

// Procesar actualización
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['actualizar'])) {
    $id = $_POST['id'];
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $email = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);
    $provincia = trim($_POST['provincia']);
    $localidad = trim($_POST['localidad']);
    $direccion = trim($_POST['direccion']);
    $rol = trim($_POST['rol']);
    $redirect_to = isset($_POST['redirect_to']) ? $_POST['redirect_to'] : 'admin.php';

    // Validaciones
    if (empty($nombre) || empty($apellido) || empty($email)) {
        $errores[] = "Los campos obligatorios deben ser completados.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El formato de email no es válido.";
    }

    // Verificar si el email ya existe en otro usuario
    $sql_check = "SELECT usuario_id FROM usuarios WHERE email = ? AND usuario_id != ?";
    $stmt_check = $conexion->prepare($sql_check);
    $stmt_check->bind_param("si", $email, $id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        $errores[] = "El email ya está registrado por otro usuario.";
    }

    if (count($errores) === 0) {
        $modificado_por = $_SESSION['email'];
        $fecha_modificacion = date('Y-m-d H:i:s');

        $sql_update = "UPDATE usuarios 
                      SET nombre = ?, apellido = ?, email = ?, telefono = ?, provincia = ?, 
                          localidad = ?, direccion = ?, rol = ?, modificado_por = ?, fecha_modificacion = ?
                      WHERE usuario_id = ?";
        
        $stmt_update = $conexion->prepare($sql_update);
        $stmt_update->bind_param("ssssssssssi", $nombre, $apellido, $email, $telefono, $provincia, 
                                 $localidad, $direccion, $rol, $modificado_por, $fecha_modificacion, $id);

        if ($stmt_update->execute()) {
              // Actualizar datos locales para mostrar
            $empleado = [
                'usuario_id' => $id,
                'nombre' => $nombre,
                'apellido' => $apellido,
                'email' => $email,
                'telefono' => $telefono,
                'provincia' => $provincia,
                'localidad' => $localidad,
                'direccion' => $direccion,
                'rol' => $rol
            ];
            
            $_SESSION['success_message'] = "¡Actualizado con éxito!";
                // Redirigir a la página especificada
            header("Location: $redirect_to");
            exit();
        } else {
            $errores[] = "Error al actualizar: " . $conexion->error;
        }
    }
}
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Empleado</title>
    <link rel="stylesheet" href="../../css/administradorCss/editarEmpleado.css"> <!-- Enlace al archivo CSS -->
       <style>
            .error {
            background-color: #ffdddd;
            border-left: 4px solid #f44336;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 4px;
            }

            .success {
            background-color: #ddffdd;
            border-left: 4px solid #4CAF50;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 4px;
            }
        </style>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <header>
        <div class="header-container">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRt_eZYMvS26mdHNwVQw-zHWqDRdSz5XzAVHQ&s" alt="Logo de Suplementos Dynamite" class="logo">
            <h1 id="title">Suplementos Dynamite</h1>
        </div>
        <nav class="nav-bar">
            <ul>
                <li><a href="../index.php">Inicio</a></li>
                <li><a href="/html/catalogo-Carrito-Producto-HTML/catalogo.php">Catálogo</a></li>
                <li><a href="../contactoHtml/contacto.php">Contacto</a></li>                                 
                <li><a href="/html/loginHtml/login.php" class="btn-login"><i class="fas fa-user"></i> Iniciar Sesión</a></li>
            </ul>
        </nav>
    </header>

    <nav class="ruta">
        <a href="../index.php">Inicio</a> > <a href="../personal.php">Panel de Personal</a> > <a href="admin.php">Panel Administrador</a> > <a href="#">Editar Empleado</a>
    </nav>

    <section class="employee-form-section">
        <div class="form-container">
            <h2>Editar Usuario</h2>
         
        <?php if (!empty($errores)): ?>
            <div class="error">
                <?php foreach ($errores as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($empleado): ?>
        <form class="employee-form" method="POST">
            <input type="hidden" name="redirect_to" value="<?php echo htmlspecialchars($redirect_to); ?>">
            <input type="hidden" name="id" value="<?php echo $empleado['usuario_id']; ?>">
            
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($empleado['nombre']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="apellido">Apellido</label>
                <input type="text" id="apellido" name="apellido" value="<?php echo htmlspecialchars($empleado['apellido']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($empleado['email']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="rol">Rol</label>
                <select id="rol" name="rol" required>
                    <option value="cliente" <?php echo ($empleado['rol'] == 'cliente') ? 'selected' : ''; ?>>Cliente</option>
                    <option value="empleado" <?php echo ($empleado['rol'] == 'empleado') ? 'selected' : ''; ?>>Empleado</option>
                    <option value="admin" <?php echo ($empleado['rol'] == 'admin') ? 'selected' : ''; ?>>Administrador</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="tel" id="telefono" name="telefono" value="<?php echo htmlspecialchars($empleado['telefono']); ?>">
            </div>
            
            <div class="form-group">
                <label for="provincia">Provincia</label>
                <select name="provincia" required>
                    <option value="">Seleccione provincia</option>
                    <?php 
                    $provincias = [
                        'Buenos Aires', 'Catamarca', 'Chaco', 'Chubut', 'Córdoba', 
                        'Corrientes', 'Entre Ríos', 'Formosa', 'Jujuy', 'La Pampa', 
                        'La Rioja', 'Mendoza', 'Misiones', 'Neuquén', 'Río Negro', 
                        'Salta', 'San Juan', 'San Luis', 'Santa Cruz', 'Santa Fe', 
                        'Santiago del Estero', 'Tierra del Fuego', 'Tucumán'
                    ];
                    
                    foreach ($provincias as $prov): 
                    ?>
                        <option value="<?php echo $prov; ?>" <?php echo ($empleado['provincia'] == $prov) ? 'selected' : ''; ?>>
                            <?php echo $prov; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label for="localidad">Localidad</label>
                <input type="text" id="localidad" name="localidad" value="<?php echo htmlspecialchars($empleado['localidad']); ?>">
            </div>
            
            <div class="form-group">
                <label for="direccion">Dirección</label>
                <input type="text" id="direccion" name="direccion" value="<?php echo htmlspecialchars($empleado['direccion']); ?>">
            </div>
            
            <button type="submit" name="actualizar" class="btn-outline-custom">Guardar Cambios</button>
        </form>
        <?php else: ?>
            <p>Empleado no encontrado</p>
        <?php endif; ?>
    </div>
</section>
    <footer>
        <p>Dirección: Av. Pres. Arturo Illia 902, Catamarca, Argentina | Email: contacto@suplementosdynamite.com | Tel: (123) 456-7890</p>
        <div class="social-icons">
            <a href="https://wa.me/1234567890" target="_blank"><i class="fab fa-whatsapp"></i></a>
            <a href="https://www.instagram.com/suplementosdynamite" target="_blank"><i class="fab fa-instagram"></i></a>
        </div>
        <p>&copy; 2024 Suplementos Dynamite. Todos los derechos reservados.</p>
    </footer>

    <script>
        const dateTimeElement = document.getElementById("date-time");
        const updateDateTime = () => {
            const now = new Date();
            dateTimeElement.innerText = now.toLocaleString();
        };
        setInterval(updateDateTime, 1000);
        updateDateTime();

    </script>
</body>
</html>

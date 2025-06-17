<?php
session_start();
include ("../../conexion.php");
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}


// Manejar eliminación de empleado
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    
    // Prevenir autoeliminación
    if ($id == $_SESSION['usuario_id']) {
        $_SESSION['error_message'] = "No puedes eliminarte a ti mismo";
        header("Location: admin.php");
        exit();
    }
    
    $sql_eliminar = "UPDATE usuarios SET estado = 'inactivo' WHERE usuario_id = ?";
    $stmt_eliminar = $conexion->prepare($sql_eliminar);
    $stmt_eliminar->bind_param("i", $id);
    
    if ($stmt_eliminar->execute()) {
        $_SESSION['success_message'] = "Empleado eliminado correctamente";
    } else {
        $_SESSION['error_message'] = "Error al eliminar el empleado: " . $conexion->error;
    }
    
    header("Location: admin.php");
    exit();
}

// todos los empleados activos , menos los clientess
$sql = "SELECT usuario_id, nombre, apellido, email, rol, telefono 
        FROM usuarios 
        WHERE rol IN ('empleado', 'admin') AND estado = 'activo'
        ORDER BY usuario_id ASC";
$result = $conexion->query($sql);
$empleados = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $empleados[] = $row;
    }
}
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Empleados</title>
    <link rel="stylesheet" href="../../css/administradorCss/admin.css"> 
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
        <br>
            <p id="date-time"></p>
        <nav class="nav-bar">
            <ul>
                <li><a href="../index.php">Inicio</a></li>
                <li><a href="../catalogo-Carrito-Producto-HTML/catalogo.php">Catálogo</a></li>
                <li><a href="../contactoHtml/contacto.php">Contacto</a></li>                                
                <li><a href="../loginHtml/login.php" class="btn-login"><i class="fas fa-user"></i> Iniciar Sesión</a></li>

            </ul>
        </nav>
    </header>

    <nav class="ruta">
        <a href="../index.php">Inicio</a> > <a href="../personal.php">Panel de Personal</a> > <a href="#">Panel Administrador</a>
    </nav>
 
    <?php if (!empty($error_message)): ?>
       <div class="error">
           <p><?php echo $error_message; ?></p>
       </div>
   <?php endif; ?>
   
   <?php if (!empty($success_message)): ?>
       <div class="success">
           <p><?php echo $success_message; ?></p>
       </div>
   <?php endif; ?>
    <section class="crud-section">
        <div class="crud-container">
            <h2>Gestión de Empleados</h2>
            <a href="altaEmpleado.php" class="altaEmpleado">Agregar Empleado</a>
            <a href="controlSesion.php" class="listarUsuarios">Listar Usuarios</a>
            <table class="crud-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Telefono</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
             <tbody>
                <?php foreach ($empleados as $empleado): ?>
                <tr>
                    <td><?php echo $empleado['usuario_id']; ?></td>
                    <td><?php echo htmlspecialchars($empleado['nombre'] . ' ' . $empleado['apellido']); ?></td>
                    <td><?php echo htmlspecialchars($empleado['email']); ?></td>
                    <td><?php echo ucfirst($empleado['rol']); ?></td>
                    <td><?php echo htmlspecialchars($empleado['telefono']); ?></td>       <td>
                            <div class="action-buttons">
                                <a href="editarempleado.php?id=<?php echo $empleado['usuario_id']; ?>" class="botonver"><i class="fas fa-edit"></i> Editar</a>
                                <a href="#" class="botonver boton-rojo" onclick="confirmarEliminacion(<?php echo $empleado['usuario_id']; ?>)"><i class="fas fa-trash-alt"></i> Eliminar</a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    
                    <?php if (empty($empleados)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center;">No se encontraron empleados</td>
                        </tr>
                    <?php endif; ?>
            </tbody>
            </table>
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

            // Función para confirmar eliminación con SweetAlert
        function confirmarEliminacion(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡Esta acción eliminará permanentemente al empleado!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirigir a la misma página con parámetro de eliminación
                    window.location.href = `admin.php?eliminar=${id}`;
                }
            });
        }
    </script>

    <?php if (isset($_SESSION['success_message'])): ?>
        <script>
            Swal.fire({
                title: 'Proceso exitoso',
                text: '<?php echo $_SESSION['success_message']; ?>',
                icon: 'success',
                confirmButtonText: 'Aceptar'
            });
            <?php unset($_SESSION['success_message']); ?>
        </script>
    <?php endif; ?>
    
    <?php if (!empty($error_message)): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '<?php echo $error_message; ?>',
                confirmButtonText: 'Aceptar'
            });
        </script>
        <?php endif; ?>
</body>
</html>

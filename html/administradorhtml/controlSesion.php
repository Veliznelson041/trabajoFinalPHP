<?php
session_start();
include("../../conexion.php");
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// Obtener todos los clientes
$sql_clientes = "SELECT usuario_id, nombre, apellido, email, rol, telefono 
                 FROM usuarios 
                 WHERE rol = 'cliente' AND estado ='activo'
                 ORDER BY usuario_id ASC";
$result_clientes = $conexion->query($sql_clientes);
$clientes = [];
if ($result_clientes->num_rows > 0) {
    while ($row = $result_clientes->fetch_assoc()) {
        $clientes[] = $row;
    }
}

// Manejar eliminación de cliente
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    
    // Prevenir autoeliminación
    if ($id == $_SESSION['usuario_id']) {
        $_SESSION['error_message'] = "No puedes eliminarte a ti mismo";
        header("Location: controlSesion.php");
        exit();
    }
    
    $sql_eliminar = "UPDATE usuarios SET estado = 'inactivo' WHERE usuario_id = ?";
    $stmt_eliminar = $conexion->prepare($sql_eliminar);
    $stmt_eliminar->bind_param("i", $id);
    
    if ($stmt_eliminar->execute()) {
        $_SESSION['success_message'] = "Cliente eliminado correctamente";
    } else {
        $_SESSION['error_message'] = "Error al eliminar el cliente: " . $conexion->error;
    }

    header("Location: controlSesion.php");
    exit();
}

// Mensajes de sesión
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
    <title>Control de Inicio de Sesión</title>
    <link rel="stylesheet" href="../../css/administradorCss/admin.css"> <!-- Asegúrate de enlazar tu archivo CSS -->
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
</head>
<body>
    <header>
        <div class="header-container">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRt_eZYMvS26mdHNwVQw-zHWqDRdSz5XzAVHQ&s" alt="Logo de Suplementos Dynamite" class="logo">
            <h1 id="title">Control de Inicio de Sesión</h1>
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
        <a href="../index.php">Inicio</a> > <a href="../personal.php">Panel de Personal</a> > <a href="admin.php">Panel Administrador</a> > <a href="#">Lista Usuarios</a>
    </nav>

    <section class="session-control-section">
        <div class="crud-container">
            <h2>Gestion de clientes</h2>


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
            
            <table class="crud-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th class="actions-header">Acciones</th>
                    </tr>
                </thead>
                <tbody id="clientesTable">
                    <?php foreach ($clientes as $cliente): ?>
                        <tr>
                            <td><?php echo $cliente['usuario_id']; ?></td>
                            <td><?php echo htmlspecialchars($cliente['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($cliente['apellido']); ?></td>
                            <td><?php echo htmlspecialchars($cliente['email']); ?></td>
                            <td><?php echo htmlspecialchars($cliente['telefono']); ?></td>
                          <td>
                              <a href="editarEmpleado.php?id=<?php echo $cliente['usuario_id']; ?>&redirect_to=controlSesion.php" class="botonver">Editar</a>
                                <a href="#" class="botonver boton-rojo" onclick="confirmarEliminacion(<?php echo $cliente['usuario_id']; ?>)">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    
                    <?php if (empty($clientes)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center;">No se encontraron clientes</td>
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
                text: "¡Esta acción no se puede deshacer!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirigir a la misma página con parámetro de eliminación
                    window.location.href = `controlSesion.php?eliminar=${id}`;
                }
            });
        }
        
        // Mostrar mensajes con SweetAlert
        <?php if (!empty($success_message)): ?>
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: '<?php echo $success_message; ?>',
                confirmButtonText: 'Aceptar'
            });
        <?php endif; ?>
        
        <?php if (!empty($error_message)): ?>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '<?php echo $error_message; ?>',
                confirmButtonText: 'Aceptar'
            });
        <?php endif; ?>
    </script>
</body>
</html>

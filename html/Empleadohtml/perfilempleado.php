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
    <title>Perfil de Empleado - Suplementos Dynamite</title>
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
            color: white;
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
            gap: 1.5rem;
        }
        
        .nav-bar a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 0;
            position: relative;
            font-weight: 500;
            transition: var(--transition);
        }
        
        .nav-bar a:hover {
            color: #fff;
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
        
        /* Breadcrumb */
        .breadcrumb {
            max-width: 1400px;
            margin: 1rem auto;
            padding: 0 1.5rem;
            font-size: 0.9rem;
        }
        
        .breadcrumb a {
            color: var(--secondary);
            text-decoration: none;
        }
        
        .breadcrumb a:hover {
            text-decoration: underline;
        }
        
        /* Main Content */
        .container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 1.5rem;
            display: grid;
            grid-template-columns: 1fr 3fr;
            gap: 2rem;
        }
        
        /* Profile Sidebar */
        .profile-sidebar {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: var(--shadow);
            height: fit-content;
            text-align: center;
        }
        
        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(45deg, var(--secondary), var(--success));
            margin: 0 auto 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: white;
            font-weight: bold;
        }
        
        .profile-name {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--dark);
        }
        
        .profile-role {
            background: var(--secondary);
            color: white;
            padding: 0.3rem 1.5rem;
            border-radius: 20px;
            display: inline-block;
            margin-bottom: 1.5rem;
            font-weight: 500;
        }
        
        .profile-stats {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
            margin-top: 2rem;
        }
        
        .stat-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.8rem 1rem;
            background: var(--light-gray);
            border-radius: 8px;
            transition: var(--transition);
        }
        
        .stat-item:hover {
            background: #e3e9f1;
            transform: translateY(-2px);
        }
        
        .stat-label {
            font-weight: 500;
            color: var(--dark);
        }
        
        .stat-value {
            font-weight: 700;
            color: var(--secondary);
        }
        
        /* Profile Content */
        .profile-content {
            background: white;
            border-radius: 12px;
            padding: 2.5rem;
            box-shadow: var(--shadow);
        }
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--light-gray);
        }
        
        .section-title {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--dark);
        }
        
        .edit-icon {
            color: var(--secondary);
            font-size: 1.2rem;
            cursor: pointer;
        }
        
        /* Alert Messages */
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-weight: 500;
        }
        
        .alert-success {
            background: rgba(39, 174, 96, 0.15);
            color: var(--success);
            border-left: 4px solid var(--success);
        }
        
        .alert-error {
            background: rgba(231, 76, 60, 0.15);
            color: var(--accent);
            border-left: 4px solid var(--accent);
        }
        
        /* Form Styles */
        .profile-form {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--dark);
        }
        
        .form-group input {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: var(--transition);
        }
        
        .form-group input:focus {
            border-color: var(--secondary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }
        
        .form-actions {
            grid-column: span 2;
            display: flex;
            justify-content: flex-end;
            margin-top: 1rem;
        }
        
        .btn-save {
            background: var(--success);
            color: white;
            border: none;
            padding: 0.8rem 2rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-save:hover {
            background: #219653;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(39, 174, 96, 0.3);
        }
        
        .btn-save i {
            font-size: 1.1rem;
        }
        
        /* Responsive */
        @media (max-width: 992px) {
            .container {
                grid-template-columns: 1fr;
            }
            
            .profile-form {
                grid-template-columns: 1fr;
            }
            
            .form-actions {
                grid-column: span 1;
            }
        }
        
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
        }
        
        @media (max-width: 576px) {
            .profile-content {
                padding: 1.5rem;
            }
            
            .section-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
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
                <li><a href="perfilempleado.php" class="btn-login"><i class="fas fa-user-cog"></i> Mi Perfil</a></li>
            </ul>
        </nav>
    </header>

    <div class="breadcrumb">
        <a href="empleado.php">Inicio</a> > <a href="#">Perfil</a>
    </div>

    <div class="container">
        <aside class="profile-sidebar">
            <div class="profile-avatar">
                <?= strtoupper(substr(htmlspecialchars($_SESSION['nombre']), 0, 1)) ?>
            </div>
            <div class="profile-name"><?= htmlspecialchars($_SESSION['nombre']) ?> <?= htmlspecialchars($_SESSION['apellido']) ?></div>
            <div class="profile-role"><?= ucfirst($_SESSION['rol']) ?></div>
            
            <div class="profile-stats">
                <div class="stat-item">
                    <span class="stat-label">Miembro desde</span>
                    <span class="stat-value"><?= date('Y') ?></span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Última actualización</span>
                    <span class="stat-value">Hoy</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Estado</span>
                    <span class="stat-value" style="color: var(--success);">Activo</span>
                </div>
            </div>
        </aside>
        
        <main class="profile-content">
            <div class="section-header">
                <h2 class="section-title">Información Personal</h2>
                <i class="fas fa-pencil-alt edit-icon"></i>
            </div>
            
            <?php if(isset($success)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?= $success ?>
                </div>
            <?php endif; ?>
            
            <?php if(isset($error)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?= $error ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" class="profile-form">
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="apellido">Apellido</label>
                    <input type="text" id="apellido" name="apellido" value="<?= htmlspecialchars($usuario['apellido']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="telefono">Teléfono</label>
                    <input type="text" id="telefono" name="telefono" value="<?= htmlspecialchars($usuario['telefono']) ?>">
                </div>
                
                <div class="form-group">
                    <label for="provincia">Provincia</label>
                    <input type="text" id="provincia" name="provincia" value="<?= htmlspecialchars($usuario['provincia']) ?>">
                </div>
                
                <div class="form-group">
                    <label for="localidad">Localidad</label>
                    <input type="text" id="localidad" name="localidad" value="<?= htmlspecialchars($usuario['localidad']) ?>">
                </div>
                
                <div class="form-group">
                    <label for="direccion">Dirección</label>
                    <input type="text" id="direccion" name="direccion" value="<?= htmlspecialchars($usuario['direccion']) ?>">
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-save">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </main>
    </div>

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
        
        // Simular el icono de edición
        document.querySelector('.edit-icon').addEventListener('click', function() {
            document.querySelector('.profile-form').scrollIntoView({ behavior: 'smooth' });
        });
    </script>
</body>
</html>
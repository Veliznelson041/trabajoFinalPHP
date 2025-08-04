<?php
require_once '../../../controlSesion.php';
require_once '../../../conexion.php';

// Verificar ID de consulta
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: buzon.php?error=ID inválido');
    exit;
}

$consulta_id = intval($_GET['id']);

// Obtener datos de la consulta
$sql = "SELECT * FROM consultas WHERE consulta_id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $consulta_id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    header('Location: buzon.php?error=Consulta no encontrada');
    exit;
}

$consulta = $resultado->fetch_assoc();

$error = '';
$success = '';

// Procesar respuesta
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $asunto = trim($_POST['asunto']);
    $respuesta = trim($_POST['respuesta']);
    
    if (empty($asunto)) {
        $error = "El asunto es obligatorio";
    } elseif (empty($respuesta)) {
        $error = "La respuesta no puede estar vacía";
    } else {
        // Guardar respuesta en la base de datos
        $modificado_por = $_SESSION['email'];
        $sql_respuesta = "INSERT INTO respuestas (consulta_id, asunto, respuesta, empleado_email, fecha) 
                          VALUES (?, ?, ?, ?, NOW())";
        $stmt_respuesta = $conexion->prepare($sql_respuesta);
        $stmt_respuesta->bind_param("isss", $consulta_id, $asunto, $respuesta, $modificado_por);
        
        if ($stmt_respuesta->execute()) {
            // Actualizar estado de la consulta
            $sql_actualizar = "UPDATE consultas SET estado = 'respondida' WHERE consulta_id = ?";
            $stmt_actualizar = $conexion->prepare($sql_actualizar);
            $stmt_actualizar->bind_param("i", $consulta_id);
            $stmt_actualizar->execute();
            
            // Enviar correo electrónico (simulado)
            // En un entorno real, usarías una librería como PHPMailer
            // mail($consulta['email'], $asunto, $respuesta);
            
            $success = "Respuesta enviada correctamente";
            
            // Redirigir después de 2 segundos
            header("Refresh: 2; URL=buzon.php");
        } else {
            $error = "Error al guardar la respuesta: " . $conexion->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responder Consulta | Suplementos Dynamite</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --accent-color: #e74c3c;
            --light-bg: #f8f9fa;
            --card-bg: #ffffff;
            --border-color: #e0e0e0;
            --success-color: #2ecc71;
            --warning-color: #f39c12;
            --text-dark: #333;
            --text-light: #777;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: var(--light-bg);
            color: var(--text-dark);
            line-height: 1.6;
        }
        
        /* Header Styles */
        .main-header {
            background: linear-gradient(135deg, var(--secondary-color), #1a2530);
            color: white;
            padding: 1rem 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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
            gap: 15px;
        }
        
        .logo {
            height: 50px;
            width: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid white;
        }
        
        .brand-text h1 {
            font-size: 1.5rem;
            font-weight: 600;
        }
        
        .brand-text p {
            font-size: 0.85rem;
            opacity: 0.9;
            margin-top: 3px;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.1);
            padding: 8px 15px;
            border-radius: 30px;
            transition: all 0.3s ease;
        }
        
        .user-info:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        
        .user-info i {
            font-size: 1.2rem;
        }
        
        .user-info span {
            font-weight: 500;
        }
        
        .nav-bar {
            background-color: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            padding: 0.5rem;
            margin-top: 1rem;
        }
        
        .nav-links {
            display: flex;
            list-style: none;
            gap: 20px;
        }
        
        .nav-links a {
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 5px;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .nav-links a:hover {
            background-color: rgba(255, 255, 255, 0.15);
        }
        
        .nav-links a.active {
            background-color: var(--primary-color);
        }
        
        /* Breadcrumb */
        .breadcrumb {
            padding: 1rem 2rem;
            background-color: var(--card-bg);
            border-bottom: 1px solid var(--border-color);
            font-size: 0.9rem;
        }
        
        .breadcrumb a {
            color: var(--primary-color);
            text-decoration: none;
        }
        
        .breadcrumb a:hover {
            text-decoration: underline;
        }
        
        .breadcrumb span {
            color: var(--text-light);
        }
        
        /* Main Content */
        .main-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1.5rem;
        }
        
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .dashboard-title {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--secondary-color);
        }
        
        .card {
            background: var(--card-bg);
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }
        
        .card-header {
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .card-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--secondary-color);
        }
        
        .consulta-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .info-group {
            margin-bottom: 1.5rem;
        }
        
        .info-label {
            font-weight: 600;
            color: var(--text-light);
            margin-bottom: 0.3rem;
            font-size: 0.9rem;
        }
        
        .info-value {
            font-size: 1.1rem;
            color: var(--text-dark);
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--border-color);
        }
        
        .consulta-mensaje {
            background-color: #f8fafd;
            border-radius: 8px;
            padding: 1.5rem;
            margin-top: 1rem;
            border-left: 4px solid var(--primary-color);
        }
        
        .mensaje-label {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .mensaje-content {
            line-height: 1.7;
            color: var(--text-dark);
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--secondary-color);
        }
        
        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }
        
        textarea.form-control {
            min-height: 200px;
            resize: vertical;
        }
        
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(52, 152, 219, 0.3);
        }
        
        .btn-cancel {
            background-color: var(--light-bg);
            color: var(--text-dark);
            margin-right: 1rem;
        }
        
        .btn-cancel:hover {
            background-color: #e0e0e0;
        }
        
        .respuesta-preview {
            background-color: #f8fafd;
            border-radius: 8px;
            padding: 1.5rem;
            margin-top: 1.5rem;
            border: 1px solid var(--border-color);
        }
        
        .preview-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid var(--border-color);
        }
        
        .preview-title {
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .preview-content {
            line-height: 1.7;
            color: var(--text-dark);
            white-space: pre-wrap;
        }
        
        .message {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
        }
        
        .message i {
            margin-right: 10px;
            font-size: 1.2rem;
        }
        
        .message-success {
            background-color: rgba(46, 204, 113, 0.1);
            color: #27ae60;
            border-left: 4px solid #2ecc71;
        }
        
        .message-error {
            background-color: rgba(231, 76, 60, 0.1);
            color: #c0392b;
            border-left: 4px solid #e74c3c;
        }
        
        .main-footer {
            text-align: center;
            padding: 1.5rem;
            color: var(--text-light);
            font-size: 0.9rem;
            border-top: 1px solid var(--border-color);
            margin-top: 2rem;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .user-info {
                align-self: flex-end;
            }
            
            .dashboard-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .consulta-info {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header class="main-header">
        <div class="header-container">
            <div class="logo-container">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRt_eZYMvS26mdHNwVQw-zHWqDRdSz5XzAVHQ&s" alt="Logo de Suplementos Dynamite" class="logo">
                <div class="brand-text">
                    <h1>Suplementos Dynamite</h1>
                    <p id="date-time"></p>
                </div>
            </div>
            
            <nav class="nav-bar">
                <ul class="nav-links">
                    <li><a href="../empleado.php">Inicio</a></li>
                    <li><a href="../perfilempleado.php" class="user-info">
                        <i class="fas fa-user"></i> 
                        <span><?= htmlspecialchars($_SESSION['nombre']) ?></span>
                    </a></li>
                </ul>
            </nav>
        </div>
    </header>
    
    <div class="breadcrumb">
        <a href="../empleado.php">Inicio</a> > 
        <a href="buzon.php">Buzón de Consultas</a> > 
        <span>Responder Consulta</span>
    </div>
    
    <div class="main-container">
        <div class="dashboard-header">
            <h2 class="dashboard-title">Responder Consulta</h2>
        </div>
        
        <?php if ($error): ?>
            <div class="message message-error">
                <i class="fas fa-exclamation-circle"></i>
                <span><?= $error ?></span>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="message message-success">
                <i class="fas fa-check-circle"></i>
                <span><?= $success ?></span>
            </div>
        <?php endif; ?>
        
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Consulta de <?= htmlspecialchars($consulta['nombre']) ?></h3>
            </div>
            
            <div class="consulta-info">
                <div class="info-group">
                    <div class="info-label">Fecha</div>
                    <div class="info-value">
                        <?= date('d/m/Y H:i', strtotime($consulta['fecha'])) ?>
                    </div>
                </div>
                
                <div class="info-group">
                    <div class="info-label">Email</div>
                    <div class="info-value">
                        <?= htmlspecialchars($consulta['email']) ?>
                    </div>
                </div>
                
                <div class="info-group">
                    <div class="info-label">Asunto</div>
                    <div class="info-value">
                        <?= htmlspecialchars($consulta['asunto']) ?>
                    </div>
                </div>
            </div>
            
            <div class="consulta-mensaje">
                <div class="mensaje-label">
                    <i class="fas fa-envelope"></i>
                    <span>Mensaje:</span>
                </div>
                <div class="mensaje-content">
                    <?= nl2br(htmlspecialchars($consulta['mensaje'])) ?>
                </div>
            </div>
            
            <form method="POST" id="form-respuesta">
                <div class="form-group">
                    <label for="asunto">Asunto de la respuesta:</label>
                    <input type="text" id="asunto" name="asunto" class="form-control" 
                           value="Re: <?= htmlspecialchars($consulta['asunto']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="respuesta">Respuesta:</label>
                    <textarea id="respuesta" name="respuesta" class="form-control" 
                              placeholder="Escribe tu respuesta aquí..." required></textarea>
                </div>
                
                <div class="form-group">
                    <a href="buzon.php" class="btn btn-cancel">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Enviar Respuesta</button>
                </div>
            </form>
            
            <div class="respuesta-preview" id="preview-container">
                <div class="preview-header">
                    <div class="preview-title">Vista previa de la respuesta</div>
                    <button type="button" class="btn btn-cancel" id="toggle-preview">Mostrar</button>
                </div>
                <div class="preview-content" id="preview-content" style="display: none;"></div>
            </div>
        </div>
    </div>
    
    <footer class="main-footer">
        <p>Suplementos Dynamite &copy; <?= date('Y') ?> - Todos los derechos reservados</p>
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
            document.getElementById('date-time').textContent = now.toLocaleDateString('es-ES', options);
        }
        setInterval(updateDateTime, 1000);
        updateDateTime();
        
        // Vista previa de la respuesta
        const respuestaInput = document.getElementById('respuesta');
        const previewContent = document.getElementById('preview-content');
        const togglePreview = document.getElementById('toggle-preview');
        
        respuestaInput.addEventListener('input', function() {
            previewContent.textContent = this.value;
        });
        
        togglePreview.addEventListener('click', function() {
            const preview = document.getElementById('preview-content');
            if (preview.style.display === 'none') {
                preview.style.display = 'block';
                this.textContent = 'Ocultar';
            } else {
                preview.style.display = 'none';
                this.textContent = 'Mostrar';
            }
        });
        
        // Enviar formulario al presionar Ctrl+Enter
        respuestaInput.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'Enter') {
                document.getElementById('form-respuesta').submit();
            }
        });
    </script>
</body>
</html>
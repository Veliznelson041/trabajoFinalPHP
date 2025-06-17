<?php
session_start();

if (!isset($_SESSION['usuario_id']) || ($_SESSION['rol'] !== 'empleado' && $_SESSION['rol'] !== 'admin')) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Gerente - Suplementos Dynamite</title>
    <link rel="stylesheet" href="../css/personal.css">
</head>
<body>
    <header>
        <div class="header-container">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRt_eZYMvS26mdHNwVQw-zHWqDRdSz5XzAVHQ&s" alt="Logo de Suplementos Dynamite" class="logo">
            <h1>Suplementos Dynamite</h1>
        </div>
        <nav class="nav-bar">
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="./catalogo-Carrito-Producto-HTML/catalogo.php">Catálogo</a></li>
                <li><a href="./contactoHtml/contacto.php">Contacto</a></li>                                
                <li><a href="./loginHtml/login.php" class="btn-login"><i class="fas fa-user"></i> Iniciar Sesión</a></li>
            </ul>
        </nav>
    </header>


    <nav class="ruta">
        <a href="index.php">Inicio</a> > <a href="#">Panel de Personal</a>
    </nav>
    <main>
        <h2>Panel de Personal de la Empresa</h2>
        <section class="opciones">
            <div class="opcion">
                <h2>Ingresar como Empleado</h2>
                <p>Operaciones como Empleado</p>
                <a href="../html/Empleadohtml/empleado.php" class="boton">Empleado</a>
            </div>
            <div class="opcion">
                <h2>Ingresar como Administrador</h2>
                <p>Operaciones como Administrador.</p>
                <a href="../html/administradorhtml/admin.php" class="boton">Administrador</a>
            </div>
            <!-- <div class="opcion">
                <h2>Ingresar como Gerente</h2>
                <p>Operaciones como Gerente</p>
                <a href="../html/gerenteHtml/principal.php" class="boton">Gerente</a>
            </div> -->
        
        </section>

    </main>



    <footer>
        <p>&copy; 2024 Suplementos Dynamite. Todos los derechos reservados.</p>
    </footer>
</body>
</html>

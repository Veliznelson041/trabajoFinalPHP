<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="stylesheet" href="../../css/loginCss/login.css">
</head>
<body>

    <!-- Encabezado -->
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
                <li><a href="/html/catalogo-Carrito-Producto-HTML/catalogo.php">Catálogo</a></li>
                <li><a href="../contactoHtml/contacto.php">Contacto</a></li>                                
                <li><a href="/html/loginHtml/login.php" class="btn-login"><i class="fas fa-user"></i> Iniciar Sesión</a></li>

            </ul>
        </nav>

    </header>
    <nav class="ruta">
        <a href="../index.php">Inicio</a> &gt; <a href="login.php">Iniciar Sesión</a> 
    </nav>

    <div class="container">
        <h1>Inicio de Sesión</h1>
        <form id="login-form">
            <label for="username">Nombre de usuario:</label>
            <input type="text" id="username" required>

            <label for="password">Clave:</label>
            <input type="password" id="password" required>

            <button type="submit">Ingresar</button>
            <p><a href="../loginHtml/recover.html">¿Olvidaste tu clave?</a></p>
            <p><a href="../loginHtml/register.html">Registrarse</a></p>
        </form>
    </div>

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
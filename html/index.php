<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suplementos Dynamite - Inicio</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

    <!-- Encabezado -->
    <header>
        <div class="header-container">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRt_eZYMvS26mdHNwVQw-zHWqDRdSz5XzAVHQ&s" alt="Logo de Suplementos Dynamite" class="logo">
            <h1>Suplementos Dynamite</h1>
            
        </div>
        <br>
            <p id="date-time"></p>
        <nav class="nav-bar">
            <ul>
                <li><a href="#">Inicio</a></li>
                <li><a href="catalogo-Carrito-Producto-HTML/catalogo.php">Catálogo</a></li>
                <li><a href="./contactoHtml/contacto.php">Contacto</a></li> 
                
                <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']): ?>
                    <li><a href="#" class="btn-login"><i class="fas fa-user"></i> Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre']); ?></a></li>
                    <li><a href="loginHtml/logout.php" class="btn-login"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
                <?php else: ?>

                    <li><a href="loginHtml/login.php" class="btn-login"><i class="fas fa-user"></i> Iniciar Sesión</a></li>
                <?php endif; ?>
                <?php if (isset($_SESSION['rol']) && ($_SESSION['rol'] == 'empleado' || $_SESSION['rol'] == 'admin')): ?>
                    <li><a href="personal.php">Personal</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <div class="search-container">
        <form id="search-form" action="#" method="GET">
            <input type="text" id="search-input" name="query" placeholder="Buscar productos..." required>
            <button type="submit" class="btn-search">Buscar</button>
        </form>
    </div>
    <!-- Menú Vertical -->


    
    <!-- Sección de Bienvenida -->
    <section class="welcome-section">
        <h2>Bienvenido a Suplementos Dynamite</h2>
        <p>Potencia tu rendimiento y salud con los mejores suplementos del mercado.</p>
        
        <!--<a> <button class="botonver">VER TODOS LOS PRODUCTOS</button> </a> -->
        <li class="menu">
            <a class="menu-link" href="#">Ver Nuestros Productos</a>
            <ul class="submenu">
                <li><a class="dropdown-item" href="#">Proteínas</a></li>
                <li><a class="dropdown-item" href="#">Creatinas</a></li>
                <li><a class="dropdown-item" href="#">Pre Entrenos</a></li>
                <li><a class="dropdown-item" href="#">Accesorios</a></li>
            </ul>
        </li>
    </section>


    <div class="featured-products">
        <h2>Productos Destacados</h2>
        <div class="card-container">
            <!-- Tarjeta 1 -->
            <div class="card">
                <img src="https://dqm4sv5xk0oaj.cloudfront.net/products/46804/large/STANUT004012.jpg?1682601964" alt="Producto 1">
                <div class="card-body">
                    <h5>Creatina StarNutrition</h5>
                    <p>$ 30.000,00</p>
                    <br>
                    <button class="btn-outline-custom" onclick="window.location.href='/html/catalogo-Carrito-Producto-HTML/producto.php'">Ver Producto</button>
                </div>
            </div>
            <!-- Tarjeta 2 -->
            <div class="card">
                <img src="https://http2.mlstatic.com/D_NQ_NP_839016-MLU43389298571_092020-F.jpg" alt="Producto 2">
                <div class="card-body">
                    <h5>Star Nutrition Premium Whey Protein 1Kg</h5>
                    <p>$37.840,00</p>
                    <br>
                    <button class="btn-outline-custom">Ver Producto</button>
                </div>
            </div>
            <!-- Repite para las demás tarjetas -->
            <div class="card">
                <img src="https://dqm4sv5xk0oaj.cloudfront.net/products/46822/large/open-uri20230428-7-12m4969.?1682643970" alt="Producto 3">
                <div class="card-body">
                    <h5>Pump 3d Ripped Pre Entreno</h5>
                    <p>$27.287,00</p>
                    <br>
                    <button class="btn-outline-custom">Ver Producto</button>
                </div>
            </div>
            <div class="card">
                <img src="https://elbloquear.vtexassets.com/arquivos/ids/160501/97_gr_-_2021-08-02t160238.png?v=637870986089930000" alt="Producto 4">
                <div class="card-body">
                    <h5>Suplemento Dietario Enaccion Multivitanimico</h5>
                    <p>$5.940,00</p>
                    <br>
                    <button class="btn-outline-custom">Ver Producto</button>
                </div>
            </div>
        </div>
    </div>
    
    

    <!-- Pie de Página -->
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


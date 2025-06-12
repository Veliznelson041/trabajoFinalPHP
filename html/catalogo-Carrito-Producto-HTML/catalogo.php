<!DOCTYPE html>
<html lang="es-AR">
<head>
    <meta charset="UTF-8">
    <title>Catálogo - Suplementos Dynamite</title>
    <link rel="stylesheet" href="../../css/Catalogo-Carrito-Css/catalogo.css">
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
                <li><a href="/html/index.php">Inicio</a></li>
                <li><a href="catalogo.php">Catálogo</a></li>
                <li><a href="carrito.php">Carrito</a></li>
                <li><a href="../../html/contactoHtml/contacto.php">Contacto</a></li>                                
                <li><a href="../../html/loginHtml/login.php" class="btn-login"><i class="fas fa-user"></i> Iniciar Sesión</a></li>

            </ul>
        </nav>
    </header>

    <nav class="ruta">
        <a href="../index.php">Inicio</a> &gt; <a href="catalogo.php">Catálogo</a> 
    </nav>

    <main>
        <h2>Catálogo de Productos</h2>
        <div class="filters">
            <input type="text" placeholder="Buscar en catálogo...">
            <select>
                <option>Categoría</option>
                <option>Proteínas</option>
                <option>Creatinas</option>
                <option>Pre Entrenos</option>
                <option>Accesorios</option>
            </select>
            <input type="range" min="0" max="100000" step="1000">
        </div>

        <div class="product-gallery">
            <!-- Tarjeta 1 -->
            <div class="card">
                <img src="https://dqm4sv5xk0oaj.cloudfront.net/products/46804/large/STANUT004012.jpg?1682601964" alt="Producto 1">
                <div class="card-body">
                    <h5>Creatina StarNutrition</h5>
                    <p>$ 30.000,00</p>
                    <br>
                    <button class="btn-outline-custom" onclick="window.location.href='producto.php'">Ver Producto</button>
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

            <div class="card">
                <img src="https://http2.mlstatic.com/D_NQ_NP_772770-MLA49211946466_022022-O.jpg" alt="Producto 5">
                <div class="card-body">
                    <h5>Creatina Micronizada ENA</h5>
                    <p>$30.000,00</p>
                    <br>
                    <button class="btn-outline-custom">Ver Producto</button>
                </div>
            </div>

            <div class="card">
                <img src="https://elbloquear.vtexassets.com/arquivos/ids/165021/01-BLACK-WASHABLE--24-.png?v=638025855544230000" alt="Producto 6">
                <div class="card-body">
                    <h5>Enargy Gel - Repositor Energetico</h5>
                    <p>$6.000,00</p>
                    <br>
                    <button class="btn-outline-custom">Ver Producto</button>
                </div>
            </div>

            <div class="card">
                <img src="https://cdn.batitienda.com/baticloud/images/product_picture_d6b6a28e22e04e0d9aac10a211c2dab0_637857835527015480_0_m.jpg" alt="Producto 7">
                <div class="card-body">
                    <h5>BCAA</h5>
                    <p>$10.000,00</p>
                    <br>
                    <button class="btn-outline-custom">Ver Producto</button>
                </div>
            </div>

            <div class="card">
                <img src="https://http2.mlstatic.com/D_NQ_NP_654132-MLU72836569993_112023-O.webp" alt="Producto 8">
                <div class="card-body">
                    <h5>Ultra Mass</h5>
                    <p>$38.000,00</p>
                    <br>
                    <button class="btn-outline-custom">Ver Producto</button>
                </div>
            </div>

            <div class="card">
                <img src="https://http2.mlstatic.com/D_NQ_NP_784151-MLA50039980860_052022-F.jpg" alt="Producto 9">
                <div class="card-body">
                    <h5>Colageno Sport</h5>
                    <p>$12.000,00</p>
                    <br>
                    <button class="btn-outline-custom">Ver Producto</button>
                </div>
            </div>

            <div class="card">
                <img src="https://cdn.batitienda.com/baticloud/images/product_picture_b0b01da22fe44aae84628636d4da5a8b_638399684396648075_0_m.jpeg" alt="Producto 10">
                <div class="card-body">
                    <h5>Amino 4500</h5>
                    <p>$11.000,00</p>
                    <br>
                    <button class="btn-outline-custom">Ver Producto</button>
                </div>
            </div>

        </div>    
    </main>

    <footer>
        <p>Dirección: Av. Pres. Arturo Illia 902, Catamarca, Argentina | Email: contacto@suplementosdynamite.com | Tel: (123) 456-7890</p>
        <div class="social-icons">
            <a href="https://wa.me/1234567890" target="_blank"><i class="fab fa-whatsapp"></i></a>
            <a href="https://www.instagram.com/suplementosdynamite" target="_blank"><i class="fab fa-instagram"></i></a>
        </div>
        <p>&copy; 2024 Suplementos Dynamite. Todos los derechos reservados.</p>
    </footer>

</body>
</html>

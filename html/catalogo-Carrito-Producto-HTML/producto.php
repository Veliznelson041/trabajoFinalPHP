<!DOCTYPE html>
<html lang="es-AR">
<head>
    <meta charset="UTF-8">
    <title>Detalles del Producto - Suplementos Dynamite</title>
    <link rel="stylesheet" href="../../css/Catalogo-Carrito-Css/producto.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

    <header>
        <div class="header-container">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRt_eZYMvS26mdHNwVQw-zHWqDRdSz5XzAVHQ&s" alt="Logo de Suplementos Dynamite" class="logo">
            <h1>Suplementos Dynamite</h1>
        </div>
        <nav class="nav-bar">
            <ul>
                <li><a href="/html/index.php">Inicio</a></li>
                <li><a href="/html/catalogo-Carrito-HTML/catalogo.php">Catálogo</a></li>
                <li><a href="/html/catalogo-Carrito-HTML/carrito.php">Carrito</a></li>
                <li><a href="/html/loginHtml/login.php" class="btn-login"><i class="fas fa-user"></i> Iniciar Sesión</a></li>
            </ul>
        </nav>
    </header>

    <nav class="ruta">
        <a href="../index.php">Inicio</a> &gt; <a href="catalogo.php">Catálogo</a> &gt; <a href="producto.html">Detalles del Producto</a>
    </nav>

    <main>
        <div class="product-detail">
            <img src="https://dqm4sv5xk0oaj.cloudfront.net/products/46804/large/STANUT004012.jpg?1682601964" alt="Nombre del Producto" class="product-image">
            <div class="product-info">
                <h2>Creatina StarNutrition</h2>
                <p class="price">$30.000,00</p>
                <p class="description">Aumenta la fuerza y la potencia muscular. Mejora el rendimiento en ejercicios de alta intensidad. Acelera la recuperación muscular después del entrenamiento. Promueve el crecimiento muscular magro. Creatina Monohidratada 100% pura.</p>
                <button class="btn-add-to-cart" onclick="window.location.href='carrito.php'">Añadir al carrito</button>
            </div>
        </div>

        <!-- Sección de Puntuación y Reseñas -->
        <section class="reviews-section">
            <h3>Puntuación y Reseñas del Producto</h3>

            <!-- Puntuación promedio -->
            <div class="average-rating">
                <span class="rating-stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </span>
                <span class="rating-score">4.5/5</span>
                <span class="total-reviews">(50 reseñas)</span>
            </div>

            <!-- Lista de reseñas -->
            <div class="reviews-list">
                <div class="review-item">
                    <span class="review-user">Juan Pérez</span>
                    <span class="review-stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </span>
                    <p class="review-comment">Excelente producto, he notado mejoras en mi rendimiento.</p>
                </div>

                <div class="review-item">
                    <span class="review-user">María López</span>
                    <span class="review-stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </span>
                    <p class="review-comment">Muy buen suplemento, realmente ayuda en el entrenamiento.</p>
                </div>

                <div class="review-item">
                    <span class="review-user">Carlos García</span>
                    <span class="review-stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </span>
                    <p class="review-comment">La creatina es de buena calidad, la recomiendo.</p>
                </div>
                
                <!-- Agregar más reseñas aquí -->
            </div>
        </section>
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

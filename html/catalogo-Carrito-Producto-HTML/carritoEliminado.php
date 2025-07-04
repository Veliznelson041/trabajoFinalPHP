<!DOCTYPE html>
<html lang="es-AR">
<head>
    <meta charset="UTF-8">
    <title>Carrito de Compras - Suplementos Dynamite</title>
    <link rel="stylesheet" href="../../css/Catalogo-Carrito-Css/carrito.css">
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
                <li><a href="/html/index.html">Inicio</a></li>
                <li><a href="catalogo.html">Catálogo</a></li>
                <li><a href="/html/contactoHtml/contacto.html">Contacto</a></li>                                
                <li><a href="/html/loginHtml/login.html" class="btn-login"><i class="fas fa-user"></i> Iniciar Sesión</a></li>
            </ul>
        </nav>
    </header>

    <nav class="ruta">
        <a href="../index.html">Inicio</a> &gt; <a href="carrito.html">Carrito</a>
    </nav>

    <main>
        <h2>Carrito de Compras</h2>
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Descuento</th>
                    <th>Total</th>
                    <th>Medio de Pago</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Rellena con productos de ejemplo -->
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td> <!-- Columna de descuento -->
                    <td>
                        <label><input type="checkbox" name="payment" value="tarjeta"> Tarjeta</label><br>
                        <label><input type="checkbox" name="payment" value="efectivo"> Efectivo</label><br>
                        <label><input type="checkbox" name="payment" value="transferencia"> Transferencia</label>
                    </td>
                    <td><a href="carritoEliminado.html" class="btn-remove">Eliminar</a></td> <!-- Enlace a la misma página -->
                </tr>
            </tbody>
        </table>

        <div class="cart-summary">
            <p>Total a pagar: <span id="total-amount">$</span></p>
            <a href="finalizarCompra.html" class="finalizar-btn">Finalizar Compra</a>
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

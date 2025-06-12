<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registro de Usuario</title>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
    />

    <link rel="stylesheet" href="../../css/loginCss/register.css" />
  </head>
  <body>
    <!-- Encabezado -->
    <header>
      <div class="header-container">
        <img
          src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRt_eZYMvS26mdHNwVQw-zHWqDRdSz5XzAVHQ&s"
          alt="Logo de Suplementos Dynamite"
          class="logo"
        />
        <h1 id="title">Suplementos Dynamite</h1>
      </div>
      <br />
      <p id="date-time"></p>
      <nav class="nav-bar">
        <ul>
          <li><a href="../index.html">Inicio</a></li>
          <li>
            <a href="/html/catalogo-Carrito-Producto-HTML/catalogo.html"
              >Catálogo</a
            >
          </li>
          <li><a href="../contactoHtml/contacto.html">Contacto</a></li>
          <li>
            <a href="/html/loginHtml/login.html" class="btn-login"
              ><i class="fas fa-user"></i> Iniciar Sesión</a
            >
          </li>
        </ul>
      </nav>
    </header>

    <nav class="ruta">
      <a href="../index.html">Inicio</a> &gt;
      <a href="login.html">Iniciar Sesión</a> &gt;
      <a href="../loginHtml/register.html">Registrarse</a>
    </nav>

    <div class="container">
      <h1>Registro de Usuario</h1>
      <form id="register-from">
        <label for="full-name">Nombres y Apellidos:</label>
        <input type="text" id="full-name" required />

        <label for="dni">DNI:</label>
        <input type="text" id="dni" required />

        <label for="calle">Calle:</label>
        <input type="text" id="calle" required />

        <label for="altura">Altura:</label>
        <input type="text" id="altura" required />

        <label for="departamento">Departamento:</label>
        <input type="text" id="departamento" required />

        <label for="provincias">Seleccione una provincia:</label>
        <select id="provincias" name="provincias">
          <option value="Buenos Aires">Buenos Aires</option>
          <option value="Catamarca">Catamarca</option>
          <option value="Chaco">Chaco</option>
          <option value="Chubut">Chubut</option>
          <option value="Córdoba">Córdoba</option>
          <option value="Corrientes">Corrientes</option>
          <option value="Entre Ríos">Entre Ríos</option>
          <option value="Formosa">Formosa</option>
          <option value="Jujuy">Jujuy</option>
          <option value="La Pampa">La Pampa</option>
          <option value="La Rioja">La Rioja</option>
          <option value="Mendoza">Mendoza</option>
          <option value="Misiones">Misiones</option>
          <option value="Neuquén">Neuquén</option>
          <option value="Río Negro">Río Negro</option>
          <option value="Salta">Salta</option>
          <option value="San Juan">San Juan</option>
          <option value="San Luis">San Luis</option>
          <option value="Santa Cruz">Santa Cruz</option>
          <option value="Santa Fe">Santa Fe</option>
          <option value="Santiago del Estero">Santiago del Estero</option>
          <option value="Tierra del Fuego">Tierra del Fuego</option>
          <option value="Tucumán">Tucumán</option>
        </select>

        <label for="genero">Genero:</label>
        <select id="genero" required>
          <option value="masculino">Masculino</option>
          <option value="femenino">Femenino</option>
          <option value="otro">Otro</option>
        </select>

        <label for="fechaNac">Fecha de Nacimiento:</label>
        <input type="date" id="fechaNac" required />

        <label for="reg-usuario">Nombre de usuario:</label>
        <input type="text" id="reg-usuario" required />

        <label for="reg-contraseña">Clave:</label>
        <input type="password" id="reg-contraseña" required />

        <label for="tipo-usuario">Tipo de usuario</label>
        <select id="tipo-usuario">
          <option value="usuario">Usuario</option>
          <option value="admin">Administrador</option>
        </select>

        <label for="reg-email">Email:</label>
        <input type="email" id="reg-email" required />

        <label for="imagen-perfil">Imagen de perfil:</label>
        <input type="file" id="imagen-perfil" required />

        <button type="submit">Registrarse</button>
        <p><a href="../loginHtml/login.html">Volver al inicio de sesión</a></p>
      </form>
    </div>

    <footer>
      <p>
        Dirección: Av. Pres. Arturo Illia 902, Catamarca, Argentina | Email:
        contacto@suplementosdynamite.com | Tel: (123) 456-7890
      </p>
      <div class="social-icons">
        <a href="https://wa.me/1234567890" target="_blank"
          ><i class="fab fa-whatsapp"></i
        ></a>
        <a href="https://www.instagram.com/suplementosdynamite" target="_blank"
          ><i class="fab fa-instagram"></i
        ></a>
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

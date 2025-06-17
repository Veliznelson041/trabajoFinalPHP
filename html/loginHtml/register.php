<?php
session_start();
include ("../../conexion.php");
?>
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
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
          <li><a href="../index.php">Inicio</a></li>
          <li>
            <a href="../catalogo-Carrito-Producto-HTML/catalogo.php"
              >Catálogo</a
            >
          </li>
          <li><a href="../contactoHtml/contacto.php">Contacto</a></li>
          <li>
            <a href="../loginHtml/login.php" class="btn-login"
              ><i class="fas fa-user"></i> Iniciar Sesión</a
            >
          </li>
        </ul>
      </nav>
    </header>

    <nav class="ruta">
      <a href="../index.php">Inicio</a> &gt;
      <a href="login.php">Iniciar Sesión</a> &gt;
      <a href="../loginHtml/register.php">Registrarse</a>
    </nav>

   <?php 
   $c=0;
   $error = [];
  //  $registroExitoso = false; // Variable de control

   if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['enviar'])){
       $nombre = trim($_POST['nombre']);
       $apellido = trim($_POST['apellido']);
       $email = strtolower(trim($_POST['email']));
       $password = $_POST['password'];
       $password1 = $_POST['confirm-password'];
       $telefono = trim($_POST['telefono']);
       $provincia = trim($_POST['provincia']);
       $localidad = trim($_POST['localidad']);
       $direccion = trim($_POST['direccion']);


    if (empty($nombre)) {
        $error[] = "El nombre no puede estar vacío.";
    } elseif (strlen($nombre) < 3) {
        $error[] = "El nombre debe tener al menos 3 caracteres.";
    } elseif (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $nombre)) {
        $error[] = "El nombre solo puede contener letras y espacios.";
    }

       
    if (empty($apellido)) {
        $error[] = "El apellido no puede estar vacío.";
    } elseif (strlen($apellido) < 3) {
        $error[] = "El apellido debe tener al menos 3 caracteres.";
    } elseif (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $apellido)) {
        $error[] = "El apellido solo puede contener letras y espacios.";
    }

    if (empty($email)) {
        $error[] = "El correo electrónico no puede estar vacío.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error[] = "El formato del correo electrónico no es válido.";
    }
    if (strlen($password) < 6) {
        $error[] = "La contraseña debe tener al menos 6 caracteres.";
    }
    if ($password != $password1) {
        $error[] = "Las contraseñas no coinciden.";
    }


     
    $sql = "SELECT email FROM usuarios WHERE email = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();
    if ($resultado->num_rows > 0) {
        $error[] = "El correo electrónico ya está registrado.";
    }

    if (empty($error)) {
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $fecha_registro = date("Y-m-d H:i:s");
    $sql = "INSERT INTO usuarios (nombre, apellido, email, password, telefono, provincia, localidad, direccion, fecha_registro, rol) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'cliente')";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sssssssss", $nombre, $apellido, $email, $password_hash, $telefono, $provincia, $localidad, $direccion, $fecha_registro);
    if ($stmt->execute()) {
          
          // $_SESSION['mensaje'] = "¡Registro exitoso! Por favor, inicia sesión.";
          // header("Location: login.php");
          // exit;
       
        echo "<script>
                Swal.fire({
                    title: 'Registro exitoso',
                    text: '¡Registro exitoso! Por favor, inicia sesión.',
                    icon: 'success',
                    confirmButtonText: 'Aceptar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'login.php';
                    }
                });
              </script>";

        } else {
            $error[] = "Error al registrar: " . $conexion->error;
        }
    }
  }
   ?>
    <div class="container">
      <h1>Registro de Usuario</h1>
      <?php if (!empty($error)): ?>
        <div class="container">
            <ul class="error">
                <?php foreach ($error as $err): ?>
                    <li><?php echo $err; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

      <form id="register-from" method="POST">
        <!-- nombre , apellido , email ,password , telefono , provincia , localidad , direccion , -->
        <label for="nombre">Nombre</label>
        <input type="text" name="nombre" required  value="<?php echo isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : ''; ?>" />

        <label for="apellido">Apellido:</label>
        <input type="text" name="apellido" required value="<?php echo isset($_POST['apellido']) ? htmlspecialchars($_POST['apellido']) : ''; ?>" />

        <label for="email">Email:</label>
        <input type="email" name="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" />

        <label for="password">Contraseña:</label>
        <input type="password" name="password" required />

        <label for="password">Repetir Contraseña:</label>
        <input type="password" name="confirm-password" required />

        <label for="telefono">Teléfono:</label>
        <input type="text" name="telefono" required value="<?php echo isset($_POST['telefono']) ? htmlspecialchars($_POST['telefono']) : ''; ?>" />

        <label for="provincia">Seleccione una provincia:</label>
        <select name="provincia">
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

        <label for="localidad">Localidad:</label>
        <input type="text" name="localidad" required value="<?php echo isset($_POST['localidad']) ? htmlspecialchars($_POST['localidad']) : ''; ?>" />

        <label for="direccion">Direccion:</label>
        <input type="text" name="direccion" required value="<?php echo isset($_POST['direccion']) ? htmlspecialchars($_POST['direccion']) : ''; ?>" />

        <button type="submit" name="enviar" value="Enviar">Registrarse</button>

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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    $dbhost = 'localhost';
    $dbusuario = 'root';
    $dbpassword= '';
    $db= 'suplementos_dynamite';

    date_default_timezone_set('America/Argentina/Buenos_Aires');
    
    $conexion = new mysqli($dbhost, $dbusuario, $dbpassword, $db);

    if ($conexion->connect_error) {
    die("**Error de conexión**: " . $conexion->connect_error);
} else {
    // echo "¡Conexión exitosa a la base de datos!<br>";
    // echo "Servidor: " . $conexion->host_info;
}
    ?>
</body>
</html>
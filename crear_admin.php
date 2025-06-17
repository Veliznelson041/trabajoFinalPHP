<?php
include("conexion.php");
// archivo par crear el admn

$nombre = "Admin";
$apellido = "Principal";
$email = "Alonso@gmail.com";
$password = "admin1234"; 
$rol = "admin";
$telefono = "1234567890";
$provincia = "provin";    
$localidad = "localidad";
$direccion = "Calle";


$password_hash = password_hash($password, PASSWORD_DEFAULT);

$sql ="INSERT INTO usuarios (nombre, apellido, email, password, telefono, provincia, localidad, direccion, rol, fecha_registro) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("sssssssss", $nombre, $apellido, $email, $password_hash, $telefono, $provincia, $localidad, $direccion, $rol);

if ($stmt->execute()) {
    echo "Administrador creado con éxito!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conexion->close();
?>
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../loginHtml/login.php');
    exit;
}

// Verificar rol (empleado, admin o gerente)
$roles_permitidos = ['empleado', 'admin', 'gerente'];
if (!in_array($_SESSION['rol'], $roles_permitidos)) {
    die('Acceso denegado: No tienes permisos para esta sección');
}

// Obtener información actualizada del usuario
require_once 'conexion.php';
$sql = "SELECT * FROM usuarios WHERE usuario_id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $_SESSION['usuario_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    session_destroy();
    header('Location: ../loginHtml/login.php');
    exit;
}

$usuario = $result->fetch_assoc();

// Verificar estado de la cuenta
if ($usuario['estado'] !== 'activo') {
    session_destroy();
    header('Location: ../loginHtml/login.php?error=cuenta_inactiva');
    exit;
}

// Actualizar datos de sesión
$_SESSION['nombre'] = $usuario['nombre'];
$_SESSION['apellido'] = $usuario['apellido'];
$_SESSION['email'] = $usuario['email'];
$_SESSION['rol'] = $usuario['rol'];
?>
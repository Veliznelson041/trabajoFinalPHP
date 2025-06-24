<?php
session_start();
require_once '../../../controlSesion.php';
require_once '../../../conexion.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Verificar si el producto tiene pedidos asociados
    $sql_check = "SELECT COUNT(*) AS total FROM detalle_pedido WHERE producto_id = ?";
    $stmt_check = $conexion->prepare($sql_check);
    $stmt_check->bind_param("i", $id);
    $stmt_check->execute();
    $result = $stmt_check->get_result()->fetch_assoc();
    
    if ($result['total'] > 0) {
        header('Location: listado-productos.php?error=producto_en_pedido');
        exit;
    }
    
    // Eliminar el producto
    $sql = "DELETE FROM productos WHERE producto_id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header('Location: listado-productos.php?success=1');
        exit;
    } else {
        die("Error al eliminar el producto: " . $conexion->error);
    }
}

header('Location: listado-productos.php');
exit;
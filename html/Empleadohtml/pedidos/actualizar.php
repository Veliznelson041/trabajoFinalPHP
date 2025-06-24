<?php
require_once '../../../controlSesion.php';
require_once '../../../conexion.php';

if (isset($_GET['id']) && isset($_GET['estado'])) {
    $id = $_GET['id'];
    $estado = $_GET['estado'];
    $modificado_por = $_SESSION['email'];

    // Validar estado permitido
    $estados_permitidos = ['enviado', 'cancelado'];
    if (!in_array($estado, $estados_permitidos)) {
        die("Estado no válido");
    }

    $sql = "UPDATE pedidos SET 
            estado = ?,
            modificado_por = ?,
            fecha_modificacion = NOW()
            WHERE pedido_id = ?";
    
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssi", $estado, $modificado_por, $id);
    
    if ($stmt->execute()) {
        header('Location: listado.php?success=1');
        exit;
    } else {
        die("Error al actualizar el pedido: " . $conexion->error);
    }
}

header('Location: listado.php');
exit;
?>
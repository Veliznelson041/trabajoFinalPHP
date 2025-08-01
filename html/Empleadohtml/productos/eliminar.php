<?php
require_once '../../../controlSesion.php';
require_once '../../../conexion.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: listado.php?error=ID inválido');
    exit;
}

$producto_id = intval($_GET['id']);

// Verificar si el producto existe
$sql_check = "SELECT * FROM productos WHERE producto_id = ?";
$stmt_check = $conexion->prepare($sql_check);
$stmt_check->bind_param("i", $producto_id);
$stmt_check->execute();
$result = $stmt_check->get_result();

if ($result->num_rows === 0) {
    header('Location: listado.php?error=Producto no encontrado');
    exit;
}

// Verificar si el producto está en algún pedido
$sql_check_pedidos = "SELECT * FROM detalle_pedido WHERE producto_id = ?";
$stmt_pedidos = $conexion->prepare($sql_check_pedidos);
$stmt_pedidos->bind_param("i", $producto_id);
$stmt_pedidos->execute();
$result_pedidos = $stmt_pedidos->get_result();

if ($result_pedidos->num_rows > 0) {
    header('Location: listado.php?error=' . urlencode("No se puede eliminar: el producto está asociado a pedidos"));
    exit;
}

// Eliminar el producto
$sql = "DELETE FROM productos WHERE producto_id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $producto_id);

if ($stmt->execute()) {
    header('Location: listado.php?success=1');
} else {
    header('Location: listado.php?error=' . urlencode("Error al eliminar: " . $conexion->error));
}
exit;
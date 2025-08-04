<?php
require_once 'conexion.php';

// Obtener ID de categoría
$categoria_id = isset($_GET['categoria_id']) ? intval($_GET['categoria_id']) : 0;

// Obtener subcategorías
$sql = "SELECT * FROM subcategorias WHERE categoria_id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $categoria_id);
$stmt->execute();
$result = $stmt->get_result();

$subcategorias = [];
while($row = $result->fetch_assoc()) {
    $subcategorias[] = $row;
}

// Limpiar buffer de salida para evitar contenido previo
while (ob_get_level()) ob_end_clean();

// Devolver en formato JSON
header('Content-Type: application/json');
echo json_encode($subcategorias);
exit; // Asegurar que no se envía nada después
?>
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

// Devolver en formato JSON
header('Content-Type: application/json');
echo json_encode($subcategorias);
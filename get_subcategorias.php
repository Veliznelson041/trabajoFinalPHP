<?php
require_once 'conexion.php';

if (isset($_GET['categoria_id'])) {
    $categoria_id = $_GET['categoria_id'];
    
    $sql = "SELECT * FROM subcategorias WHERE categoria_id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $categoria_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $subcategorias = [];
    while ($row = $result->fetch_assoc()) {
        $subcategorias[] = $row;
    }
    
    header('Content-Type: application/json');
    echo json_encode($subcategorias);
    exit;
}

echo json_encode([]);
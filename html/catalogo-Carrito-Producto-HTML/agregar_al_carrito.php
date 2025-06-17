<?php
session_start();

// Inicializar carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Productos simulados (normalmente vendrían de la BD)
$productos = [
    1 => ['nombre' => 'Proteína Whey', 'precio' => 15000],
    2 => ['nombre' => 'Creatina Monohidratada', 'precio' => 8000],
    3 => ['nombre' => 'Aminoácidos BCAA', 'precio' => 10000],
];

// Agregar al carrito
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    if (isset($productos[$id])) {
        $producto = $productos[$id];
        $ya_existe = false;

        foreach ($_SESSION['carrito'] as &$item) {
            if ($item['producto_id'] === $id) {
                $item['cantidad']++;
                $ya_existe = true;
                break;
            }
        }

        if (!$ya_existe) {
            $_SESSION['carrito'][] = [
                'producto_id' => $id,
                'nombre' => $producto['nombre'],
                'precio' => $producto['precio'],
                'cantidad' => 1
            ];
        }
    }
    header("Location: carrito.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Catálogo de Productos</title>
    <link rel="stylesheet" href="/web2025/trabajoFinalPHP/css/Catalogo-Carrito-Css/carrito.css">
    <style>
        .catalogo {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 30px;
            padding: 40px;
        }
        .producto {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            width: 250px;
            background-color: #fff;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .producto h3 {
            color: var(--color-principal);
            margin-bottom: 10px;
        }
        .producto p {
            margin-bottom: 15px;
        }
        .btn-add {
            background-color: var(--color-principal);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-add:hover {
            background-color: var(--color-hover);
        }
    </style>
</head>
<body>
    <h1 style="text-align: center;">🧪 Catálogo de Productos</h1>
    <div class="catalogo">
        <?php foreach ($productos as $id => $p): ?>
            <div class="producto">
                <h3><?= htmlspecialchars($p['nombre']) ?></h3>
                <p>Precio: $<?= number_format($p['precio'], 2) ?></p>
                <a href="?id=<?= $id ?>"><button class="btn-add">Agregar al carrito</button></a>
            </div>
        <?php endforeach; ?>
    </div>

    <div style="text-align: center;">
        <a href="carrito.php">🛒 Ver Carrito</a>
    </div>
</body>
</html>

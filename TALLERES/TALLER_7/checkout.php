<?php
require 'config_sesion.php';

// Verificar que hay productos en el carrito
if (empty($_SESSION['carrito'])) {
    header("Location: productos.php");
    exit();
}

// Calcular total
$total = 0;
foreach ($_SESSION['carrito'] as $item) {
    $total += $item['precio'] * $item['cantidad'];
}

// Guardar resumen antes de vaciar el carrito
$resumen_compra = $_SESSION['carrito'];

// Vaciar el carrito
$_SESSION['carrito'] = [];


setcookie("usuario", "Jonathan", [
    'expires' => time() + (24 * 3600),  
    'path' => '/',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Strict'
]);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compra Exitosa</title>
    <style>
        .contenedor { 
            max-width: 800px; 
            margin: 50px auto; 
            background: white;
            padding: 40px;
            border-radius: 10px;
            border: black;
        }
        h1 { color: #333; margin-bottom: 30px; }
        table {
            width: 100%;
            margin: auto;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="contenedor">
        <h1>Compra Realizada</h1>
        <h2>Resumen de tu Compra:</h2>
        <table>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($resumen_compra as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['nombre']); ?></td>
                        <td>$<?php echo number_format($item['precio'], 2); ?></td>
                        <td><?php echo $item['cantidad']; ?></td>
                        <td>$<?php echo number_format($item['precio'] * $item['cantidad'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div>
            <div>Total: $<?php echo number_format($total, 2); ?></div>
        </div>
        
        <a href="productos.php">Volver a la Tienda</a>
    </div>
</body>
</html>
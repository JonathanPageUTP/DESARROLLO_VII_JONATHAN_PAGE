<?php
require 'config_sesion.php';

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

$total = 0;
foreach ($_SESSION['carrito'] as $item) {
    $total += $item['precio'] * $item['cantidad'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
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
        <div>
            <h1>Carrito</h1>
        </div>

        <?php if (empty($_SESSION['carrito'])): ?>
                <h2>Tu carrito está vacío</h2>
                <p>Agregue productos.</p>
                <a href="productos.php">Ir a Productos</a>
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio Unitario</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['carrito'] as $id => $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['nombre']); ?></td>
                            <td>$<?php echo number_format($item['precio'], 2); ?></td>
                            <td>
                                    <input type="number" name="cantidad" value="<?php echo $item['cantidad']; ?>" min="1" max="10"  disabled>
                            </td>
                            <td>$<?php echo number_format($item['precio'] * $item['cantidad'], 2); ?></td>
                            <td>
                                <form action="eliminar_del_carrito.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="id_producto" value="<?php echo $id; ?>">
                                    <button type="submit">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div>
                <div>Total: $<?php echo number_format($total, 2); ?></div>
            </div>

            <div>
                <a href="productos.php">Seguir Comprando</a>
                <a href="checkout.php">Proceder al Pago</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
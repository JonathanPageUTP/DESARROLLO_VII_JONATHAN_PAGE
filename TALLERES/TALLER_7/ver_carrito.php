<?php
require 'config_sesion.php';

// Inicializar carrito si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Calcular total
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
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f5f5f5; }
        .contenedor { max-width: 1000px; margin: 0 auto; }
        .header-carrito { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        a { color: #007bff; text-decoration: none; }
        a:hover { text-decoration: underline; }
        table { width: 100%; border-collapse: collapse; background: white; margin-bottom: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #007bff; color: white; }
        .vacio { text-align: center; padding: 40px; background: white; border-radius: 8px; }
        .total-section { background: white; padding: 20px; border-radius: 8px; text-align: right; }
        .total { font-size: 24px; font-weight: bold; color: #28a745; margin-top: 10px; }
        .botones { display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px; }
        button, .btn { padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-continuar { background: #007bff; color: white; }
        .btn-continuar:hover { background: #0056b3; }
        .btn-checkout { background: #28a745; color: white; }
        .btn-checkout:hover { background: #218838; }
        .btn-eliminar { background: #dc3545; color: white; }
        .btn-eliminar:hover { background: #c82333; }
        .cantidad-input { width: 60px; padding: 5px; }
        .mensaje { padding: 10px; background: #d4edda; color: #155724; border-radius: 5px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="contenedor">
        <div class="header-carrito">
            <h1>🛒 Tu Carrito de Compras</h1>
            <a href="productos.php">← Volver a Productos</a>
        </div>

        <?php
        if (isset($_SESSION['mensaje'])) {
            echo "<div class='mensaje'>" . htmlspecialchars($_SESSION['mensaje']) . "</div>";
            unset($_SESSION['mensaje']);
        }
        ?>

        <?php if (empty($_SESSION['carrito'])): ?>
            <div class="vacio">
                <h2>Tu carrito está vacío</h2>
                <p>Agrega algunos productos para comenzar a comprar.</p>
                <a href="productos.php" class="btn btn-continuar">Ir a Productos</a>
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
                                <form action="actualizar_carrito.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="id_producto" value="<?php echo $id; ?>">
                                    <input type="number" name="cantidad" value="<?php echo $item['cantidad']; ?>" min="1" max="10" class="cantidad-input">
                                    <button type="submit" style="padding: 5px 10px;">Actualizar</button>
                                </form>
                            </td>
                            <td>$<?php echo number_format($item['precio'] * $item['cantidad'], 2); ?></td>
                            <td>
                                <form action="eliminar_del_carrito.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="id_producto" value="<?php echo $id; ?>">
                                    <button type="submit" class="btn-eliminar">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="total-section">
                <div>Subtotal: $<?php echo number_format($total, 2); ?></div>
                <div>Envío: $9.99</div>
                <div class="total">Total: $<?php echo number_format($total + 9.99, 2); ?></div>
            </div>

            <div class="botones">
                <a href="productos.php" class="btn btn-continuar">Seguir Comprando</a>
                <a href="checkout.php" class="btn btn-checkout">Proceder al Pago</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
<?php
require 'config_sesion.php';

// Verificar que existe un resumen de compra
if (!isset($_SESSION['resumen_compra'])) {
    header("Location: productos.php");
    exit();
}

$resumen = $_SESSION['resumen_compra'];
$numero_orden = strtoupper(substr(md5(time()), 0, 8));
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compra Confirmada</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .contenedor { max-width: 600px; margin: 40px auto; }
        .confirmacion { background: white; border-radius: 10px; padding: 40px; box-shadow: 0 10px 40px rgba(0,0,0,0.3); text-align: center; }
        .icono-exito { font-size: 60px; margin-bottom: 20px; }
        h1 { color: #28a745; margin: 20px 0; }
        .numero-orden { background: #f0f0f0; padding: 15px; border-radius: 5px; margin: 20px 0; font-family: monospace; font-size: 18px; }
        .detalles { text-align: left; background: #f9f9f9; padding: 20px; border-radius: 5px; margin: 20px 0; }
        .detalles h3 { margin-top: 0; }
        .detalle-fila { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #eee; }
        .detalle-fila:last-child { border-bottom: none; }
        .total-fila { font-weight: bold; font-size: 18px; color: #28a745; padding-top: 10px; margin-top: 10px; border-top: 2px solid #ddd; }
        .botones { display: flex; gap: 10px; margin-top: 30px; }
        a, button { flex: 1; padding: 12px; border: none; border-radius: 5px; text-decoration: none; cursor: pointer; font-size: 16px; }
        .btn-productos { background: #007bff; color: white; }
        .btn-productos:hover { background: #0056b3; }
        .btn-nueva { background: #28a745; color: white; }
        .btn-nueva:hover { background: #218838; }
        .mensaje-cookie { background: #cfe2ff; border: 1px solid #b6d4fe; color: #084298; padding: 12px; border-radius: 5px; margin-bottom: 20px; font-size: 14px; }
    </style>
</head>
<body>
    <div class="contenedor">
        <div class="confirmacion">
            <div class="icono-exito">✅</div>
            <h1>¡Compra Confirmada!</h1>
            <p>Gracias <?php echo htmlspecialchars($resumen['nombre']); ?>, tu pedido ha sido procesado exitosamente.</p>

            <div class="numero-orden">
                Número de Orden: #<?php echo $numero_orden; ?>
            </div>

            <div class="mensaje-cookie">
                📝 Hemos guardado tu nombre en una cookie segura por 24 horas para futuras compras.
            </div>

            <div class="detalles">
                <h3>Resumen del Pedido</h3>
                
                <?php foreach ($resumen['productos'] as $producto): ?>
                    <div class="detalle-fila">
                        <span><?php echo htmlspecialchars($producto['nombre']); ?> × <?php echo $producto['cantidad']; ?></span>
                        <strong>$<?php echo number_format($producto['precio'] * $producto['cantidad'], 2); ?></strong>
                    </div>
                <?php endforeach; ?>

                <div class="detalle-fila">
                    <span>Subtotal</span>
                    <strong>$<?php echo number_format($resumen['subtotal'], 2); ?></strong>
                </div>

                <div class="detalle-fila">
                    <span>Envío</span>
                    <strong>$<?php echo number_format($resumen['envio'], 2); ?></strong>
                </div>

                <div class="detalle-fila total-fila">
                    <span>Total Pagado</span>
                    <span>$<?php echo number_format($resumen['total'], 2); ?></span>
                </div>
            </div>

            <div style="background: #e7f3ff; padding: 15px; border-radius: 5px; margin: 20px 0; font-size: 14px;">
                <strong>📧 Confirmación:</strong> Se ha enviado un email a tu dirección con los detalles del pedido.
                <br><strong>📅 Fecha:</strong> <?php echo $resumen['fecha']; ?>
            </div>

            <div class="botones">
                <a href="productos.php" class="btn-productos">Seguir Comprando</a>
                <a href="ver_carrito.php" class="btn-nueva">Ver Carrito</a>
            </div>
        </div>
    </div>
</body>
</html>
<?php
require 'config_sesion.php';

// Verificar que hay productos en el carrito
if (empty($_SESSION['carrito'])) {
    $_SESSION['mensaje'] = "El carrito está vacío";
    header("Location: ver_carrito.php");
    exit();
}

// Procesar el formulario si se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre_usuario'])) {
    
    // Sanitizar y validar el nombre
    $nombre_usuario = htmlspecialchars(trim($_POST['nombre_usuario']));
    
    if (empty($nombre_usuario) || strlen($nombre_usuario) < 2) {
        $error = "Por favor ingresa un nombre válido";
    } else {
        // Calcular totales
        $subtotal = 0;
        $resumen_compra = [];
        
        foreach ($_SESSION['carrito'] as $id => $item) {
            $subtotal += $item['precio'] * $item['cantidad'];
            $resumen_compra[] = $item;
        }
        
        $envio = 9.99;
        $total = $subtotal + $envio;
        
        // Guardar resumen en sesión
        $_SESSION['resumen_compra'] = [
            'nombre' => $nombre_usuario,
            'productos' => $resumen_compra,
            'subtotal' => $subtotal,
            'envio' => $envio,
            'total' => $total,
            'fecha' => date('d/m/Y H:i:s')
        ];
        
        // Crear cookie segura con el nombre del usuario (24 horas)
        setcookie("nombre_usuario_compra", $nombre_usuario, [
            'expires' => time() + (24 * 3600),  // 24 horas
            'path' => '/',
            'secure' => true,
            'httponly' => true,
            'samesite' => 'Strict'
        ]);
        
        // Vaciar el carrito
        $_SESSION['carrito'] = [];
        
        // Redirigir a página de confirmación
        header("Location: confirmacion.php");
        exit();
    }
}

// Calcular total actual
$subtotal = 0;
foreach ($_SESSION['carrito'] as $item) {
    $subtotal += $item['precio'] * $item['cantidad'];
}
$envio = 9.99;
$total = $subtotal + $envio;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Finalizar Compra</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f5f5f5; }
        .contenedor { max-width: 800px; margin: 0 auto; }
        .checkout-container { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; }
        .formulario, .resumen { background: white; padding: 20px; border-radius: 8px; }
        .formulario h2, .resumen h2 { margin-top: 0; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="email"] { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        input[type="text"]:focus, input[type="email"]:focus { outline: none; border-color: #007bff; }
        button { background: #28a745; color: white; padding: 12px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; width: 100%; }
        button:hover { background: #218838; }
        a { color: #007bff; text-decoration: none; }
        .resumen-item { padding: 10px 0; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; }
        .resumen-item:last-child { border-bottom: none; }
        .total-linea { font-size: 18px; font-weight: bold; color: #28a745; margin-top: 15px; padding-top: 15px; border-top: 2px solid #ddd; }
        .error { color: #dc3545; background: #f8d7da; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
        @media (max-width: 768px) {
            .checkout-container { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="contenedor">
        <h1>✅ Finalizar Compra</h1>
        <a href="ver_carrito.php">← Volver al Carrito</a>

        <div class="checkout-container">
            <!-- Formulario -->
            <div class="formulario">
                <h2>Datos de Envío</h2>
                
                <?php if (isset($error)): ?>
                    <div class="error"><?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-group">
                        <label for="nombre_usuario">Nombre Completo:</label>
                        <input type="text" id="nombre_usuario" name="nombre_usuario" required placeholder="Tu nombre">
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" placeholder="tu@email.com">
                    </div>

                    <div class="form-group">
                        <label>Método de Pago:</label>
                        <input type="text" value="Tarjeta de Crédito" disabled style="background: #f0f0f0; cursor: not-allowed;">
                    </div>

                    <button type="submit">Confirmar Compra</button>
                </form>
            </div>

            <!-- Resumen -->
            <div class="resumen">
                <h2>Resumen del Pedido</h2>
                
                <?php foreach ($_SESSION['carrito'] as $item): ?>
                    <div class="resumen-item">
                        <span><?php echo htmlspecialchars($item['nombre']); ?> × <?php echo $item['cantidad']; ?></span>
                        <strong>$<?php echo number_format($item['precio'] * $item['cantidad'], 2); ?></strong>
                    </div>
                <?php endforeach; ?>

                <div class="resumen-item">
                    <span>Subtotal</span>
                    <strong>$<?php echo number_format($subtotal, 2); ?></strong>
                </div>

                <div class="resumen-item">
                    <span>Envío</span>
                    <strong>$<?php echo number_format($envio, 2); ?></strong>
                </div>

                <div class="total-linea">
                    Total: $<?php echo number_format($total, 2); ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
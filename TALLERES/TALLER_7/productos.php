<?php
require 'config_sesion.php';

// Array de productos (en producción sería una base de datos)
$productos = [
    1 => ['nombre' => 'Laptop', 'precio' => 899.99, 'descripcion' => 'Laptop potente para trabajo'],
    2 => ['nombre' => 'Mouse', 'precio' => 29.99, 'descripcion' => 'Mouse inalámbrico ergonómico'],
    3 => ['nombre' => 'Teclado', 'precio' => 79.99, 'descripcion' => 'Teclado mecánico RGB'],
    4 => ['nombre' => 'Monitor 4K', 'precio' => 399.99, 'descripcion' => 'Monitor 4K de 27 pulgadas'],
    5 => ['nombre' => 'Auriculares', 'precio' => 149.99, 'descripcion' => 'Auriculares con cancelación de ruido']
];

// Inicializar carrito en sesión si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda - Carrito de Compras</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f5f5f5; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .carrito-link { background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; }
        .carrito-link:hover { background: #0056b3; }
        .productos-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
        .producto-card { background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .producto-card h3 { margin-top: 0; color: #333; }
        .precio { font-size: 24px; color: #28a745; font-weight: bold; margin: 10px 0; }
        .descripcion { color: #666; font-size: 14px; margin-bottom: 15px; }
        form { display: inline-block; }
        input[type="number"] { width: 60px; padding: 5px; }
        button { background: #28a745; color: white; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer; }
        button:hover { background: #218838; }
        .mensaje { padding: 10px; background: #d4edda; color: #155724; border-radius: 5px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🛒 Tienda en Línea</h1>
        <a href="ver_carrito.php" class="carrito-link">Ver Carrito (<?php echo count($_SESSION['carrito']); ?> items)</a>
    </div>

    <?php
    // Mostrar mensaje si se agregó un producto exitosamente
    if (isset($_SESSION['mensaje'])) {
        echo "<div class='mensaje'>" . htmlspecialchars($_SESSION['mensaje']) . "</div>";
        unset($_SESSION['mensaje']);
    }
    ?>

    <div class="productos-grid">
        <?php foreach ($productos as $id => $producto): ?>
            <div class="producto-card">
                <h3><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                <p class="descripcion"><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                <div class="precio">$<?php echo number_format($producto['precio'], 2); ?></div>
                
                <form action="agregar_al_carrito.php" method="POST">
                    <input type="hidden" name="id_producto" value="<?php echo $id; ?>">
                    <input type="hidden" name="nombre" value="<?php echo htmlspecialchars($producto['nombre']); ?>">
                    <input type="hidden" name="precio" value="<?php echo $producto['precio']; ?>">
                    
                    <label for="cantidad_<?php echo $id; ?>">Cantidad:</label>
                    <input type="number" id="cantidad_<?php echo $id; ?>" name="cantidad" value="1" min="1" max="10">
                    <button type="submit">Agregar al Carrito</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
<?php
require 'config_sesion.php';

$products = [
    1 => ['nombre'=>'IPhone 17', 'precio' => '1400'],
    2 => ['nombre'=>'IPhone 18', 'precio' => '2000'],
    3 => ['nombre'=>'Xiaomi 17 PRO MAX', 'precio' => '1500'],
    4 => ['nombre'=>'Nokia', 'precio' => '5000'],
    5 => ['nombre'=>'Sony Experian', 'precio' => '15000']
];
// Inicializar carrito en sesión si no existe
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .productos {
            background: white;
            border-radius: 15px;
            padding: 25px;
            border: 2px solid black;
            margin: 4rem;
        }
    </style>
</head>
<body>

    <div>
        <h1> Tienda en Línea</h1>
        <?php if (isset($_COOKIE['usuario'])): ?>
            <p>Bienvenido, <?php echo htmlspecialchars($_COOKIE['usuario']); ?>!</p>
        <?php endif; ?>
        <a href="ver_carrito.php">Ver Carrito (<?php echo count($_SESSION['carrito']); ?> items)</a>
    </div>
    <div>
            <?php foreach($products as $id => $pro)
            { ?>
               <div  class="productos">
                    <p><?php  echo"Nombre: ". $pro['nombre'];  ?></p>
                    <p><?php  echo"Precio: " . $pro['precio']; ?></p>
                    
                    <form action="agregar_al_carrito.php" method="POST">
                    <input type="hidden" name="id_producto" value="<?php echo $id; ?>">
                    <input type="hidden" name="nombre" value="<?php echo $pro['nombre']; ?>">
                    <input type="hidden" name="precio" value="<?php echo $pro['precio']; ?>">
                    
                    <label for="cantidad_<?php echo $id; ?>">Cantidad:</label>
                    <input type="number" id="cantidad_<?php echo $id; ?>" name="cantidad" value="1" min="1" max="10">
                    <button type="submit">Agregar al Carrito</button>
            </form>
                    <br>
                    
               </div>
           <?php }?>
    </div>
</body>
</html>
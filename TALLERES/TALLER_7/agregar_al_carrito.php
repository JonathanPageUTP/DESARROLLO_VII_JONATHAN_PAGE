<?php 
session_start();

if($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $id_pro = $_POST['id_producto'];
    $nombre = $_POST['nombre'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];

    if(!isset($_SESSION['carrito'])){
        $_SESSION['carrito'] = [];
    }

    $_SESSION['carrito'][$id_pro] = [
    'nombre' => $nombre,
    'precio' => $precio,
    'cantidad' => $cantidad
    ];
}

header("Location: productos.php");
exit();
?>
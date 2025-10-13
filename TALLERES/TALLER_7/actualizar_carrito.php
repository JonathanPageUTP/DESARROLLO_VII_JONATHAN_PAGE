<?php
require 'config_sesion.php';

// Validar datos POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_producto'], $_POST['cantidad'])) {
    
    $id_producto = (int)$_POST['id_producto'];
    $cantidad = (int)$_POST['cantidad'];
    
    // Validaciones
    if ($cantidad < 1 || $cantidad > 10) {
        $_SESSION['mensaje'] = "Cantidad inválida";
    } elseif (!isset($_SESSION['carrito'][$id_producto])) {
        $_SESSION['mensaje'] = "Producto no encontrado en el carrito";
    } else {
        // Actualizar cantidad
        $_SESSION['carrito'][$id_producto]['cantidad'] = $cantidad;
        $_SESSION['mensaje'] = "Carrito actualizado";
    }
} else {
    $_SESSION['mensaje'] = "Error al actualizar el carrito";
}

// Redirigir de vuelta al carrito
header("Location: ver_carrito.php");
exit();
?>
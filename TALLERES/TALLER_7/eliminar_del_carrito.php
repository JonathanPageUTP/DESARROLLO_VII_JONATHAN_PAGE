<?php
require 'config_sesion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_producto'])) {
    
    $id_producto = (int)$_POST['id_producto'];
    
    if (isset($_SESSION['carrito'][$id_producto])) {
        $nombre = htmlspecialchars($_SESSION['carrito'][$id_producto]['nombre']);
        
        if ($_SESSION['carrito'][$id_producto]['cantidad'] > 1) {
            $_SESSION['carrito'][$id_producto]['cantidad']--;
            $_SESSION['mensaje'] = "Se eliminó 1 unidad de " . $nombre;
        } else {
            unset($_SESSION['carrito'][$id_producto]);
            $_SESSION['mensaje'] = "Correcto! " . $nombre . " eliminado del carrito";
        }
    } else {
        $_SESSION['mensaje'] = "Producto no encontrado en el carrito";
    }
} else {
    $_SESSION['mensaje'] = "Error al eliminar el producto";
}

header("Location: ver_carrito.php");
exit();
?>
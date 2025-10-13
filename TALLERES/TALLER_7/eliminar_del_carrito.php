<?php
require 'config_sesion.php';

// Validar datos POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_producto'])) {
    
    $id_producto = (int)$_POST['id_producto'];
    
    // Verificar que el producto existe en el carrito
    if (isset($_SESSION['carrito'][$id_producto])) {
        $nombre = htmlspecialchars($_SESSION['carrito'][$id_producto]['nombre']);
        
        // Eliminar el producto
        unset($_SESSION['carrito'][$id_producto]);
        
        $_SESSION['mensaje'] = "Correcto!" . $nombre . " eliminado del carrito";
    } else {
        $_SESSION['mensaje'] = "Producto no encontrado en el carrito";
    }
} else {
    $_SESSION['mensaje'] = "Error al eliminar el producto";
}

// Redirigir de vuelta al carrito
header("Location: ver_carrito.php");
exit();
?>
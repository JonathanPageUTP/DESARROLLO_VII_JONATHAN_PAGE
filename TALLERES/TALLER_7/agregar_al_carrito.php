<?php
require 'config_sesion.php';

// Validar que los datos POST existan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_producto'], $_POST['cantidad'], $_POST['precio'])) {
    
    // Sanitizar y validar datos
    $id_producto = (int)$_POST['id_producto'];
    $cantidad = (int)$_POST['cantidad'];
    $precio = (float)$_POST['precio'];
    $nombre = htmlspecialchars($_POST['nombre']);
    
    // Validaciones
    if ($cantidad < 1 || $cantidad > 10) {
        $_SESSION['mensaje'] = "Cantidad inválida. Debe estar entre 1 y 10.";
        header("Location: productos.php");
        exit();
    }
    
    if ($id_producto < 1 || $precio < 0) {
        $_SESSION['mensaje'] = "Datos del producto inválidos.";
        header("Location: productos.php");
        exit();
    }
    
    // Inicializar carrito si no existe
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }
    
    // Verificar si el producto ya está en el carrito
    if (isset($_SESSION['carrito'][$id_producto])) {
        // Aumentar cantidad
        $_SESSION['carrito'][$id_producto]['cantidad'] += $cantidad;
        $_SESSION['mensaje'] = "Cantidad actualizada para " . $nombre;
    } else {
        // Agregar nuevo producto al carrito
        $_SESSION['carrito'][$id_producto] = [
            'nombre' => $nombre,
            'precio' => $precio,
            'cantidad' => $cantidad
        ];
        $_SESSION['mensaje'] = "Correcto!" . $nombre . " agregado al carrito";
    }
    
} else {
    $_SESSION['mensaje'] = "Error al agregar el producto";
}

// Redirigir de vuelta a productos
header("Location: productos.php");
exit();
?>
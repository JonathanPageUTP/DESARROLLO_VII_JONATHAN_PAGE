<?php

$nombre_archivo = 'inventario.json';

function leerInventario($archivo) {
    if (!file_exists($archivo)) {
        echo "Error: El archivo '{$archivo}' no existe.\n";
        return null;
    }
    $contenido = file_get_contents($archivo);
    return json_decode($contenido, true);
}

function ordenarInventario($inventario) {
    usort($inventario, function($a, $b) {
        return strcmp($a['nombre'], $b['nombre']);
    });
    return $inventario;
}

function mostrarResumenInventario($inventario) {
    echo "--- Inventario ---\n";
    foreach ($inventario as $producto) {
        echo "Producto: {$producto['nombre']}, Precio: {$producto['precio']}, Cantidad: {$producto['cantidad']}\n";
    }
}

function calcularValorTotal($inventario) {
    $valores_productos = array_map(function($producto) {
        return $producto['precio'] * $producto['cantidad'];
    }, $inventario);
    return array_sum($valores_productos);
}

function generarInformeStockBajo($inventario) {
    return array_filter($inventario, function($producto) {
        return $producto['cantidad'] < 5;
    });
}

$inventario = leerInventario($nombre_archivo);

if ($inventario) {
    echo "Sistema de Gestión de Inventario Iniciado.\n\n";
    
    $inventario_ordenado = ordenarInventario($inventario);
    mostrarResumenInventario($inventario_ordenado);
    echo "\n";
    
    $valor_total = calcularValorTotal($inventario);
    echo "Valor Total del Inventario: {$valor_total}\n\n";
    
    $stock_bajo = generarInformeStockBajo($inventario_ordenado);
    if (!empty($stock_bajo)) {
        echo "Informe de Productos con Stock Bajo (< 5 unidades):\n";
        foreach ($stock_bajo as $producto) {
            echo " - {$producto['nombre']}: {$producto['cantidad']} unidades\n";
        }
    } else {
        echo "No hay productos con stock bajo.\n";
    }
}
?>
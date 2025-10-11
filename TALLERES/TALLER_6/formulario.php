<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resumen de Registros</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }
        h1 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .registro-card {
            background-color: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .registro-card h3 {
            margin-top: 0;
            color: #4CAF50;
        }
        .registro-card img {
            max-width: 150px;
            border-radius: 5px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .no-registros {
            padding: 20px;
            background-color: white;
            text-align: center;
            color: #666;
        }
        .stats {
            background-color: white;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .stats span {
            font-weight: bold;
            color: #4CAF50;
        }
    </style>
</head>
<body>
    <h1>Resumen de Registros</h1>
    <a href="formulario.php" class="btn">Volver al Formulario</a>
    
    <?php
    $archivoJson = 'registros.json';
    $registros = [];

    // Leer registros del archivo JSON
    if (file_exists($archivoJson)) {
        $contenido = file_get_contents($archivoJson);
        $registros = json_decode($contenido, true) ?? [];
    }

    if (empty($registros)) {
        echo "<div class='no-registros'>";
        echo "<p>No hay registros disponibles.</p>";
        echo "</div>";
    } else {
        // Mostrar estadísticas
        echo "<div class='stats'>";
        echo "<h3>Estadísticas</h3>";
        echo "<p>Total de registros: <span>" . count($registros) . "</span></p>";
        
        // Calcular edad promedio
        $edades = array_column($registros, 'edad');
        $edadPromedio = array_sum($edades) / count($edades);
        echo "<p>Edad promedio: <span>" . round($edadPromedio, 1) . " años</span></p>";
        
        // Contar géneros
        $generos = array_count_values(array_column($registros, 'genero'));
        echo "<p>Distribución por género:</p>";
        echo "<ul>";
        foreach ($generos as $genero => $cantidad) {
            echo "<li>" . ucfirst($genero) . ": <span>$cantidad</span></li>";
        }
        echo "</ul>";
        echo "</div>";

        // Mostrar cada registro
        foreach ($registros as $index => $registro) {
            echo "<div class='registro-card'>";
            echo "<h3>Registro #" . ($index + 1) . "</h3>";
            
            echo "<p><strong>Nombre:</strong> " . htmlspecialchars($registro['nombre'] ?? 'N/A') . "</p>";
            echo "<p><strong>Email:</strong> " . htmlspecialchars($registro['email'] ?? 'N/A') . "</p>";
            echo "<p><strong>Fecha de Nacimiento:</strong> " . htmlspecialchars($registro['fecha_nacimiento'] ?? 'N/A') . "</p>";
            echo "<p><strong>Edad:</strong> " . htmlspecialchars($registro['edad'] ?? 'N/A') . " años</p>";
            echo "<p><strong>Género:</strong> " . htmlspecialchars($registro['genero'] ?? 'N/A') . "</p>";
            
            if (isset($registro['sitio_web']) && !empty($registro['sitio_web'])) {
                echo "<p><strong>Sitio Web:</strong> <a href='" . htmlspecialchars($registro['sitio_web']) . "' target='_blank'>" . htmlspecialchars($registro['sitio_web']) . "</a></p>";
            }
            
            if (isset($registro['intereses']) && is_array($registro['intereses'])) {
                echo "<p><strong>Intereses:</strong> " . implode(", ", array_map('htmlspecialchars', $registro['intereses'])) . "</p>";
            }
            
            if (isset($registro['comentarios']) && !empty($registro['comentarios'])) {
                echo "<p><strong>Comentarios:</strong> " . htmlspecialchars($registro['comentarios']) . "</p>";
            }
            
            if (isset($registro['foto_perfil']) && file_exists($registro['foto_perfil'])) {
                echo "<p><strong>Foto de Perfil:</strong><br>";
                echo "<img src='" . htmlspecialchars($registro['foto_perfil']) . "' alt='Foto de perfil'></p>";
            }
            
            if (isset($registro['fecha_registro'])) {
                echo "<p><strong>Fecha de Registro:</strong> " . htmlspecialchars($registro['fecha_registro']) . "</p>";
            }
            
            echo "</div>";
        }
    }
    ?>

    

</body>
</html>
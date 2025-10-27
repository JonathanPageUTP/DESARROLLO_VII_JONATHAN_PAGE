<?php
require_once "config_mysqli.php";

// 1. Mostrar las últimas 5 publicaciones con el nombre del autor y la fecha
$sql = "SELECT p.titulo, p.contenido, u.nombre as autor, p.fecha_publicacion 
        FROM publicaciones p 
        INNER JOIN usuarios u ON p.usuario_id = u.id 
        ORDER BY p.fecha_publicacion DESC 
        LIMIT 5";

$result = mysqli_query($conn, $sql);

if ($result) {
    echo "<h3>Últimas 5 publicaciones:</h3>";
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "Título: " . $row['titulo'] . "<br>";
            echo "Autor: " . $row['autor'] . "<br>";
            echo "Fecha: " . $row['fecha_publicacion'] . "<br><br>";
        }
    } else {
        echo "No se encontraron publicaciones.<br>";
    }
    mysqli_free_result($result);
} else {
    echo "Error: " . mysqli_error($conn);
}

// 2. Listar los usuarios que no han realizado ninguna publicación
$sql = "SELECT u.id, u.nombre, u.email 
        FROM usuarios u 
        LEFT JOIN publicaciones p ON u.id = p.usuario_id 
        WHERE p.id IS NULL";

$result = mysqli_query($conn, $sql);

if ($result) {
    echo "<h3>Usuarios sin publicaciones:</h3>";
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "Nombre: " . $row['nombre'] . ", Email: " . $row['email'] . "<br>";
        }
    } else {
        echo "Todos los usuarios tienen al menos una publicación.<br>";
    }
    mysqli_free_result($result);
} else {
    echo "Error: " . mysqli_error($conn);
}

// 3. Calcular el promedio de publicaciones por usuario
$sql = "SELECT AVG(num_publicaciones) as promedio 
        FROM (
            SELECT COUNT(p.id) as num_publicaciones 
            FROM usuarios u 
            LEFT JOIN publicaciones p ON u.id = p.usuario_id 
            GROUP BY u.id
        ) as conteos";

$result = mysqli_query($conn, $sql);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    echo "<h3>Promedio de publicaciones por usuario:</h3>";
    echo "Promedio: " . number_format($row['promedio'], 2) . " publicaciones<br>";
    mysqli_free_result($result);
} else {
    echo "Error: " . mysqli_error($conn);
}

// 4. Encontrar la publicación más reciente de cada usuario
$sql = "SELECT u.nombre, p.titulo, p.fecha_publicacion 
        FROM publicaciones p 
        INNER JOIN usuarios u ON p.usuario_id = u.id 
        WHERE p.fecha_publicacion = (
            SELECT MAX(p2.fecha_publicacion) 
            FROM publicaciones p2 
            WHERE p2.usuario_id = u.id
        )
        ORDER BY u.nombre";

$result = mysqli_query($conn, $sql);

if ($result) {
    echo "<h3>Publicación más reciente de cada usuario:</h3>";
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "Usuario: " . $row['nombre'] . "<br>";
            echo "Título: " . $row['titulo'] . "<br>";
            echo "Fecha: " . $row['fecha_publicacion'] . "<br><br>";
        }
    } else {
        echo "No se encontraron publicaciones.<br>";
    }
    mysqli_free_result($result);
} else {
    echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
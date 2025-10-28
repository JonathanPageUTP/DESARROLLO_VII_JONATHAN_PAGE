<?php
require_once "config.php";

function registrar_error($archivo, $mensaje) {
    $log = "TALLER_8/errores.log";
    $contenido = date("Y-m-d H:i:s") . " | " . $archivo . " | " . $mensaje . "\n";
    file_put_contents($log, $contenido, FILE_APPEND);
}

// Añadir nuevo libro
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion']) && $_POST['accion'] == 'crear'){
    try {
        $titulo = mysqli_real_escape_string($conn, $_POST['titulo']);
        $autor = mysqli_real_escape_string($conn, $_POST['autor']);
        $isbn = mysqli_real_escape_string($conn, $_POST['isbn']);
        $año = mysqli_real_escape_string($conn, $_POST['año']);
        $cantidad = mysqli_real_escape_string($conn, $_POST['cantidad']);
        
        $sql = "INSERT INTO libros (titulo, autor, isbn, año_publicacion, cantidad_disponible) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssii", $titulo, $autor, $isbn, $año, $cantidad);
        
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error al crear libro: " . mysqli_error($conn));
        }
        echo "<p style='color:green;'>Libro creado con éxito.</p>";
    } catch (Exception $e) {
        echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
        registrar_error("libros.php", $e->getMessage());
    }
}

// Listar todos los libros con paginación
$por_pagina = 5;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina - 1) * $por_pagina;

try {
    $sql = "SELECT * FROM libros LIMIT $inicio, $por_pagina";
    $result = mysqli_query($conn, $sql);
    
    if (!$result) {
        throw new Exception("Error en la consulta: " . mysqli_error($conn));
    }
    
    echo "<h3>Libros</h3>";
    echo "<table><tr><th>ID</th><th>Título</th><th>Autor</th><th>ISBN</th><th>Año</th><th>Disponibles</th></tr>";
    
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr><td>" . $row['id'] . "</td><td>" . $row['titulo'] . "</td><td>" . $row['autor'] . "</td><td>" . $row['isbn'] . "</td><td>" . $row['año_publicacion'] . "</td><td>" . $row['cantidad_disponible'] . "</td></tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No hay libros registrados.</td></tr>";
    }
    echo "</table>";
    
    $sql_total = "SELECT COUNT(*) as total FROM libros";
    $result_total = mysqli_query($conn, $sql_total);
    $row_total = mysqli_fetch_assoc($result_total);
    $total_paginas = ceil($row_total['total'] / $por_pagina);
    
    echo "<p>Página $pagina de $total_paginas | ";
    for ($i = 1; $i <= $total_paginas; $i++) {
        echo "<a href='?pagina=$i'>$i</a> ";
    }
    echo "</p>";
    
    mysqli_free_result($result);
} catch (Exception $e) {
    echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
    registrar_error("libros.php", $e->getMessage());
}

mysqli_close($conn);
?>

<h3>Agregar nuevo libro</h3>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <input type="hidden" name="accion" value="crear">
    <div><label>Título:</label><input type="text" name="titulo" required></div>
    <div><label>Autor:</label><input type="text" name="autor" required></div>
    <div><label>ISBN:</label><input type="text" name="isbn" required></div>
    <div><label>Año:</label><input type="number" name="año" required></div>
    <div><label>Cantidad:</label><input type="number" name="cantidad" required></div>
    <input type="submit" value="Crear Libro">
</form>
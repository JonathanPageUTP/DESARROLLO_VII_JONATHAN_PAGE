<?php
require_once "config.php";

function registrar_error($archivo, $mensaje) {
    $log = "errores.log";
    $contenido = date("Y-m-d H:i:s") . " | " . $archivo . " | " . $mensaje . "\n";
    file_put_contents($log, $contenido, FILE_APPEND);
}

// Validar conexión
if (!isset($conn) || $conn === false) {
    die("Error: No hay conexión a la base de datos.");
}

// Registrar préstamo (con transacción)
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion']) && $_POST['accion'] == 'crear'){
    mysqli_begin_transaction($conn);
    try {
        $usuario_id = (int)$_POST['usuario_id'];
        $libro_id = (int)$_POST['libro_id'];
        
        // Verificar si hay disponibilidad
        $sql = "SELECT cantidad_disponible FROM libros WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $libro_id);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error: " . mysqli_error($conn));
        }
        $result = mysqli_stmt_get_result($stmt);
        $libro = mysqli_fetch_assoc($result);
        
        if (!$libro || $libro['cantidad_disponible'] <= 0) {
            throw new Exception("No hay libros disponibles.");
        }
        
        // Insertar préstamo
        $sql = "INSERT INTO prestamos (usuario_id, libro_id, fecha_prestamo) VALUES (?, ?, NOW())";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $usuario_id, $libro_id);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error: " . mysqli_error($conn));
        }
        
        // Actualizar cantidad disponible
        $sql = "UPDATE libros SET cantidad_disponible = cantidad_disponible - 1 WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $libro_id);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error: " . mysqli_error($conn));
        }
        
        mysqli_commit($conn);
        echo "<p style='color:green;'>Préstamo registrado con éxito.</p>";
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
        registrar_error("prestamos.php", $e->getMessage());
    }
}

// Registrar devolución (con transacción)
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion']) && $_POST['accion'] == 'devolver'){
    mysqli_begin_transaction($conn);
    try {
        $prestamo_id = (int)$_POST['prestamo_id'];
        
        // Obtener libro_id del préstamo
        $sql = "SELECT libro_id FROM prestamos WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $prestamo_id);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error: " . mysqli_error($conn));
        }
        $result = mysqli_stmt_get_result($stmt);
        $prestamo = mysqli_fetch_assoc($result);
        
        if (!$prestamo) {
            throw new Exception("Préstamo no encontrado.");
        }
        
        // Actualizar préstamo
        $sql = "UPDATE prestamos SET fecha_devolucion = NOW() WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $prestamo_id);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error: " . mysqli_error($conn));
        }
        
        // Actualizar cantidad disponible
        $sql = "UPDATE libros SET cantidad_disponible = cantidad_disponible + 1 WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $prestamo['libro_id']);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error: " . mysqli_error($conn));
        }
        
        mysqli_commit($conn);
        echo "<p style='color:green;'>Devolución registrada con éxito.</p>";
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
        registrar_error("prestamos.php", $e->getMessage());
    }
}

// Listar préstamos activos con paginación
$por_pagina = 5;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina - 1) * $por_pagina;

try {
    $sql = "SELECT p.id, u.nombre, l.titulo, p.fecha_prestamo, p.fecha_devolucion 
            FROM prestamos p 
            INNER JOIN usuarios u ON p.usuario_id = u.id 
            INNER JOIN libros l ON p.libro_id = l.id 
            WHERE p.fecha_devolucion IS NULL 
            LIMIT $inicio, $por_pagina";
    $result = mysqli_query($conn, $sql);
    
    if (!$result) {
        throw new Exception("Error: " . mysqli_error($conn));
    }
    
    echo "<h3>Préstamos Activos</h3>";
    echo "<table border='1'><tr><th>ID</th><th>Usuario</th><th>Libro</th><th>Fecha Préstamo</th><th>Acción</th></tr>";
    
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr><td>" . $row['id'] . "</td><td>" . $row['nombre'] . "</td><td>" . $row['titulo'] . "</td><td>" . $row['fecha_prestamo'] . "</td><td>
            <form method='post' style='display:inline;'>
                <input type='hidden' name='accion' value='devolver'>
                <input type='hidden' name='prestamo_id' value='" . $row['id'] . "'>
                <input type='submit' value='Devolver'>
            </form>
            </td></tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No hay préstamos activos.</td></tr>";
    }
    echo "</table>";
    
    mysqli_free_result($result);
} catch (Exception $e) {
    echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
    registrar_error("prestamos.php", $e->getMessage());
}
?>

<h3>Registrar Préstamo</h3>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <input type="hidden" name="accion" value="crear">
    <div><label>Usuario ID:</label><input type="number" name="usuario_id" required></div>
    <div><label>Libro ID:</label><input type="number" name="libro_id" required></div>
    <input type="submit" value="Registrar Préstamo">
</form>

<?php
mysqli_close($conn);
?>
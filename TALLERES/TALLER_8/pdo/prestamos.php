<?php
require_once "config.php";

function registrar_error($archivo, $mensaje) {
    $log = "../errores.log";
    $contenido = date("Y-m-d H:i:s") . " | " . $archivo . " | " . $mensaje . "\n";
    file_put_contents($log, $contenido, FILE_APPEND);
}

// Registrar préstamo (con transacción)
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion']) && $_POST['accion'] == 'crear'){
    try {
        $pdo->beginTransaction();
        
        $usuario_id = (int)$_POST['usuario_id'];
        $libro_id = (int)$_POST['libro_id'];
        
        // Verificar disponibilidad
        $sql = "SELECT cantidad_disponible FROM libros WHERE id = :libro_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':libro_id' => $libro_id]);
        $libro = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($libro['cantidad_disponible'] <= 0) {
            throw new Exception("No hay libros disponibles.");
        }
        
        // Insertar préstamo
        $sql = "INSERT INTO prestamos (usuario_id, libro_id, fecha_prestamo) VALUES (:usuario_id, :libro_id, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':usuario_id' => $usuario_id, ':libro_id' => $libro_id]);
        
        // Actualizar cantidad
        $sql = "UPDATE libros SET cantidad_disponible = cantidad_disponible - 1 WHERE id = :libro_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':libro_id' => $libro_id]);
        
        $pdo->commit();
        echo "<p style='color:green;'>Préstamo registrado con éxito.</p>";
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
        registrar_error("prestamos.php", $e->getMessage());
    }
}

// Registrar devolución (con transacción)
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion']) && $_POST['accion'] == 'devolver'){
    try {
        $pdo->beginTransaction();
        
        $prestamo_id = (int)$_POST['prestamo_id'];
        
        // Obtener libro_id
        $sql = "SELECT libro_id FROM prestamos WHERE id = :prestamo_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':prestamo_id' => $prestamo_id]);
        $prestamo = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Actualizar préstamo
        $sql = "UPDATE prestamos SET fecha_devolucion = NOW() WHERE id = :prestamo_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':prestamo_id' => $prestamo_id]);
        
        // Actualizar cantidad disponible
        $sql = "UPDATE libros SET cantidad_disponible = cantidad_disponible + 1 WHERE id = :libro_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':libro_id' => $prestamo['libro_id']]);
        
        $pdo->commit();
        echo "<p style='color:green;'>Devolución registrada con éxito.</p>";
    } catch (Exception $e) {
        $pdo->rollBack();
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
            LIMIT :inicio, :por_pagina";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':inicio', $inicio, PDO::PARAM_INT);
    $stmt->bindParam(':por_pagina', $por_pagina, PDO::PARAM_INT);
    $stmt->execute();
    
    echo "<h3>Préstamos Activos</h3>";
    echo "<table border='1'><tr><th>ID</th><th>Usuario</th><th>Libro</th><th>Fecha Préstamo</th><th>Acción</th></tr>";
    
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
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
    
} catch(PDOException $e) {
    echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
    registrar_error("prestamos.php", $e->getMessage());
}

$pdo = null;
?>

<h3>Registrar Préstamo</h3>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <input type="hidden" name="accion" value="crear">
    <div><label>Usuario ID:</label><input type="number" name="usuario_id" required></div>
    <div><label>Libro ID:</label><input type="number" name="libro_id" required></div>
    <input type="submit" value="Registrar Préstamo">
</form>
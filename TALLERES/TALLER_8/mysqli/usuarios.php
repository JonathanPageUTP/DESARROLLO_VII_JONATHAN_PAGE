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

// Registrar nuevo usuario
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion']) && $_POST['accion'] == 'crear'){
    try {
        $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $contraseña = mysqli_real_escape_string($conn, $_POST['contraseña']);
        $contraseña_hash = password_hash($contraseña, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO usuarios (nombre, email, contraseña) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $nombre, $email, $contraseña_hash);
        
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error al crear usuario: " . mysqli_error($conn));
        }
        echo "<p style='color:green;'>Usuario creado con éxito.</p>";
        mysqli_stmt_close($stmt);
    } catch (Exception $e) {
        echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
        registrar_error("usuarios.php", $e->getMessage());
    }
}

// Listar todos los usuarios con paginación
$por_pagina = 5;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina - 1) * $por_pagina;

try {
    $sql = "SELECT id, nombre, email FROM usuarios LIMIT $inicio, $por_pagina";
    $result = mysqli_query($conn, $sql);
    
    if (!$result) {
        throw new Exception("Error en la consulta: " . mysqli_error($conn));
    }
    
    echo "<h3>Usuarios</h3>";
    echo "<table border='1'><tr><th>ID</th><th>Nombre</th><th>Email</th></tr>";
    
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr><td>" . $row['id'] . "</td><td>" . $row['nombre'] . "</td><td>" . $row['email'] . "</td></tr>";
        }
    } else {
        echo "<tr><td colspan='3'>No hay usuarios registrados.</td></tr>";
    }
    echo "</table>";
    
    $sql_total = "SELECT COUNT(*) as total FROM usuarios";
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
    registrar_error("usuarios.php", $e->getMessage());
}
?>

<h3>Registrar nuevo usuario</h3>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <input type="hidden" name="accion" value="crear">
    <div><label>Nombre:</label><input type="text" name="nombre" required></div>
    <div><label>Email:</label><input type="email" name="email" required></div>
    <div><label>Contraseña:</label><input type="password" name="contraseña" required></div>
    <input type="submit" value="Registrar Usuario">
</form>

<?php
mysqli_close($conn);
?>
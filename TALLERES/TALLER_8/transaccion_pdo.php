<?php
require_once "config_pdo.php";

try {
    $pdo->beginTransaction();

    // Insertar un nuevo usuario
    $sql = "INSERT INTO usuarios (nombre, email) VALUES (:nombre, :email)";
    $stmt = $pdo->prepare($sql);
    
    if (!$stmt->execute([':nombre' => 'Nuevo Usuario', ':email' => 'nuevo@example.com'])) {
        throw new Exception("Error en la consulta: " . $stmt->errorInfo()[2]);
    }
    $usuario_id = $pdo->lastInsertId();

    // Insertar una publicación para ese usuario
    $sql = "INSERT INTO publicaciones (usuario_id, titulo, contenido) VALUES (:usuario_id, :titulo, :contenido)";
    $stmt = $pdo->prepare($sql);
    
    if (!$stmt->execute([
        ':usuario_id' => $usuario_id,
        ':titulo' => 'Nueva Publicación',
        ':contenido' => 'Contenido de la nueva publicación'
    ])) {
        throw new Exception("Error en la consulta: " . $stmt->errorInfo()[2]);
    }

    $pdo->commit();
    echo "Transacción completada con éxito.";
} catch (Exception $e) {
    $pdo->rollBack();
    echo "Error en la transacción: " . $e->getMessage();
    registrar_error("transaccion_pdo.php", $e->getMessage());
}

function registrar_error($archivo, $mensaje) {
    $log = "errores.log";
    $contenido = date("Y-m-d H:i:s") . " | " . $archivo . " | " . $mensaje . "\n";
    file_put_contents($log, $contenido, FILE_APPEND);
}
?>
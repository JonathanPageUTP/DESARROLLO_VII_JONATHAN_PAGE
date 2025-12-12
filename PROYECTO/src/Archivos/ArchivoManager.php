<?php

class ArchivoManager {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function obtenerTodos() {
        $stmt = $this->db->query("SELECT * FROM archivos ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId(int $id) {
        $stmt = $this->db->prepare("SELECT * FROM archivos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerPorUsuario(int $usuarioId) {
        $stmt = $this->db->prepare("SELECT * FROM archivos WHERE usuario_id = ? ORDER BY created_at DESC");
        $stmt->execute([$usuarioId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorCarpeta(int $carpetaId) {
        $stmt = $this->db->prepare("SELECT * FROM archivos WHERE carpeta_id = ? ORDER BY created_at DESC");
        $stmt->execute([$carpetaId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerSinCarpeta(int $usuarioId) {
        $stmt = $this->db->prepare("SELECT * FROM archivos WHERE usuario_id = ? AND carpeta_id IS NULL ORDER BY created_at DESC");
        $stmt->execute([$usuarioId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crearArchivo(int $usuarioId, ?int $carpetaId, string $nombre, int $tamano, string $rutaArchivo): bool {
        $sql = "INSERT INTO archivos (usuario_id, carpeta_id, nombre, tamano, ruta_archivo) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$usuarioId, $carpetaId, $nombre, $tamano, $rutaArchivo]);
    }

    public function actualizarArchivo(int $id, string $nombre, ?int $carpetaId): bool {
        $sql = "UPDATE archivos SET nombre = ?, carpeta_id = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$nombre, $carpetaId, $id]);
    }

    public function moverACarpeta(int $id, ?int $carpetaId): bool {
        $sql = "UPDATE archivos SET carpeta_id = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$carpetaId, $id]);
    }

    public function eliminarArchivo(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM archivos WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function obtenerTamanoTotalPorUsuario(int $usuarioId): int {
        $stmt = $this->db->prepare("SELECT SUM(tamano) as total FROM archivos WHERE usuario_id = ?");
        $stmt->execute([$usuarioId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

public function actualizarArchivoConVersion(int $id, string $nuevoContenido): bool {
    // 1. Obtener archivo actual
    $archivo = $this->obtenerPorId($id);
    if (!$archivo) return false;
    
    require_once BASE_PATH . 'src/Versiones/VersionManager.php';
    $versionManager = new VersionManager();
    
    // 2. Obtener siguiente número de versión
    $siguienteVersion = $versionManager->obtenerSiguienteNumeroVersion($id);
    
    // 3. Crear nuevo archivo físico para esta versión
    $infoRuta = pathinfo($archivo['ruta_archivo']);
    $nuevaRuta = $infoRuta['dirname'] . '/' . 
                 $infoRuta['filename'] . '_v' . $siguienteVersion . '.' . 
                 $infoRuta['extension'];
    
    // 4. Guardar nuevo archivo físico
    $rutaCompleta = BASE_PATH . $nuevaRuta;
    file_put_contents($rutaCompleta, $nuevoContenido);
    
    // 5. Calcular tamaño
    $nuevoTamano = strlen($nuevoContenido);
    
    // 6. Crear nueva versión
    $versionManager->crearVersion(
        $id, 
        $nuevoTamano, 
        $nuevaRuta,  // Cada versión tiene su propio archivo
        $siguienteVersion
    );
    
    // 7. Actualizar registro principal
    $sql = "UPDATE archivos SET tamano = ?, ruta_archivo = ? WHERE id = ?";
    $stmt = $this->db->prepare($sql);
    return $stmt->execute([$nuevoTamano, $nuevaRuta, $id]);
}
public function obtenerUltimoId(): ?int {
    return $this->db->lastInsertId();
}
}
?>
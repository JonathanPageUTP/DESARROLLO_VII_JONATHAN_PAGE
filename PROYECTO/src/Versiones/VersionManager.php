<?php

class VersionManager {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function obtenerTodos() {
        $stmt = $this->db->query("SELECT v.* FROM versiones v INNER JOIN (SELECT archivo_id, MAX(id) AS max_version_id FROM versiones GROUP BY archivo_id) AS ultimas_versiones ON v.id = ultimas_versiones.max_version_id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerUltimasPorUsuario(int $usuarioId): array {
    $sql = "
        SELECT v.* FROM versiones v
        INNER JOIN (
            -- 1. Obtener el ID de la última versión para cada archivo
            SELECT archivo_id, MAX(id) AS max_version_id 
            FROM versiones 
            GROUP BY archivo_id
        ) AS ultimas_versiones ON v.id = ultimas_versiones.max_version_id
        INNER JOIN archivos a ON v.archivo_id = a.id -- 2. Unir con la tabla archivos
        WHERE a.usuario_id = ? -- 3. Filtrar por el ID del usuario
        ORDER BY v.created_at DESC
    ";
    
    $stmt = $this->db->prepare($sql);
    $stmt->execute([$usuarioId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function obtenerPorId(int $id) {
        $stmt = $this->db->prepare("SELECT * FROM versiones WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerPorArchivo(int $archivoId) {
        $stmt = $this->db->prepare("SELECT * FROM versiones WHERE archivo_id = ? ORDER BY numero_version DESC");
        $stmt->execute([$archivoId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerVersionReciente(int $archivoId) {
        $stmt = $this->db->prepare("SELECT * FROM versiones WHERE archivo_id = ? ORDER BY numero_version DESC LIMIT 1");
        $stmt->execute([$archivoId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerVersionEspecifica(int $archivoId, int $numeroVersion) {
        $stmt = $this->db->prepare("SELECT * FROM versiones WHERE archivo_id = ? AND numero_version = ?");
        $stmt->execute([$archivoId, $numeroVersion]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerSiguienteNumeroVersion(int $archivoId): int {
        $stmt = $this->db->prepare("SELECT MAX(numero_version) as max_version FROM versiones WHERE archivo_id = ?");
        $stmt->execute([$archivoId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return ($result['max_version'] ?? 0) + 1;
    }

    public function contarVersiones(int $archivoId): int {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM versiones WHERE archivo_id = ?");
        $stmt->execute([$archivoId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    public function obtenerTamanoTotal(int $archivoId): int {
        $stmt = $this->db->prepare("SELECT SUM(tamano) as total FROM versiones WHERE archivo_id = ?");
        $stmt->execute([$archivoId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    public function crearVersion(int $archivoId, int $tamano, string $rutaArchivo, ?int $numeroVersion = null): bool {
        if ($numeroVersion === null) {
            $numeroVersion = $this->obtenerSiguienteNumeroVersion($archivoId);
        }

        $sql = "INSERT INTO versiones (archivo_id, numero_version, tamano, ruta_archivo) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$archivoId, $numeroVersion, $tamano, $rutaArchivo]);
    }

    public function obtenerUltimoId(): ?int {
        return $this->db->lastInsertId();
    }

    public function eliminarVersion(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM versiones WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function eliminarVersionesAnteriores(int $archivoId, int $mantenerUltimas = 5): int {
        // Obtener IDs de las versiones a mantener
        $stmt = $this->db->prepare("SELECT id FROM versiones WHERE archivo_id = ? ORDER BY numero_version DESC LIMIT ?");
        $stmt->execute([$archivoId, $mantenerUltimas]);
        $mantener = $stmt->fetchAll(PDO::FETCH_COLUMN);

        if (empty($mantener)) {
            return 0;
        }

        $placeholders = str_repeat('?,', count($mantener) - 1) . '?';
        $sql = "DELETE FROM versiones WHERE archivo_id = ? AND id NOT IN ($placeholders)";
        
        $params = array_merge([$archivoId], $mantener);
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->rowCount();
    }

    public function eliminarTodasLasVersiones(int $archivoId): bool {
        $stmt = $this->db->prepare("DELETE FROM versiones WHERE archivo_id = ?");
        return $stmt->execute([$archivoId]);
    }

    public function obtenerHistorialCompleto(int $archivoId): array {
        $sql = "SELECT v.*, a.nombre as nombre_archivo 
                FROM versiones v 
                INNER JOIN archivos a ON v.archivo_id = a.id 
                WHERE v.archivo_id = ? 
                ORDER BY v.numero_version DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$archivoId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function compararVersiones(int $versionId1, int $versionId2): array {
        $version1 = $this->obtenerPorId($versionId1);
        $version2 = $this->obtenerPorId($versionId2);

        if (!$version1 || !$version2) {
            return [];
        }

        return [
            'version_1' => $version1,
            'version_2' => $version2,
            'diferencia_tamano' => $version2['tamano'] - $version1['tamano'],
            'diferencia_tiempo' => strtotime($version2['created_at']) - strtotime($version1['created_at'])
        ];
    }

    public function obtenerEstadisticas(int $archivoId): array {
        $totalVersiones = $this->contarVersiones($archivoId);
        $tamanoTotal = $this->obtenerTamanoTotal($archivoId);
        $versionReciente = $this->obtenerVersionReciente($archivoId);

        $stmt = $this->db->prepare("SELECT AVG(tamano) as tamano_promedio FROM versiones WHERE archivo_id = ?");
        $stmt->execute([$archivoId]);
        $tamanoPromedio = $stmt->fetch(PDO::FETCH_ASSOC)['tamano_promedio'] ?? 0;

        return [
            'total_versiones' => $totalVersiones,
            'tamano_total' => $tamanoTotal,
            'tamano_promedio' => round($tamanoPromedio),
            'version_actual' => $versionReciente['numero_version'] ?? 0,
            'fecha_ultima_version' => $versionReciente['created_at'] ?? null
        ];
    }
}
?>
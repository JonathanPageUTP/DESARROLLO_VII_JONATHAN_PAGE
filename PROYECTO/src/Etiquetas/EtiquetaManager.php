<?php

class EtiquetaManager {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function obtenerTodos() {
        $stmt = $this->db->query("SELECT * FROM etiquetas ORDER BY nombre ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId(int $id) {
        $stmt = $this->db->prepare("SELECT * FROM etiquetas WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerPorUsuario(int $usuarioId) {
        $stmt = $this->db->prepare("SELECT * FROM etiquetas WHERE usuario_id = ? ORDER BY nombre ASC");
        $stmt->execute([$usuarioId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorNombre(int $usuarioId, string $nombre) {
        $stmt = $this->db->prepare("SELECT * FROM etiquetas WHERE usuario_id = ? AND nombre = ?");
        $stmt->execute([$usuarioId, $nombre]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function buscarPorNombre(int $usuarioId, string $busqueda) {
        $stmt = $this->db->prepare("SELECT * FROM etiquetas WHERE usuario_id = ? AND nombre LIKE ? ORDER BY nombre ASC");
        $stmt->execute([$usuarioId, "%{$busqueda}%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function existeEtiqueta(int $usuarioId, string $nombre): bool {
        $etiqueta = $this->obtenerPorNombre($usuarioId, $nombre);
        return $etiqueta !== false;
    }

    public function contarArchivosPorEtiqueta(int $etiquetaId): int {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM archivo_etiqueta WHERE etiqueta_id = ?");
        $stmt->execute([$etiquetaId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    public function obtenerEtiquetasMasUsadas(int $usuarioId, int $limite = 10): array {
        $sql = "SELECT e.*, COUNT(ae.archivo_id) as total_archivos 
                FROM etiquetas e 
                LEFT JOIN archivo_etiqueta ae ON e.id = ae.etiqueta_id 
                WHERE e.usuario_id = ? 
                GROUP BY e.id 
                ORDER BY total_archivos DESC, e.nombre ASC 
                LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usuarioId, $limite]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerEtiquetasSinUsar(int $usuarioId): array {
        $sql = "SELECT e.* 
                FROM etiquetas e 
                LEFT JOIN archivo_etiqueta ae ON e.id = ae.etiqueta_id 
                WHERE e.usuario_id = ? AND ae.archivo_id IS NULL 
                ORDER BY e.nombre ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usuarioId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crearEtiqueta(int $usuarioId, string $nombre): bool {
        // Verificar que no exista ya
        if ($this->existeEtiqueta($usuarioId, $nombre)) {
            return false;
        }

        $sql = "INSERT INTO etiquetas (usuario_id, nombre) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$usuarioId, $nombre]);
    }

    public function actualizarEtiqueta(int $id, string $nombre): bool {
        $sql = "UPDATE etiquetas SET nombre = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$nombre, $id]);
    }

    public function eliminarEtiqueta(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM etiquetas WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function obtenerEstadisticas(int $usuarioId): array {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total_etiquetas FROM etiquetas WHERE usuario_id = ?");
        $stmt->execute([$usuarioId]);
        $totalEtiquetas = $stmt->fetch(PDO::FETCH_ASSOC)['total_etiquetas'] ?? 0;

        $sql = "SELECT COUNT(DISTINCT ae.archivo_id) as archivos_etiquetados 
                FROM archivo_etiqueta ae 
                INNER JOIN etiquetas e ON ae.etiqueta_id = e.id 
                WHERE e.usuario_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usuarioId]);
        $archivosEtiquetados = $stmt->fetch(PDO::FETCH_ASSOC)['archivos_etiquetados'] ?? 0;

        return [
            'total_etiquetas' => $totalEtiquetas,
            'archivos_etiquetados' => $archivosEtiquetados,
            'etiquetas_sin_usar' => count($this->obtenerEtiquetasSinUsar($usuarioId))
        ];
    }
}
?>
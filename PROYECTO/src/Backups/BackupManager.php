<?php

class BackupManager {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function obtenerTodos() {
        $stmt = $this->db->query("SELECT * FROM backups ORDER BY fecha_inicio DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId(int $id) {
        $stmt = $this->db->prepare("SELECT * FROM backups WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerPorUsuario(int $usuarioId) {
        $stmt = $this->db->prepare("SELECT * FROM backups WHERE usuario_id = ? ORDER BY fecha_inicio DESC");
        $stmt->execute([$usuarioId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorEstado(string $estado) {
        $stmt = $this->db->prepare("SELECT * FROM backups WHERE estado = ? ORDER BY fecha_inicio DESC");
        $stmt->execute([$estado]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerEnProgreso() {
        return $this->obtenerPorEstado('en_progreso');
    }

    public function obtenerCompletados() {
        return $this->obtenerPorEstado('completado');
    }

    public function obtenerFallidos() {
        return $this->obtenerPorEstado('fallido');
    }

    public function crearBackup(int $usuarioId, string $nombre, string $rutaBackup, string $estado = 'en_progreso'): bool {
        $sql = "INSERT INTO backups (usuario_id, nombre, estado, ruta_backup) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$usuarioId, $nombre, $estado, $rutaBackup]);
    }

    public function actualizarBackup(int $id, string $nombre): bool {
        $sql = "UPDATE backups SET nombre = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$nombre, $id]);
    }

    public function actualizarEstado(int $id, string $estado): bool {
        $sql = "UPDATE backups SET estado = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$estado, $id]);
    }

    public function completarBackup(int $id, int $tamanoTotal): bool {
        $sql = "UPDATE backups SET estado = 'completado', tamano_total = ?, fecha_fin = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$tamanoTotal, $id]);
    }

    public function marcarComoFallido(int $id): bool {
        $sql = "UPDATE backups SET estado = 'fallido', fecha_fin = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function actualizarTamano(int $id, int $tamanoTotal): bool {
        $sql = "UPDATE backups SET tamano_total = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$tamanoTotal, $id]);
    }

    public function eliminarBackup(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM backups WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function obtenerTamanoTotalPorUsuario(int $usuarioId): int {
        $stmt = $this->db->prepare("SELECT SUM(tamano_total) as total FROM backups WHERE usuario_id = ? AND estado = 'completado'");
        $stmt->execute([$usuarioId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }
}
?>
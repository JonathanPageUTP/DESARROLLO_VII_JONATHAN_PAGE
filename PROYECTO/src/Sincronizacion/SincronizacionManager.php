<?php

class SincronizacionManager {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function obtenerTodos() {
        $stmt = $this->db->query("SELECT * FROM sincronizaciones ORDER BY fecha_inicio DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId(int $id) {
        $stmt = $this->db->prepare("SELECT * FROM sincronizaciones WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerPorUsuario(int $usuarioId) {
        $stmt = $this->db->prepare("SELECT * FROM sincronizaciones WHERE usuario_id = ? ORDER BY fecha_inicio DESC");
        $stmt->execute([$usuarioId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorDispositivo(int $dispositivoId) {
        $stmt = $this->db->prepare("SELECT * FROM sincronizaciones WHERE dispositivo_id = ? ORDER BY fecha_inicio DESC");
        $stmt->execute([$dispositivoId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorEstado(string $estado) {
        $stmt = $this->db->prepare("SELECT * FROM sincronizaciones WHERE estado = ? ORDER BY fecha_inicio DESC");
        $stmt->execute([$estado]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPendientes() {
        return $this->obtenerPorEstado('pendiente');
    }

    public function obtenerEnProgreso() {
        return $this->obtenerPorEstado('en_progreso');
    }

    public function obtenerCompletadas() {
        return $this->obtenerPorEstado('completada');
    }

    public function obtenerFallidas() {
        return $this->obtenerPorEstado('fallida');
    }

    public function obtenerUltimaSincronizacion(int $dispositivoId) {
        $stmt = $this->db->prepare("SELECT * FROM sincronizaciones WHERE dispositivo_id = ? AND estado = 'completada' ORDER BY fecha_fin DESC LIMIT 1");
        $stmt->execute([$dispositivoId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerSincronizacionesActivas(int $usuarioId) {
        $stmt = $this->db->prepare("SELECT * FROM sincronizaciones WHERE usuario_id = ? AND estado IN ('pendiente', 'en_progreso') ORDER BY fecha_inicio DESC");
        $stmt->execute([$usuarioId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crearSincronizacion(int $usuarioId, int $dispositivoId, string $estado = 'pendiente'): bool {
        $sql = "INSERT INTO sincronizaciones (usuario_id, dispositivo_id, estado) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$usuarioId, $dispositivoId, $estado]);
    }

    public function obtenerUltimoId(): ?int {
        return $this->db->lastInsertId();
    }

    public function actualizarEstado(int $id, string $estado): bool {
        $sql = "UPDATE sincronizaciones SET estado = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$estado, $id]);
    }

    public function iniciarSincronizacion(int $id): bool {
        $sql = "UPDATE sincronizaciones SET estado = 'en_progreso' WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function actualizarProgreso(int $id, int $archivosSincronizados): bool {
        $sql = "UPDATE sincronizaciones SET archivos_sincronizados = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$archivosSincronizados, $id]);
    }

    public function incrementarArchivos(int $id, int $cantidad = 1): bool {
        $sql = "UPDATE sincronizaciones SET archivos_sincronizados = archivos_sincronizados + ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$cantidad, $id]);
    }

    public function completarSincronizacion(int $id, int $archivosSincronizados): bool {
        $sql = "UPDATE sincronizaciones SET estado = 'completada', archivos_sincronizados = ?, fecha_fin = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$archivosSincronizados, $id]);
    }

    public function marcarComoFallida(int $id): bool {
        $sql = "UPDATE sincronizaciones SET estado = 'fallida', fecha_fin = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function cancelarSincronizacion(int $id): bool {
        $sql = "UPDATE sincronizaciones SET estado = 'cancelada', fecha_fin = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function eliminarSincronizacion(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM sincronizaciones WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function obtenerEstadisticas(int $usuarioId): array {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM sincronizaciones WHERE usuario_id = ?");
        $stmt->execute([$usuarioId]);
        $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        $stmt = $this->db->prepare("SELECT COUNT(*) as completadas FROM sincronizaciones WHERE usuario_id = ? AND estado = 'completada'");
        $stmt->execute([$usuarioId]);
        $completadas = $stmt->fetch(PDO::FETCH_ASSOC)['completadas'] ?? 0;

        $stmt = $this->db->prepare("SELECT COUNT(*) as fallidas FROM sincronizaciones WHERE usuario_id = ? AND estado = 'fallida'");
        $stmt->execute([$usuarioId]);
        $fallidas = $stmt->fetch(PDO::FETCH_ASSOC)['fallidas'] ?? 0;

        $stmt = $this->db->prepare("SELECT SUM(archivos_sincronizados) as total_archivos FROM sincronizaciones WHERE usuario_id = ? AND estado = 'completada'");
        $stmt->execute([$usuarioId]);
        $totalArchivos = $stmt->fetch(PDO::FETCH_ASSOC)['total_archivos'] ?? 0;

        return [
            'total_sincronizaciones' => $total,
            'completadas' => $completadas,
            'fallidas' => $fallidas,
            'total_archivos_sincronizados' => $totalArchivos
        ];
    }

    public function obtenerEstadisticasPorDispositivo(int $dispositivoId): array {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM sincronizaciones WHERE dispositivo_id = ?");
        $stmt->execute([$dispositivoId]);
        $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        $stmt = $this->db->prepare("SELECT SUM(archivos_sincronizados) as total_archivos FROM sincronizaciones WHERE dispositivo_id = ? AND estado = 'completada'");
        $stmt->execute([$dispositivoId]);
        $totalArchivos = $stmt->fetch(PDO::FETCH_ASSOC)['total_archivos'] ?? 0;

        $ultimaSinc = $this->obtenerUltimaSincronizacion($dispositivoId);

        return [
            'total_sincronizaciones' => $total,
            'total_archivos_sincronizados' => $totalArchivos,
            'ultima_sincronizacion' => $ultimaSinc
        ];
    }
}
?>
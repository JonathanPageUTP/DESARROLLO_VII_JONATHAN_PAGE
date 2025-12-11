<?php

class DispositivoManager {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function obtenerTodos() {
        $stmt = $this->db->query("SELECT * FROM dispositivos ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId(int $id) {
        $stmt = $this->db->prepare("SELECT * FROM dispositivos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crearDispositivo(int $usuarioId, string $nombre, string $tipo): bool {
        $sql = "INSERT INTO dispositivos (usuario_id, nombre, tipo) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$usuarioId, $nombre, $tipo]);
    }

    public function actualizarDispositivo(int $id, string $nombre, string $tipo): bool {
        $sql = "UPDATE dispositivos SET nombre = ?, tipo = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$nombre, $tipo, $id]);
    }

    public function eliminarDispositivo(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM dispositivos WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
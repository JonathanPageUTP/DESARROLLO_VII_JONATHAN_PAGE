<?php

class CarpetaManager {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function obtenerTodos() {
        $stmt = $this->db->query("SELECT * FROM carpetas ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId(int $id) {
        $stmt = $this->db->prepare("SELECT * FROM carpetas WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerPorUsuario(int $usuarioId) {
        $stmt = $this->db->prepare("SELECT * FROM carpetas WHERE usuario_id = ? ORDER BY nombre ASC");
        $stmt->execute([$usuarioId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerCarpetasRaiz(int $usuarioId) {
        $stmt = $this->db->prepare("SELECT * FROM carpetas WHERE usuario_id = ? AND carpeta_padre_id IS NULL ORDER BY nombre ASC");
        $stmt->execute([$usuarioId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerSubcarpetas(int $carpetaPadreId) {
        $stmt = $this->db->prepare("SELECT * FROM carpetas WHERE carpeta_padre_id = ? ORDER BY nombre ASC");
        $stmt->execute([$carpetaPadreId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerRutaCarpeta(int $carpetaId): array {
        $ruta = [];
        $carpetaActual = $this->obtenerPorId($carpetaId);
        
        while ($carpetaActual) {
            array_unshift($ruta, $carpetaActual);
            if ($carpetaActual['carpeta_padre_id']) {
                $carpetaActual = $this->obtenerPorId($carpetaActual['carpeta_padre_id']);
            } else {
                $carpetaActual = null;
            }
        }
        
        return $ruta;
    }

    public function contarSubcarpetas(int $carpetaId): int {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM carpetas WHERE carpeta_padre_id = ?");
        $stmt->execute([$carpetaId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    public function crearCarpeta(int $usuarioId, string $nombre, ?int $carpetaPadreId = null): bool {
        $sql = "INSERT INTO carpetas (usuario_id, nombre, carpeta_padre_id) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$usuarioId, $nombre, $carpetaPadreId]);
    }

    public function actualizarCarpeta(int $id, string $nombre): bool {
        $sql = "UPDATE carpetas SET nombre = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$nombre, $id]);
    }

    public function moverCarpeta(int $id, ?int $carpetaPadreId): bool {
        // Verificar que no se mueva a sí misma o a una de sus subcarpetas
        if ($carpetaPadreId && $this->esSubcarpetaDe($carpetaPadreId, $id)) {
            return false;
        }
        
        $sql = "UPDATE carpetas SET carpeta_padre_id = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$carpetaPadreId, $id]);
    }

    private function esSubcarpetaDe(int $carpetaId, int $posiblePadreId): bool {
        $carpetaActual = $this->obtenerPorId($carpetaId);
        
        while ($carpetaActual) {
            if ($carpetaActual['id'] == $posiblePadreId) {
                return true;
            }
            if ($carpetaActual['carpeta_padre_id']) {
                $carpetaActual = $this->obtenerPorId($carpetaActual['carpeta_padre_id']);
            } else {
                $carpetaActual = null;
            }
        }
        
        return false;
    }

    public function eliminarCarpeta(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM carpetas WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function eliminarCarpetaRecursiva(int $id): bool {
        // Primero eliminar todas las subcarpetas
        $subcarpetas = $this->obtenerSubcarpetas($id);
        foreach ($subcarpetas as $subcarpeta) {
            $this->eliminarCarpetaRecursiva($subcarpeta['id']);
        }
        
        // Luego eliminar la carpeta actual
        return $this->eliminarCarpeta($id);
    }
}
?>
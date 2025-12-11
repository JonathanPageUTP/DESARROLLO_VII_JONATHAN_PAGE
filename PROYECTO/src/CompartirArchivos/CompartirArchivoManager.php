<?php

class CompartirArchivoManager {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function obtenerTodos() {
        $stmt = $this->db->query("SELECT * FROM compartir_archivos ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId(int $id) {
        $stmt = $this->db->prepare("SELECT * FROM compartir_archivos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerPorArchivo(int $archivoId) {
        $stmt = $this->db->prepare("SELECT * FROM compartir_archivos WHERE archivo_id = ? ORDER BY created_at DESC");
        $stmt->execute([$archivoId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorPropietario(int $usuarioPropietarioId) {
        $stmt = $this->db->prepare("SELECT * FROM compartir_archivos WHERE usuario_propietario_id = ? ORDER BY created_at DESC");
        $stmt->execute([$usuarioPropietarioId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerCompartidosConmigo(int $usuarioCompartidoId) {
        $stmt = $this->db->prepare("SELECT * FROM compartir_archivos WHERE usuario_compartido_id = ? ORDER BY created_at DESC");
        $stmt->execute([$usuarioCompartidoId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorEnlacePublico(string $enlacePublico) {
        $stmt = $this->db->prepare("SELECT * FROM compartir_archivos WHERE enlace_publico = ?");
        $stmt->execute([$enlacePublico]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerEnlacesPublicos(int $usuarioPropietarioId) {
        $stmt = $this->db->prepare("SELECT * FROM compartir_archivos WHERE usuario_propietario_id = ? AND enlace_publico IS NOT NULL ORDER BY created_at DESC");
        $stmt->execute([$usuarioPropietarioId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function compartirConUsuario(int $archivoId, int $usuarioPropietarioId, int $usuarioCompartidoId, string $permiso = 'lectura'): bool {
        $sql = "INSERT INTO compartir_archivos (archivo_id, usuario_propietario_id, usuario_compartido_id, permiso) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$archivoId, $usuarioPropietarioId, $usuarioCompartidoId, $permiso]);
    }

    public function generarEnlacePublico(int $archivoId, int $usuarioPropietarioId, string $permiso = 'lectura'): ?string {
        $enlacePublico = $this->generarEnlaceUnico();
        
        $sql = "INSERT INTO compartir_archivos (archivo_id, usuario_propietario_id, enlace_publico, permiso) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        
        if ($stmt->execute([$archivoId, $usuarioPropietarioId, $enlacePublico, $permiso])) {
            return $enlacePublico;
        }
        
        return null;
    }

    private function generarEnlaceUnico(): string {
        do {
            $enlace = bin2hex(random_bytes(16)); // 32 caracteres
            $existe = $this->obtenerPorEnlacePublico($enlace);
        } while ($existe);
        
        return $enlace;
    }

    public function actualizarPermiso(int $id, string $permiso): bool {
        $sql = "UPDATE compartir_archivos SET permiso = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$permiso, $id]);
    }

    public function eliminarCompartido(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM compartir_archivos WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function eliminarTodosPorArchivo(int $archivoId): bool {
        $stmt = $this->db->prepare("DELETE FROM compartir_archivos WHERE archivo_id = ?");
        return $stmt->execute([$archivoId]);
    }

    public function eliminarEnlacePublico(string $enlacePublico): bool {
        $stmt = $this->db->prepare("DELETE FROM compartir_archivos WHERE enlace_publico = ?");
        return $stmt->execute([$enlacePublico]);
    }

    public function verificarAcceso(int $archivoId, int $usuarioId): ?array {
        $stmt = $this->db->prepare("SELECT * FROM compartir_archivos WHERE archivo_id = ? AND (usuario_propietario_id = ? OR usuario_compartido_id = ?)");
        $stmt->execute([$archivoId, $usuarioId, $usuarioId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function tienePermiso(int $archivoId, int $usuarioId, string $permisoRequerido): bool {
        $acceso = $this->verificarAcceso($archivoId, $usuarioId);
        
        if (!$acceso) {
            return false;
        }
        
        // El propietario siempre tiene todos los permisos
        if ($acceso['usuario_propietario_id'] == $usuarioId) {
            return true;
        }
        
        // Verificar permiso específico
        if ($permisoRequerido === 'lectura') {
            return true; // Si tiene acceso, siempre puede leer
        }
        
        if ($permisoRequerido === 'escritura') {
            return in_array($acceso['permiso'], ['escritura', 'admin']);
        }
        
        if ($permisoRequerido === 'admin') {
            return $acceso['permiso'] === 'admin';
        }
        
        return false;
    }
}
?>
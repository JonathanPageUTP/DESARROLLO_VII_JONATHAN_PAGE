<?php

class ArchivoEtiquetaManager {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function obtenerTodos() {
        $stmt = $this->db->query("SELECT * FROM archivo_etiqueta");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorArchivoYEtiqueta(int $archivoId, int $etiquetaId) {
        $stmt = $this->db->prepare("SELECT * FROM archivo_etiqueta WHERE archivo_id = ? AND etiqueta_id = ?");
        $stmt->execute([$archivoId, $etiquetaId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerEtiquetasPorArchivo(int $archivoId) {
        $sql = "SELECT e.* FROM etiquetas e 
                INNER JOIN archivo_etiqueta ae ON e.id = ae.etiqueta_id 
                WHERE ae.archivo_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$archivoId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerArchivosPorEtiqueta(int $etiquetaId) {
        $sql = "SELECT a.* FROM archivos a 
                INNER JOIN archivo_etiqueta ae ON a.id = ae.archivo_id 
                WHERE ae.etiqueta_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$etiquetaId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crearRelacion(int $archivoId, int $etiquetaId): bool {
        $sql = "INSERT INTO archivo_etiqueta (archivo_id, etiqueta_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$archivoId, $etiquetaId]);
    }

    public function eliminarRelacion(int $archivoId, int $etiquetaId): bool {
        $stmt = $this->db->prepare("DELETE FROM archivo_etiqueta WHERE archivo_id = ? AND etiqueta_id = ?");
        return $stmt->execute([$archivoId, $etiquetaId]);
    }

    public function eliminarTodasPorArchivo(int $archivoId): bool {
        $stmt = $this->db->prepare("DELETE FROM archivo_etiqueta WHERE archivo_id = ?");
        return $stmt->execute([$archivoId]);
    }

    public function eliminarTodasPorEtiqueta(int $etiquetaId): bool {
        $stmt = $this->db->prepare("DELETE FROM archivo_etiqueta WHERE etiqueta_id = ?");
        return $stmt->execute([$etiquetaId]);
    }
}
?>
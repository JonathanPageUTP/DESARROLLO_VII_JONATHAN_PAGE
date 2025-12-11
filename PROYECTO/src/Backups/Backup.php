<?php
class Backup {
    public $id;
    public $usuarioId;
    public $nombre;
    public $estado;
    public $tamanoTotal;
    public $rutaBackup;
    public $fechaInicio;
    public $fechaFin;

    /**
     * Constructor para crear un objeto Backup a partir de un array de datos.
     * Los nombres de las claves del array deben coincidir con los de la base de datos (snake_case).
     *
     * @param array $data Array asociativo con los datos del backup.
     */
    public function __construct(array $data) {
        $this->id = $data['id'] ?? null;
        $this->usuarioId = $data['usuario_id'] ?? null;
        $this->nombre = $data['nombre'] ?? '';
        $this->estado = $data['estado'] ?? 'en_progreso';
        $this->tamanoTotal = $data['tamano_total'] ?? 0;
        $this->rutaBackup = $data['ruta_backup'] ?? '';
        $this->fechaInicio = $data['fecha_inicio'] ?? null;
        $this->fechaFin = $data['fecha_fin'] ?? null;
    }

}
?>
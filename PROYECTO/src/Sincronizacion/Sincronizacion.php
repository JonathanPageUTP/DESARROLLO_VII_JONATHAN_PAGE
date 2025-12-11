<?php
class Sincronizacion {
    public $id;
    public $usuarioId;
    public $dispositivoId;
    public $estado;
    public $archivosSincronizados;
    public $fechaInicio;
    public $fechaFin;

    /**
     * Constructor para crear un objeto Sincronizacion a partir de un array de datos.
     * Los nombres de las claves del array deben coincidir con los de la base de datos (snake_case).
     *
     * @param array $data Array asociativo con los datos de la sincronización.
     */
    public function __construct(array $data) {
        $this->id = $data['id'] ?? null;
        $this->usuarioId = $data['usuario_id'] ?? null;
        $this->dispositivoId = $data['dispositivo_id'] ?? null;
        $this->estado = $data['estado'] ?? 'pendiente';
        $this->archivosSincronizados = $data['archivos_sincronizados'] ?? 0;
        $this->fechaInicio = $data['fecha_inicio'] ?? null;
        $this->fechaFin = $data['fecha_fin'] ?? null;
    }

}
?>
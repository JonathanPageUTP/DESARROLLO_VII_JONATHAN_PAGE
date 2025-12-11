<?php
class Dispositivo {
    public $id;
    public $usuarioId;
    public $nombre;
    public $tipo;
    public $createdAt;

    /**
     * Constructor para crear un objeto Dispositivo a partir de un array de datos.
     * Los nombres de las claves del array deben coincidir con los de la base de datos (snake_case).
     *
     * @param array $data Array asociativo con los datos del dispositivo.
     */
    public function __construct(array $data) {
        $this->id = $data['id'] ?? null;
        $this->usuarioId = $data['usuario_id'] ?? null;
        $this->nombre = $data['nombre'] ?? '';
        $this->tipo = $data['tipo'] ?? '';
        $this->createdAt = $data['created_at'] ?? null;
    }

}
?>
<?php
class Etiqueta {
    public $id;
    public $usuarioId;
    public $nombre;
    public $createdAt;

    /**
     * Constructor para crear un objeto Etiqueta a partir de un array de datos.
     * Los nombres de las claves del array deben coincidir con los de la base de datos (snake_case).
     *
     * @param array $data Array asociativo con los datos de la etiqueta.
     */
    public function __construct(array $data) {
        $this->id = $data['id'] ?? null;
        $this->usuarioId = $data['usuario_id'] ?? null;
        $this->nombre = $data['nombre'] ?? '';
        $this->createdAt = $data['created_at'] ?? null;
    }

}
?>
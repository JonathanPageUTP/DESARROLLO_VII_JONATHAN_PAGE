<?php
class Carpeta {
    public $id;
    public $usuarioId;
    public $nombre;
    public $carpetaPadreId;
    public $createdAt;

    /**
     * Constructor para crear un objeto Carpeta a partir de un array de datos.
     * Los nombres de las claves del array deben coincidir con los de la base de datos (snake_case).
     *
     * @param array $data Array asociativo con los datos de la carpeta.
     */
    public function __construct(array $data) {
        $this->id = $data['id'] ?? null;
        $this->usuarioId = $data['usuario_id'] ?? null;
        $this->nombre = $data['nombre'] ?? '';
        $this->carpetaPadreId = $data['carpeta_padre_id'] ?? null;
        $this->createdAt = $data['created_at'] ?? null;
    }

}
?>
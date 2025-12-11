<?php
class CompartirArchivo {
    public $id;
    public $archivoId;
    public $usuarioPropietarioId;
    public $usuarioCompartidoId;
    public $permiso;
    public $enlacePublico;
    public $createdAt;

    /**
     * Constructor para crear un objeto CompartirArchivo a partir de un array de datos.
     * Los nombres de las claves del array deben coincidir con los de la base de datos (snake_case).
     *
     * @param array $data Array asociativo con los datos del archivo compartido.
     */
    public function __construct(array $data) {
        $this->id = $data['id'] ?? null;
        $this->archivoId = $data['archivo_id'] ?? null;
        $this->usuarioPropietarioId = $data['usuario_propietario_id'] ?? null;
        $this->usuarioCompartidoId = $data['usuario_compartido_id'] ?? null;
        $this->permiso = $data['permiso'] ?? 'lectura';
        $this->enlacePublico = $data['enlace_publico'] ?? null;
        $this->createdAt = $data['created_at'] ?? null;
    }

}
?>
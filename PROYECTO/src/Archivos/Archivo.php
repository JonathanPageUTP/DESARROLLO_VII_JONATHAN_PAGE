<?php
class Archivo {
    public $id;
    public $usuarioId;
    public $carpetaId;
    public $nombre;
    public $tamano;
    public $rutaArchivo;
    public $createdAt;

    /**
     * Constructor para crear un objeto Archivo a partir de un array de datos.
     * Los nombres de las claves del array deben coincidir con los de la base de datos (snake_case).
     *
     * @param array $data Array asociativo con los datos del archivo.
     */
    public function __construct(array $data) {
        $this->id = $data['id'] ?? null;
        $this->usuarioId = $data['usuario_id'] ?? null;
        $this->carpetaId = $data['carpeta_id'] ?? null;
        $this->nombre = $data['nombre'] ?? '';
        $this->tamano = $data['tamano'] ?? 0;
        $this->rutaArchivo = $data['ruta_archivo'] ?? '';
        $this->createdAt = $data['created_at'] ?? null;
    }

}
?>
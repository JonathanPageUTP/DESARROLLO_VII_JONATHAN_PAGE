<?php
class Version {
    public $id;
    public $archivoId;
    public $numeroVersion;
    public $tamano;
    public $rutaArchivo;
    public $createdAt;

    /**
     * Constructor para crear un objeto Version a partir de un array de datos.
     * Los nombres de las claves del array deben coincidir con los de la base de datos (snake_case).
     *
     * @param array $data Array asociativo con los datos de la versión.
     */
    public function __construct(array $data) {
        $this->id = $data['id'] ?? null;
        $this->archivoId = $data['archivo_id'] ?? null;
        $this->numeroVersion = $data['numero_version'] ?? 1;
        $this->tamano = $data['tamano'] ?? 0;
        $this->rutaArchivo = $data['ruta_archivo'] ?? '';
        $this->createdAt = $data['created_at'] ?? null;
    }

}
?>
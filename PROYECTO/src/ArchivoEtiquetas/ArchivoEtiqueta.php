<?php
class ArchivoEtiqueta {
    public $archivoId;
    public $etiquetaId;

    /**
     * Constructor para crear un objeto ArchivoEtiqueta a partir de un array de datos.
     * Los nombres de las claves del array deben coincidir con los de la base de datos (snake_case).
     *
     * @param array $data Array asociativo con los datos de la relación archivo-etiqueta.
     */
    public function __construct(array $data) {
        $this->archivoId = $data['archivo_id'] ?? null;
        $this->etiquetaId = $data['etiqueta_id'] ?? null;
    }

}
?>
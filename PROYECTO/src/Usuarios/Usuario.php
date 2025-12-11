<?php
class Usuario {
    public $id;
    public $nombre;
    public $email;
    public $password;
    public $espacioTotal;
    public $espacioUsado;
    public $createdAt;

    /**
     * Constructor para crear un objeto Usuario a partir de un array de datos.
     * Los nombres de las claves del array deben coincidir con los de la base de datos (snake_case).
     *
     * @param array $data Array asociativo con los datos del usuario.
     */
    public function __construct(array $data) {
        $this->id = $data['id'] ?? null;
        $this->nombre = $data['nombre'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->password = $data['password'] ?? '';
        $this->espacioTotal = $data['espacio_total'] ?? 5368709120;
        $this->espacioUsado = $data['espacio_usado'] ?? 0;
        $this->createdAt = $data['created_at'] ?? null;
    }
}
?>
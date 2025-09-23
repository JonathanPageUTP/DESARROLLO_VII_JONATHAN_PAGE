<?php

require_once 'Empleado.php';
require_once 'Evaluable.php';

Class Desarrollador extends Empleado implements Evaluable{
    private $lenguajeProgramacion;
    private $nivelExperiencia;

    public function __construct($nombre, $idEmpleado, $salarioBase, $lenguajeProgramacion,$nivelExperiencia) {
    parent::__construct($nombre, $idEmpleado, $salarioBase);
    $this->lenguajeProgramacion = $lenguajeProgramacion;
    $this->nivelExperiencia = $nivelExperiencia;
    }

    public function getlenguajeProgramacion() {
        return $this->lenguajeProgramacion;
    }
    public function getnivelExperiencia() {
        return $this->nivelExperiencia;
    }

    public function evaluarDesempenio() {
        return "Desarrollador" . $this->nombre . "- Nivel de exp: " . $this->nivelExperiencia . ".";
    }

}
?>
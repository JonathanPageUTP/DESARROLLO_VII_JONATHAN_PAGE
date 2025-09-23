<?php
require_once 'Empleado.php';
require_once 'Evaluable.php';

class Gerente extends Empleado implements Evaluable {
    public $departamento;
    public $bono = 0;
    
    public function __construct($nombre, $idEmpleado, $salarioBase, $departamento) {
        parent::__construct($nombre, $idEmpleado, $salarioBase);
        $this->departamento = $departamento;
    }
    
    public function asignarBono($cantidad) {
        $this->bono = $cantidad;
    }
    
    public function calcularSalario() {
        return $this->salarioBase + $this->bono;
    }
    
    public function evaluarDesempenio() {
        return "Gerente " . $this->nombre . " - Desempeño: Excelente";
    }
}
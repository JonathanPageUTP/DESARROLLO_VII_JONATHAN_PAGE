<?php
require_once 'Empleado.php';

class Empresa {
    public $nombre;
    public $empleados = array();
    
    public function __construct($nombre) {
        $this->nombre = $nombre;
    }
    
    public function agregarEmpleado($empleado) {
        $this->empleados[] = $empleado;
    }
    
    public function listarEmpleados() {
        foreach ($this->empleados as $empleado) {
            echo $empleado->getNombre() . " (ID: " . $empleado->getIdEmpleado() . ")\n"; 
        }
    }
    
    public function calcularNomina() {
        $total = 0;
        foreach ($this->empleados as $empleado) {
            $total += $empleado->calcularSalario();
        }
        return $total;
    }
    
    public function evaluarTodos() {
        foreach ($this->empleados as $empleado) {
            if ($empleado instanceof Evaluable) {
                echo $empleado->evaluarDesempenio() . "\n";
            }
        }
    }
}
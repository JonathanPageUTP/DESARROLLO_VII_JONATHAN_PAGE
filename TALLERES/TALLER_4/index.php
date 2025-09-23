<!DOCTYPE html>
<html>
<head>
    <title>Sistema de Empleados</title>
    <meta charset="UTF-8">
</head>
<body>
    <h1>Sistema de Gestión de Empleados</h1>
    
<?php
require_once 'Empleado.php';
require_once 'Gerente.php';
require_once 'Desarrollador.php';
require_once 'Empresa.php';


$empresa = new Empresa("Mi Empresa");



$gerente = new Gerente("Ana", "V323", 50000, "Ventas");
$gerente->asignarBono(5000);

$dev1 = new Desarrollador("Luis", "D001", 40000, "PHP", "Senior");
$dev2 = new Desarrollador("María", "D002", 35000, "JavaScript", "Junior");


$empresa->agregarEmpleado($gerente);
$empresa->agregarEmpleado($dev1);
$empresa->agregarEmpleado($dev2);



echo "<h2> EMPLEADOS </h2>";
echo "<div>";
$empresa->listarEmpleados();
echo "</div>";

echo "<h2> NÓMINA TOTAL </h2>";
echo "<p>$" . $empresa->calcularNomina() . "</p>";

echo "<h2> EVALUACIONES </h2>";
echo "<div>";
$empresa->evaluarTodos();
echo "</div>";
?>

</body>
</html>

<?php   

//Define varibles
$nombre_completo = "Jonathan Page";
$edad = "23";
$correo = "jonathan.page@utp.ac.pa";
$telefono  = "62027837";

//Define the constant ocupación
define("PROFESION","Desarrollador");


$fiumba1 = "El estudiante $nombre_completo de $edad años ha sido capturado con un pentabyte de cursos piratas.";

$fuimba2 = "Este enviaba toda la información desde el correo universitario $correo y se comunicaba con su telefono: $telefono, perdió su trabajo en una multinacional como " . PROFESION . ".";

echo $fiumba1 . "<br>";
print($fuimba2 . "<br><br>");

printf("En resumen: %s, %d años, %s, %s<br>", $nombre_completo, $edad, $correo, $telefono, PROFESION);

echo "<br>Información de debugging:<br>";
var_dump($nombre_completo);
echo "<br>";
var_dump($edad);
echo "<br>";
var_dump($correo);
echo "<br>";
var_dump($telefono);
echo "<br>";
var_dump(PROFESION);
echo "<br>";
?>
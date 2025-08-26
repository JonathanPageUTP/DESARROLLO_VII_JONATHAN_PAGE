<?php

$calificacion = 60;
$aprobacion = ($calificacion >= 60) ? "APROBADO" : "REPROBADO";

echo "<h2>Calificador<br>";

if($calificacion >= 90 && $calificacion <=100){
    echo "Tu calificación es A.<br>";
} elseif ($calificacion >= 80 && $calificacion <= 89) {
    echo "Tu calificación es B.<br>";
} elseif ($calificacion >= 70 && $calificacion <= 79) {
    echo "Tu calificación es C.<br>";
} elseif ($calificacion >= 60 && $calificacion <= 69) {
    echo "Tu calificación es D.<br>";
} elseif ($calificacion >= 0 && $calificacion <= 59) {
    echo "Tu calificación es F.<br>";
}
echo "<br>";

echo "Usted está $aprobacion";
echo "<br>";
echo "<br>";

switch (true) {
    case ($calificacion >= 90 && $calificacion <=100):
        echo "Excelente trabajo.<br>";
        break;
    case ($calificacion >= 80 && $calificacion <= 89):
        echo "Buen trabajo.<br>";
        break;
    case ($calificacion >= 70 && $calificacion <= 79):
        echo "Trabajo aceptable.<br>";
        break;
    case ($calificacion >= 60 && $calificacion <= 69):
        echo "Necesitas mejorar.<br>";
        break;
    case ($calificacion >= 0 && $calificacion <= 59):
        echo "Debes esforzarte más.<br>";
        break;
    default:
        echo "Kaboon.<br>";
}
echo "<br>";


?>
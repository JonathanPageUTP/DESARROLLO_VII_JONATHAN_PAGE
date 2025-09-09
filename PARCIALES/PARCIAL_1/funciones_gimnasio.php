<?php

echo"PROBLEMA 2<br><br>";

function calcular_promocion($antiguedad_meses)
{

    echo"Este es su descuento: ";
if ($antiguedad_meses <= 3) {
    echo "No hay promoción";
}else if ($antiguedad_meses > 3 && $antiguedad_meses <= 12) {
    echo"8%";
}else if ($antiguedad_meses > 12 && $antiguedad_meses <= 24) {
    echo "12%";
}else if ($antiguedad_meses > 24) {
    echo "20%";
}

}



function calcular_seguro_medico($cuota_base)
{
    $total = $cuota_base + $cuota_base * 0.05;
    $seguro = $cuota_base * 0.05;

    echo"Calculo de su seguro<br>";
    echo"Seguro:$seguro <br>";
    echo"$cuota_base + 5% = $total";
}

function calcular_cuota_final($cuota_base, $descuento_porcentaje,$seguro_medico){

    $total = $cuota_base - ($cuota_base * $descuento_porcentaje/100) + $seguro_medico;

    echo"La cuota final: $total";

}


calcular_promocion(24);

echo"<br><br>";

calcular_seguro_medico(25);

echo"<br><br>";

calcular_cuota_final(20,10,10)
?>
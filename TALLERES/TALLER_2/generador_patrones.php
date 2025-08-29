<?php

//Triangulo 1
echo "Triangulo: <br>";
for ($i = 0, $x = 5, $c = 0; $i <= 5; $i++, $x--, $c += 2) {
    for($p = $x; $p >= 0; $p--){
        echo" &nbsp";
    }
    for($j = 0; $j <= $c; $j++){
        echo"*";
    }
    echo "<br>";
}
echo "<br><br>";

//Triangulo 2
echo "Triangulo 2: <br>";
for ($i = 0, $x = 5; $i <= 5; $i++, $x--) {
    for($p = $x; $p >= 0; $p--){
        echo" &nbsp";
    }
    for($j = 0; $j <= $i * 2; $j++){
        echo"*";
    }
    echo "<br>";
}
echo "<br><br>";

//Triangulo rectangulo
echo "Triangulo rectangulo<br>";
for($i = 0; $i <= 5; $i ++){
    for($j = 0; $j <= $i; $j ++){
        echo"*";
    }
    echo"<br>";
}

//Contador de numeros impares
$c1 = 1;
echo"<br>Números impares<br>";
do{
    echo"$c1 ";
    $c1+=2;
} while ($c1<=20);
echo "<br><br>";

//Identificador de numero
echo"Identificador de número<br>";
$num = 10;
$prin = "";
do{
    $prin = ($num <> 5) ? "$num " : " ";
    echo"$prin";
    $num --;
} while($num > 0)


?>
<?php


include 'funciones_gimnasio';


$membresia = [
    'basica'=> 80,
    'premium' => 120,
    'vip'=> 180,
    'familiar'=> 250,
    'corporativa'=> 300
] ;

foreach ($membresia as $key) {
    echo"" . calcular_promocion($key["basica"]);
}

$miembros = [
    'Juan Perez' => ['tipo' => 'premiun', 'antiguedad' => 15],
    'Ana Garcia' => ['tipo' => 'basica', 'antiguedad' => 2],
    'Carlos Lopez' => ['tipo' => 'vip', 'antiguedad' => 30],
    'Maria Rodriguez' => ['tipo' => 'familiar', 'antiguedad' => 8],
    'Luis Martinez' => ['tipo' => 'corporativa', 'antiguedad' => 18],
];



?>

<?php

function contar_palabras_repetidas($texto) {
    
    $texto = strtolower($texto);
    
    
    $palabras = explode(' ', $texto);
    
    $contador = array();
    
    foreach ($palabras as $palabra) {
        $palabra = trim($palabra);
        if (!empty($palabra)) {
            if (isset($contador[$palabra])) {
                $contador[$palabra]++;
            } else {
                $contador[$palabra] = 1;
            }
        }
    }
    
    return $contador;
}

// Ejemplo de uso
$texto_ejemplo = "tres por tres es nueve";
$resultado = contar_palabras_repetidas($texto_ejemplo);

echo "Texto: '$texto_ejemplo'<br>";
echo "Resultado:<br>";
print_r($resultado);


echo"<br><br>";


function capitalizar_palabras($texto) {
    $palabras = explode(' ', $texto);
    $palabras_capitalizadas = array();
    
    foreach ($palabras as $palabra) {
        if (strlen($palabra) > 0) {
            $primera_letra = strtoupper(substr($palabra, 0, 1));
            
            $resto = strtolower(substr($palabra, 1));
            
            $palabras_capitalizadas[] = $primera_letra . $resto;
        } else {
            
            $palabras_capitalizadas[] = $palabra;
        }
    }
    
    return implode(' ', $palabras_capitalizadas);
}

// Ejemplos de uso
echo "Ejemplos de capitalizar_palabras:<br><br>";

$ejemplos = array(
    "hola mundo",
    "ESTO ES UNA PRUEBA",
    "primera letra mayúscula",
    "el gato subió al tejado",
    "PHP es un LENGUAJE de programación"
);

foreach ($ejemplos as $texto) {
    $resultado = capitalizar_palabras($texto);
    echo "Original: '$texto'<br>";
    echo "Resultado: '$resultado'<br><br>";
}

?>
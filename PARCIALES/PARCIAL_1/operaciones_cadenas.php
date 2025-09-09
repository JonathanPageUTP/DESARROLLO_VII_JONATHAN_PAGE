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

// Más ejemplos de prueba
echo "<br>--- Más ejemplos ---<br>";

echo"<br><br>";


function capitalizar_palabras($texto) {
    // Dividir el texto en palabras individuales
    $palabras = explode(' ', $texto);
    
    // Array para almacenar las palabras capitalizadas
    $palabras_capitalizadas = array();
    
    // Procesar cada palabra
    foreach ($palabras as $palabra) {
        if (strlen($palabra) > 0) {
            // Primer carácter en mayúscula
            $primera_letra = strtoupper(substr($palabra, 0, 1));
            
            // Resto de caracteres en minúscula
            $resto = strtolower(substr($palabra, 1));
            
            // Combinar primera letra y resto
            $palabras_capitalizadas[] = $primera_letra . $resto;
        } else {
            // Mantener espacios vacíos
            $palabras_capitalizadas[] = $palabra;
        }
    }
    
    // Unir todas las palabras con espacios
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
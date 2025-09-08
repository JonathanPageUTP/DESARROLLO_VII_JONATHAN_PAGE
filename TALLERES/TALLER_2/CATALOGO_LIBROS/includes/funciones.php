<?php 
function  obtenerLibros() { 

    $libros = [
        [
    'titulo' => 'El Quijote',
    'autor' => 'Miguel de Cervantes',
    'anio_publicacion' => 1605,
    'genero' => 'Novela',
    'descripcion' => 'La historia del ingenioso hidalgo Don Quijote de la Mancha.'
        ],  
        [
    'titulo' => 'Meditaciones',
    'autor' => 'Marco Aurelio',
    'anio_publicacion' => 1605,
    'genero' => 'Novela',
    'descripcion' => 'La historia del ingenioso hidalgo Don Quijote de la Mancha.'
        ],
        [
            'titulo' => 'Poesía Completa',
            'autor' => 'Pizarnik'
        ]
    ];
    

    echo"Banco de libros:<br>";
    foreach ($libros as $libro) {
        echo  "Libro: " . $libro["titulo"] . "<br>";
        echo "Autor: " . $libro["autor"] . "<br>";

        echo "<br>";
    }

}

obtenerLibros();


function mostrarDetallesLibro($libro) {
    
    // Comenzar a construir el HTML
    $html = "<div class='libro-detalle' style='border: 1px solid #ccc; padding: 15px; margin: 10px 0; border-radius: 5px; background-color: #f9f9f9;'>";
    $html .= "<h3 style='color: #333; margin-top: 0;'>Detalles del Libro</h3>";
    
    // Recorrer cada campo del libro
    foreach ($libro as $campo => $valor) {
        // Agregar cada detalle con formato
        $html .= "<p><strong>$campo:</strong> $valor</p>";
    }
    
    $html .= "</div>";
    
    return $html;
}

// Ejemplo de uso
$libro1 = [
    'titulo' => 'El Quijote',
    'autor' => 'Miguel de Cervantes',
    'anio_publicacion' => 1605,
    'genero' => 'Novela',
    'descripcion' => 'La historia del ingenioso hidalgo Don Quijote de la Mancha.'
];

$libro2 = [
    'titulo' => 'Cien años de soledad',
    'autor' => 'Gabriel García Márquez',
    'anio_publicacion' => 1967,
    'genero' => 'Realismo mágico',
    'descripcion' => 'La historia de la familia Buendía en Macondo.'
];

// Mostrar los libros
echo mostrarDetallesLibro($libro1);
echo mostrarDetallesLibro($libro2);
?>
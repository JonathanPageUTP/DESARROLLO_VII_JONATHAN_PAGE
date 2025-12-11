<?php 
// Iniciamos el buffer de salida
ob_start(); 
?>
<?php 
// Guardamos el contenido del buffer en la variable $content
$content = ob_get_clean();
// Incluimos el layout
require '../../views/layout.php';
?>
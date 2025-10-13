<?php
// Configurar opciones de sesión antes de iniciar la sesión
ini_set('session.cookie_httponly', 1);      // No accesible desde JavaScript
ini_set('session.use_only_cookies', 1);     // Solo usar cookies
ini_set('session.cookie_secure', 1);        // Solo HTTPS
ini_set('session.cookie_samesite', 'Strict'); // Proteger contra CSRF

session_start();

// Regenerar el ID de sesión periódicamente para mayor seguridad
if (!isset($_SESSION['ultima_actividad']) || (time() - $_SESSION['ultima_actividad'] > 900)) {
    session_regenerate_id(true);
}
$_SESSION['ultima_actividad'] = time();
?>
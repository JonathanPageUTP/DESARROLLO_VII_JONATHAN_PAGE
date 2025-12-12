<?php
// Asumo que tu motor de plantillas o controlador pasa $pageTitle y $content a esta plantilla
// Si no pasas $pageTitle, puedes usar un valor por defecto.
$pageTitle = $pageTitle ?? "Plataforma de Backup"; 

// Estas constantes DEBEN estar definidas ANTES de que se cargue la plantilla, 
// o incluir tu config.php aquí (aunque lo primero es más seguro en un entorno MVC/framwork)
// Si necesitas incluirlos aquí:
/*
define('BASE_PATH', __DIR__ . '/');
require_once BASE_PATH . 'config.php';
if (!defined('BASE_URL')) {
    define('BASE_URL', '/');
}
*/
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/assets/css/index.css">
    <title><?= htmlspecialchars($pageTitle) ?></title> 
    
    
    </head>
<body>
    
    <div id="mySidebar" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <div class="sidebar-title">Módulos</div>
        <a href="<?= BASE_URL ?>Usuarios">
            <div class="module-icon-sidebar">U</div>
            Usuarios
        </a>
        <a href="<?= BASE_URL ?>Archivos">
            <div class="module-icon-sidebar">A</div>
            Archivos
        </a>
        <a href="<?= BASE_URL ?>Carpetas">
            <div class="module-icon-sidebar">C</div>
            Carpetas
        </a>
        <a href="<?= BASE_URL ?>Versiones">
            <div class="module-icon-sidebar">V</div>
            Versiones
        </a>
    </div>

    <div id="main-content">
        <header>
            <div class="header-container">
                <button id="menu-toggle" onclick="openNav()">
                    &#9776; <span>Módulos</span>
                </button>
                
                <div class="title-area">
                    <h1>Plataforma de Backup de Archivos</h1>
                    <p class="subtitle">Sistema de gestión y respaldo de documentos</p>
                </div>
                
                <div class="user-links">
                    <a href="<?= BASE_URL ?>logout.php">Salir</a>
                </div>
            </div>
        </header>

        <div class="container">
            <?php echo $content; ?>
        </div>

        <footer>
            <p>Plataforma de Backup de Archivos &copy; 2025</p>
        </footer>
    </div>
    
    <script>
        // Definimos el ancho del menú para las funciones JS
        const sidebarWidth = "250px";

        function openNav() {
            document.getElementById("mySidebar").style.width = sidebarWidth;
            document.getElementById("main-content").style.marginLeft = sidebarWidth;
        }

        function closeNav() {
            document.getElementById("mySidebar").style.width = "0";
            document.getElementById("main-content").style.marginLeft = "0";
        }
    </script>
    </body>
</html>
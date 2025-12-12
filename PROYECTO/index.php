<?php
define('BASE_PATH', __DIR__ . '/');
require_once BASE_PATH . 'config.php';
// Define BASE_URL para evitar errores si no está en config.php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plataforma de Backup</title>
    <link rel="stylesheet" href="/PROYECTO/public/assets/css/index.css">

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
                    <a href="logout.php">Salir</a>
                </div>
            </div>
        </header>

        <div class="container">
            <h2 class="section-title">Accesos Rápidos</h2>
            
            <div class="main-modules-container">
                <a href="<?= BASE_URL ?>Archivos" class="module-card module-card-archivos">
                    <h3>Archivos</h3>
                    <p>Gestiona todos tus archivos, crea versiones y realiza backups automáticos</p>
                </a>

                <a href="<?= BASE_URL ?>Carpetas" class="module-card module-card-carpetas">
                    <h3>Carpetas</h3>
                    <p>Organiza tus documentos en carpetas jerárquicas para mejor gestión</p>
                </a>
            </div>
        </div>

        <footer>
            <p>Plataforma de Backup de Archivos &copy; 2025</p>
        </footer>
    </div>
    
    <script>
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
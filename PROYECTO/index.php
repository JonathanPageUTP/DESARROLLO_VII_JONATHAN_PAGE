<?php
define('BASE_PATH', __DIR__ . '/');
require_once BASE_PATH . 'config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plataforma de Backup</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            min-height: 100vh;
        }
        
        header {
            background: #ffffff;
            border-bottom: 1px solid #e0e0e0;
            padding: 20px 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        h1 {
            color: #1a1a1a;
            font-size: 28px;
            font-weight: 600;
        }
        
        .subtitle {
            color: #666;
            font-size: 14px;
            margin-top: 5px;
        }
        
        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        .section-title {
            color: #1a1a1a;
            font-size: 18px;    
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e0e0e0;
        }
        
        .modules-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .module-card {
            background: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 24px;
            text-decoration: none;
            color: #1a1a1a;
            transition: all 0.2s ease;
            display: flex;
            flex-direction: column;
        }
        
        .module-card:hover {
            border-color: #333;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transform: translateY(-2px);
        }
        
        .module-icon {
            width: 48px;
            height: 48px;
            background: #f0f0f0;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 16px;
        }
        
        .module-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #1a1a1a;
        }
        
        .module-description {
            font-size: 13px;
            color: #666;
            line-height: 1.5;
        }
        
        footer {
            background: #ffffff;
            border-top: 1px solid #e0e0e0;
            padding: 20px 0;
            margin-top: 60px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <h1>Plataforma de Backup de Archivos</h1>
            <p class="subtitle">Sistema de gestión y respaldo de documentos</p>
        </div>
    </header>

    <div class="container">
        <h2 class="section-title">Módulos del Sistema</h2>
        
        <div class="modules-grid">
            <a href="<?= BASE_URL ?>Usuarios" class="module-card">
                <div class="module-icon">U</div>
                <div class="module-title">Usuarios</div>
                <div class="module-description">Gestión de usuarios y permisos del sistema</div>
            </a>
            
            <a href="<?= BASE_URL ?>Archivos" class="module-card">
                <div class="module-icon">A</div>
                <div class="module-title">Archivos</div>
                <div class="module-description">Administración de archivos y documentos</div>
            </a>
            
            <a href="<?= BASE_URL ?>Carpetas" class="module-card">
                <div class="module-icon">C</div>
                <div class="module-title">Carpetas</div>
                <div class="module-description">Organización jerárquica de contenidos</div>
            </a>
            
            <a href="<?= BASE_URL ?>Backups" class="module-card">
                <div class="module-icon">B</div>
                <div class="module-title">Backups</div>
                <div class="module-description">Respaldos y copias de seguridad</div>
            </a>
            
            <a href="<?= BASE_URL ?>Dispositivos" class="module-card">
                <div class="module-icon">D</div>
                <div class="module-title">Dispositivos</div>
                <div class="module-description">Control de equipos conectados</div>
            </a>
            
            <a href="<?= BASE_URL ?>Etiquetas" class="module-card">
                <div class="module-icon">E</div>
                <div class="module-title">Etiquetas</div>
                <div class="module-description">Sistema de clasificación y búsqueda</div>
            </a>
            
            <a href="<?= BASE_URL ?>Versiones" class="module-card">
                <div class="module-icon">V</div>
                <div class="module-title">Versiones</div>
                <div class="module-description">Control de versiones de archivos</div>
            </a>
            
            <a href="<?= BASE_URL ?>Sincronizacion" class="module-card">
                <div class="module-icon">S</div>
                <div class="module-title">Sincronización</div>
                <div class="module-description">Sincronización automática de datos</div>
            </a>
        </div>
    </div>

    <footer>
        <p>Plataforma de Backup de Archivos &copy; 2024</p>
    </footer>
</body>
</html>
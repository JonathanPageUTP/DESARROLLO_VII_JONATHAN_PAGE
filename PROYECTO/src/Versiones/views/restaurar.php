<?php 
ob_start(); 

require_once BASE_PATH . 'src/Archivos/ArchivoManager.php';
$archivoManager = new ArchivoManager();
$archivo = $archivoManager->obtenerPorId($version['archivo_id']);
?>


<div class="task-form">
    <h2>Restaurar Versión</h2>
    
    <div class="warning-box">
        <h3>Advertencia</h3>
        <p>Está a punto de restaurar el archivo a la versión <?= $version['numero_version'] ?>.</p>
        <p>Esta acción creará una NUEVA versión con el contenido de la versión <?= $version['numero_version'] ?>.</p>
        <p><strong>No se perderán las versiones existentes.</strong></p>
    </div>
    
    <div class="version-details">
        <p><strong>Archivo:</strong> <?= htmlspecialchars($archivo['nombre'] ?? 'Desconocido') ?></p>
        <p><strong>Versión a restaurar:</strong> <span class="version-badge">v<?= $version['numero_version'] ?></span></p>
        <p><strong>Tamaño:</strong> <?= number_format($version['tamano'] / 1024, 2) ?> KB</p>
        <p><strong>Fecha de esta versión:</strong> <?= date('d/m/Y H:i', strtotime($version['created_at'])) ?></p>
    </div>
    
    <form method="POST" action="<?= BASE_URL ?>Versiones/restaurar/<?= $version['id'] ?>" onsubmit="return confirm('¿Está seguro de restaurar esta versión? Se creará una nueva versión con este contenido.')">
        <div class="form-actions">
            <button type="submit" class="btn btn-warning">
                 Confirmar Restauración
            </button>
            <a href="<?= BASE_URL ?>Archivos/edit_content/<?= $archivo['id'] ?>" class="btn btn-secondary">
                 Cancelar
            </a>
        </div>
    </form>
</div>



<?php 
$content = ob_get_clean();
require BASE_PATH . 'views/layout.php';
?>
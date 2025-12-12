<?php 
ob_start(); 

require_once BASE_PATH . 'src/Archivos/ArchivoManager.php';
$archivoManager = new ArchivoManager();
$archivo = $archivoManager->obtenerPorId($version['archivo_id']);
?>

<div class="task-form">
    <h2>Detalles de Versión</h2>
    
    <div class="version-details">
        <p><strong>Archivo:</strong> <?= htmlspecialchars($archivo['nombre'] ?? 'Desconocido') ?></p>
        <p><strong>Número de versión:</strong> <span class="version-badge">v<?= $version['numero_version'] ?></span></p>
        <p><strong>Tamaño:</strong> <?= number_format($version['tamano'] / 1024, 2) ?> KB</p>
        <p><strong>Ruta:</strong> <?= htmlspecialchars($version['ruta_archivo']) ?></p>
        <p><strong>Fecha de creación:</strong> <?= htmlspecialchars($version['created_at']) ?></p>
    </div>
    
    <div class="form-actions">
        <a href="<?= BASE_URL ?>Versiones/restaurar/<?= $version['id'] ?>" 
           class="btn btn-warning">Restaurar Esta Versión</a>
        <a href="<?= BASE_URL ?>Versiones/por_archivo?archivo_id=<?= $version['archivo_id'] ?>" 
           class="btn btn-info">Ver Todas las Versiones</a>
        <a href="<?= BASE_URL ?>Versiones" class="btn btn-secondary">Volver</a>
    </div>
</div>



<?php 
$content = ob_get_clean();
require BASE_PATH . 'views/layout.php';
?>
<?php 
ob_start(); 
?>

<div class="task-form">
    <h2>Ver Archivo: <?= htmlspecialchars($archivo['nombre']) ?>.txt</h2>
    
    <div class="file-info">
        <p><strong>Tamaño:</strong> <?= number_format($archivo['tamano'] / 1024, 2) ?> KB</p>
        <p><strong>Fecha:</strong> <?= htmlspecialchars($archivo['created_at']) ?></p>
    </div>
    
    <div class="file-content">
        <textarea id="contenido" name="contenido" rows="20" style="width: 100%; font-family: monospace;" readonly><?= htmlspecialchars($contenido) ?></textarea>
    </div>
    
    <div class="form-actions">
        <a href="<?= BASE_URL ?>Archivos/download/<?= $archivo['id'] ?>" 
           class="btn btn-success">Descargar</a>
        <a href="<?= BASE_URL ?>Archivos" class="btn btn-secondary">← Volver</a>
    </div>
</div>



<?php 
$content = ob_get_clean();
require BASE_PATH . 'views/layout.php';
?>
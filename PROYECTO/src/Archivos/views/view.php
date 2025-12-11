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
        <pre><?= htmlspecialchars($contenido) ?></pre>
    </div>
    
    <div class="form-actions">
        <a href="<?= BASE_URL ?>Archivos/download/<?= $archivo['id'] ?>" 
           class="btn btn-success">Descargar</a>
        <a href="<?= BASE_URL ?>Archivos" class="btn btn-secondary">← Volver</a>
    </div>
</div>

<style>
    .file-info {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
    }
    .file-content {
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 20px;
        max-height: 500px;
        overflow-y: auto;
    }
    .file-content pre {
        margin: 0;
        white-space: pre-wrap;
        word-wrap: break-word;
        font-family: 'Courier New', monospace;
        font-size: 14px;
    }
</style>

<?php 
$content = ob_get_clean();
require BASE_PATH . 'views/layout.php';
?>
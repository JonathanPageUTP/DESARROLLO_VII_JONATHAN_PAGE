<?php 
ob_start(); 
?>

<div class="task-form">
    <h2>Editar Contenido: <?= htmlspecialchars($archivo['nombre']) ?></h2>
    
    <form method="POST" action="<?= BASE_URL ?>Archivos/save_content/<?= $archivo['id'] ?>">
        <div class="form-group">
            <label for="contenido">Contenido del archivo:</label>
            <textarea id="contenido" name="contenido" rows="20" style="width: 100%; font-family: monospace;"><?= htmlspecialchars($contenido) ?></textarea>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="<?= BASE_URL ?>Archivos" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
    
</div>

<?php 
$content = ob_get_clean();
require BASE_PATH . 'views/layout.php';
?>
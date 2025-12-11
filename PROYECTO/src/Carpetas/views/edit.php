<?php 
ob_start(); 
?>

<div class="task-form">
    <h2>Editar Carpeta</h2>
    <form method="POST" action="<?= BASE_URL ?>Carpetas/edit/<?= $carpeta['id'] ?>">
        <div class="form-group">
            <label for="nombre">Nombre de la carpeta:</label>
            <input type="text" id="nombre" name="nombre" 
                   value="<?= htmlspecialchars($carpeta['nombre'] ?? '') ?>" required>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="<?= BASE_URL ?>Carpetas" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<?php 
$content = ob_get_clean();
require BASE_PATH . 'views/layout.php';
?>
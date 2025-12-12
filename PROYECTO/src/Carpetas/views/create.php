<?php 
ob_start(); 
?>

<div class="task-form">
    <h2>Nueva Carpeta</h2>
    <form method="POST" action="<?= BASE_URL ?>Carpetas/create">
        <div class="form-group">
            <label for="nombre">Nombre de la carpeta:</label>
            <input type="text" id="nombre" name="nombre" required>
        </div>
        
        <div class="form-group">
            <label for="carpeta_padre_id">Carpeta padre (opcional):</label>
            <select id="carpeta_padre_id" name="carpeta_padre_id">
                <option value="">Raíz (sin padre)</option>
                <?php 
                $carpetaManager = new CarpetaManager();
                $todasCarpetas = $carpetaManager->obtenerTodos();
                foreach ($todasCarpetas as $c): 
                ?>
                    <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nombre']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <input type="hidden" name="usuario_id" value="<?= $_SESSION['usuario_id'] ?? 1 ?>">
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Crear</button>
            <a href="<?= BASE_URL ?>Carpetas" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<?php 
$content = ob_get_clean();
require BASE_PATH . 'views/layout.php';
?>
<?php 
ob_start(); 
?>

<div class="task-form">
    <h2>Nueva Versión</h2>
    <form method="POST" action="<?= BASE_URL ?>Versiones/create">
        <div class="form-group">
            <label for="archivo_id">Archivo:</label>
            <select id="archivo_id" name="archivo_id" required>
                <option value="">Seleccionar archivo</option>
                <?php 
                require_once BASE_PATH . 'src/Archivos/ArchivoManager.php';
                $archivoManager = new ArchivoManager();
                $archivos = $archivoManager->obtenerTodos();
                
                foreach ($archivos as $archivo): 
                ?>
                    <option value="<?= $archivo['id'] ?>">
                        <?= htmlspecialchars($archivo['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="ruta_archivo">Ruta del archivo de versión:</label>
            <input type="text" id="ruta_archivo" name="ruta_archivo" 
                   placeholder="/versiones/archivo_v2.txt" required>
        </div>
        
        <div class="form-group">
            <label for="tamano">Tamaño (bytes):</label>
            <input type="number" id="tamano" name="tamano" value="0" required>
        </div>
        
        <div class="form-group">
            <label for="numero_version">Número de versión (opcional):</label>
            <input type="number" id="numero_version" name="numero_version" 
                   placeholder="Se asignará automáticamente si se deja vacío">
            <small>Si no se especifica, se asignará el siguiente número disponible</small>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Crear Versión</button>
            <a href="<?= BASE_URL ?>Versiones" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>


<?php 
$content = ob_get_clean();
require BASE_PATH . 'views/layout.php';
?>
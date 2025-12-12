<?php 
ob_start(); 
?>

<div class="task-form">
    <h2>Mover Archivo: <?= htmlspecialchars($archivo['nombre']) ?></h2>
    
    <div class="file-info">
        <p><strong>Carpeta actual:</strong> 
            <?php if ($archivo['carpeta_id']): ?>
                <?php 
                    require_once BASE_PATH . 'src/Carpetas/CarpetaManager.php';
                    $carpetaManager = new CarpetaManager();
                    $carpetaActual = $carpetaManager->obtenerPorId($archivo['carpeta_id']);
                    echo htmlspecialchars($carpetaActual['nombre']);
                ?>
            <?php else: ?>
                Raíz (sin carpeta)
            <?php endif; ?>
        </p>
    </div>
    
    <form method="POST" action="<?= BASE_URL ?>Archivos/mover/<?= $archivo['id'] ?>">
        <div class="form-group">
            <label for="carpeta_id">Mover a carpeta:</label>
            <select id="carpeta_id" name="carpeta_id">
                <option value="">Raíz (sin carpeta)</option>
                <?php 
                require_once BASE_PATH . 'src/Carpetas/CarpetaManager.php';
                $carpetaManager = new CarpetaManager();
                $carpetas = $carpetaManager->obtenerTodos();
                
                function mostrarCarpetasJerarquicas($carpetas, $carpetaPadreId = null, $nivel = 0, $selectedId = null) {
                    foreach ($carpetas as $carpeta) {
                        if ($carpeta['carpeta_padre_id'] == $carpetaPadreId) {
                            $indent = str_repeat('&nbsp;&nbsp;&nbsp;', $nivel);
                            $selected = ($carpeta['id'] == $selectedId) ? 'selected' : '';
                            echo '<option value="' . $carpeta['id'] . '" ' . $selected . '>';
                            echo $indent . htmlspecialchars($carpeta['nombre']);
                            echo '</option>';
                            mostrarCarpetasJerarquicas($carpetas, $carpeta['id'], $nivel + 1, $selectedId);
                        }
                    }
                }
                
                mostrarCarpetasJerarquicas($carpetas, null, 0, $archivo['carpeta_id']);
                ?>
            </select>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Mover</button>
            <a href="<?= BASE_URL ?>Archivos" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<?php 
$content = ob_get_clean();
require BASE_PATH . 'views/layout.php';
?>
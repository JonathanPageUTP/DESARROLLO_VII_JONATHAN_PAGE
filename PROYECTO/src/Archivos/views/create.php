<?php 
ob_start(); 
?>

<div class="task-form">
    <h2>Subir Nuevo Archivo</h2>
    <form method="POST" action="<?= BASE_URL ?>Archivos/create" enctype="multipart/form-data">
        <div class="form-group">
            <label for="archivo">Seleccionar archivo (.txt):</label>
            <input type="file" id="archivo" name="archivo" accept=".txt" required>
            <small>Solo archivos .txt permitidos</small>
        </div>
        
        <div class="form-group">
            <label for="nombre">Nombre personalizado (opcional):</label>
            <input type="text" id="nombre" name="nombre" 
                   placeholder="Dejar vacío para usar el nombre original">
        </div>
        
        <div class="form-group">
            <label for="carpeta_id">Carpeta destino:</label>
            <select id="carpeta_id" name="carpeta_id">
                <option value="">Raíz (sin carpeta)</option>
                <?php 
                require_once BASE_PATH . 'src/Carpetas/CarpetaManager.php';
                $carpetaManager = new CarpetaManager();
                $carpetas = $carpetaManager->obtenerTodos();
                
                function mostrarCarpetasJerarquicas($carpetas, $carpetaPadreId = null, $nivel = 0) {
                    foreach ($carpetas as $carpeta) {
                        if ($carpeta['carpeta_padre_id'] == $carpetaPadreId) {
                            $indent = str_repeat('&nbsp;&nbsp;&nbsp;', $nivel);
                            echo '<option value="' . $carpeta['id'] . '">';
                            echo $indent . htmlspecialchars($carpeta['nombre']);
                            echo '</option>';
                            mostrarCarpetasJerarquicas($carpetas, $carpeta['id'], $nivel + 1);
                        }
                    }
                }
                
                mostrarCarpetasJerarquicas($carpetas);
                ?>
            </select>
        </div>
        
        <input type="hidden" name="usuario_id" value="1">
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Subir Archivo</button>
            <a href="<?= BASE_URL ?>Archivos" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<style>
    input[type="file"] {
        padding: 10px;
        border: 2px dashed #007bff;
        border-radius: 5px;
        background: #f8f9fa;
        cursor: pointer;
        width: 100%;
    }
    input[type="file"]:hover {
        background: #e9ecef;
    }
    small {
        display: block;
        margin-top: 5px;
        color: #666;
        font-size: 0.9em;
    }
</style>

<?php 
$content = ob_get_clean();
require BASE_PATH . 'views/layout.php';
?>
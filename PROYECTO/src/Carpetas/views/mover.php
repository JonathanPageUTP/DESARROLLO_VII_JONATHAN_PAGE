<?php 
ob_start(); 
?>

<div class="task-form">
    <h2>Mover Carpeta: <?= htmlspecialchars($carpeta['nombre']) ?></h2>
    <form method="POST" action="<?= BASE_URL ?>Carpetas/mover/<?= $carpeta['id'] ?>">
        <div class="form-group">
            <label for="carpeta_padre_id">Mover a:</label>
            <select id="carpeta_padre_id" name="carpeta_padre_id">
                <option value="">Raíz (sin padre)</option>
                <?php 
                foreach ($carpetas as $c): 
                    if ($c['id'] != $carpeta['id']):
                ?>
                    <option value="<?= $c['id'] ?>" 
                            <?= ($c['id'] == $carpeta['carpeta_padre_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['nombre']) ?>
                    </option>
                <?php 
                    endif;
                endforeach; 
                ?>
            </select>
            <small>No puedes mover una carpeta a una de sus subcarpetas</small>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Mover</button>
            <a href="<?= BASE_URL ?>Carpetas" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>



<?php 
$content = ob_get_clean();
require BASE_PATH . 'views/layout.php';
?>
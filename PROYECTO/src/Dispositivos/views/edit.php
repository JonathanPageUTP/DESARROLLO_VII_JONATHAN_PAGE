<?php 
ob_start(); 
?>

<div class="task-form">
    <h2>Editar Dispositivo</h2>
    <form method="POST" action="<?= BASE_URL ?>Dispositivos/edit/<?= $dispositivo['id'] ?>">
        <div class="form-group">
            <label for="nombre">Nombre del dispositivo:</label>
            <input type="text" id="nombre" name="nombre" 
                   value="<?= htmlspecialchars($dispositivo['nombre'] ?? '') ?>" required>
        </div>
        
        <div class="form-group">
            <label for="tipo">Tipo de dispositivo:</label>
            <select id="tipo" name="tipo" required>
                <option value="">Seleccionar tipo</option>
                <option value="PC" <?= ($dispositivo['tipo'] == 'PC') ? 'selected' : '' ?>>PC</option>
                <option value="Laptop" <?= ($dispositivo['tipo'] == 'Laptop') ? 'selected' : '' ?>>Laptop</option>
                <option value="Móvil" <?= ($dispositivo['tipo'] == 'Móvil') ? 'selected' : '' ?>>Móvil</option>
                <option value="Tablet" <?= ($dispositivo['tipo'] == 'Tablet') ? 'selected' : '' ?>>Tablet</option>
            </select>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="<?= BASE_URL ?>Dispositivos" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<?php 
$content = ob_get_clean();
require BASE_PATH . 'views/layout.php';
?>
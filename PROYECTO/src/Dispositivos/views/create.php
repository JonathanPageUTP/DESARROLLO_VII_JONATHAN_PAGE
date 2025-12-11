<?php 
ob_start(); 
?>

<div class="task-form">
    <h2>Nuevo Dispositivo</h2>
    <form method="POST" action="<?= BASE_URL ?>Dispositivos/create">
        <div class="form-group">
            <label for="nombre">Nombre del dispositivo:</label>
            <input type="text" id="nombre" name="nombre" 
                   placeholder="Ej: Mi laptop personal" required>
        </div>
        
        <div class="form-group">
            <label for="tipo">Tipo de dispositivo:</label>
            <select id="tipo" name="tipo" required>
                <option value="">Seleccionar tipo</option>
                <option value="PC">PC</option>
                <option value="Laptop">Laptop</option>
                <option value="Móvil">Móvil</option>
                <option value="Tablet">Tablet</option>
            </select>
        </div>
        
        <input type="hidden" name="usuario_id" value="1">
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Crear</button>
            <a href="<?= BASE_URL ?>Dispositivos" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<?php 
$content = ob_get_clean();
require BASE_PATH . 'views/layout.php';
?>
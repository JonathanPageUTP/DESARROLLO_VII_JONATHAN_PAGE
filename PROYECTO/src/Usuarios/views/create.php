<?php 
ob_start(); 
?>

<div class="task-form">
    <h2>Nuevo Usuario</h2>
    <form method="POST" action="<?= BASE_URL ?>Usuarios/create">
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        
        <div class="form-group">
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="<?= BASE_URL ?>Usuarios" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<?php 
$content = ob_get_clean();
require BASE_PATH . 'views/layout.php';
?>
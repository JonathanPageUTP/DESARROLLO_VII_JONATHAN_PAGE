<?php 
ob_start(); 
?>

<div class="task-form">
    <h2>Editar Usuario</h2>
    <form method="POST" action="<?= BASE_URL ?>Usuarios/edit/<?= $usuario['id'] ?>">
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" 
                   value="<?= htmlspecialchars($usuario['nombre'] ?? '') ?>" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" 
                   value="<?= htmlspecialchars($usuario['email'] ?? '') ?>" required>
        </div>
        
        <div class="form-group">
            <label for="password">Nueva Contraseña (opcional):</label>
            <input type="password" id="password" name="password" 
                   placeholder="Dejar vacío para no cambiar">
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Actualizar</button>
            <a href="<?= BASE_URL ?>Usuarios" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<?php 
$content = ob_get_clean();
require BASE_PATH . 'views/layout.php';
?>                                                                                                              
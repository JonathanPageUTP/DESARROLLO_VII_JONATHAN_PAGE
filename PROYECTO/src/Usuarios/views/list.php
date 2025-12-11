<?php 
ob_start(); 
?>

<div class="task-list">
    <h2>Usuarios</h2>
    <a href="<?= BASE_URL ?>Usuarios/create" class="btn">Nuevo Usuario</a>
    
    <?php if (empty($usuarios)): ?>
        <p>No hay usuarios registrados.</p>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Fecha Creación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?= htmlspecialchars($usuario['id'] ?? '') ?></td>
                    <td><?= htmlspecialchars($usuario['nombre'] ?? '') ?></td>
                    <td><?= htmlspecialchars($usuario['email'] ?? '') ?></td>
                    <td><?= htmlspecialchars($usuario['fecha_creacion'] ?? '') ?></td>
                    <td>
                        <a href="<?= BASE_URL ?>Usuarios/edit/<?= $usuario['id'] ?>" 
                           class="btn btn-edit">Editar</a>
                        <a href="<?= BASE_URL ?>Usuarios/delete/<?= $usuario['id'] ?>" 
                           class="btn btn-delete" 
                           onclick="return confirm('¿Eliminar este usuario?')">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php 
$content = ob_get_clean();
require BASE_PATH . 'views/layout.php';
?>
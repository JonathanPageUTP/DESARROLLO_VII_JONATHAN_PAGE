<?php 
ob_start(); 
?>

<div class="task-list">
    <h2>Dispositivos</h2>
    <a href="<?= BASE_URL ?>Dispositivos/create" class="btn">Nuevo Dispositivo</a>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_SESSION['success']) ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <?= htmlspecialchars($_SESSION['error']) ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    
    <?php if (empty($dispositivos)): ?>
        <p>No hay dispositivos registrados.</p>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Fecha Registro</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dispositivos as $dispositivo): ?>
                <tr>
                    <td><?= htmlspecialchars($dispositivo['id'] ?? '') ?></td>
                    <td><?= htmlspecialchars($dispositivo['nombre'] ?? '') ?></td>
                    <td>
                        <span class="badge badge-<?= strtolower($dispositivo['tipo']) ?>">
                            <?= htmlspecialchars($dispositivo['tipo'] ?? '') ?>
                        </span>
                    </td>
                    <td><?= htmlspecialchars($dispositivo['created_at'] ?? '') ?></td>
                    <td>
                        <a href="<?= BASE_URL ?>Dispositivos/edit/<?= $dispositivo['id'] ?>" 
                           class="btn btn-edit">Editar</a>
                        <a href="<?= BASE_URL ?>Dispositivos/delete/<?= $dispositivo['id'] ?>" 
                           class="btn btn-delete" 
                           onclick="return confirm('¿Eliminar este dispositivo?')">Eliminar</a>
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
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

<style>
    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 5px;
    }
    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    .badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 3px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }
    .badge-pc {
        background: #e3f2fd;
        color: #1976d2;
    }
    .badge-laptop {
        background: #f3e5f5;
        color: #7b1fa2;
    }
    .badge-móvil, .badge-movil {
        background: #e8f5e9;
        color: #388e3c;
    }
    .badge-tablet {
        background: #fff3e0;
        color: #f57c00;
    }
</style>

<?php 
$content = ob_get_clean();
require BASE_PATH . 'views/layout.php';
?>
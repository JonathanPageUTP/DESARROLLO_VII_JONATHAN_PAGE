<?php 
ob_start(); 
?>

<div class="task-list">
    <h2>Carpetas</h2>
    <a href="<?= BASE_URL ?>Carpetas/create" class="btn">Nueva Carpeta</a>
    
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
    
    <?php if (empty($carpetas)): ?>
        <p>No hay carpetas registradas.</p>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Carpeta Padre</th>
                    <th>Fecha Creación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($carpetas as $carpeta): ?>
                <tr>
                    <td><?= htmlspecialchars($carpeta['id'] ?? '') ?></td>
                    <td><?= htmlspecialchars($carpeta['nombre'] ?? '') ?></td>
                    <td>
                        <?php if ($carpeta['carpeta_padre_id']): ?>
                            <?php 
                                $carpetaPadre = (new CarpetaManager())->obtenerPorId($carpeta['carpeta_padre_id']);
                                echo htmlspecialchars($carpetaPadre['nombre'] ?? 'Carpeta #' . $carpeta['carpeta_padre_id']);
                            ?>
                        <?php else: ?>
                            Raíz
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($carpeta['created_at'] ?? '') ?></td>
                    <td>
                        <a href="<?= BASE_URL ?>Carpetas/ver/<?= $carpeta['id'] ?>" 
                           class="btn btn-info">Ver</a>
                        <a href="<?= BASE_URL ?>Carpetas/edit/<?= $carpeta['id'] ?>" 
                           class="btn btn-edit">Editar</a>
                        <a href="<?= BASE_URL ?>Carpetas/mover/<?= $carpeta['id'] ?>" 
                           class="btn btn-warning">Mover</a>
                        <a href="<?= BASE_URL ?>Carpetas/delete/<?= $carpeta['id'] ?>" 
                           class="btn btn-delete" 
                           onclick="return confirm('¿Eliminar esta carpeta? (Las subcarpetas permanecerán)')">Eliminar</a>
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
    .btn-info {
        background: #17a2b8;
    }
    .btn-warning {
        background: #ffc107;
        color: #000;
    }
</style>

<?php 
$content = ob_get_clean();
require BASE_PATH . 'views/layout.php';
?>
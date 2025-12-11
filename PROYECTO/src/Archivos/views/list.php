<?php 
ob_start(); 
?>

<div class="task-list">
    <h2>Archivos</h2>
    <a href="<?= BASE_URL ?>Archivos/create" class="btn">Subir Archivo</a>
    
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
    
    <?php if (empty($archivos)): ?>
        <p>No hay archivos registrados.</p>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Tamaño</th>
                    <th>Carpeta</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                require_once BASE_PATH . 'src/Carpetas/CarpetaManager.php';
                $carpetaManager = new CarpetaManager();
                
                foreach ($archivos as $archivo): 
                ?>
                <tr>
                    <td><?= htmlspecialchars($archivo['id'] ?? '') ?></td>
                    <td><?= htmlspecialchars($archivo['nombre'] ?? '') ?></td>
                    <td><?= number_format($archivo['tamano'] / 1024, 2) ?> KB</td>
                    <td>
                        <?php if ($archivo['carpeta_id']): ?>
                            <?php 
                                $carpeta = $carpetaManager->obtenerPorId($archivo['carpeta_id']);
                                if ($carpeta) {
                                    echo '<span class="carpeta-badge">' . htmlspecialchars($carpeta['nombre']) . '</span>';
                                } else {
                                    echo 'Sin carpeta';
                                }
                            ?>
                        <?php else: ?>
                            <span class="carpeta-badge-raiz">Raíz</span>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($archivo['created_at'] ?? '') ?></td>
                    <td>
                        <a href="<?= BASE_URL ?>Archivos/view/<?= $archivo['id'] ?>" 
                           class="btn btn-info">Ver</a>
                        <a href="<?= BASE_URL ?>Archivos/download/<?= $archivo['id'] ?>" 
                           class="btn btn-success">Descargar</a>
                        <a href="<?= BASE_URL ?>Archivos/mover/<?= $archivo['id'] ?>" 
                           class="btn btn-warning">Mover</a>
                        <a href="<?= BASE_URL ?>Archivos/edit/<?= $archivo['id'] ?>" 
                           class="btn btn-edit">Editar</a>
                        <a href="<?= BASE_URL ?>Archivos/delete/<?= $archivo['id'] ?>" 
                           class="btn btn-delete" 
                           onclick="return confirm('¿Eliminar este archivo?')">Eliminar</a>
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
    .btn-success {
        background: #28a745;
    }
    .btn-warning {
        background: #ffc107;
        color: #000;
    }
    .carpeta-badge {
        display: inline-block;
        padding: 4px 8px;
        background: #e3f2fd;
        color: #1976d2;
        border-radius: 3px;
        font-size: 12px;
        font-weight: 600;
    }
    .carpeta-badge-raiz {
        display: inline-block;
        padding: 4px 8px;
        background: #f5f5f5;
        color: #666;
        border-radius: 3px;
        font-size: 12px;
        font-weight: 600;
    }
</style>

<?php 
$content = ob_get_clean();
require BASE_PATH . 'views/layout.php';
?>
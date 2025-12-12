<?php 
ob_start(); 

require_once BASE_PATH . 'src/Archivos/ArchivoManager.php';
$archivoManager = new ArchivoManager();
$archivo = $archivoManager->obtenerPorId($_GET['archivo_id']);
?>

<div class="task-list">
    <h2>Versiones de: <?= htmlspecialchars($archivo['nombre'] ?? 'Archivo desconocido') ?></h2>
    
    <div class="actions-bar">
        <a href="<?= BASE_URL ?>Versiones" class="btn btn-secondary">Volver</a>
    </div>
    
    <?php if (empty($versiones)): ?>
        <p>No hay versiones para este archivo.</p>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Versión</th>
                    <th>Tamaño</th>
                    <th>Fecha Creación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($versiones as $version): ?>
                <tr>
                    <td><span class="version-badge">v<?= $version['numero_version'] ?></span></td>
                    <td><?= number_format($version['tamano'] / 1024, 2) ?> KB</td>
                    <td><?= htmlspecialchars($version['created_at'] ?? '') ?></td>
                    <td>
                        <a href="<?= BASE_URL ?>Versiones/ver/<?= $version['id'] ?>" 
                           class="btn btn-info">Ver</a>
                        <a href="<?= BASE_URL ?>Versiones/restaurar/<?= $version['id'] ?>" 
                           class="btn btn-warning">Restaurar</a>
                        <a href="<?= BASE_URL ?>Versiones/delete/<?= $version['id'] ?>" 
                           class="btn btn-delete" 
                           onclick="return confirm('¿Eliminar esta versión?')">Eliminar</a>
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
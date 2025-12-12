<?php 
ob_start(); 
?>

<div class="task-list">
    <h2>Versiones</h2>
    
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
    
    <?php if (empty($versiones)): ?>
        <p>No hay versiones registradas.</p>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Archivo</th>
                    <th>Número Versión</th>
                    <th>Tamaño</th>
                    <th>Fecha Creación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            
            <tbody>
                <?php 
                require_once BASE_PATH . 'src/Archivos/ArchivoManager.php';
                $archivoManager = new ArchivoManager();
                
                foreach ($versiones as $version): 
                    $archivo = $archivoManager->obtenerPorId($version['archivo_id']);
                ?>
                <tr>
                    <td><?= htmlspecialchars($version['id'] ?? '') ?></td>
                    <td>
                        <?php if ($archivo): ?>
                            <a href="<?= BASE_URL ?>Versiones/por_archivo?archivo_id=<?= $version['archivo_id'] ?>">
                                <?= htmlspecialchars($archivo['nombre']) ?>
                            </a>
                        <?php else: ?>
                            Archivo ID: <?= $version['archivo_id'] ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="version-badge">v<?= $version['numero_version'] ?></span>
                    </td>
                    <td><?= number_format($version['tamano'] / 1024, 2) ?> KB</td>
                    <td><?= htmlspecialchars($version['created_at'] ?? '') ?></td>
                    <td>
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
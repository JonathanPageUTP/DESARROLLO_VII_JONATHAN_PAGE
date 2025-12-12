<?php 
ob_start(); 
?>

<div class="task-form">
    <h2>Detalles de Carpeta</h2>
    
    <div class="breadcrumb">
        <strong>Ruta:</strong> 
        <?php foreach ($ruta as $index => $r): ?>
            <?php if ($index > 0): ?> / <?php endif; ?>
            <a href="<?= BASE_URL ?>Carpetas/ver/<?= $r['id'] ?>">
                <?= htmlspecialchars($r['nombre']) ?>
            </a>
        <?php endforeach; ?>
    </div>
    
    <div class="folder-info">
        <p><strong>Nombre:</strong> <?= htmlspecialchars($carpeta['nombre']) ?></p>
        <p><strong>Fecha de creación:</strong> <?= htmlspecialchars($carpeta['created_at']) ?></p>
        <p><strong>Carpeta padre:</strong> 
            <?php if ($carpeta['carpeta_padre_id']): ?>
                <?php 
                    $carpetaPadre = (new CarpetaManager())->obtenerPorId($carpeta['carpeta_padre_id']);
                    echo htmlspecialchars($carpetaPadre['nombre']);
                ?>
            <?php else: ?>
                Raíz
            <?php endif; ?>
        </p>
    </div>
    
    <h3>Subcarpetas</h3>
    <?php if (empty($subcarpetas)): ?>
        <p>No hay subcarpetas en esta carpeta.</p>
    <?php else: ?>
        <ul class="subcarpetas-list">
            <?php foreach ($subcarpetas as $sub): ?>
                <li>
                    <a href="<?= BASE_URL ?>Carpetas/ver/<?= $sub['id'] ?>">
                        <?= htmlspecialchars($sub['nombre']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    
    <div class="form-actions">
        <a href="<?= BASE_URL ?>Carpetas/edit/<?= $carpeta['id'] ?>" class="btn btn-edit">Editar</a>
        <a href="<?= BASE_URL ?>Carpetas/mover/<?= $carpeta['id'] ?>" class="btn btn-warning">Mover</a>
        <a href="<?= BASE_URL ?>Carpetas" class="btn btn-secondary">Volver</a>
    </div>
</div>


<?php 
$content = ob_get_clean();
require BASE_PATH . 'views/layout.php';
?>
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

<style>
    .breadcrumb {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
    }
    .breadcrumb a {
        color: #007bff;
        text-decoration: none;
    }
    .breadcrumb a:hover {
        text-decoration: underline;
    }
    .folder-info {
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 15px;
        margin-bottom: 20px;
    }
    .folder-info p {
        margin: 10px 0;
    }
    .subcarpetas-list {
        list-style: none;
        padding: 0;
    }
    .subcarpetas-list li {
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        margin-bottom: 10px;
        background: #f8f9fa;
    }
    .subcarpetas-list a {
        color: #007bff;
        text-decoration: none;
        font-weight: 500;
    }
    .subcarpetas-list a:hover {
        text-decoration: underline;
    }
</style>

<?php 
$content = ob_get_clean();
require BASE_PATH . 'views/layout.php';
?>
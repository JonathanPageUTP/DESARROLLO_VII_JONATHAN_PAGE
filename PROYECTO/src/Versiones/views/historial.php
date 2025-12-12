<?php 
ob_start(); 
?>

<div class="task-form">
    <h2>Historial Completo de Versiones</h2>
    
    <div class="estadisticas-box">
        <h3>Estadísticas</h3>
        <div class="stats-grid">
            <div class="stat-item">
                <span class="stat-label">Total de versiones:</span>
                <span class="stat-value"><?= $estadisticas['total_versiones'] ?></span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Versión actual:</span>
                <span class="stat-value">v<?= $estadisticas['version_actual'] ?></span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Tamaño total:</span>
                <span class="stat-value"><?= number_format($estadisticas['tamano_total'] / 1024, 2) ?> KB</span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Tamaño promedio:</span>
                <span class="stat-value"><?= number_format($estadisticas['tamano_promedio'] / 1024, 2) ?> KB</span>
            </div>
        </div>
    </div>
    
    <h3>Historial</h3>
    <?php if (empty($historial)): ?>
        <p>No hay historial disponible.</p>
    <?php else: ?>
        <div class="timeline">
            <?php foreach ($historial as $version): ?>
            <div class="timeline-item">
                <div class="timeline-marker">v<?= $version['numero_version'] ?></div>
                <div class="timeline-content">
                    <h4>Versión <?= $version['numero_version'] ?></h4>
                    <p><strong>Tamaño:</strong> <?= number_format($version['tamano'] / 1024, 2) ?> KB</p>
                    <p><strong>Fecha:</strong> <?= htmlspecialchars($version['created_at']) ?></p>
                    <p><strong>Ruta:</strong> <?= htmlspecialchars($version['ruta_archivo']) ?></p>
                    <div class="timeline-actions">
                        <a href="<?= BASE_URL ?>Versiones/ver/<?= $version['id'] ?>" class="btn btn-small">Ver</a>
                        <a href="<?= BASE_URL ?>Versiones/restaurar/<?= $version['id'] ?>" class="btn btn-small">Restaurar</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <div class="form-actions">
        <a href="<?= BASE_URL ?>Versiones" class="btn btn-secondary">Volver</a>
    </div>
</div>

<style>
    .estadisticas-box {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 5px;
        margin-bottom: 30px;
        border: 1px solid #e0e0e0;
    }
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-top: 15px;
    }
    .stat-item {
        background: white;
        padding: 15px;
        border-radius: 5px;
        border: 1px solid #e0e0e0;
    }
    .stat-label {
        display: block;
        color: #666;
        font-size: 14px;
        margin-bottom: 5px;
    }
    .stat-value {
        display: block;
        color: #1a1a1a;
        font-size: 24px;
        font-weight: 600;
    }
    .timeline {
        position: relative;
        padding-left: 40px;
    }
    .timeline::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e0e0e0;
    }
    .timeline-item {
        position: relative;
        margin-bottom: 30px;
    }
    .timeline-marker {
        position: absolute;
        left: -40px;
        top: 0;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #007bff;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        font-weight: 600;
        border: 3px solid white;
        box-shadow: 0 0 0 2px #007bff;
    }
    .timeline-content {
        background: white;
        padding: 15px;
        border: 1px solid #e0e0e0;
        border-radius: 5px;
    }
    .timeline-content h4 {
        margin-top: 0;
        color: #1a1a1a;
    }
    .timeline-content p {
        margin: 5px 0;
        color: #666;
    }
    .timeline-actions {
        margin-top: 10px;
        display: flex;
        gap: 10px;
    }
    .btn-small {
        padding: 5px 10px;
        font-size: 12px;
    }
</style>

<?php 
$content = ob_get_clean();
require BASE_PATH . 'views/layout.php';
?>
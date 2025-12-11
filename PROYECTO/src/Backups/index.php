<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('BASE_PATH', dirname(dirname(__DIR__)) . '/');

require_once BASE_PATH . 'config.php';

require_once BASE_PATH . 'src/Database.php';
require_once 'BackupManager.php';
require_once 'Backup.php';


$backupManager = new BackupManager(); 


$action = $_GET['action'] ?? 'list';


switch ($action) {
    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuarioId = $_POST['usuario_id'] ?? 1;
            $nombre = $_POST['nombre'] ?? '';
            $rutaBackup = $_POST['ruta_backup'] ?? '';
            $estado = $_POST['estado'] ?? 'en_progreso';

            if (!empty($nombre) && !empty($rutaBackup)) {
                $backupManager->crearBackup($usuarioId, $nombre, $rutaBackup, $estado);
            }
            header('Location: ' . BASE_URL . "src/Backups");
            exit;
        }
        require BASE_PATH . 'views/backup_form.php'; 
        break;

    case 'edit': 
        $id = $_GET['id'] ?? null;

        if ($id && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';

            if (!empty($nombre)) {
                $backupManager->actualizarBackup($id, $nombre);
            }
            header('Location: ' . BASE_URL . "src/Backups");
            exit;
        } elseif ($id) {
            $backup = $backupManager->obtenerPorId($id);
            if ($backup) {
                require BASE_PATH . 'views/backup_edit.php';
            } else {
                header('Location: ' . BASE_URL . "src/Backups"); 
            }
        }
        break;

    case 'completar':
        $id = $_GET['id'] ?? null;
        
        if ($id && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $tamanoTotal = $_POST['tamano_total'] ?? 0;
            $backupManager->completarBackup($id, $tamanoTotal);
            header('Location: ' . BASE_URL . "src/Backups");
            exit;
        } elseif ($id) {
            $backup = $backupManager->obtenerPorId($id);
            if ($backup) {
                require BASE_PATH . 'views/backup_completar.php';
            } else {
                header('Location: ' . BASE_URL . "src/Backups");
            }
        }
        break;

    case 'marcar_fallido':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $backupManager->marcarComoFallido($id);
        }
        header('Location: ' . BASE_URL . "src/Backups");
        exit;

    case 'cambiar_estado':
        $id = $_GET['id'] ?? null;
        
        if ($id && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $estado = $_POST['estado'] ?? 'en_progreso';
            $backupManager->actualizarEstado($id, $estado);
            header('Location: ' . BASE_URL . "src/Backups");
            exit;
        }
        break;

    case 'delete':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $backupManager->eliminarBackup($id);
        }
        header('Location: ' . BASE_URL . "src/Backups");
        exit;

    case 'en_progreso':
        $backups = $backupManager->obtenerEnProgreso();
        print_r($backups);
        exit;

    case 'completados':
        $backups = $backupManager->obtenerCompletados();
        print_r($backups);
        exit;

    case 'fallidos':
        $backups = $backupManager->obtenerFallidos();
        print_r($backups);
        exit;
        
    default:
        $backups = $backupManager->obtenerTodos(); 
        print_r($backups);
        exit;
        require BASE_PATH . 'views/backup_list.php'; 
        break;
}
?>
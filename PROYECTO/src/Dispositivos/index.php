<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('BASE_PATH', dirname(dirname(__DIR__)) . '/');

require_once BASE_PATH . 'config.php';
require_once BASE_PATH . 'src/Database.php';
require_once __DIR__ . '/DispositivoManager.php';
require_once __DIR__ . '/Dispositivo.php';

session_start();

$dispositivoManager = new DispositivoManager(); 
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

switch ($action) {
    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuarioId = $_POST['usuario_id'] ?? 1;
            $nombre = $_POST['nombre'] ?? '';
            $tipo = $_POST['tipo'] ?? '';

            if (!empty($nombre) && !empty($tipo)) {
                $dispositivoManager->crearDispositivo($usuarioId, $nombre, $tipo);
                $_SESSION['success'] = 'Dispositivo creado correctamente';
                header('Location: ' . BASE_URL . 'Dispositivos');
                exit;
            }
        }
        require __DIR__ . '/views/create.php'; 
        break;

    case 'edit': 
        if ($id && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $tipo = $_POST['tipo'] ?? '';

            if (!empty($nombre) && !empty($tipo)) {
                $dispositivoManager->actualizarDispositivo($id, $nombre, $tipo);
                $_SESSION['success'] = 'Dispositivo actualizado correctamente';
                header('Location: ' . BASE_URL . 'Dispositivos');
                exit;
            }
        } elseif ($id) {
            $dispositivo = $dispositivoManager->obtenerPorId($id);
            if ($dispositivo) {
                require __DIR__ . '/views/edit.php';
            } else {
                header('Location: ' . BASE_URL . 'Dispositivos'); 
                exit;
            }
        }
        break;

    case 'delete':
        if ($id) {
            $dispositivoManager->eliminarDispositivo($id);
            $_SESSION['success'] = 'Dispositivo eliminado correctamente';
        }
        header('Location: ' . BASE_URL . 'Dispositivos');
        exit;
        
    case 'list':
    default:
        $dispositivos = $dispositivoManager->obtenerTodos(); 
        require __DIR__ . '/views/list.php'; 
        break;
}
?>
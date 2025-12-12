<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('BASE_PATH', dirname(dirname(__DIR__)) . '/');

require_once BASE_PATH . 'config.php';
require_once BASE_PATH . 'src/Database.php';
require_once __DIR__ . '/CarpetaManager.php';
require_once __DIR__ . '/Carpeta.php';

session_start();

$carpetaManager = new CarpetaManager(); 
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

switch ($action) {
    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuarioId = $_POST['usuario_id'] ?? 1;
            $nombre = $_POST['nombre'] ?? '';
            $carpetaPadreId = !empty($_POST['carpeta_padre_id']) ? (int)$_POST['carpeta_padre_id'] : null;

            if (!empty($nombre)) {
                $carpetaManager->crearCarpeta($usuarioId, $nombre, $carpetaPadreId);
                $_SESSION['success'] = 'Carpeta creada correctamente';
                header('Location: ' . BASE_URL . 'Carpetas');
                exit;
            }
        }
        require __DIR__ . '/views/create.php'; 
        break;

    case 'edit': 
        if ($id && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';

            if (!empty($nombre)) {
                $carpetaManager->actualizarCarpeta($id, $nombre);
                $_SESSION['success'] = 'Carpeta actualizada correctamente';
                header('Location: ' . BASE_URL . 'Carpetas');
                exit;
            }
        } elseif ($id) {
            $carpeta = $carpetaManager->obtenerPorId($id);
            if ($carpeta) {
                require __DIR__ . '/views/edit.php';
            } else {
                header('Location: ' . BASE_URL . 'Carpetas'); 
                exit;
            }
        }
        break;

    case 'mover':
        if ($id && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $carpetaPadreId = !empty($_POST['carpeta_padre_id']) ? (int)$_POST['carpeta_padre_id'] : null;
            $resultado = $carpetaManager->moverCarpeta($id, $carpetaPadreId);
            
            if (!$resultado) {
                $_SESSION['error'] = 'No se puede mover una carpeta a una de sus subcarpetas';
            } else {
                $_SESSION['success'] = 'Carpeta movida correctamente';
            }
            
            header('Location: ' . BASE_URL . 'Carpetas');
            exit;
        } elseif ($id) {
            $carpeta = $carpetaManager->obtenerPorId($id);
            if ($carpeta) {
                $carpetas = $carpetaManager->obtenerTodos();
                require __DIR__ . '/views/mover.php';
            } else {
                header('Location: ' . BASE_URL . 'Carpetas');
                exit;
            }
        }
        break;

    case 'delete':
        if ($id) {
            $carpetaManager->eliminarCarpeta($id);
            $_SESSION['success'] = 'Carpeta eliminada correctamente';
        }
        header('Location: ' . BASE_URL . 'Carpetas');
        exit;

    case 'ver':
        if ($id) {
            $carpeta = $carpetaManager->obtenerPorId($id);
            $subcarpetas = $carpetaManager->obtenerSubcarpetas($id);
            $ruta = $carpetaManager->obtenerRutaCarpeta($id);
            
            require __DIR__ . '/views/ver.php';
        } else {
            header('Location: ' . BASE_URL . 'Carpetas');
            exit;
        }
        break;
        
    case 'list':
default:
    $usuarioId = $_SESSION['usuario_id'] ?? 1;
    $carpetas = $carpetaManager->obtenerPorUsuario($usuarioId); 
    require __DIR__ . '/views/list.php'; 
    break;
}
?>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('BASE_PATH', dirname(dirname(__DIR__)) . '/');

require_once BASE_PATH . 'config.php';
require_once BASE_PATH . 'src/Database.php';
require_once __DIR__ . '/ArchivoManager.php';
require_once __DIR__ . '/Archivo.php';

session_start();

$archivoManager = new ArchivoManager(); 
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

switch ($action) {
    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuarioId = $_POST['usuario_id'] ?? 1;
            $carpetaId = !empty($_POST['carpeta_id']) ? (int)$_POST['carpeta_id'] : null;
            
            if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['archivo']['tmp_name'];
                $fileName = $_FILES['archivo']['name'];
                $fileSize = $_FILES['archivo']['size'];
                $fileType = $_FILES['archivo']['type'];
                $fileNameCmps = explode(".", $fileName);
                $fileExtension = strtolower(end($fileNameCmps));
                
                if ($fileExtension !== 'txt') {
                    $_SESSION['error'] = 'Solo se permiten archivos .txt';
                    header('Location: ' . BASE_URL . 'Archivos/create');
                    exit;
                }
                
                $nombreArchivo = !empty($_POST['nombre']) ? $_POST['nombre'] : pathinfo($fileName, PATHINFO_FILENAME);
                $newFileName = uniqid() . '_' . $fileName;
                
                $uploadFileDir = BASE_PATH . 'uploads/';
                if (!file_exists($uploadFileDir)) {
                    mkdir($uploadFileDir, 0755, true);
                }
                
                $dest_path = $uploadFileDir . $newFileName;
                
                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    $rutaArchivo = 'uploads/' . $newFileName;
                    $archivoManager->crearArchivo($usuarioId, $carpetaId, $nombreArchivo, $fileSize, $rutaArchivo);
                    
                    $_SESSION['success'] = 'Archivo subido correctamente';
                    header('Location: ' . BASE_URL . 'Archivos');
                    exit;
                } else {
                    $_SESSION['error'] = 'Error al mover el archivo';
                }
            } else {
                $_SESSION['error'] = 'Error al subir el archivo';
            }
            
            header('Location: ' . BASE_URL . 'Archivos/create');
            exit;
        }
        require __DIR__ . '/views/create.php'; 
        break;

    case 'download':
        if ($id) {
            $archivo = $archivoManager->obtenerPorId($id);
            if ($archivo && file_exists(BASE_PATH . $archivo['ruta_archivo'])) {
                $filePath = BASE_PATH . $archivo['ruta_archivo'];
                
                header('Content-Type: text/plain');
                header('Content-Disposition: attachment; filename="' . $archivo['nombre'] . '.txt"');
                header('Content-Length: ' . filesize($filePath));
                
                readfile($filePath);
                exit;
            }
        }
        header('Location: ' . BASE_URL . 'Archivos');
        exit;

    case 'view':
        if ($id) {
            $archivo = $archivoManager->obtenerPorId($id);
            if ($archivo && file_exists(BASE_PATH . $archivo['ruta_archivo'])) {
                $contenido = file_get_contents(BASE_PATH . $archivo['ruta_archivo']);
                require __DIR__ . '/views/view.php';
                break;
            }
        }
        header('Location: ' . BASE_URL . 'Archivos');
        exit;

    case 'edit': 
        if ($id && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $carpetaId = !empty($_POST['carpeta_id']) ? (int)$_POST['carpeta_id'] : null;

            if (!empty($nombre)) {
                $archivoManager->actualizarArchivo($id, $nombre, $carpetaId);
                $_SESSION['success'] = 'Archivo actualizado correctamente';
                header('Location: ' . BASE_URL . 'Archivos');
                exit;
            }
        } elseif ($id) {
            $archivo = $archivoManager->obtenerPorId($id);
            if ($archivo) {
                require __DIR__ . '/views/edit.php';
            } else {
                header('Location: ' . BASE_URL . 'Archivos'); 
                exit;
            }
        }
        break;

    case 'mover':
        if ($id && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $carpetaId = !empty($_POST['carpeta_id']) ? (int)$_POST['carpeta_id'] : null;
            $archivoManager->moverACarpeta($id, $carpetaId);
            $_SESSION['success'] = 'Archivo movido correctamente';
            header('Location: ' . BASE_URL . 'Archivos');
            exit;
        } elseif ($id) {
            $archivo = $archivoManager->obtenerPorId($id);
            if ($archivo) {
                require __DIR__ . '/views/mover.php';
            } else {
                header('Location: ' . BASE_URL . 'Archivos');
                exit;
            }
        }
        break;

    case 'delete':
        if ($id) {
            $archivo = $archivoManager->obtenerPorId($id);
            if ($archivo) {
                $filePath = BASE_PATH . $archivo['ruta_archivo'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                $archivoManager->eliminarArchivo($id);
                $_SESSION['success'] = 'Archivo eliminado correctamente';
            }
        }
        header('Location: ' . BASE_URL . 'Archivos');
        exit;

    case 'por_carpeta':
        $carpetaId = $_GET['carpeta_id'] ?? null;
        if ($carpetaId) {
            $archivos = $archivoManager->obtenerPorCarpeta($carpetaId);
        } else {
            $archivos = [];
        }
        require __DIR__ . '/views/list.php';
        break;

    case 'sin_carpeta':
        $usuarioId = $_GET['usuario_id'] ?? 1;
        $archivos = $archivoManager->obtenerSinCarpeta($usuarioId);
        require __DIR__ . '/views/list.php';
        break;
        
    case 'list':
    default:
        $archivos = $archivoManager->obtenerTodos(); 
        require __DIR__ . '/views/list.php'; 
        break;
}
?>
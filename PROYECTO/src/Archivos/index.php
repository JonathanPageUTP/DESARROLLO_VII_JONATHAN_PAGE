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

$usuarioId = $_SESSION['usuario_id'] ?? null;

if (!$usuarioId && $action !== 'create' && $action !== 'login_if_unauthorized') {
     $_SESSION['error'] = 'Debe iniciar sesión para ver archivos.';
     header('Location: ' . BASE_URL . 'login');
     exit;
}

switch ($action) {


    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuarioId = $_SESSION['usuario_id'] ?? null;
            if (!$usuarioId) {
                $_SESSION['error'] = 'Debes iniciar sesión para subir archivos';
                header('Location: ' . BASE_URL . 'login');
                exit;
            }
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
                    
                    // 1. Crear el archivo
                    $archivoManager->crearArchivo($usuarioId, $carpetaId, $nombreArchivo, $fileSize, $rutaArchivo);
                    
                    // 2. Obtener el ID del archivo recién creado
                    $archivoId = $archivoManager->obtenerUltimoId();
                    
                    // 3. Crear la versión 1 inicial
                    require_once BASE_PATH . 'src/Versiones/VersionManager.php';
                    $versionManager = new VersionManager();
                    $versionManager->crearVersion($archivoId, $fileSize, $rutaArchivo, 1);
                    
                    $_SESSION['success'] = 'Archivo subido correctamente con versión 1';
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

    case 'restaurar_version':
    if ($id && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $versionId = $_POST['version_id'] ?? null;
        
        if ($versionId) {
            require_once BASE_PATH . 'src/Versiones/VersionManager.php';
            $versionManager = new VersionManager();
            
            // Obtener la versión que se quiere restaurar
            $versionAntigua = $versionManager->obtenerPorId($versionId);
            
            if ($versionAntigua) {
                $archivo = $archivoManager->obtenerPorId($versionAntigua['archivo_id']);
                
                // Leer contenido del archivo actual (que tiene la versión antigua)
                $rutaCompleta = BASE_PATH . $archivo['ruta_archivo'];
                $contenidoActual = file_get_contents($rutaCompleta);
                
                // PROBLEMA: El archivo físico solo tiene UNA versión (la última)
                // SOLUCIÓN: Necesitas guardar cada versión en archivos DIFERENTES
                
                $_SESSION['error'] = 'Para restaurar versiones necesitas guardar archivos físicos separados';
            }
        }
    }
    header('Location: ' . BASE_URL . 'Archivos');
    exit;

case 'edit_content':
    if ($id) {
        $archivo = $archivoManager->obtenerPorId($id);
        if ($archivo && file_exists(BASE_PATH . $archivo['ruta_archivo'])) {
            $contenido = file_get_contents(BASE_PATH . $archivo['ruta_archivo']);
            require __DIR__ . '/views/edit_content.php';
            break;
        }
    }
    header('Location: ' . BASE_URL . 'Archivos');
    exit;

case 'save_content':
    if ($id && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $nuevoContenido = $_POST['contenido'] ?? '';
        
        if ($archivoManager->actualizarArchivoConVersion($id, $nuevoContenido)) {
            $_SESSION['success'] = 'Nueva versión guardada correctamente';
        } else {
            $_SESSION['error'] = 'Error al guardar la versión';
        }
    }
    header('Location: ' . BASE_URL . 'Archivos');
    exit;
        
    case 'list':
    default:
        if ($usuarioId) {
        $archivos = $archivoManager->obtenerPorUsuario($usuarioId);
            } else {
                $archivos = []; // O maneja la redirección aquí
            }
            require __DIR__ . '/views/list.php'; 
            break;
 }
?>
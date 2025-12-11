<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('BASE_PATH', dirname(dirname(__DIR__)) . '/');

require_once BASE_PATH . 'config.php';

require_once BASE_PATH . 'src/Database.php';
require_once 'VersionManager.php';
require_once 'Version.php';


$versionManager = new VersionManager(); 


$action = $_GET['action'] ?? 'list';


switch ($action) {
    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $archivoId = $_POST['archivo_id'] ?? null;
            $tamano = $_POST['tamano'] ?? 0;
            $rutaArchivo = $_POST['ruta_archivo'] ?? '';
            $numeroVersion = !empty($_POST['numero_version']) ? (int)$_POST['numero_version'] : null;

            if ($archivoId && !empty($rutaArchivo)) {
                $versionManager->crearVersion($archivoId, $tamano, $rutaArchivo, $numeroVersion);
                $_SESSION['mensaje'] = 'Nueva versión creada exitosamente';
            }
            header('Location: ' . BASE_URL . "src/Versiones");
            exit;
        }
        require BASE_PATH . 'views/version_form.php'; 
        break;

    case 'delete':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $versionManager->eliminarVersion($id);
            $_SESSION['mensaje'] = 'Versión eliminada';
        }
        header('Location: ' . BASE_URL . "src/Versiones");
        exit;

    case 'limpiar_antiguas':
        $archivoId = $_GET['archivo_id'] ?? null;
        $mantener = $_GET['mantener'] ?? 5;
        
        if ($archivoId) {
            $eliminadas = $versionManager->eliminarVersionesAnteriores($archivoId, $mantener);
            $_SESSION['mensaje'] = "Se eliminaron $eliminadas versiones antiguas";
        }
        header('Location: ' . BASE_URL . "src/Versiones");
        exit;

    case 'eliminar_todas':
        $archivoId = $_GET['archivo_id'] ?? null;
        
        if ($archivoId && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $versionManager->eliminarTodasLasVersiones($archivoId);
            $_SESSION['mensaje'] = 'Todas las versiones han sido eliminadas';
            header('Location: ' . BASE_URL . "src/Versiones");
            exit;
        } elseif ($archivoId) {
            $totalVersiones = $versionManager->contarVersiones($archivoId);
            require BASE_PATH . 'views/version_confirmar_eliminar.php';
        }
        break;

    case 'por_archivo':
        $archivoId = $_GET['archivo_id'] ?? null;
        if ($archivoId) {
            $versiones = $versionManager->obtenerPorArchivo($archivoId);
            print_r($versiones);
            exit;
        }
        header('Location: ' . BASE_URL . "src/Versiones");
        exit;

    case 'historial':
        $archivoId = $_GET['archivo_id'] ?? null;
        if ($archivoId) {
            $historial = $versionManager->obtenerHistorialCompleto($archivoId);
            $estadisticas = $versionManager->obtenerEstadisticas($archivoId);
            require BASE_PATH . 'views/version_historial.php';
        } else {
            header('Location: ' . BASE_URL . "src/Versiones");
        }
        break;

    case 'version_reciente':
        $archivoId = $_GET['archivo_id'] ?? null;
        if ($archivoId) {
            $version = $versionManager->obtenerVersionReciente($archivoId);
            print_r($version);
            exit;
        }
        header('Location: ' . BASE_URL . "src/Versiones");
        exit;

    case 'version_especifica':
        $archivoId = $_GET['archivo_id'] ?? null;
        $numeroVersion = $_GET['numero_version'] ?? null;
        
        if ($archivoId && $numeroVersion) {
            $version = $versionManager->obtenerVersionEspecifica($archivoId, $numeroVersion);
            print_r($version);
            exit;
        }
        header('Location: ' . BASE_URL . "src/Versiones");
        exit;

    case 'comparar':
        $versionId1 = $_GET['version_id_1'] ?? null;
        $versionId2 = $_GET['version_id_2'] ?? null;
        
        if ($versionId1 && $versionId2) {
            $comparacion = $versionManager->compararVersiones($versionId1, $versionId2);
            
            if (!empty($comparacion)) {
                require BASE_PATH . 'views/version_comparar.php';
            } else {
                $_SESSION['error'] = 'No se pudieron comparar las versiones';
                header('Location: ' . BASE_URL . "src/Versiones");
            }
        } else {
            header('Location: ' . BASE_URL . "src/Versiones");
        }
        break;

    case 'estadisticas':
        $archivoId = $_GET['archivo_id'] ?? null;
        if ($archivoId) {
            $estadisticas = $versionManager->obtenerEstadisticas($archivoId);
            print_r($estadisticas);
            exit;
        }
        header('Location: ' . BASE_URL . "src/Versiones");
        exit;

    case 'restaurar':
        $id = $_GET['id'] ?? null;
        
        if ($id) {
            $version = $versionManager->obtenerPorId($id);
            
            if ($version) {
                // Aquí iría la lógica para restaurar el archivo a esta versión
                // Por ejemplo, copiar el archivo de ruta_archivo a la ubicación actual
                $_SESSION['mensaje'] = "Versión {$version['numero_version']} lista para restaurar";
                require BASE_PATH . 'views/version_restaurar.php';
            } else {
                header('Location: ' . BASE_URL . "src/Versiones");
            }
        } else {
            header('Location: ' . BASE_URL . "src/Versiones");
        }
        break;

    case 'ver':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $version = $versionManager->obtenerPorId($id);
            if ($version) {
                require BASE_PATH . 'views/version_ver.php';
            } else {
                header('Location: ' . BASE_URL . "src/Versiones");
            }
        } else {
            header('Location: ' . BASE_URL . "src/Versiones");
        }
        break;
        
    default:
        $versiones = $versionManager->obtenerTodos(); 
        print_r($versiones);
        exit;
        require BASE_PATH . 'views/version_list.php'; 
        break;
}
?>
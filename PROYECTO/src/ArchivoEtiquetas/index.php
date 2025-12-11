<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('BASE_PATH', dirname(dirname(__DIR__)) . '/');

require_once BASE_PATH . 'config.php';

require_once BASE_PATH . 'src/Database.php';
require_once 'ArchivoEtiquetaManager.php';
require_once 'ArchivoEtiqueta.php';


$archivoEtiquetaManager = new ArchivoEtiquetaManager(); 


$action = $_GET['action'] ?? 'list';


switch ($action) {
    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $archivoId = $_POST['archivo_id'] ?? null;
            $etiquetaId = $_POST['etiqueta_id'] ?? null;

            if ($archivoId && $etiquetaId) {
                $archivoEtiquetaManager->crearRelacion($archivoId, $etiquetaId);
            }
            header('Location: ' . BASE_URL . "src/ArchivoEtiqueta");
            exit;
        }
        require BASE_PATH . 'views/archivo_etiqueta_form.php'; 
        break;

    case 'delete':
        $archivoId = $_GET['archivo_id'] ?? null;
        $etiquetaId = $_GET['etiqueta_id'] ?? null;
        
        if ($archivoId && $etiquetaId) {
            $archivoEtiquetaManager->eliminarRelacion($archivoId, $etiquetaId);
        }
        header('Location: ' . BASE_URL . "src/ArchivoEtiqueta");
        exit;

    case 'por_archivo':
        $archivoId = $_GET['archivo_id'] ?? null;
        if ($archivoId) {
            $etiquetas = $archivoEtiquetaManager->obtenerEtiquetasPorArchivo($archivoId);
            print_r($etiquetas);
            exit;
        }
        header('Location: ' . BASE_URL . "src/ArchivoEtiqueta");
        exit;

    case 'por_etiqueta':
        $etiquetaId = $_GET['etiqueta_id'] ?? null;
        if ($etiquetaId) {
            $archivos = $archivoEtiquetaManager->obtenerArchivosPorEtiqueta($etiquetaId);
            print_r($archivos);
            exit;
        }
        header('Location: ' . BASE_URL . "src/ArchivoEtiqueta");
        exit;
        
    default:
        $relaciones = $archivoEtiquetaManager->obtenerTodos(); 
        print_r($relaciones);
        exit;
        require BASE_PATH . 'views/archivo_etiqueta_list.php'; 
        break;
}
?>
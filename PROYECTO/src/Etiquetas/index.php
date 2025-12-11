<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('BASE_PATH', dirname(dirname(__DIR__)) . '/');

require_once BASE_PATH . 'config.php';

require_once BASE_PATH . 'src/Database.php';
require_once 'EtiquetaManager.php';
require_once 'Etiqueta.php';


$etiquetaManager = new EtiquetaManager(); 


$action = $_GET['action'] ?? 'list';


switch ($action) {
    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuarioId = $_POST['usuario_id'] ?? 1;
            $nombre = trim($_POST['nombre'] ?? '');

            if (!empty($nombre)) {
                $resultado = $etiquetaManager->crearEtiqueta($usuarioId, $nombre);
                
                if (!$resultado) {
                    $_SESSION['error'] = 'La etiqueta ya existe';
                } else {
                    $_SESSION['mensaje'] = 'Etiqueta creada exitosamente';
                }
            }
            header('Location: ' . BASE_URL . "src/Etiquetas");
            exit;
        }
        require BASE_PATH . 'views/etiqueta_form.php'; 
        break;

    case 'edit': 
        $id = $_GET['id'] ?? null;

        if ($id && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre'] ?? '');

            if (!empty($nombre)) {
                $etiquetaManager->actualizarEtiqueta($id, $nombre);
                $_SESSION['mensaje'] = 'Etiqueta actualizada exitosamente';
            }
            header('Location: ' . BASE_URL . "src/Etiquetas");
            exit;
        } elseif ($id) {
            $etiqueta = $etiquetaManager->obtenerPorId($id);
            if ($etiqueta) {
                require BASE_PATH . 'views/etiqueta_edit.php';
            } else {
                header('Location: ' . BASE_URL . "src/Etiquetas"); 
            }
        }
        break;

    case 'delete':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $etiquetaManager->eliminarEtiqueta($id);
            $_SESSION['mensaje'] = 'Etiqueta eliminada exitosamente';
        }
        header('Location: ' . BASE_URL . "src/Etiquetas");
        exit;

    case 'buscar':
        $usuarioId = $_GET['usuario_id'] ?? 1;
        $busqueda = $_GET['q'] ?? '';
        
        if (!empty($busqueda)) {
            $etiquetas = $etiquetaManager->buscarPorNombre($usuarioId, $busqueda);
            print_r($etiquetas);
            exit;
        }
        header('Location: ' . BASE_URL . "src/Etiquetas");
        exit;

    case 'mas_usadas':
        $usuarioId = $_GET['usuario_id'] ?? 1;
        $limite = $_GET['limite'] ?? 10;
        $etiquetas = $etiquetaManager->obtenerEtiquetasMasUsadas($usuarioId, $limite);
        print_r($etiquetas);
        exit;

    case 'sin_usar':
        $usuarioId = $_GET['usuario_id'] ?? 1;
        $etiquetas = $etiquetaManager->obtenerEtiquetasSinUsar($usuarioId);
        print_r($etiquetas);
        exit;

    case 'estadisticas':
        $usuarioId = $_GET['usuario_id'] ?? 1;
        $estadisticas = $etiquetaManager->obtenerEstadisticas($usuarioId);
        print_r($estadisticas);
        exit;

    case 'ver':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $etiqueta = $etiquetaManager->obtenerPorId($id);
            $totalArchivos = $etiquetaManager->contarArchivosPorEtiqueta($id);
            
            if ($etiqueta) {
                require BASE_PATH . 'views/etiqueta_ver.php';
            } else {
                header('Location: ' . BASE_URL . "src/Etiquetas");
            }
        } else {
            header('Location: ' . BASE_URL . "src/Etiquetas");
        }
        break;

    case 'por_usuario':
        $usuarioId = $_GET['usuario_id'] ?? 1;
        $etiquetas = $etiquetaManager->obtenerPorUsuario($usuarioId);
        print_r($etiquetas);
        exit;
        
    default:
        $etiquetas = $etiquetaManager->obtenerTodos(); 
        print_r($etiquetas);
        exit;
        require BASE_PATH . 'views/etiqueta_list.php'; 
        break;
}
?>
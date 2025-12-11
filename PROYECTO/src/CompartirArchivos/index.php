<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('BASE_PATH', dirname(dirname(__DIR__)) . '/');

require_once BASE_PATH . 'config.php';

require_once BASE_PATH . 'src/Database.php';
require_once 'CompartirArchivoManager.php';
require_once 'CompartirArchivo.php';


$compartirArchivoManager = new CompartirArchivoManager(); 


$action = $_GET['action'] ?? 'list';


switch ($action) {
    case 'compartir_usuario':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $archivoId = $_POST['archivo_id'] ?? null;
            $usuarioPropietarioId = $_POST['usuario_propietario_id'] ?? 1;
            $usuarioCompartidoId = $_POST['usuario_compartido_id'] ?? null;
            $permiso = $_POST['permiso'] ?? 'lectura';

            if ($archivoId && $usuarioCompartidoId) {
                $compartirArchivoManager->compartirConUsuario($archivoId, $usuarioPropietarioId, $usuarioCompartidoId, $permiso);
            }
            header('Location: ' . BASE_URL . "src/CompartirArchivos");
            exit;
        }
        require BASE_PATH . 'views/compartir_archivo_usuario_form.php'; 
        break;

    case 'generar_enlace':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $archivoId = $_POST['archivo_id'] ?? null;
            $usuarioPropietarioId = $_POST['usuario_propietario_id'] ?? 1;
            $permiso = $_POST['permiso'] ?? 'lectura';

            if ($archivoId) {
                $enlacePublico = $compartirArchivoManager->generarEnlacePublico($archivoId, $usuarioPropietarioId, $permiso);
                
                if ($enlacePublico) {
                    $_SESSION['enlace_generado'] = $enlacePublico;
                    $_SESSION['mensaje'] = 'Enlace público generado exitosamente';
                }
            }
            header('Location: ' . BASE_URL . "src/CompartirArchivos");
            exit;
        }
        require BASE_PATH . 'views/compartir_archivo_enlace_form.php';
        break;

    case 'ver_enlace':
        $enlace = $_GET['enlace'] ?? null;
        
        if ($enlace) {
            $compartido = $compartirArchivoManager->obtenerPorEnlacePublico($enlace);
            
            if ($compartido) {
                require BASE_PATH . 'views/compartir_archivo_publico.php';
            } else {
                echo "Enlace no válido o expirado";
            }
        } else {
            header('Location: ' . BASE_URL . "src/CompartirArchivos");
        }
        break;

    case 'actualizar_permiso':
        $id = $_GET['id'] ?? null;
        
        if ($id && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $permiso = $_POST['permiso'] ?? 'lectura';
            $compartirArchivoManager->actualizarPermiso($id, $permiso);
            header('Location: ' . BASE_URL . "src/CompartirArchivos");
            exit;
        } elseif ($id) {
            $compartido = $compartirArchivoManager->obtenerPorId($id);
            if ($compartido) {
                require BASE_PATH . 'views/compartir_archivo_permiso.php';
            } else {
                header('Location: ' . BASE_URL . "src/CompartirArchivos");
            }
        }
        break;

    case 'delete':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $compartirArchivoManager->eliminarCompartido($id);
        }
        header('Location: ' . BASE_URL . "src/CompartirArchivos");
        exit;

    case 'eliminar_enlace':
        $enlace = $_GET['enlace'] ?? null;
        if ($enlace) {
            $compartirArchivoManager->eliminarEnlacePublico($enlace);
        }
        header('Location: ' . BASE_URL . "src/CompartirArchivos");
        exit;

    case 'mis_archivos_compartidos':
        $usuarioId = $_GET['usuario_id'] ?? 1;
        $compartidos = $compartirArchivoManager->obtenerPorPropietario($usuarioId);
        print_r($compartidos);
        exit;

    case 'compartidos_conmigo':
        $usuarioId = $_GET['usuario_id'] ?? 1;
        $compartidos = $compartirArchivoManager->obtenerCompartidosConmigo($usuarioId);
        print_r($compartidos);
        exit;

    case 'enlaces_publicos':
        $usuarioId = $_GET['usuario_id'] ?? 1;
        $enlaces = $compartirArchivoManager->obtenerEnlacesPublicos($usuarioId);
        print_r($enlaces);
        exit;

    case 'por_archivo':
        $archivoId = $_GET['archivo_id'] ?? null;
        if ($archivoId) {
            $compartidos = $compartirArchivoManager->obtenerPorArchivo($archivoId);
            print_r($compartidos);
            exit;
        }
        header('Location: ' . BASE_URL . "src/CompartirArchivos");
        exit;
        
    default:
        $compartidos = $compartirArchivoManager->obtenerTodos(); 
        print_r($compartidos);
        exit;
        require BASE_PATH . 'views/compartir_archivo_list.php'; 
        break;
}
?>
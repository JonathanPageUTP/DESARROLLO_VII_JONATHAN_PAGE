<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('BASE_PATH', dirname(dirname(__DIR__)) . '/');

require_once BASE_PATH . 'config.php';

require_once BASE_PATH . 'src/Database.php';
require_once 'SincronizacionManager.php';
require_once 'Sincronizacion.php';


$sincronizacionManager = new SincronizacionManager(); 


$action = $_GET['action'] ?? 'list';


switch ($action) {
    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuarioId = $_POST['usuario_id'] ?? 1;
            $dispositivoId = $_POST['dispositivo_id'] ?? null;
            $estado = $_POST['estado'] ?? 'pendiente';

            if ($dispositivoId) {
                $sincronizacionManager->crearSincronizacion($usuarioId, $dispositivoId, $estado);
                $_SESSION['mensaje'] = 'Sincronización creada exitosamente';
            }
            header('Location: ' . BASE_URL . "src/Sincronizaciones");
            exit;
        }
        require BASE_PATH . 'views/sincronizacion_form.php'; 
        break;

    case 'iniciar':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $sincronizacionManager->iniciarSincronizacion($id);
            $_SESSION['mensaje'] = 'Sincronización iniciada';
        }
        header('Location: ' . BASE_URL . "src/Sincronizaciones");
        exit;

    case 'completar':
        $id = $_GET['id'] ?? null;
        
        if ($id && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $archivosSincronizados = $_POST['archivos_sincronizados'] ?? 0;
            $sincronizacionManager->completarSincronizacion($id, $archivosSincronizados);
            $_SESSION['mensaje'] = 'Sincronización completada';
            header('Location: ' . BASE_URL . "src/Sincronizaciones");
            exit;
        } elseif ($id) {
            $sincronizacion = $sincronizacionManager->obtenerPorId($id);
            if ($sincronizacion) {
                require BASE_PATH . 'views/sincronizacion_completar.php';
            } else {
                header('Location: ' . BASE_URL . "src/Sincronizaciones");
            }
        }
        break;

    case 'actualizar_progreso':
        $id = $_GET['id'] ?? null;
        
        if ($id && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $archivosSincronizados = $_POST['archivos_sincronizados'] ?? 0;
            $sincronizacionManager->actualizarProgreso($id, $archivosSincronizados);
            
            echo json_encode(['success' => true, 'message' => 'Progreso actualizado']);
            exit;
        }
        break;

    case 'incrementar':
        $id = $_GET['id'] ?? null;
        $cantidad = $_GET['cantidad'] ?? 1;
        
        if ($id) {
            $sincronizacionManager->incrementarArchivos($id, $cantidad);
            echo json_encode(['success' => true]);
            exit;
        }
        break;

    case 'marcar_fallida':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $sincronizacionManager->marcarComoFallida($id);
            $_SESSION['mensaje'] = 'Sincronización marcada como fallida';
        }
        header('Location: ' . BASE_URL . "src/Sincronizaciones");
        exit;

    case 'cancelar':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $sincronizacionManager->cancelarSincronizacion($id);
            $_SESSION['mensaje'] = 'Sincronización cancelada';
        }
        header('Location: ' . BASE_URL . "src/Sincronizaciones");
        exit;

    case 'cambiar_estado':
        $id = $_GET['id'] ?? null;
        
        if ($id && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $estado = $_POST['estado'] ?? 'pendiente';
            $sincronizacionManager->actualizarEstado($id, $estado);
            $_SESSION['mensaje'] = 'Estado actualizado';
            header('Location: ' . BASE_URL . "src/Sincronizaciones");
            exit;
        }
        break;

    case 'delete':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $sincronizacionManager->eliminarSincronizacion($id);
            $_SESSION['mensaje'] = 'Sincronización eliminada';
        }
        header('Location: ' . BASE_URL . "src/Sincronizaciones");
        exit;

    case 'pendientes':
        $sincronizaciones = $sincronizacionManager->obtenerPendientes();
        print_r($sincronizaciones);
        exit;

    case 'en_progreso':
        $sincronizaciones = $sincronizacionManager->obtenerEnProgreso();
        print_r($sincronizaciones);
        exit;

    case 'completadas':
        $sincronizaciones = $sincronizacionManager->obtenerCompletadas();
        print_r($sincronizaciones);
        exit;

    case 'fallidas':
        $sincronizaciones = $sincronizacionManager->obtenerFallidas();
        print_r($sincronizaciones);
        exit;

    case 'activas':
        $usuarioId = $_GET['usuario_id'] ?? 1;
        $sincronizaciones = $sincronizacionManager->obtenerSincronizacionesActivas($usuarioId);
        print_r($sincronizaciones);
        exit;

    case 'por_dispositivo':
        $dispositivoId = $_GET['dispositivo_id'] ?? null;
        if ($dispositivoId) {
            $sincronizaciones = $sincronizacionManager->obtenerPorDispositivo($dispositivoId);
            print_r($sincronizaciones);
            exit;
        }
        header('Location: ' . BASE_URL . "src/Sincronizaciones");
        exit;

    case 'ultima':
        $dispositivoId = $_GET['dispositivo_id'] ?? null;
        if ($dispositivoId) {
            $sincronizacion = $sincronizacionManager->obtenerUltimaSincronizacion($dispositivoId);
            print_r($sincronizacion);
            exit;
        }
        header('Location: ' . BASE_URL . "src/Sincronizaciones");
        exit;

    case 'estadisticas':
        $usuarioId = $_GET['usuario_id'] ?? 1;
        $estadisticas = $sincronizacionManager->obtenerEstadisticas($usuarioId);
        print_r($estadisticas);
        exit;

    case 'estadisticas_dispositivo':
        $dispositivoId = $_GET['dispositivo_id'] ?? null;
        if ($dispositivoId) {
            $estadisticas = $sincronizacionManager->obtenerEstadisticasPorDispositivo($dispositivoId);
            print_r($estadisticas);
            exit;
        }
        header('Location: ' . BASE_URL . "src/Sincronizaciones");
        exit;

    case 'ver':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $sincronizacion = $sincronizacionManager->obtenerPorId($id);
            if ($sincronizacion) {
                require BASE_PATH . 'views/sincronizacion_ver.php';
            } else {
                header('Location: ' . BASE_URL . "src/Sincronizaciones");
            }
        } else {
            header('Location: ' . BASE_URL . "src/Sincronizaciones");
        }
        break;
        
    default:
        $sincronizaciones = $sincronizacionManager->obtenerTodos(); 
        print_r($sincronizaciones);
        exit;
        require BASE_PATH . 'views/sincronizacion_list.php'; 
        break;
}
?>
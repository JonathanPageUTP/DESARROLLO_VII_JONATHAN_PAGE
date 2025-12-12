<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('BASE_PATH', dirname(dirname(__DIR__)) . '/');

require_once BASE_PATH . 'config.php';
require_once BASE_PATH . 'src/Database.php';
require_once __DIR__ . '/VersionManager.php';
require_once __DIR__ . '/Version.php';

session_start();

$versionManager = new VersionManager(); 
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

$usuarioId = $_SESSION['usuario_id'] ?? null; 

if (!$usuarioId) {
     $_SESSION['error'] = 'Debe iniciar sesión para ver las versiones.';
     header('Location: ' . BASE_URL . 'login');
     exit;
}

switch ($action) {
    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $archivoId = $_POST['archivo_id'] ?? null;
            $tamano = $_POST['tamano'] ?? 0;
            $rutaArchivo = $_POST['ruta_archivo'] ?? '';
            $numeroVersion = !empty($_POST['numero_version']) ? (int)$_POST['numero_version'] : null;

            if ($archivoId && !empty($rutaArchivo)) {
                $versionManager->crearVersion($archivoId, $tamano, $rutaArchivo, $numeroVersion);
                $_SESSION['success'] = 'Nueva versión creada exitosamente';
                header('Location: ' . BASE_URL . 'Versiones');
                exit;
            }
        }
        require __DIR__ . '/views/create.php'; 
        break;

    case 'delete':
        if ($id) {
            $versionManager->eliminarVersion($id);
            $_SESSION['success'] = 'Versión eliminada correctamente';
        }
        header('Location: ' . BASE_URL . 'Versiones');
        exit;

    case 'limpiar':
        $archivoId = $_GET['archivo_id'] ?? null;
        $mantener = $_GET['mantener'] ?? 5;
        
        if ($archivoId) {
            $eliminadas = $versionManager->eliminarVersionesAnteriores($archivoId, $mantener);
            $_SESSION['success'] = "Se eliminaron $eliminadas versiones antiguas";
        }
        header('Location: ' . BASE_URL . 'Versiones');
        exit;

    case 'por_archivo':
        $archivoId = $_GET['archivo_id'] ?? null;
        if ($archivoId) {
            $versiones = $versionManager->obtenerPorArchivo($archivoId);
            require __DIR__ . '/views/por_archivo.php';
        } else {
            header('Location: ' . BASE_URL . 'Versiones');
            exit;
        }
        break;

    case 'historial':
        $archivoId = $_GET['archivo_id'] ?? null;
        if ($archivoId) {
            $historial = $versionManager->obtenerHistorialCompleto($archivoId);
            $estadisticas = $versionManager->obtenerEstadisticas($archivoId);
            require __DIR__ . '/views/historial.php';
        } else {
            header('Location: ' . BASE_URL . 'Versiones');
            exit;
        }
        break;

    case 'comparar':
        $versionId1 = $_GET['version_id_1'] ?? null;
        $versionId2 = $_GET['version_id_2'] ?? null;
        
        if ($versionId1 && $versionId2) {
            $comparacion = $versionManager->compararVersiones($versionId1, $versionId2);
            
            if (!empty($comparacion)) {
                require __DIR__ . '/views/comparar.php';
            } else {
                $_SESSION['error'] = 'No se pudieron comparar las versiones';
                header('Location: ' . BASE_URL . 'Versiones');
                exit;
            }
        } else {
            header('Location: ' . BASE_URL . 'Versiones');
            exit;
        }
        break;

    case 'estadisticas':
        $archivoId = $_GET['archivo_id'] ?? null;
        if ($archivoId) {
            $estadisticas = $versionManager->obtenerEstadisticas($archivoId);
            require __DIR__ . '/views/estadisticas.php';
        } else {
            header('Location: ' . BASE_URL . 'Versiones');
            exit;
        }
        break;

  case 'restaurar':
    if ($id && $_SERVER['REQUEST_METHOD'] === 'POST') {
        // Cuando el usuario confirma la restauración
        $version = $versionManager->obtenerPorId($id);
        
        if ($version && file_exists(BASE_PATH . $version['ruta_archivo'])) {
            require_once BASE_PATH . 'src/Archivos/ArchivoManager.php';
            $archivoManager = new ArchivoManager();
            
            // 1. Leer contenido de la versión que se quiere restaurar
            $contenidoAntiguo = file_get_contents(BASE_PATH . $version['ruta_archivo']);
            
            // 2. Obtener información del archivo actual
            $archivo = $archivoManager->obtenerPorId($version['archivo_id']);
            
            // 3. Obtener el siguiente número de versión
            $siguienteVersion = $versionManager->obtenerSiguienteNumeroVersion($version['archivo_id']);
            
            // 4. Crear ruta para el nuevo archivo físico
            $infoRuta = pathinfo($archivo['ruta_archivo']);
            $baseNombre = preg_replace('/_v\d+$/', '', $infoRuta['filename']); // Remover _vN si existe
            $nuevaRuta = $infoRuta['dirname'] . '/' . 
                         $baseNombre . '_v' . $siguienteVersion . '.' . 
                         $infoRuta['extension'];
            
            // 5. Guardar el archivo físico con el contenido restaurado
            $rutaCompleta = BASE_PATH . $nuevaRuta;
            file_put_contents($rutaCompleta, $contenidoAntiguo);
            
            // 6. Calcular tamaño
            $nuevoTamano = strlen($contenidoAntiguo);
            
            // 7. Crear nueva versión en la base de datos
            $versionManager->crearVersion($version['archivo_id'], $nuevoTamano, $nuevaRuta, $siguienteVersion);
            
            // 8. Actualizar el registro principal del archivo
            $sql = "UPDATE archivos SET tamano = ?, ruta_archivo = ?, updated_at = NOW() WHERE id = ?";
            $stmt = Database::getInstance()->getConnection()->prepare($sql);
            $stmt->execute([$nuevoTamano, $nuevaRuta, $version['archivo_id']]);
            
            $_SESSION['success'] = "Versión {$version['numero_version']} restaurada exitosamente como versión $siguienteVersion";
            header('Location: ' . BASE_URL . 'Archivos/edit_content/' . $version['archivo_id']);
            exit;
        } else {
            $_SESSION['error'] = 'No se pudo encontrar el archivo de la versión';
            header('Location: ' . BASE_URL . 'Versiones');
            exit;
        }
    } elseif ($id) {
        // Mostrar la página de confirmación
        $version = $versionManager->obtenerPorId($id);
        if ($version) {
            require __DIR__ . '/views/restaurar.php';
            break;
        }
    }
    header('Location: ' . BASE_URL . 'Versiones');
    exit;

    case 'restaurar_confirmar':
    if ($id && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $version = $versionManager->obtenerPorId($id);
        
        if ($version) {
            // Obtener contenido de la versión antigua
            $rutaVersionAntigua = BASE_PATH . $version['ruta_archivo'];
            $contenidoAntiguo = file_get_contents($rutaVersionAntigua);
            
            // Restaurar usando el método que creamos
            require_once BASE_PATH . 'src/Archivos/ArchivoManager.php';
            $archivoManager = new ArchivoManager();
            
            if ($archivoManager->actualizarArchivoConVersion($version['archivo_id'], $contenidoAntiguo)) {
                $_SESSION['success'] = "Versión {$version['numero_version']} restaurada correctamente";
            }
        }
        
        header('Location: ' . BASE_URL . 'Versiones/historial/' . $version['archivo_id']);
        exit;
    }
    break;
        
    case 'list':
    default:
        if ($usuarioId) {
            $versiones = $versionManager->obtenerUltimasPorUsuario($usuarioId); 
        } else {
            $versiones = [];
        }
        require __DIR__ . '/views/list.php'; 
        break;
}

?>
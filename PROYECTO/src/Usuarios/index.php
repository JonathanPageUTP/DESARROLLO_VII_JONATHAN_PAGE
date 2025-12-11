<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('BASE_PATH', dirname(dirname(__DIR__)) . '/');

require_once BASE_PATH . 'config.php';
require_once BASE_PATH . 'src/Database.php';
require_once __DIR__ . '/UsuarioManager.php';
require_once __DIR__ . '/Usuario.php';

$usuarioManager = new UsuarioManager(); 
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

switch ($action) {
    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (!empty($nombre) && !empty($email) && !empty($password)) {
                $usuarioManager->crearUsuario($nombre, $email, $password);
                header('Location: ' . BASE_URL . 'Usuarios');
                exit;
            }
        }
        require __DIR__ . '/views/create.php'; 
        break;

    case 'edit': 
        if ($id && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (!empty($nombre) && !empty($email)) {
                $usuarioManager->actualizarUsuario($id, $nombre, $email, $password);
                header('Location: ' . BASE_URL . 'Usuarios');
                exit;
            }
        } elseif ($id) {
            $usuario = $usuarioManager->obtenerPorId($id);
            if ($usuario) {
                require __DIR__ . '/views/edit.php';
            } else {
                header('Location: ' . BASE_URL . 'Usuarios'); 
                exit;
            }
        }
        break;

    case 'delete':
        if ($id) {
            $usuarioManager->eliminarUsuario($id);
        }
        header('Location: ' . BASE_URL . 'Usuarios');
        exit;
        
    case 'list':
    default:
        $usuarios = $usuarioManager->obtenerTodos();    
        require __DIR__ . '/views/list.php'; 
        break;
}
<?php
session_start();
define('BASE_PATH', __DIR__ . '/');
require_once BASE_PATH . 'config.php';
require_once BASE_PATH . 'src/Database.php';
require_once BASE_PATH . 'src/Usuarios/UsuarioManager.php';

// Si ya está logueado, redirigir al dashboard
if (isset($_SESSION['usuario_id'])) {
    header('Location: ' . BASE_URL . 'index.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    

    if (empty($nombre) || empty($email) || empty($password)) {
        $error = 'Por favor completa todos los campos';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'El email no es válido';
    } elseif (strlen($password) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres';
    } elseif ($password !== $password_confirm) {
        $error = 'Las contraseñas no coinciden';
    } else {
        try {
            $usuarioManager = new UsuarioManager();
            
           
            $usuarioExistente = $usuarioManager->obtenerPorEmail($email);
            
            if ($usuarioExistente) {
                $error = 'Este email ya está registrado';
            } else {
                
                if ($usuarioManager->crearUsuario($nombre, $email, $password)) {
                    $success = 'Cuenta creada exitosamente. Redirigiendo al login...';
                    header('refresh:2;url=' . BASE_URL . 'login.php');
                } else {
                    $error = 'Error al crear la cuenta. Intenta nuevamente';
                }
            }
            
        } catch (Exception $e) {
            $error = 'Error al procesar la solicitud';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Plataforma de Backup</title>
   <link rel="stylesheet" href="public/assets/css/auth.css">
</head>
<body>
    <div class="register-container">
        <div class="logo-section">
            <h1>Crear Cuenta</h1>
        </div>
        
        <?php if ($error): ?>
            <div class="error-message">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success-message">
             <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="nombre">Nombre completo</label>
                <input 
                    type="text" 
                    id="nombre" 
                    name="nombre" 
                    placeholder="Juan Pérez"
                    required
                    value="<?= isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : '' ?>"
                >
            </div>
            
            <div class="form-group">
                <label for="email">Correo electrónico</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="tu@email.com"
                    required
                    value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>"
                >
            </div>
            
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="••••••••"
                    required
                >
                <p class="password-hint">Mínimo 6 caracteres</p>
            </div>
            
            <div class="form-group">
                <label for="password_confirm">Confirmar contraseña</label>
                <input 
                    type="password" 
                    id="password_confirm" 
                    name="password_confirm" 
                    placeholder="••••••••"
                    required
                >
            </div>
            
            <button type="submit" class="btn-register">Crear Cuenta</button>
        </form>
        
        <div class="footer-links"><a href="login.php">Inicia sesión</a>
        </div>
    </div>
</body>
</html>

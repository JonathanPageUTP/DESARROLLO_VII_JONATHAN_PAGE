<?php
session_start();
define('BASE_PATH', __DIR__ . '/');
require_once BASE_PATH . 'config.php';

if (isset($_SESSION['usuario_id'])) {
    header('Location: ' . BASE_URL . 'login.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    
    if ($email && $password) {
        try {
            $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            
            if ($conn->connect_error) {
                throw new Exception('Error de conexión');
            }
            $stmt = $conn->prepare("SELECT id, nombre, email, password FROM usuarios WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 1) {
                $usuario = $result->fetch_assoc();
                if (password_verify($password, $usuario['password'])) {
                    $_SESSION['usuario_id'] = $usuario['id'];
                    $_SESSION['usuario_nombre'] = $usuario['nombre'];
                    $_SESSION['usuario_email'] = $usuario['email'];
                    
                    header('Location: ' . BASE_URL . 'index.php');
                    exit;
                } else {
                    $error = 'Email o contraseña incorrectos';
                }
            } else {
                $error = 'Email o contraseña incorrectos';
            }
            
            $stmt->close();
            $conn->close();
            
        } catch (Exception $e) {
            $error = 'Error al procesar la solicitud';
        }
    } else {
        $error = 'Por favor completa todos los campos';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Plataforma de Backup</title>
    <link rel="stylesheet" href="public/assets/css/auth.css">
</head>
<body>
    <div class="login-container">
        <div class="logo-section">
            <h1>Iniciar Sesión</h1>
        </div>
        
        <?php if ($error): ?>
            <div class="error-message">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
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
            </div>
            
            <button type="submit" class="btn-login">Iniciar Sesión</button>
        </form>
        
        <div class="footer-links">
            <a href="register.php">Registrate</a>
        </div>
    </div>
</body>
</html>
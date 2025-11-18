<?php 
session_start();
$users = [
    1 => ['usuario' => 'Jaen', 'password' => '1234', 'rol' => 'Profesor'],
    2 => ['usuario' => 'Mike', 'password' => '1234', 'rol' => 'Estudiante']
 ];

 if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    foreach($users as $user){
        
    if($usuario === $user['usuario'] && $password === $user['password']){
        $_SESSION['usuario'] = $usuario;
        $_SESSION['password'] = $password;
        $_SESSION['rol'] = $user['rol'];
        echo"Hola " .$_SESSION['usuario'] ." " .$_SESSION['password'] ." " .$_SESSION['rol'];
        
        header("Location: dashboard.php");
    }
    }
 }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
 <h1>Login</h1>
 <form action="" method="POST">
    <br>
    <input type="text" name="usuario" placeholder="Coloque su usuario">
    <br><br>
    <input type="text" name="password" placeholder="Coloque su contraseña">
    <br><br>
    <button type="submit">ingresa</button>
 </form>
</body>
</html>
<?php
require_once "database.php";



if ($_SERVER['REQUEST_METHOD'] === 'GET') {
 $id = mysqli_real_escape_string($conn, $_GET['id']);


$sql = "DELETE FROM productos where id=?";

if($stmt = mysqli_prepare($conn, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        if(mysqli_stmt_execute($stmt)){
            echo "Usuario eliminado con éxito.";
        } else{
            echo "ERROR: No se pudo ejecutar $sql. " . mysqli_error($conn);
        }
    }
    
    mysqli_stmt_close($stmt);
}


?>
<a href="index.php">Volver</a>

<?php 
require_once "database.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $nombre = mysqli_real_escape_string($conn, string: $_POST['nombre']);
    $precio = mysqli_real_escape_string($conn, $_POST['precio']);
    $cantidad = mysqli_real_escape_string($conn, $_POST['cantidad']);
    $categoria = mysqli_real_escape_string($conn, $_POST['categoria']);
    
    $sql = "INSERT INTO productos (nombre, precio,cantidad, categoria) VALUES (?, ?, ?,?)";
    
    if($stmt = mysqli_prepare($conn, $sql)){
        mysqli_stmt_bind_param($stmt, "ssss", $nombre, $precio, $cantidad, $categoria);
        
        if(mysqli_stmt_execute($stmt)){
            echo "Product creado con éxito.";
        } else{
            echo "ERROR: No se pudo ejecutar $sql. " . mysqli_error($conn);
        }
    }
    
    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <div><label>Nombre</label><input type="text" name="nombre" required></div>
    <div><label>Precio</label><input type="number" name="precio" required></div>
    <div><label>cantidad</label><input type="number" name="cantidad" required></div>
    <div><label>categoria</label><input type="text" name="categoria" required></div>
    <input type="submit" value="Crear producto">
</form>


<a href="index.php">Volver</a>
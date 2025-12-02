<?php
require_once "database.php";

$sql = "SELECT id, nombre, precio, cantidad, categoria FROM productos";
$result = mysqli_query($conn, $sql);

if($result){
    if(mysqli_num_rows($result) > 0){
        echo "<table>";
            echo "<tr>";
                echo "<th>ID</th>";
                echo "<th>Nombre</th>";
                echo "<th>precio</th>";
                echo "<th>cantidad</th>";
                echo "<th>categoria</th>";
            echo "</tr>";
        while($row = mysqli_fetch_array($result)){
            echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['nombre'] . "</td>";
                echo "<td>" . $row['precio'] . "</td>";
                echo "<td>" . $row['cantidad'] . "</td>";
                echo "<td>" . $row['categoria'] . "</td>";
                ?>
                <td><a href="eliminar.php?action=delete&id=<?= $row['id'] ?>" class="btn" onclick="return confirm('¿Eliminar este producto?')">x</a>
                <td><a href="editar.php?action=edit&id=<?= $row['id'] ?>" class="btn" onclick="return confirm('Editar este producto?')">EDITAR</a>
               </td>
                 <?php 
            echo "</tr>";
        }
        echo "</table>";
        mysqli_free_result($result);
    } else{
        echo "No se encontraron registros.";
    }
} else{
    echo "ERROR: No se pudo ejecutar $sql. " . mysqli_error($conn);
}
?>
<a href="crear.php" class="btn" onclick="return confirm('¿Desea crea un producto?')">+</a>
<?php 


mysqli_close($conn);
?>


        
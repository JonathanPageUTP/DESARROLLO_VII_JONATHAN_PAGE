<?php 
session_start();


$notas = [
    'Mike' => 98,
    'John' => 100,
    'Luis' => 80,
];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <?php if($_SESSION['rol'] == "Profesor") { ?>
    <table>
        <th><tr>
            <td>Estudiante</td>
            <td>Nota</td>
            
</tr>
        </th>
            <div>
                <?php foreach($notas as $estu => $nota){ ?>
                <tr>
                <td><?php echo"</br>" . $estu ; ?> </td>
                <td><?php echo"</br>" . $nota ; ?></td>
                </tr>
                <?php } ?> 
            </div>
        
    </table>
    <?php } ?>
    
    <?php if($_SESSION['rol'] == "Estudiante") { ?>
    <table>
        <th><tr>
            <td>Estudiante</td>
            <td>Nota</td>
            
</tr>
        </th>
            <div>
                <?php foreach($notas as $estu => $nota){ ?>
                <tr>
                    <?php if($_SESSION['usuario'] == $estu) {?>
                        <td><?php echo"</br>" . $estu ; ?> </td>
                        <td><?php echo"</br>" . $nota ; ?></td>
                    <?php } ?>
                </tr>
                <?php } ?> 
            </div>
        
    </table>
    <?php } ?>
</body>
</html>
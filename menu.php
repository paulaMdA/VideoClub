<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$rol = isset($_SESSION['rol']) ? $_SESSION['rol'] : ''; 

if (isset($_POST['cerrar_sesion'])) {
    session_destroy(); 
    header("Location: login.php"); 
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <title>Menú</title>
</head>
<body>
    <h2>Bienvenido, <?php echo $_SESSION['usuario']; ?></h2>
    
    <?php if ($rol == 'cliente') { ?>
        <ul>
            <li><a href="./usuarios/alquilar.php">Alquilar</a></li>
            <li><a href="./usuarios/devolver.php">Devolver</a></li>
        </ul>
    <?php } elseif ($rol == 'empleado') { ?>
        <ul>
            <li><a href="./empleados/dar_alta_disco.php">Alta Disco</a></li>
            <li><a href="./empleados/dar_baja_disco.php">Baja Disco</a></li>
            <li><a href="./empleados/dar_baja_usuario.php">Baja Usuario</a></li>
        </ul>
    <?php } elseif ($rol == 'administrador') { ?>
        <ul>
            <li><a href="./administrador/baja_empleado.php">Baja Empleado</a></li>
            <li><a href="./administrador/alta_empleado.php">Alta Empleado</a></li>
            <!-- Otras opciones del menú para el administrador -->
        </ul>
    
    <?php } ?>
    
    <form method="post">
        <button type="submit" name="cerrar_sesion">Cerrar sesión</button>
    </form>
</body>
</html>

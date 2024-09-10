<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hostname = "localhost";
    $username = "pau";
    $password = "kk";
    $database = "videoclub";

    $conexion = new mysqli($hostname, $username, $password, $database);

    if ($conexion->connect_error) {
        die("Error de conexión a la base de datos: " . $conexion->connect_error);
    }

    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $contrasena = $_POST["contrasena"];

    // Consulta SQL para insertar un nuevo empleado en la base de datos
    $sql = "INSERT INTO PERSONA (nombre, apellido, contraseña, rol) VALUES (?, ?, ?, 'empleado')";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("sss", $nombre, $apellido, $contrasena);

    if ($stmt->execute()) {
        echo "Empleado dado de alta con éxito.";
    } else {
        echo "Error al dar de alta al empleado: " . $stmt->error;
    }

    $stmt->close();
    $conexion->close(); 
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
    <title>Alta Empleado</title>
</head>
<body>
    <h2>Alta Empleado</h2>
    <form method="POST" action="">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" required><br>
        <label for="apellido">Apellido:</label>
        <input type="text" name="apellido" required><br>
        <label for="contrasena">Contraseña:</label>
        <input type="password" name="contrasena" required><br>
        <button type="submit">Dar de Alta</button>
    </form>
    <a href="../menu.php">Volver al Menú</a>
</body>
</html>

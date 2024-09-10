<?php
session_start();

require_once 'config.php';

if (isset($_SESSION['usuario'])) {
    header("Location: menu.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $contrasena = $_POST["contrasena"];

    if (!empty($nombre) && !empty($apellido) && !empty($contrasena)) {
        $query = "INSERT INTO PERSONA (nombre, apellido, contraseña, rol) VALUES (?, ?, ?, 'cliente')";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("sss", $nombre, $apellido, $contrasena);

        if ($stmt->execute()) {
            $_SESSION['usuario'] = $nombre;
            $_SESSION['rol'] = 'cliente'; 
            header("Location: menu.php");
            exit();
        } else {
            echo "Error al registrar el usuario: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Por favor, completa todos los campos. <a href='registro.php'>Volver al registro</a>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registro</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>
    <h2>Registro</h2>
    <form method="POST" action="">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" required><br>
        <label for="apellido">Apellido:</label>
        <input type="text" name="apellido" required><br>
        <label for="contrasena">Contraseña:</label>
        <input type="password" name="contrasena" required><br>
        <button type="submit">Registrar</button>
        <a href="login.php">Cancelar</a>
    </form>
</body>
</html>

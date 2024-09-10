<?php
session_start();

require_once 'config.php'; // Incluye el archivo de configuración con las credenciales de la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM PERSONA WHERE nombre = ? AND contraseña = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['usuario'] = $username;
        $_SESSION['rol'] = $row['rol'];
        header('Location: menu.php');
        exit();
    } else {
        echo "Credenciales incorrectas. <a href='login.php'>Volver al login</a>";
    }

    $stmt->close();
} else {
    header('Location: login.php');
    exit();
}
?>

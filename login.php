<?php
session_start(); 

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
    $username = $_POST['username'];
    $password = $_POST['password']; 

 
    $query = "SELECT * FROM PERSONA WHERE nombre = ? AND contraseña = ?";
    $stmt = $mysqli->prepare($query); 
    $stmt->bind_param("ss", $username, $password); 
    $stmt->execute(); 
    $result = $stmt->get_result(); 


    if ($result->num_rows > 0) { 
        $row = $result->fetch_assoc(); 
        $_SESSION['usuario'] = $username; 
        $_SESSION['rol'] = $row['rol']; 
        $_SESSION['id_socio'] = $row['id_socio']; 
        header('Location: menu.php'); 
        exit(); 
    } else {
        echo "Credenciales incorrectas. <a href='login.php'>Volver al login</a>";
    }

    $stmt->close(); 
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <title>Iniciar Sesión</title>
</head>
<body>
    <h2>Iniciar Sesión</h2>
    <form method="POST" action="login.php">
        <label for="username">Nombre de Usuario:</label>
        <input type="text" id="username" name="username" required><br>
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required><br>
        <button type="submit">Iniciar Sesión</button>
    </form>
     <p>¿No tienes una cuenta? <a href="registro.php">Regístrate aquí</a></p>
</body>
</body>
</html>

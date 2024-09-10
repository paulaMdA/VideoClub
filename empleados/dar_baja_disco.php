<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../login.php");
    exit();
}

// Conecta a la base de datos
$conexion = new mysqli("localhost", "pau", "kk", "videoclub");

if ($conexion->connect_error) {
    die("Error de conexión a la base de datos: " . $conexion->connect_error);
}

// Procesar solicitud de dar de baja cuando se hace clic en el botón "Dar de Baja"
if (isset($_POST['dar_de_baja'])) {
    $id_disco = $_POST['id_disco'];

    // Realizar la operación para dar de baja el disco en la base de datos
    $sql = "DELETE FROM DISCO WHERE id_disco = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_disco);

    if ($stmt->execute()) {
        // Redirigir a la página de dar de baja discos para actualizar la tabla
        header("Location: dar_baja_disco.php");
        exit();
    } else {
        echo "Error al dar de baja el disco: " . $stmt->error;
    }
}

// Obtén todos los discos de la base de datos
$sql = "SELECT * FROM DISCO";
$resultado = $conexion->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
    <title>Dar de Baja Discos</title>
</head>
<body>
    <h2>Dar de Baja Discos</h2>

    <table>
        <tr>
            <th>ID Disco</th>
            <th>Nombre</th>
            <th>Autor/Protagonista</th>
            <th>Tipo</th>
            <th>Acción</th>
        </tr>
        <?php while ($fila = $resultado->fetch_assoc()): ?>
            <tr>
                <td><?php echo $fila['id_disco']; ?></td>
                <td><?php echo $fila['nombre']; ?></td>
                <td><?php echo $fila['autor_protagonista']; ?></td>
                <td><?php echo $fila['tipo']; ?></td>
                <td>
                    <form method="POST" action="">
                        <input type="hidden" name="id_disco" value="<?php echo $fila['id_disco']; ?>">
                        <button type="submit" name="dar_de_baja">Dar de Baja</button>
                    </form>
                    <a href="../menu.php">Volver al Menú</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

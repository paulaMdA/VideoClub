<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../login.php");
    exit();
}

$conexion = new mysqli("localhost", "pau", "kk", "videoclub");

if ($conexion->connect_error) {
    die("Error de conexión a la base de datos: " . $conexion->connect_error);
}

if (isset($_GET['id']) && isset($_SESSION['id_socio'])) {
    $id_disco = $_GET['id'];
    $id_socio = $_SESSION['id_socio'];

    // Actualizar el estado del disco a "ocupado" en la base de datos
    $sql = "UPDATE DISCO SET disponible = FALSE WHERE id_disco = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_disco);

    if ($stmt->execute()) {
        $fecha_actual = date("Y-m-d"); // Obtener la fecha actual

        $sql = "INSERT INTO ALQUILER (id_socio, id_disco, fecha_salida) VALUES (?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("iis", $id_socio, $id_disco, $fecha_actual);

        if ($stmt->execute()) {
            echo "Alquiler realizado con éxito.";
        } else {
            echo "Error al registrar el alquiler: " . $stmt->error;
        }
    } else {
        echo "Error al actualizar el estado del disco: " . $stmt->error;
    }
}

$sql = "SELECT * FROM DISCO WHERE disponible = TRUE";
$resultado = $conexion->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Alquilar Discos</title>
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
</head>
<body>
    <h2>Alquilar Discos</h2>
    
    <form action="alquilar.php" method="GET">
        <label>Filtrar por tipo:</label>
        <select name="tipo">
            <option value="cd">CD</option>
            <option value="dvd">DVD</option>
        </select>
        <button type="submit">Filtrar</button>
    </form>
    
    <table>
        <tr>
            <th>Nombre</th>
            <th>Autor/Protagonista</th>
            <th>Tipo</th>
            <th>Acción</th>
        </tr>
        <?php while ($fila = $resultado->fetch_assoc()): ?>
            <tr>
                <td><?php echo $fila['nombre']; ?></td>
                <td><?php echo $fila['autor_protagonista']; ?></td>
                <td><?php echo $fila['tipo']; ?></td>
                <td>
                    <a href="alquilar.php?id=<?php echo $fila['id_disco']; ?>">Alquilar</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
    <a href="../menu.php">Volver al Menú</a>
</body>
</html>

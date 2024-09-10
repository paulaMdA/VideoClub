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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_alquiler'])) {
    $id_alquiler = $_POST['id_alquiler'];

    $sql = "UPDATE ALQUILER SET fecha_devolucion = CURRENT_DATE() WHERE id_alquiler = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_alquiler);
    
    if ($stmt->execute()) {
        // Obtener el ID del disco asociado con el alquiler actual
        $sql_disco = "SELECT id_disco FROM ALQUILER WHERE id_alquiler = ?";
        $stmt_disco = $conexion->prepare($sql_disco);
        $stmt_disco->bind_param("i", $id_alquiler);
        $stmt_disco->execute();
        $resultado_disco = $stmt_disco->get_result();
        $fila_disco = $resultado_disco->fetch_assoc();
        $id_disco = $fila_disco['id_disco'];

        // Actualizar el estado del disco a "disponible" en la base de datos
        $sql_update_disco = "UPDATE DISCO SET disponible = TRUE WHERE id_disco = ?";
        $stmt_update_disco = $conexion->prepare($sql_update_disco);
        $stmt_update_disco->bind_param("i", $id_disco);
        $stmt_update_disco->execute();

        header("Location: devolver.php");
        exit();
    } else {
        echo "Error al realizar la devolución: " . $stmt->error;
    }
}

$usuario = $_SESSION['usuario'];
$sql = "SELECT ALQUILER.id_alquiler, DISCO.nombre
        FROM ALQUILER
        INNER JOIN PERSONA ON ALQUILER.id_socio = PERSONA.id_socio
        INNER JOIN DISCO ON ALQUILER.id_disco = DISCO.id_disco
        WHERE PERSONA.nombre = '$usuario' AND ALQUILER.fecha_devolucion IS NULL";

$resultado = $conexion->query($sql);
?>


<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
    <title>Devolver Películas</title>
</head>
<body>
    <h2>Devolver Películas</h2>

    <table>
        <tr>
            <th>ID Alquiler</th>
            <th>Nombre de la Película</th>
            <th>Acción</th>
        </tr>
        <?php while ($fila = $resultado->fetch_assoc()): ?>
            <tr>
                <td><?php echo $fila['id_alquiler']; ?></td>
                <td><?php echo $fila['nombre']; ?></td>
                <td>
                    <form method="POST" action="devolver.php">
                        <input type="hidden" name="id_alquiler" value="<?php echo $fila['id_alquiler']; ?>">
                        <button type="submit">Devolver</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
    <a href="../menu.php">Volver al Menú</a>
</body>
</html>

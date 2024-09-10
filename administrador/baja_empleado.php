<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'administrador') {
    header("Location: ../login.php");
    exit();
}

$conexion = new mysqli("localhost", "pau", "kk", "videoclub");

if ($conexion->connect_error) {
    die("Error de conexión a la base de datos: " . $conexion->connect_error);
}

if (isset($_POST['baja_empleado'])) {
    $id_empleado = $_POST['id_empleado'];

    $sql = "SELECT COUNT(*) as cantidad_registros FROM ALQUILER WHERE id_socio = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_empleado);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $fila = $resultado->fetch_assoc();

    if ($fila['cantidad_registros'] > 0) {
        echo "No se puede dar de baja al empleado. Tiene registros activos.";
    } else {
        // Realizar la baja del empleado en la base de datos (cambiar estado a "inactivo" o eliminar el registro)
        $sql = "UPDATE PERSONA SET estado = 'inactivo' WHERE id_socio = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id_empleado);

        if ($stmt->execute()) {
            echo "Empleado dado de baja correctamente.";
        } else {
            echo "Error al dar de baja al empleado: " . $stmt->error;
        }
    }
}

// Obtener la lista de empleados con estado "activo"
$sql = "SELECT * FROM PERSONA WHERE rol = 'empleado' AND estado = 'activo'";
$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
    <title>Dar de Baja Empleados</title>
</head>
<body>
    <h2>Dar de Baja Empleados</h2>

    <form action="" method="POST">
        <table>
            <tr>
                <th>ID Empleado</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Acción</th>
            </tr>
            <?php while ($fila = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $fila['id_socio']; ?></td>
                    <td><?php echo $fila['nombre']; ?></td>
                    <td><?php echo $fila['apellido']; ?></td>
                    <td>
                        <input type="hidden" name="id_empleado" value="<?php echo $fila['id_socio']; ?>">
                        <button type="submit" name="baja_empleado">Dar de Baja</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </form>
    <br>
    <a href="../menu.php">Volver al Menú</a>
</body>
</html>

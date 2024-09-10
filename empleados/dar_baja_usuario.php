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

if (isset($_POST['baja_usuario'])) {
    $id_usuario = $_POST['id_usuario'];

    // Verificar si el usuario tiene multas pendientes
    $sql_multas = "SELECT COUNT(*) as multas FROM ALQUILER WHERE id_socio = ? AND fecha_devolucion IS NULL";
    $stmt_multas = $conexion->prepare($sql_multas);
    $stmt_multas->bind_param("i", $id_usuario);
    $stmt_multas->execute();
    $result_multas = $stmt_multas->get_result();
    $row_multas = $result_multas->fetch_assoc();

    if ($row_multas['multas'] > 0) {
        echo "Este usuario tiene multas pendientes. La multa es de " . ($row_multas['multas'] * 50) . " euros.";
        echo "¿Desea pagar la multa y borrar su usuario?<br>";
        echo "<form action='dar_baja_usuario.php' method='POST'>";
        echo "<input type='hidden' name='id_usuario' value='$id_usuario'>";
        echo "<button type='submit' name='pagar_multa'>Pagar Multa y Borrar Usuario</button>";
        echo "<button type='submit' name='no_pagar_multa'>No Pagar Multa</button>";
        echo "</form>";
    } else {
        // El usuario no tiene multas pendientes, se puede dar de baja directamente
        $sql = "UPDATE PERSONA SET estado = 'inactivo' WHERE id_socio = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $id_usuario);

        if ($stmt->execute()) {
            echo "Usuario dado de baja correctamente.";
        } else {
            echo "Error al dar de baja al usuario: " . $stmt->error;
        }
    }
}

$sql = "SELECT * FROM PERSONA WHERE estado = 'activo' AND rol = 'cliente'";
$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
    <title>Dar de Baja Usuario</title>
</head>
<body>
    <h2>Dar de Baja Usuario</h2>
    
    <form action="dar_baja_usuario.php" method="POST">
        <label>Filtrar por Apellido:</label>
        <input type="text" name="apellido_filtro" value="<?php echo isset($_POST['apellido_filtro']) ? $_POST['apellido_filtro'] : ''; ?>">
        <button type="submit">Filtrar</button>
    </form>
    
    <form action="dar_baja_usuario.php" method="POST">
        <table>
            <tr>
                <th>ID Usuario</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Rol</th>
                <th>Acción</th>
            </tr>
            <?php while ($fila = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $fila['id_socio']; ?></td>
                    <td><?php echo $fila['nombre']; ?></td>
                    <td><?php echo $fila['apellido']; ?></td>
                    <td><?php echo $fila['rol']; ?></td>
                    <td>
                        <form action="dar_baja_usuario.php" method="POST">
                            <input type="hidden" name="id_usuario" value="<?php echo $fila['id_socio']; ?>">
                            <button type="submit" name="baja_usuario">Dar de Baja</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </form>
    <a href="../menu.php">Volver al Menú</a>
</body>
</html>

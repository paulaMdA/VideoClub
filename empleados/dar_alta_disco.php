<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
    <title>Dar de Alta Disco</title>
</head>
<body>
    <h2>Dar de Alta Disco</h2>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Conectar a la base de datos
        $conexion = new mysqli("localhost", "pau", "kk", "videoclub");

        if ($conexion->connect_error) {
            die("Error de conexión a la base de datos: " . $conexion->connect_error);
        }

        $nombre = $_POST["nombre"];
        $autor_protagonista = $_POST["autor_protagonista"];
        $tipo = $_POST["tipo"];

        // Verifica que los campos no estén vacíos
        if (!empty($nombre) && !empty($autor_protagonista) && !empty($tipo)) {
            // Inserta el nuevo disco en la base de datos
            $sql = "INSERT INTO DISCO (nombre, autor_protagonista, tipo, disponible) VALUES (?, ?, ?, TRUE)";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("sss", $nombre, $autor_protagonista, $tipo);

            if ($stmt->execute()) {
                echo "Disco dado de alta con éxito.";
            } else {
                echo "Error al dar de alta el disco: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Por favor, completa todos los campos.";
        }

        // Cierra la conexión a la base de datos
        $conexion->close();
    }
    ?>

    <form method="POST" action="">
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" required><br>
        <label for="autor_protagonista">Autor/Protagonista:</label>
        <input type="text" name="autor_protagonista" required><br>
        <label for="tipo">Tipo:</label>
        <select name="tipo">
            <option value="cd">CD</option>
            <option value="dvd">DVD</option>
        </select><br>
        <button type="submit">Dar de Alta Disco</button>
        <a href="../menu.php">Volver al Menú</a>
    </form>
</body>
</html>


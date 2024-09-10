<?php
$hostname = "localhost";
$username = "pau";
$password = "kk";
$database = "videoclub";

$mysqli = new mysqli($hostname, $username, $password, $database);



if ($mysqli->connect_error) {
    die("Error de conexión a la base de datos: " . $mysqli->connect_error);
}
?>

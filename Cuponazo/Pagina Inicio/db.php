<?php
// db.php - La llave para conectar la base de datos
$conexion = mysqli_connect("localhost", "root", "", "cuponazo");

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>
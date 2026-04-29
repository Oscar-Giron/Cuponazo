<?php
//conexion a la bbdd
header('Content-Type: application/json'); 
$conexion = new mysqli("localhost", "root", "", "cuponazo");

if ($conexion->connect_error) {
    die(json_encode(["error" => "Error de conexión"]));
}

// Hacemos un JOIN para que en la tabla aparezca el nombre de la categoria y no el numero 
$sql = "SELECT c.id_cupon, c.nombre, cat.nombre_categoria as categoria, c.precio, c.stock 
        FROM cupon c
        INNER JOIN categoria cat ON c.id_categoria = cat.id_categoria";

$resultado = $conexion->query($sql);
$productos = [];

if ($resultado) {
    while($fila = $resultado->fetch_assoc()){
        $productos[] = $fila;
    }
}

echo json_encode($productos);
$conexion->close();
?>
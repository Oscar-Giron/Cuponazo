<?php 
//Conexion a la bbdd
$conexion = new mysqli("localhost", "root", "", "cuponazo"); 

if ($conexion->connect_error) { 
    die("Error de conexión: " . $conexion->connect_error); 
} 

//Recibir datos JSON
$data = json_decode(file_get_contents("php://input"), true); 

//Variables 
$id        = $data["id_cupon"]; 
$nombre    = $data["nombre"]; 
$categoria = $data["categoria"]; // Aquí llega el 1, 2 o 3 del SELECT
$precio    = $data["precio"]; 
$stock     = $data["stock"]; 


$sql = "INSERT INTO cupon (id_cupon, nombre, id_categoria, precio, stock) 
        VALUES ('$id', '$nombre', $categoria, $precio, $stock)"; 

if ($conexion->query($sql) === TRUE) { 
    echo "OK"; 
} else { 
    echo "Error en la base de datos: " . $conexion->error; 
} 

$conexion->close(); 
?>
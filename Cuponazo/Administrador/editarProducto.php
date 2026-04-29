
<?php
//Conexion a la bbdd
$conexion = new mysqli("localhost", "root", "", "cuponazo");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Captura y limpieza del JSON
$data = json_decode(file_get_contents("php://input"), true);

//Usamos trim() para evitar espacios accidentales que rompan el WHERE
$id       = trim($data["id_cupon"]); 
$nombre    = trim($data["nombre"]);
$categoria = trim($data["categoria"]);
$precio    = $data["precio"];
$stock     = $data["stock"];

//Consulta SQL 

$sql = "UPDATE cupon  
        SET nombre='$nombre', 
            id_categoria='$categoria', 
            precio=$precio, 
            stock=$stock 
        WHERE id_cupon='$id'";

if ($conexion->query($sql) === TRUE) {
    if ($conexion->affected_rows > 0) {
        echo "OK";
    } else {
        echo "Cero filas afectadas. Revisa si el ID '$id' existe.";
    }
} else {
    echo "Error en SQL: " . $conexion->error;
}

$conexion->close();
?>


<?php
// 1. Conexión
$conexion = new mysqli("localhost", "root", "", "cuponazo");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// 2. Capturar y limpiar el JSON
$data = json_decode(file_get_contents("php://input"), true);

// Usamos trim() para evitar espacios accidentales que rompan el WHERE
$id       = trim($data["id_cupon"]); 
$nombre    = trim($data["nombre"]);
$categoria = trim($data["categoria"]);
$precio    = $data["precio"];
$stock     = $data["stock"];

// 3. Consulta SQL (Asegúrate de que los nombres de columnas sean exactos)
// Si precio y stock son números en la DB, quité las comillas simples.
$sql = "UPDATE cupon  
        SET nombre='$nombre', 
            categoria='$categoria', 
            precio=$precio, 
            stock=$stock 
        WHERE id_cupon='$id'";

if ($conexion->query($sql) === TRUE) {
    // Es importante saber si realmente se editó algo
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

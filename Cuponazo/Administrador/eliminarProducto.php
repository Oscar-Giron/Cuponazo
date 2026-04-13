<?php
// 1. Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "cuponazo");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// 2. Recibir el ID enviado desde el administrador.js
$data = json_decode(file_get_contents("php://input"), true);

// IMPORTANTE: Tu JS envía { id_cupon: id }, así que aquí lo recibimos igual
if (isset($data['id_cupon'])) {
    $id = $data['id_cupon'];

    // 3. Ejecutar el borrado (Asegúrate de que la columna sea id_cupon)
    $sql = "DELETE FROM cupon WHERE id_cupon = '$id'";

    if ($conexion->query($sql) === TRUE) {
        echo "OK";
    } else {
        echo "Error al eliminar: " . $conexion->error;
    }
} else {
    echo "Error: No se recibió el ID del cupón";
}

$conexion->close();
?>
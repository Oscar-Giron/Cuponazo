<?php
include __DIR__ . '/../Pagina Inicio/db.php';

// Recibimos los datos del JS
$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['id_compra']) && isset($input['id_cupon'])) {
    $id_compra = intval($input['id_compra']);
    $id_cupon = intval($input['id_cupon']);

    // Actualizamos el estado a 1 (Canjeado)
    $sql = "UPDATE compra_cupon SET canjeado = 1 
            WHERE id_compra = $id_compra AND id_cupon = $id_cupon";

    if (mysqli_query($conexion, $sql)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($conexion)]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Datos insuficientes']);
}
?>
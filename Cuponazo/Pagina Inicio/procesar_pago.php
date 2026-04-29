<?php
// Iniciamos la sesión para poder leer quién es
session_start();

// Conexión a la base de datos
include 'db.php';

// Indicamos que vamos a responder en formato JSON
header('Content-Type: application/json');

// Por seguridad: si alguien intenta comprar sin sesión, lo bloqueamos
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['status' => 'error', 'message' => 'No has iniciado sesión.']);
    exit;
}

// Recibimos los datos enviados desde la pasarela (JSON)
$json_input = file_get_contents('php://input');
$input = json_decode($json_input, true);

if (!$input) {
    echo json_encode(['status' => 'error', 'message' => 'No se han recibido datos válidos.']);
    exit;
}

// Extraemos los datos (¡EL ID LO COGEMOS DE LA SESIÓN, NO DEL JSON!)
$id_usuario = $_SESSION['id_usuario'];
$items      = $input['items'];
$total      = $input['total'];
$metodo     = isset($input['metodo']) ? $input['metodo'] : 'No especificado';

// Iniciamos una transaccion
mysqli_begin_transaction($conexion);

try {
    //  Insertamos  la tabla 'compra'
    // Usamos 'fecha' como nombre de columna
    $query_compra = "INSERT INTO compra (id_usuario, fecha) VALUES ($id_usuario, NOW())";
    
    if (!mysqli_query($conexion, $query_compra)) {
        throw new Exception("Error al crear la compra: " . mysqli_error($conexion));
    }
    
    // Obtenemos el ID de la compra que se acaba de generar
    $id_compra = mysqli_insert_id($conexion);

    // Insertamos los productos en 'compra_cupon' y bajar Stock
    foreach ($items as $item) {
        $id_cupon = $item['id'];
        $cantidad = $item['cantidad'];
        
        // Registro del cupon en la compra
        $query_item = "INSERT INTO compra_cupon (id_compra, id_cupon, cantidad) 
                       VALUES ($id_compra, $id_cupon, $cantidad)";
        
        if (!mysqli_query($conexion, $query_item)) {
            throw new Exception("Error al registrar el cupón ID $id_cupon: " . mysqli_error($conexion));
        }

        // Descontamos el stock de la tabla 'cupon'
        $query_stock = "UPDATE cupon SET stock = stock - $cantidad WHERE id_cupon = $id_cupon";
        mysqli_query($conexion, $query_stock);
    }

    //Registramos el pago en la tabla 'pago'
    $query_pago = "INSERT INTO pago (id_compra, importe, metodo_pago, estado, fecha) 
                   VALUES ($id_compra, $total, '$metodo', 'Completado', NOW())";
    
    if (!mysqli_query($conexion, $query_pago)) {
        throw new Exception("Error al registrar el pago: " . mysqli_error($conexion));
    }

    // Si todo ha ido bien, guardamos los cambios definitivamente
    mysqli_commit($conexion);
    
    // Respondemos exito al JavaScript
    echo json_encode(['status' => 'success']);

} catch (Exception $e) {
    // Si algo falla, deshacemos todo lo que se haya hecho en este proceso
    mysqli_rollback($conexion);
    
    // Respondemos con el error detallado
    echo json_encode([
        'status' => 'error', 
        'message' => $e->getMessage()
    ]);
}
?>
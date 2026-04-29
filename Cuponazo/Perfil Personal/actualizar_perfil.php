<?php
session_start();
//Incluimos la conexión
include __DIR__ . '/../Pagina Inicio/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //Recogemos los datos del formulario
    $id_usuario = 2; 
    $nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
    $password_nueva = $_POST['password'];

    // Preparamos la consulta básica
    $query = "UPDATE usuarios SET nombre = '$nombre' WHERE id_usuario = $id_usuario";

    //Si el usuario cambio la contraseña, tambien la actualizamos
    if (!empty($password_nueva)) {
        //Encriptamos la contraseña igual que en el registro
        $password_hash = password_hash($password_nueva, PASSWORD_DEFAULT);
        $query = "UPDATE usuarios SET nombre = '$nombre', password = '$password_hash' WHERE id_usuario = $id_usuario";
    }

    //Ejecutamos la actualizacion
    if (mysqli_query($conexion, $query)) {
        // Si todo sale bien, volvemos al perfil con un mensaje de exito
        header("Location: Perfil.php?status=success");
    } else {
        // Si hay error, avisamos
        echo "Error al actualizar: " . mysqli_error($conexion);
    }
} else {
    //Si alguien intenta entrar a este archivo sin enviar el formulario, lo echamos
    header("Location: Perfil.php");
}
?>
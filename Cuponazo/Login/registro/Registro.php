<?php
//Conexión a la base de datos (servidor, usuario, contraseña, base_de_datos)
$conexion = mysqli_connect("localhost", "root", "", "cuponazo");

//Verificamos si la conexion funciona
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

//Recogemos los datos del HTML usando los valores de los "name" de los inputs
$nombre = $_POST['nombre_usuario'];
$email  = $_POST['correo_usuario'];
$password = $_POST['password'];

// ENCRIPTAR LA CONTRASEÑA
$password_fuerte = password_hash($password, PASSWORD_DEFAULT);

//Orden de SQL para insertar los valores dentro de la tabla
$sql = "INSERT INTO usuarios (nombre, email, password) 
        VALUES ('$nombre', '$email', '$password_fuerte')";

//Ejecutar la orden y avisar al usuario
if (mysqli_query($conexion, $sql)) {
    echo "<h1>¡Registro completado con éxito!</h1>";
    
} else {
    echo "Error al registrar: " . mysqli_error($conexion);
}

// 6. Cerrar la conexión
mysqli_close($conexion);
?>
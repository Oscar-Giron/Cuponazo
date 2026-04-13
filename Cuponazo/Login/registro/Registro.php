<?php
// 1. Conexión a la base de datos (servidor, usuario, contraseña, base_de_datos)
$conexion = mysqli_connect("localhost", "root", "", "cuponazo");

// Verificamos si la conexión falló
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// 2. Recogemos los datos del HTML usando los 'name' de los inputs
$nombre = $_POST['nombre_usuario'];
$email  = $_POST['correo_usuario'];
$password = $_POST['password'];

// 3. ENCRIPTAR LA CONTRASEÑA (¡Vital por seguridad!)
// Esto convierte "12345" en algo como "$2y$10$asdf..."
$password_fuerte = password_hash($password, PASSWORD_DEFAULT);

// 4. La orden de SQL para insertar
$sql = "INSERT INTO usuarios (nombre, email, password) 
        VALUES ('$nombre', '$email', '$password_fuerte')";

// 5. Ejecutar la orden y avisar al usuario
if (mysqli_query($conexion, $sql)) {
    echo "<h1>¡Registro completado con éxito!</h1>";
    
} else {
    echo "Error al registrar: " . mysqli_error($conexion);
}

// 6. Cerrar la conexión
mysqli_close($conexion);
?>
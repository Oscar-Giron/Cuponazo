<?php
session_start();

// 1. Conexión
$conexion = mysqli_connect("localhost", "root", "", "cuponazo");

// IMPORTANTE: Establecer el conjunto de caracteres a UTF8 para que reconozca la "ñ"
mysqli_set_charset($conexion, "utf8");

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

$usuario_introducido = $_POST['usuario'];
$password_introducida = $_POST['password'];

// --- BLOQUE 1: BUSCAR EN LA TABLA ADMINISTRADOR ---
// Aquí usamos la columna 'contraseña'
$consulta_admin = "SELECT * FROM administrador WHERE nombre = '$usuario_introducido'";
$res_admin = mysqli_query($conexion, $consulta_admin);

if ($res_admin && mysqli_num_rows($res_admin) > 0) {
    $datos_admin = mysqli_fetch_assoc($res_admin);
    
    // Comparamos con la columna 'contraseña'
    if ($password_introducida === $datos_admin['contraseña']) {
        $_SESSION['usuario_nombre'] = $datos_admin['nombre'];
        header("Location: http://localhost/Cuponazo/Administrador/administrador.html");
        exit();
    }
}

// --- BLOQUE 2: BUSCAR EN LA TABLA USUARIOS ---
// Aquí la columna se llamaba 'password' según tu código anterior
$consulta_user = "SELECT * FROM usuarios WHERE nombre = '$usuario_introducido'";
$res_user = mysqli_query($conexion, $consulta_user);

if ($res_user && mysqli_num_rows($res_user) > 0) {
    $datos_user = mysqli_fetch_assoc($res_user);
    
    // Verificamos si la contraseña coincide (ya sea hash o texto plano)
    if (password_verify($password_introducida, $datos_user['password']) || $password_introducida === $datos_user['password']) {
        $_SESSION['usuario_nombre'] = $datos_user['nombre'];
        header("Location: http://localhost/Cuponazo/Pagina%20Inicio/Pag_Inicio.html");
        exit();
    }
}

// --- SI NADA COINCIDE ---
echo "<script>
        alert('Usuario o contraseña incorrectos');
        window.location.href='inicio_sesion.html';
      </script>";

mysqli_close($conexion);
?>
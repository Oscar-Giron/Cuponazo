<?php
session_start();
// 1. Conexion a la bbddd
$conexion = mysqli_connect("localhost", "root", "", "cuponazo");

mysqli_set_charset($conexion, "utf8");

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

$usuario_introducido = $_POST['usuario'];
$password_introducida = $_POST['password'];

//BUSCAR EN LA TABLA ADMINISTRADOR
$consulta_admin = "SELECT * FROM administrador WHERE nombre = '$usuario_introducido'";
$res_admin = mysqli_query($conexion, $consulta_admin);

if ($res_admin && mysqli_num_rows($res_admin) > 0) {
    $datos_admin = mysqli_fetch_assoc($res_admin);
    
    // Usamos un condicional para buscar la contraseña y si coincide nos redirige a la pagina del administrador
    if ($password_introducida === $datos_admin['contraseña']) {
        $_SESSION['usuario_nombre'] = $datos_admin['nombre'];
        header("Location: http://localhost/Cuponazo/Administrador/administrador.html");
        exit();
    }
}

//BUSCAR EN LA TABLA USUARIOS

$consulta_user = "SELECT * FROM usuarios WHERE nombre = '$usuario_introducido'";
$res_user = mysqli_query($conexion, $consulta_user);

if ($res_user && mysqli_num_rows($res_user) > 0) {
    $datos_user = mysqli_fetch_assoc($res_user);
    
       //Usamos un condicional para buscar la contraseña y si coincide nos redirige a la pagina de inicio
    if (password_verify($password_introducida, $datos_user['password']) || $password_introducida === $datos_user['password']) {
        $_SESSION['usuario_nombre'] = $datos_user['nombre'];
        header("Location: http://localhost/Cuponazo/Pagina%20Inicio/Pag_inicio.php");
        exit();
    }
}

//SI NADA COINCIDE
echo "<script>
        alert('Usuario o contraseña incorrectos');
        window.location.href='inicio_sesion.html';
      </script>";

mysqli_close($conexion);
?>
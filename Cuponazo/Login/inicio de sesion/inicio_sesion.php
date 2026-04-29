<?php
session_start();
// Conexion a la bbdd
$conexion = mysqli_connect("localhost", "root", "", "cuponazo");
mysqli_set_charset($conexion, "utf8");

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

$usuario_introducido = $_POST['usuario'];
$password_introducida = $_POST['password'];

// 1. BUSCAR EN LA TABLA ADMINISTRADOR
$consulta_admin = "SELECT * FROM administrador WHERE nombre = '$usuario_introducido'";
$res_admin = mysqli_query($conexion, $consulta_admin);

if ($res_admin && mysqli_num_rows($res_admin) > 0) {
    $datos_admin = mysqli_fetch_assoc($res_admin);
    
    // Si coincide la contraseña del admin
    if ($password_introducida === $datos_admin['contraseña']) {
        $_SESSION['usuario_nombre'] = $datos_admin['nombre'];
        // Redirigir administrador
        header("Location: http://localhost/Cuponazo/Administrador/administrador.html");
        exit();
    }
}

// 2. BUSCAR EN LA TABLA USUARIOS
$consulta_user = "SELECT * FROM usuarios WHERE nombre = '$usuario_introducido'";
$res_user = mysqli_query($conexion, $consulta_user);

if ($res_user && mysqli_num_rows($res_user) > 0) {
    $datos_user = mysqli_fetch_assoc($res_user);
    
    // Si la contraseña es correcta
    if (password_verify($password_introducida, $datos_user['password']) || $password_introducida === $datos_user['password']) {
        
        // ¡LA PIEZA CLAVE QUE FALTABA!
        $_SESSION['usuario_nombre'] = $datos_user['nombre'];
        $_SESSION['id_usuario'] = $datos_user['id_usuario']; // Le ponemos la "pulsera" con su ID
        
        // Lo mandamos a la página de inicio
        header("Location: http://localhost/Cuponazo/Pagina%20Inicio/Pag_inicio.php");
        exit();
    }
}

// 3. SI NADA COINCIDE (Muestra alerta y vuelve al login)
echo "<script>
        alert('Usuario o contraseña incorrectos');
        window.location.href='inicio_sesion.html';
      </script>";

mysqli_close($conexion);
?>
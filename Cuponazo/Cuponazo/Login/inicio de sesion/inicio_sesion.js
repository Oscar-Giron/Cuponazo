function iniciarSesion() {
    // 1. Capturamos los valores (opcional para validación)
    const user = document.getElementById('usuario').value;
    const pass = document.getElementById('password').value;

    //Simulacion de validacion
    if (user === "admin" && pass === "1234") {
        alert("¡Inicio de sesión exitoso!");
        //Redirigir a la página de inicio del administrador
        window.location.href = "../Administrador/administrador.html"; 
    } else {
        alert("Usuario o contraseña incorrectos");
    }
}
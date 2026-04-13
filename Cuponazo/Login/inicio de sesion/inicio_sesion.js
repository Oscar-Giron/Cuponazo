function iniciarSesion() {
    // 1. Capturamos los valores (opcional para validación)
    const user = document.getElementById('usuario').value;
    const pass = document.getElementById('password').value;

    // 2. Simulación de validación
    if (user === "admin" && pass === "1234") {
        alert("¡Inicio de sesión exitoso!");

        // 3. Redirigir a la página de inicio
        // Cambia 'inicio.html' por el nombre de tu archivo principal
        window.location.href = "../Administrador/administrador.html"; 
    } else {
        alert("Usuario o contraseña incorrectos");
    }
}
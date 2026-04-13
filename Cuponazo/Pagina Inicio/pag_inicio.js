// Función para abrir el menú lateral
function abrirMenu() {
    document.getElementById("miMenu").classList.add("abierto");
}

// Función para cerrar el menú lateral
function cerrarMenu() {
    document.getElementById("miMenu").classList.remove("abierto");
}

// Función para el desplegable de Categorías
function toggleCategorias() {
    const submenu = document.getElementById("sub-categorias");
    const flecha = document.getElementById("flecha");

    if (submenu.style.display === "block") {
        submenu.style.display = "none";
        flecha.style.transform = "rotate(0deg)";
    } else {
        submenu.style.display = "block";
        flecha.style.transform = "rotate(180deg)";
    }
}
// Función para el desplegable del perfil
function togglePerfil() {
    const submenu = document.getElementById("sub-perfil");
    const flecha = document.getElementById("flecha");

    if (submenu.style.display === "block") {
        submenu.style.display = "none";
        flecha.style.transform = "rotate(0deg)";
    } else {
        submenu.style.display = "block";
        flecha.style.transform = "rotate(180deg)";
    }
}
// Esperamos a que el contenido esté cargado
document.addEventListener('DOMContentLoaded', () => {
    
    // 1. Capturamos el input de búsqueda
    // Lo buscamos dentro de la clase que creamos en el header
    const buscador = document.querySelector('.search-container input');
    
    // 2. Capturamos todos los contenedores de productos
    // Usamos los nombres de tus clases actuales
    const productos = document.querySelectorAll('.rectangulo1, .rectangulo2, .rectangulo3');

    // 3. Escuchamos cuando el usuario escribe
    buscador.addEventListener('input', () => {
        const filtro = buscador.value.toLowerCase(); // Lo que escribe el usuario en minúsculas

        productos.forEach(producto => {
            // Obtenemos el texto del título (h1, h2 o h3) y de la descripción
            const titulo = producto.querySelector('h1, h2, h3').textContent.toLowerCase();
            const descripcion = producto.querySelector('.descripcion').textContent.toLowerCase();

            // 4. Comprobamos si el filtro coincide con el título o la descripción
            if (titulo.includes(filtro) || descripcion.includes(filtro)) {
                // Si coincide, mostramos el producto
                // IMPORTANTE: Ponemos 'flex' porque es el display que te di en el CSS
                producto.style.display = 'flex'; 
            } else {
                // Si no coincide, lo ocultamos
                producto.style.display = 'none';
            }
        });
    });
});

/* --- AQUÍ ABAJO PUEDES SEGUIR CON TUS FUNCIONES DE MENÚ LATERAL --- */

function abrirMenu() {
    document.getElementById("miMenu").classList.add("abierto");
}

function cerrarMenu() {
    document.getElementById("miMenu").classList.remove("abierto");
}

// ... (tus funciones de togglePerfil y toggleCategorias)
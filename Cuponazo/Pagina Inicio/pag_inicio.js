/* ESTADO INICIAL Y LOCALSTORAGE */

// Intentamos cargar el carrito. Si falla o esta corrupto, empezamos con un array vacio []
let carrito = [];
try {
    const datosGuardados = localStorage.getItem('carrito_cuponazo');
    carrito = datosGuardados ? JSON.parse(datosGuardados) : [];
} catch (e) {
    console.error("Error al cargar el storage, reiniciando carrito");
    carrito = [];
}

function guardarEnStorage() {
    localStorage.setItem('carrito_cuponazo', JSON.stringify(carrito));
}

/* MENUS Y NAVEGACION */

function abrirMenu() { document.getElementById("miMenu").classList.add("abierto"); }
function cerrarMenu() { document.getElementById("miMenu").classList.remove("abierto"); }

function toggleCategorias() {
    const submenu = document.getElementById("sub-categorias");
    const flecha = document.getElementById("flecha-cat");
    if (submenu.style.display === "block") {
        submenu.style.display = "none";
        flecha.style.transform = "rotate(0deg)";
    } else {
        submenu.style.display = "block";
        flecha.style.transform = "rotate(180deg)";
    }
}

function togglePerfil() {
    const submenu = document.getElementById("sub-perfil");
    const flecha = document.getElementById("flecha-perfil");
    if (submenu.style.display === "block") {
        submenu.style.display = "none";
        flecha.style.transform = "rotate(0deg)";
    } else {
        submenu.style.display = "block";
        flecha.style.transform = "rotate(180deg)";
    }
}

function confirmarCerrarSesion() {
    if (confirm("¿Estás seguro de que quieres cerrar sesión?")) {
        window.location.href = 'http://localhost/Cuponazo/Login/inicio%20de%20sesion/inicio_sesion.html'; 
    }
}

/* GESTION DEL CARRITO*/

function abrirCarrito() { document.getElementById("cart-sidebar").classList.add("abierto"); }
function cerrarCarrito() { document.getElementById("cart-sidebar").classList.remove("abierto"); }

function añadirAlCarrito(id, nombre, precio, stockMax) {
    const idStr = String(id);
    // Convertimos el precio a numero de forma segura
    const precioNum = parseFloat(precio) || 0;
    const stockNum = parseInt(stockMax) || 0;

    const itemExistente = carrito.find(item => String(item.id) === idStr);

    if (itemExistente) {
        if (itemExistente.cantidad < stockNum) {
            itemExistente.cantidad++;
        } else {
            alert(`Lo sentimos, el stock máximo de ${nombre} es de ${stockNum} unidades.`);
            return;
        }
    } else {
        carrito.push({
            id: idStr,
            nombre: nombre,
            precio: precioNum,
            cantidad: 1,
            stockMax: stockNum
        });
    }
    
    guardarEnStorage();
    actualizarInterfazCarrito();
    abrirCarrito();
}

function cambiarCantidad(index, delta) {
    const item = carrito[index];
    if (!item) return;

    const nuevaCantidad = item.cantidad + delta;

    if (nuevaCantidad > 0 && nuevaCantidad <= item.stockMax) {
        item.cantidad = nuevaCantidad;
    } else if (nuevaCantidad <= 0) {
        carrito.splice(index, 1);
    } else {
        alert("No hay más unidades disponibles en stock.");
    }

    guardarEnStorage();
    actualizarInterfazCarrito();
}

function eliminarDelCarrito(index) {
    carrito.splice(index, 1);
    guardarEnStorage();
    actualizarInterfazCarrito();
}

function actualizarInterfazCarrito() {
    const contenedor = document.getElementById("cart-items");
    const totalElemento = document.getElementById("cart-total");
    
    if (!contenedor || !totalElemento) return;

    if (carrito.length === 0) {
        contenedor.innerHTML = '<p class="cart-vacio">Tu carrito está vacío</p>';
        totalElemento.textContent = "0.00€";
        return;
    }

    contenedor.innerHTML = "";
    let total = 0;

    carrito.forEach((item, index) => {
        
        const p = parseFloat(item.precio) || 0;
        const subtotal = p * item.cantidad;
        total += subtotal;
        
        contenedor.innerHTML += `
            <div class="item-carrito">
                <div class="item-info">
                    <p style="font-weight:bold; color:#1e293b; margin:0">${item.nombre}</p>
                    <p style="color:#64748b; font-size:11px; margin:0">${p.toFixed(2)}€/ud</p>
                </div>
                
                <div class="cantidad-control">
                    <button class="btn-qty" onclick="cambiarCantidad(${index}, -1)">-</button>
                    <span class="qty-numero">${item.cantidad}</span>
                    <button class="btn-qty" onclick="cambiarCantidad(${index}, 1)">+</button>
                </div>
                
                <p style="font-weight:bold; width:50px; text-align:right; margin:0">${subtotal.toFixed(2)}€</p>

                <button class="btn-eliminar-item" onclick="eliminarDelCarrito(${index})">
                    🗑️
                </button>
            </div>
        `;
    });

    totalElemento.textContent = total.toFixed(2) + "€";
}

/* INICIALIZACIÓN Y EVENTOS */

document.addEventListener('DOMContentLoaded', () => {
    
    actualizarInterfazCarrito();

    //Buscador
    const buscador = document.querySelector('.search-container input');
    if (buscador) {
        buscador.addEventListener('input', () => {
            const filtro = buscador.value.toLowerCase();
            const productos = document.querySelectorAll('.rectangulo-producto');
            productos.forEach(p => {
                const titulo = p.querySelector('h1').textContent.toLowerCase();
                p.style.display = titulo.includes(filtro) ? 'flex' : 'none';
            });
        });
    }

    document.addEventListener('click', (e) => {
        if (e.target && e.target.classList.contains('boton-añadir')) {
            const b = e.target;
            añadirAlCarrito(
                b.getAttribute('data-id'),
                b.getAttribute('data-nombre'),
                b.getAttribute('data-precio'),
                b.getAttribute('data-stock')
            );
        }
    });
});

function procesarPago() {
    if (carrito.length === 0) {
        alert("El carrito está vacío.");
        return;
    }
    //En lugar de pagar directamente, vamos a la nueva pagina
    window.location.href = 'pasarela.php';
}
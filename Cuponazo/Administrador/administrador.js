// -------------------- VARIABLES GLOBALES -------------------- 
let filaEditando = null; 

// -------------------- FUNCIONALIDAD DEL BUSCADOR -------------------- 

function filtrarProductos() {
    const input = document.getElementById('inputBuscador');
    const filtro = input.value.toLowerCase();
    const filas = document.querySelectorAll('#tablaProductos tr');

    filas.forEach(fila => {
        // Buscamos en las columnas: ID (0), Nombre (1) y Categoría (2)
        const id = fila.children[0].textContent.toLowerCase();
        const nombre = fila.children[1].textContent.toLowerCase();
        const categoria = fila.children[2].textContent.toLowerCase();

        if (id.includes(filtro) || nombre.includes(filtro) || categoria.includes(filtro)) {
            fila.style.display = ""; // Mostrar
        } else {
            fila.style.display = "none"; // Ocultar
        }
    });
}

// -------------------- CARGAR PRODUCTOS -------------------- 

function cargarProductos(){ 
    fetch("obtenerProductos.php") 
    .then(res => res.json()) 
    .then(data => { 
        const tabla = document.getElementById("tablaProductos"); 
        tabla.innerHTML = ""; 

        data.forEach(producto => { 
            const fila = ` 
            <tr> 
                <td>${producto.id_cupon}</td> 
                <td>${producto.nombre}</td> 
                <td>${producto.categoria}</td> 
                <td>${producto.precio}€</td> 
                <td>${producto.stock}</td> 
                <td> 
                    <button class="btn-edit" onclick="editarProducto(this)">Editar</button> 
                    <button class="btn-delete" onclick="eliminarProducto('${producto.id_cupon}')">Eliminar</button> 
                </td> 
            </tr> 
            `; 
            tabla.innerHTML += fila; 
        }); 

        // IMPORTANTE: Después de llenar la tabla, aplicamos el filtro 
        // por si el usuario ya tenía algo escrito en el buscador.
        filtrarProductos();
    })
    .catch(err => console.error("Error cargando productos:", err));
} 

// -------------------- GESTIÓN DE PRODUCTOS (GUARDAR/EDITAR/ELIMINAR) -------------------- 

function GuardarProducto(){ 
    const id = document.getElementById("p_id").value; 
    const nombre = document.getElementById("p_nombre").value; 
    const categoria = document.getElementById("p_categoria").value; 
    const precio = document.getElementById("p_precio").value; 
    const stock = document.getElementById("p_stock").value; 

    if(!id || !nombre || !categoria || !precio || !stock){ 
        alert("Todos los campos son obligatorios."); 
        return; 
    } 

    const datos = { id_cupon: id, nombre, categoria, precio, stock };
    const url = filaEditando ? "editarProducto.php" : "guardarProducto.php";

    fetch(url, { 
        method: "POST", 
        headers: {"Content-Type": "application/json"}, 
        body: JSON.stringify(datos) 
    }) 
    .then(res => res.text()) 
    .then(respuesta => { 
        if(respuesta.trim() === "OK") {
            cargarProductos(); 
            cerrarModal(); 
        } else {
            alert("Error: " + respuesta);
        }
    });
} 

function eliminarProducto(id){ 
    if(!confirm("¿Eliminar " + id + "?")) return; 
    fetch("eliminarProducto.php", { 
        method: "POST", 
        headers: {"Content-Type": "application/json"}, 
        body: JSON.stringify({ id_cupon: id }) 
    }) 
    .then(res => res.text()) 
    .then(res => { if(res.trim() === "OK") cargarProductos(); });
}

// -------------------- MODAL Y EDICIÓN -------------------- 

function AdCupon(){ 
    document.getElementById("modalProducto").style.display = "block"; 
    document.getElementById("tituloModal").innerText = filaEditando ? "Editar Producto" : "Añadir Nuevo Producto"; 
    document.getElementById("p_id").disabled = !!filaEditando;
} 

function cerrarModal(){ 
    document.getElementById("modalProducto").style.display = "none"; 
    document.querySelectorAll(".modal input, .modal select").forEach(i => i.value = "");
    filaEditando = null; 
} 

function editarProducto(boton){ 
    filaEditando = boton.parentElement.parentElement; 
    const celdas = filaEditando.children; 
    document.getElementById("p_id").value = celdas[0].innerText; 
    document.getElementById("p_nombre").value = celdas[1].innerText; 
    document.getElementById("p_precio").value = parseFloat(celdas[3].innerText); 
    document.getElementById("p_stock").value = celdas[4].innerText; 
    AdCupon(); 
} 

// -------------------- INICIALIZACIÓN -------------------- 

window.onload = function(){ 
    cargarProductos(); 

    // Escuchar el buscador
    document.getElementById('inputBuscador').addEventListener('input', filtrarProductos);
}
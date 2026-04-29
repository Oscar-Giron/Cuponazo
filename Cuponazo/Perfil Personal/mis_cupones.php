<?php 
session_start();
include __DIR__ . '/../Pagina Inicio/db.php'; 

// Verificamos el usuario
if (!isset($_SESSION['id_usuario'])) {
    // Si no está logueado, lo mandamos al login
    header("Location: ../Login/inicio_sesion.html"); 
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

// Consulta para traer los cupones que ha comprado este usuario
$sql = "SELECT c.nombre, c.imagen, cc.id_compra, cc.id_cupon, cc.canjeado, co.fecha 
        FROM cupon c 
        JOIN compra_cupon cc ON c.id_cupon = cc.id_cupon 
        JOIN compra co ON cc.id_compra = co.id_compra 
        WHERE co.id_usuario = $id_usuario 
        ORDER BY co.fecha DESC";

$resultado = mysqli_query($conexion, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Cupones - Cuponazo</title>
    <link rel="stylesheet" href="../Pagina Inicio/pag_inicio.css">
    <link rel="stylesheet" href="mis_cupones.css">
</head>
<body>
    <main>
        <!-- Logo -->
        <header class="main-header"> 
            <a href="../Pagina Inicio/Pag_inicio.php" class="logo-link">
                <img src="../imagenes/logo.png" alt="Logo" class="logo"> 
            </a>
            <!--Barra de busqueda y carrito -->
            <div class="search-container">
                <span style="cursor:pointer; font-size: 1.5rem;" onclick="abrirCarrito()">🛒</span>
                <input placeholder="Búsqueda">
            </div>
        </header>
<!--BARRA LATERAL IZQUIERDA-->
        <div class="menu-icon" onclick="abrirMenu()">
            <div></div><div></div><div></div>
        </div>

        <nav id="miMenu" class="nav-side">
            <a href="javascript:void(0)" class="btn-cerrar" onclick="cerrarMenu()">&times;</a>
            
            <button class="btn-desplegable" onclick="togglePerfil()">
                Mi Perfil <span id="flecha-perfil">▼</span>
            </button>
            <div id="sub-perfil" class="contenedor-submenu">
                <a href="Perfil.php">Perfil Personal</a>
                <a href="mis_cupones.php" style="color: #16A34A; font-weight: bold;">Mis Cupones</a>
                <a href="javascript:void(0)" onclick="cerrarMenu(); abrirCarrito();">Mi Carrito</a>
            </div>
            
            <button class="btn-desplegable" onclick="toggleCategorias()">
                Categorías <span id="flecha-cat">▼</span>
            </button>
            <div id="sub-categorias" class="contenedor-submenu">
                <a href="../Pagina Inicio/Pag_inicio.php?id_cat=1">Deportes</a>
                <a href="../Pagina Inicio/Pag_inicio.php?id_cat=2">Bienestar</a>
                <a href="../Pagina Inicio/Pag_inicio.php?id_cat=3">Ocio</a>
                <a href="../Pagina Inicio/Pag_inicio.php" style="color: #FFD700; font-weight: bold;">Ver Todos</a>
            </div>

            <a href="javascript:void(0)" class="enlace-logout" onclick="confirmarCerrarSesion()">Cerrar Sesión</a>
        </nav>
<!-- Mis cupones -->
        <section style="max-width: 1200px; margin: 2rem auto; padding: 0 1rem; min-height: 80vh;">
            <h2 style="color: #1e293b;">Mis Cupones Adquiridos</h2>
            <hr style="border: 0; border-top: 1px solid #E2E8F0; margin: 1.5rem 0 2.5rem 0;">

            <div class="cupones-grid">
                <?php while($cupon = mysqli_fetch_assoc($resultado)) { 
                    $fecha_caducidad = date("d/m/Y", strtotime($cupon['fecha'] . " +1 month"));
                ?>
                    <div class="ticket-cupon">
                        <img src="../imagenes/<?php echo $cupon['imagen']; ?>" class="ticket-img" onerror="this.src='../imagenes/logo.png'">
                        <div class="ticket-body">
                            <h3><?php echo htmlspecialchars($cupon['nombre']); ?></h3>
                            <p class="ticket-info-fecha">Adquirido: <span class="fecha-valor"><?php echo date("d/m/Y", strtotime($cupon['fecha'])); ?></span></p>
                            <p class="ticket-info-fecha">Vence: <span class="fecha-caducidad"><?php echo $fecha_caducidad; ?></span></p>
                            
                            <div class="codigo-canje">
                                CP-<?php echo strtoupper(substr(md5($cupon['id_compra'] . $cupon['id_cupon']), 0, 8)); ?>
                            </div>

                            <button class="btn-canjear <?php echo ($cupon['canjeado'] == 0) ? 'btn-activo' : 'btn-usado'; ?>" 
                                    <?php echo ($cupon['canjeado'] == 1) ? 'disabled' : ''; ?> 
                                    onclick="canjearCupon(this, <?php echo $cupon['id_compra']; ?>, <?php echo $cupon['id_cupon']; ?>)">
                                <?php echo ($cupon['canjeado'] == 0) ? 'Canjear Cupón' : 'Cupón Usado'; ?>
                            </button>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <!--Carrito lateral derecho-->
            <?php if(mysqli_num_rows($resultado) == 0): ?>
                <div style="text-align:center; padding: 5rem 0; color: #94A3B8;">
                    <p>No tienes cupones. <a href="../Pagina Inicio/Pag_inicio.php" style="color:#16A34A">¡Compra el primero!</a></p>
                </div>
            <?php endif; ?>
        </section>

        <div id="cart-sidebar" class="cart-side">
            <div class="cart-header">
                <h2>Tu Carrito</h2>
                <a href="javascript:void(0)" class="btn-cerrar" onclick="cerrarCarrito()">&times;</a>
            </div>
            
            <div id="cart-items" class="cart-body">
                <p class="cart-vacio">Cargando carrito...</p>
            </div>

            <div class="cart-footer">
                <div class="total-container">
                    <span>Total:</span>
                    <span id="cart-total">0.00€</span>
                </div>
                <button class="btn-pagar" onclick="procesarPago()">Pagar Ahora</button>
            </div>
        </div>

    </main>

    <script src="../Pagina Inicio/pag_inicio.js"></script>
    
    <script>
//Funcion de canjear el cupon 
 function canjearCupon(btn, idCompra, idCupon) {
    if(confirm("¿Seguro que quieres canjearlo? Una vez hecho, no podrás volver a usarlo.")) {
        
        // Enviamos la petición al servidor
        fetch('actualizar_canjeo.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                id_compra: idCompra,
                id_cupon: idCupon
            })
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                // Si la base de datos se actualizó, cambiamos el botón
                btn.innerHTML = "Cupón Usado";
                btn.className = "btn-canjear btn-usado";
                btn.disabled = true;
                alert("✅ ¡Cupón canjeado con éxito! El cambio se ha guardado.");
            } else {
                alert("❌ Error al canjear: " + data.message);
            }
        })
        .catch(err => {
            console.error("Error:", err);
            alert("Hubo un fallo al conectar con el servidor.");
        });
    }
}
    </script>
</body>
</html>

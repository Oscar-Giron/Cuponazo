<?php 
include __DIR__ . '/db.php'; 

//Obtener el ID del cupon desde la URL
$id_cupon = isset($_GET['id']) ? intval($_GET['id']) : 0;

//Consultar los datos del cupon
$consulta = "SELECT * FROM cupon WHERE id_cupon = $id_cupon";
$resultado = mysqli_query($conexion, $consulta);
$cupon = mysqli_fetch_assoc($resultado);

// Si el cupón no existe, volvemos a la tienda
if (!$cupon) {
    header("Location: Pag_inicio.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($cupon['nombre']); ?> - Detalle</title>
    <link rel="stylesheet" href="pag_inicio.css">
    <link rel="stylesheet" href="detalle_cupon.css">
</head>
<body class="detalle-page">
<!-- Logo, barra de busqueda y carrito -->
    <main>
        <header class="main-header"> 
            <a href="Pag_inicio.php" class="logo-link">
                <img src="../imagenes/logo.png" alt="Logo" class="logo"> 
            </a>
            
            <div class="search-container">
                <span style="cursor:pointer; font-size: 1.5rem;" onclick="abrirCarrito()">🛒</span>
                <input placeholder="Búsqueda">
            </div>
        </header>
<!-- menu lateral izquierdo -->
        <div class="menu-icon" onclick="abrirMenu()">
            <div></div><div></div><div></div>
        </div>

        <nav id="miMenu" class="nav-side">
            <a href="javascript:void(0)" class="btn-cerrar" onclick="cerrarMenu()">&times;</a>
            
            <button class="btn-desplegable" onclick="togglePerfil()">
                Mi Perfil <span id="flecha-perfil">▼</span>
            </button>
            <div id="sub-perfil" class="contenedor-submenu">
                <a href="../Perfil Personal/Perfil.php">Perfil Personal</a>
                <a href="../Perfil Personal/mis_cupones.php">Mis Cupones</a>
                <a href="javascript:void(0)" onclick="cerrarMenu(); abrirCarrito();">Mi Carrito</a>
            </div>
            
            <button class="btn-desplegable" onclick="toggleCategorias()">
                Categorías <span id="flecha-cat">▼</span>
            </button>
            <div id="sub-categorias" class="contenedor-submenu">
                <a href="Pag_inicio.php?id_cat=1">Deportes</a>
                <a href="Pag_inicio.php?id_cat=2">Bienestar</a>
                <a href="Pag_inicio.php?id_cat=3">Ocio</a>
                <a href="Pag_inicio.php" style="color: #FFD700; font-weight: bold;">Ver Todos</a>
            </div>

            <a href="javascript:void(0)" class="enlace-logout" onclick="confirmarCerrarSesion()">Cerrar Sesión</a>
        </nav>
<!--tarjetas de cupones -->
        <div class="main-content-detalle">
            <section class="tarjeta-detalle-max">
                
                <div class="seccion-superior">
                    <div class="bloque-img-top">
                        <img src="../imagenes/<?php echo $cupon['imagen']; ?>" onerror="this.src='../imagenes/logo.png'">
                    </div>

                    <div class="bloque-compra-top">
                        <span class="etiqueta-oferta">Oferta Destacada</span>
                        <h1><?php echo htmlspecialchars($cupon['nombre']); ?></h1>
                        
                        <div class="precios-foco">
                            <span class="p-actual"><?php echo number_format($cupon['precio'], 2); ?>€</span>
                            <span class="p-antes">Antes: <?php echo number_format($cupon['precio_original'], 2); ?>€</span>
                        </div>

                        <button class="boton-añadir btn-compra-detalle" 
                                data-id="<?php echo $cupon['id_cupon']; ?>" 
                                data-nombre="<?php echo htmlspecialchars($cupon['nombre'], ENT_QUOTES); ?>"
                                data-precio="<?php echo $cupon['precio']; ?>" 
                                data-stock="<?php echo $cupon['stock']; ?>">
                            Añadir al Carrito
                        </button>
                    </div>
                </div>

                <div class="seccion-texto-abajo">
                    <div class="info-bloque-ancho">
                        <h3>Detalles de la oferta</h3>
                        <p><?php echo $cupon['descripcion'] ?? "Disfruta de este increíble descuento exclusivo en Cuponazo. Calidad y ahorro en un solo click."; ?></p>
                    </div>
                    
                    <div class="info-bloque-ancho">
                        <h3>Condiciones de uso</h3>
                        <ul>
                            <li>Uso válido por persona y cupón.</li>
                            <li>Caducidad: 1 mes desde la compra.</li>
                            <li>Sujeto a disponibilidad del local y reserva previa.</li>
                        </ul>
                    </div>
                </div>
            </section>
        </div>
<!--Carrito lateral derecho-->
        <div id="cart-sidebar" class="cart-side">
            <div class="cart-header">
                <h2>Tu Carrito</h2>
                <a href="javascript:void(0)" class="btn-cerrar" onclick="cerrarCarrito()">&times;</a>
            </div>
            
            <div id="cart-items" class="cart-body">
                <p class="cart-vacio">El carrito está vacío</p>
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

    <script src="pag_inicio.js"></script>
</body>
</html>
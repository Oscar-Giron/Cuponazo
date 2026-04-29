<?php 
include __DIR__ . '/db.php'; 

//Miramos si el usuario ha pinchado en una categoria
$categoria_seleccionada = isset($_GET['id_cat']) ? $_GET['id_cat'] : null;

// Preparamos la consulta
if ($categoria_seleccionada) {
    $id = intval($categoria_seleccionada);
    $consulta = "SELECT * FROM cupon WHERE id_categoria = $id";
} else {
    $consulta = "SELECT * FROM cupon";
}

$resultado = mysqli_query($conexion, $consulta);

if (!$resultado) {
    die("Error en la consulta: " . mysqli_error($conexion));
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cuponazo - Ofertas</title>
    <link rel="stylesheet" href="pag_inicio.css">
</head>
<body>
<!--Logo, carrito y barra de busqueda -->
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
<!-- barra lateral izquierda -->
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
<!--Zona central con los cupones--> 
        <?php while($fila = mysqli_fetch_assoc($resultado)) { ?>
        <section>
            <div class="rectangulo-producto">
                <a href="detalle_cupon.php?id=<?php echo $fila['id_cupon']; ?>" class="enlace-detalle">
                    <article class="articulo-producto">
                        <img src="../imagenes/<?php echo $fila['imagen']; ?>" onerror="this.src='../imagenes/logo.png'">
                        
                        <div class="info">
                            <h1><?php echo htmlspecialchars($fila['nombre']); ?></h1>
                            <p class="descripcion">Aprovecha este cuponazo en <?php echo htmlspecialchars($fila['nombre']); ?>. ¡Unidades limitadas!</p>
                            
                            <div class="contenedor-precios">
                                <div class="precio-viejo">
                                    <span class="tachado"><?php echo number_format($fila['precio_original'], 2); ?>€</span>
                                    <span class="ahorro-badge">
                                        -<?php echo ($fila['precio_original'] > 0) ? round((1 - ($fila['precio'] / $fila['precio_original'])) * 100) : 0; ?>%
                                    </span>
                                </div>
                                <p class="precio-producto">Precio: <?php echo number_format($fila['precio'], 2); ?>€</p>
                            </div>
                        </div>
                    </article>
                </a>
                
                <button class="boton-añadir" 
                        data-id="<?php echo $fila['id_cupon']; ?>" 
                        data-nombre="<?php echo htmlspecialchars($fila['nombre'], ENT_QUOTES); ?>"
                        data-precio="<?php echo $fila['precio']; ?>" 
                        data-stock="<?php echo $fila['stock']; ?>">
                    Añadir al Carrito
                </button>
            </div> 
        </section>
        <?php } ?>
<!--Zona lateral derecha del carrito-->    
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
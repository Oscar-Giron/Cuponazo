<?php 
session_start();
include __DIR__ . '/../Pagina Inicio/db.php'; 

// Verificamos si el usuario tiene la "pulsera" (sesión)
if (!isset($_SESSION['id_usuario'])) {
    // Si no está logueado, lo mandamos al login de cabeza
    header("Location: ../Login/inicio_sesion.html"); 
    exit();
}

// Ahora $id_usuario será el de la persona que acaba de entrar, ¡Ya no es siempre el 2!
$id_usuario = $_SESSION['id_usuario'];

//CONSULTA PARA DATOS PERSONALES
$consulta_user = "SELECT * FROM usuarios WHERE id_usuario = $id_usuario";
$resultado_user = mysqli_query($conexion, $consulta_user);
$usuario = mysqli_fetch_assoc($resultado_user);

//CONSULTA PARA ESTADÍSTICAS (Suma de cupones y ahorro)
//Cruzamos la tabla de compras con la de cupones para saber que se ahorro en cada uno
$sql_stats = "SELECT 
                COUNT(cc.id_cupon) as total_cupones,
                SUM((c.precio_original - c.precio) * cc.cantidad) as ahorro_total
              FROM compra co
              JOIN compra_cupon cc ON co.id_compra = cc.id_compra
              JOIN cupon c ON cc.id_cupon = c.id_cupon
              WHERE co.id_usuario = $id_usuario";

$res_stats = mysqli_query($conexion, $sql_stats);
$stats = mysqli_fetch_assoc($res_stats);

//Guardamos los valores (si no hay compras, ponemos 0)
$cupones_comprados = $stats['total_cupones'] ?? 0;
$ahorro_acumulado = $stats['ahorro_total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil Personal - Cuponazo</title>
    <link rel="stylesheet" href="../Pagina Inicio/pag_inicio.css">
    <link rel="stylesheet" href="perfil.css">
</head>
<body>

    <main>
        <header class="main-header"> 
            <a href="../Pagina Inicio/Pag_inicio.php" class="logo-link">
                <img src="../imagenes/logo.png" alt="Logo" class="logo"> 
            </a>
            
            <div class="search-container">
                <span style="cursor:pointer" onclick="abrirCarrito()">🛒</span>
                <input placeholder="Busqueda">
            </div>
        </header>

        <div class="menu-icon" onclick="abrirMenu()">
            <div></div><div></div><div></div>
        </div>

        <nav id="miMenu" class="nav-side">
            <a href="javascript:void(0)" class="btn-cerrar" onclick="cerrarMenu()">&times;</a>
            
            <div class="menu-content">
                <button class="btn-desplegable" onclick="togglePerfil()">
                    Mi Perfil <span id="flecha-perfil">▼</span>
                </button>
                <div id="sub-perfil" class="contenedor-submenu">
                    <a href="Perfil.php">Perfil Personal</a>
                    <a href="../Perfil Personal/mis_cupones.php">Mis Cupones</a>
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
            </div>

            <a href="javascript:void(0)" class="enlace-logout" onclick="confirmarCerrarSesion()">Cerrar Sesión</a>
        </nav>

        <section style="margin-top: 2rem;">
            <div class="rectangulo-producto" style="flex-direction: column; cursor: default;">
                <div class="perfil-grid">
                    
                    <div class="avatar-section">
                        <div class="avatar-placeholder">
                            <?php echo strtoupper(substr($usuario['nombre'], 0, 1)); ?>
                        </div>
                        <h2><?php echo $usuario['nombre']; ?></h2>
                        <p style="color: #64748b;">
                            Miembro desde:<br>
                            <strong><?php echo date("d/m/Y", strtotime($usuario['fecha_registro'])); ?></strong>
                        </p>
                    </div>

                    <div class="info-section">
                        <form action="actualizar_perfil.php" method="POST">
                            <div class="form-group">
                                <label>Nombre de Usuario</label>
                                <input type="text" name="nombre" class="form-control" value="<?php echo $usuario['nombre']; ?>">
                            </div>
                            <div class="form-group">
                                <label>Email (No editable)</label>
                                <input type="email" class="form-control" value="<?php echo $usuario['email']; ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label>Nueva Contraseña</label>
                                <input type="password" name="password" class="form-control" placeholder="Dejar vacío para no cambiar">
                            </div>
                            <button type="submit" class="boton-añadir" style="width: auto; align-self: flex-start;">
                                Guardar Cambios
                            </button>
                        </form>

                    <div class="stats-container">
                        <div class="stat-card">
                            <span class="stat-num"><?php echo $cupones_comprados; ?></span>
                            <span style="font-size: 0.8rem; color: #64748b;">Cupones comprados</span>
                        </div>
                        <div class="stat-card">
                            <span class="stat-num"><?php echo number_format($ahorro_acumulado, 2); ?>€</span>
                            <span style="font-size: 0.8rem; color: #64748b;">Ahorro total</span>
                        </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <div id="cart-sidebar" class="cart-side">
            <div class="cart-header">
                <h2>Tu Carrito</h2>
                <a href="javascript:void(0)" class="btn-cerrar" onclick="cerrarCarrito()">&times;</a>
            </div>
            <div id="cart-items" class="cart-body"></div>
            <div class="cart-footer">
                <div class="total-container"><span>Total:</span><span id="cart-total">0.00€</span></div>
            </div>
        </div>

    </main>

    <script src="../Pagina Inicio/pag_inicio.js"></script>
</body>
</html>
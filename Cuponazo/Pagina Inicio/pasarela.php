<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Finalizar Compra - Cuponazo</title>
    <link rel="stylesheet" href="pag_inicio.css">
    <link rel="stylesheet" href="pasarela.css">
</head>
<body>
    <header class="main-header">
        <a href="Pag_inicio.php" class="logo-link"><img src="../imagenes/logo.png" class="logo"></a>
    </header>

    <div class="pasarela-container">
        <h2 style="margin-top:0">Finalizar Compra</h2>
        
        <div class="resumen-pago">
            <p style="margin:0; color:#64748b; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px;">Total a pagar</p>
            <h1 id="total-pasarela" style="margin:0.5rem 0 0 0; color:#16A34A">0.00€</h1>
        </div>
<!--Metodos de pago -->
        <h3>Elige tu método de pago:</h3>

        <div class="metodo-pago" id="metodo-tarjeta" onclick="seleccionarMetodo('tarjeta')">
            <span><input type="radio" name="metodo" id="radio-tarjeta"> 💳 Tarjeta de Crédito</span>
            <div id="form-tarjeta" class="formulario-pago">
                <input type="text" class="input-pago" placeholder="Número de tarjeta">
                <div style="display:flex; gap:10px">
                    <input type="text" class="input-pago" placeholder="MM/AA">
                    <input type="text" class="input-pago" placeholder="CVV">
                </div>
            </div>
        </div>

        <div class="metodo-pago" id="metodo-paypal" onclick="seleccionarMetodo('paypal')">
            <span><input type="radio" name="metodo" id="radio-paypal"> 🔵 PayPal</span>
            <div id="form-paypal" class="formulario-pago">
                <input type="email" class="input-pago" placeholder="Correo de PayPal">
            </div>
        </div>

        <div class="metodo-pago" id="metodo-bizum" onclick="seleccionarMetodo('bizum')">
            <span><input type="radio" name="metodo" id="radio-bizum"> 📲 Bizum</span>
            <div id="form-bizum" class="formulario-pago">
                <input type="text" class="input-pago" placeholder="Número de teléfono">
            </div>
        </div>
<!--Boton de confirmacion -->
        <button onclick="confirmarPagoFinal()" class="btn-confirmar" style="margin-top: 2rem;">Confirmar y Pagar</button>
    </div>

    <script>
        // Logica del Carrito
        const carrito = JSON.parse(localStorage.getItem('carrito_cuponazo')) || [];
        const total = carrito.reduce((acc, item) => acc + (item.precio * item.cantidad), 0);
        document.getElementById('total-pasarela').textContent = total.toFixed(2) + "€";

        function seleccionarMetodo(tipo) {
            // Limpiar estados anteriores
            document.querySelectorAll('.formulario-pago').forEach(f => f.style.display = 'none');
            document.querySelectorAll('.metodo-pago').forEach(m => m.classList.remove('active'));
            
            // Activar el nuevo
            document.getElementById('form-' + tipo).style.display = 'block';
            document.getElementById('radio-' + tipo).checked = true;
            document.getElementById('metodo-' + tipo).classList.add('active');
        }

        function confirmarPagoFinal() {
            if (carrito.length === 0) {
                alert("El carrito está vacío.");
                return;
            }

            // Llamada al servidor
            fetch('procesar_pago.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    items: carrito,
                    total: total,
                    id_usuario: 2
                })
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    alert("✅ ¡Pago realizado con éxito!");
                    localStorage.removeItem('carrito_cuponazo');
                    window.location.href = '../Perfil Personal/Perfil.php';
                } else {
                    alert("❌ Error: " + data.message);
                }
            })
            .catch(err => {
                console.error("Fallo de red:", err);
                alert("Error de conexión con el servidor.");
            });
        }
    </script>
</body>
</html>
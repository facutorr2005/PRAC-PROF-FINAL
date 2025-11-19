<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compra</title>
    <link rel="stylesheet" href="<?= url('/css/compra.css') ?>">
</head>
<body>
    <div class="encabezado">
        <div class="logo-titulo">
            <img class="logo" src="../Public/imagenes/logo.png" alt="logo">
            <div class="titulo">Q-Pay</div>
        </div>

        <button id="cancelarBtn" class="boton">Cancelar</button>
    </div>

    <div id="errorDiv" class="error-mensaje"></div>

    <div id="scanner-container" style="display: none;">
        <div id="reader"></div>
        <button id="close-scanner-btn" class="boton-accion">Cerrar</button>
    </div>

    <main class="carrito-contenedor">
        <h2>Carrito de Compras</h2>
        <div id="carrito" class="carrito"></div>
    </main>

    <div class="footer-fijo">
        <div id="total" class="total">Total: $0</div>
        <div class="botones">
            <button id="scanBtn" class="boton-accion">Escanear Producto</button>
            <button id="finalizarBtn" class="boton-accion">Finalizar Compra</button>
        </div>
    </div>

    <!-- Confirmación de cancelación -->
    <div id="confirmacion" class="modal">
        <div class="modal-contenido">
            <p>Se borrará el carrito de compras</p>
            <button id="confirmarCancelar" class="boton-accion">Confirmar</button>
            <button id="cerrarModal" class="boton-accion">Volver</button>
        </div>
    </div>

 <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>

<script>
    // ====== Carrito y elementos base ======
    const carrito    = [];
    const carritoDiv = document.getElementById('carrito');
    const totalDiv   = document.getElementById('total');
    const errorDiv   = document.getElementById('errorDiv');

    // ====== Modal cancelar compra ======
    const modal             = document.getElementById('confirmacion');
    const cancelarBtn       = document.getElementById('cancelarBtn');
    const confirmarCancelar = document.getElementById('confirmarCancelar');
    const cerrarModal       = document.getElementById('cerrarModal');

    cancelarBtn.addEventListener('click', () => {
        modal.style.display = 'flex';
    });

    cerrarModal.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    confirmarCancelar.addEventListener('click', () => {
        window.location.href = '<?= url('/panel') ?>';
    });

    // ====== SCANNER con html5-qrcode (PC OK - Celular depende navegador) ======
    const scanBtn = document.getElementById('scanBtn');
    const scannerContainer = document.getElementById('scanner-container');
    const closeScannerBtn = document.getElementById('close-scanner-btn');

    let html5QrCode = null;

    scanBtn.addEventListener('click', () => {

        // Si el navegador NO permite getUserMedia → fallback manual
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            const ean = prompt("Ingrese código EAN del producto:");
            if (ean) buscarProducto(ean);
            return;
        }

        // Mostrar contenedor
        scannerContainer.style.display = "block";

        // Inicializar scanner si no existe
        if (!html5QrCode) {
            html5QrCode = new Html5Qrcode("reader");
        }

        html5QrCode.start(
            { facingMode: "environment" }, // cámara trasera
            {
                fps: 10,
                qrbox: { width: 250, height: 250 }
            },
            async (decodedText) => {
                // Cuando escanea → paramos cámara y buscamos producto
                await html5QrCode.stop();
                scannerContainer.style.display = "none";
                buscarProducto(decodedText);
            },
            (errorMessage) => { /* ignorar errores de lectura */ }
        ).catch(err => {
            console.error("Error al iniciar cámara:", err);
            mostrarError("No se pudo acceder a la cámara. Se usará modo manual.");
            scannerContainer.style.display = "none";

            const ean = prompt("Ingrese código EAN del producto:");
            if (ean) buscarProducto(ean);
        });
    });

    closeScannerBtn.addEventListener('click', async () => {
        if (html5QrCode) await html5QrCode.stop();
        scannerContainer.style.display = 'none';
    });

    // ====== Buscar producto en backend ======
    async function buscarProducto(ean) {
        if (!ean) return;

        try {
            const respuesta = await fetch('<?= url('/api/producto') ?>?ean=' + encodeURIComponent(ean));
            const producto = await respuesta.json();

            if (!producto || !producto.nombre) {
                mostrarError("Producto no encontrado.");
                return;
            }

            const existente = carrito.find(p => p.ean === producto.ean);
            if (existente) {
                existente.cantidad++;
            } else {
                carrito.push({ ...producto, cantidad: 1 });
            }

            actualizarCarrito();
        } catch (err) {
            mostrarError("Error al conectar con la base de datos.");
        }
    }

    // ====== Actualizar carrito ======
    function actualizarCarrito() {
        carritoDiv.innerHTML = '';
        let total = 0;

        carrito.forEach((p, i) => {
            total += p.precio * p.cantidad;

            const item = document.createElement('div');
            item.classList.add('item');

            item.innerHTML = `
                <img src="${p.imagen}" class="img-prod">
                <div class="info-prod">
                    <h3>${p.nombre}</h3>
                    <p>$${p.precio.toFixed(2)} c/u</p>
                </div>
                <div class="cantidad">
                    <button onclick="cambiarCantidad(${i}, -1)">-</button>
                    <span>${p.cantidad}</span>
                    <button onclick="cambiarCantidad(${i}, 1)">+</button>
                </div>
                <button onclick="eliminar(${i})" class="eliminar">🗑</button>
            `;
            carritoDiv.appendChild(item);
        });

        totalDiv.textContent = 'Total: $' + total.toFixed(2);
    }

    // Global para onclick HTML dinámico
    window.cambiarCantidad = function(i, valor) {
        carrito[i].cantidad += valor;
        if (carrito[i].cantidad <= 0) carrito.splice(i, 1);
        actualizarCarrito();
    };

    window.eliminar = function(i) {
        carrito.splice(i, 1);
        actualizarCarrito();
    };

    // ====== Mostrar errores ======
    function mostrarError(msg) {
        errorDiv.textContent = msg;
        errorDiv.style.display = 'block';
        setTimeout(() => errorDiv.style.display = 'none', 3000);
    }

    // ====== Finalizar compra ======
    const finalizarBtn = document.getElementById('finalizarBtn');

    finalizarBtn.addEventListener('click', async () => {
        if (carrito.length === 0) {
            mostrarError("No hay productos en el carrito.");
            return;
        }

        try {
            const respuesta = await fetch('<?= url('/api/compra') ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(carrito)
            });

            const data = await respuesta.json();

            if (data.success) {
                alert("Compra finalizada correctamente. Generando QR...");
            } else {
                mostrarError("Error al guardar la compra.");
            }

        } catch {
            mostrarError("No se pudo enviar la compra.");
        }
    });
</script>


</body>
</html>

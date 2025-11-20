<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compra</title>
    <link rel="stylesheet" href="../Public/css/compra.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
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

    <div id="confirmacion" class="modal">
        <div class="modal-contenido">
            <p>Se borrará el carrito de compras</p>
            <button id="confirmarCancelar" class="boton-accion">Confirmar</button>
            <button id="cerrarModal" class="boton-accion">Volver</button>
        </div>
    </div>

    <script>
        const carrito = [];
        const carritoDiv = document.getElementById('carrito');
        const totalDiv = document.getElementById('total');
        const errorDiv = document.getElementById('errorDiv');

        const modal = document.getElementById('confirmacion');
        const cancelarBtn = document.getElementById('cancelarBtn');
        const confirmarCancelar = document.getElementById('confirmarCancelar');
        const cerrarModal = document.getElementById('cerrarModal');

        cancelarBtn.addEventListener('click', () => {
            modal.style.display = 'flex';
        });

        cerrarModal.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        confirmarCancelar.addEventListener('click', () => {
            window.location.href="http://localhost/PRAC-PROF-FINAL/views/panel.php"
        });

        const scannerContainer = document.getElementById('scanner-container');
        const closeScannerBtn = document.getElementById('close-scanner-btn');
        const html5QrCode = new Html5Qrcode("reader");

        document.getElementById('scanBtn').addEventListener('click', () => {
            scannerContainer.style.display = 'block';
            html5QrCode.start(
                { facingMode: "environment" },
                {
                    fps: 10,
                    qrbox: { width: 250, height: 250 }
                },
                async (decodedText, decodedResult) => {
                    await html5QrCode.stop();
                    scannerContainer.style.display = 'none';
                    buscarProducto(decodedText);
                },
                (errorMessage) => {}
            )
            .catch((err) => console.log(err));
        });

        closeScannerBtn.addEventListener('click', async () => {
            await html5QrCode.stop();
            scannerContainer.style.display = 'none';
        });

        async function buscarProducto(ean) {
            if (!ean) return;

            try {
                const respuesta = await fetch(`../Controller/ProductoController.php?ean=${ean}`);
                const producto = await respuesta.json();

                if (!producto.nombre) {
                    mostrarError("Producto no encontrado.");
                    return;
                }

                const existente = carrito.find(p => p.ean === ean);
                if (existente) {
                    existente.cantidad++;
                } else {
                    carrito.push({ ...producto, cantidad: 1 });
                }

                actualizarCarrito();
            } catch {
                mostrarError("Error al conectar con la base de datos.");
            }
        }

        function actualizarCarrito() {
            carritoDiv.innerHTML = '';
            let total = 0;

            carrito.forEach((p, i) => {
                total += p.precio * p.cantidad;

                const item = document.createElement('div');
                item.classList.add('item');

                item.innerHTML = `
                    <div class="item-izq">
                        <img src="${p.imagen}" class="img-prod">
                        
                        <div class="info-prod">
                            <h3>${p.nombre}</h3>
                            <p>$${p.precio.toFixed(2)} c/u</p>
                        </div>
                    </div>

                    <div class="item-der">
                        <div class="cantidad">
                            <button onclick="cambiarCantidad(${i}, -1)">-</button>
                            <span>${p.cantidad}</span>
                            <button onclick="cambiarCantidad(${i}, 1)">+</button>
                        </div>

                        <button onclick="eliminar(${i})" class="eliminar red-box">🗑</button>
                    </div>
                `;

                carritoDiv.appendChild(item);
            });

            totalDiv.textContent = `Total: $${total.toFixed(2)}`;
        }

        function cambiarCantidad(i, valor) {
            carrito[i].cantidad += valor;
            if (carrito[i].cantidad <= 0) carrito.splice(i, 1);
            actualizarCarrito();
        }

        function eliminar(i) {
            carrito.splice(i, 1);
            actualizarCarrito();
        }

        function mostrarError(msg) {
            errorDiv.textContent = msg;
            errorDiv.style.display = 'block';
            setTimeout(() => errorDiv.style.display = 'none', 3000);
        }

        document.getElementById('finalizarBtn').addEventListener('click', async () => {
            if (carrito.length === 0) {
                mostrarError("No hay productos en el carrito.");
                return;
            }

            try {
                const respuesta = await fetch('../Controller/CompraController.php', {
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

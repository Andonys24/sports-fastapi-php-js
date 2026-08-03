class CheckoutUI {
    constructor(apiService, carritoManager) {
        this.api = apiService;
        this.carrito = carritoManager;
        this.form = document.getElementById('form-checkout');
        this.chkDomicilio =  document.getElementById('entrega-domicilio');
        this.inputDireccion = document.getElementById('direccion');
    }

    iniciar() {
        // Validar autenticación antes de permitir el checkout
        if (!authManager.estaAutenticado()) {
            this.mostrarModalAuth();
            return;
        }

        if (this.carrito.items.length === 0) {
            alert("Tu carrito está vacío. Redirigiendo al catálogo...");
            window.router.navegar('/catalogo');
            return;
        }

        this.mostrarResumen();
        
        // Autocompletar datos si existen en el perfil del usuario
        const inputNombre = document.getElementById('nombre');
        if (inputNombre) inputNombre.value = authManager.usuarioActual.nombre;

        // Lógica para mostrar/ocultar dirección
        if (this.chkDomicilio) {
            this.chkDomicilio.addEventListener('change', (e) => {
                this.inputDireccion.style.display = e.target.checked ? 'block' : 'none';
                this.inputDireccion.required = e.target.checked;
                if (!e.target.checked) this.inputDireccion.value = ''; // Limpiar si se desmarca
            });
        }

        if (this.form) {
            this.form.addEventListener('submit', (e) => this.procesarPago(e));
        }
    }

    // Muestra un aviso con opciones para Iniciar Sesión o Registrarse.
    // Se usa cuando el usuario intenta ir a checkout sin estar logueado.
    // Como no sabemos si ya tiene cuenta o no, se ofrecen ambas opciones.
    mostrarModalAuth() {
        // Evita crear el modal dos veces si el usuario hace doble clic, etc.
        if (document.getElementById('modal-auth-checkout')) return;

        const overlay = document.createElement('div');
        overlay.id = 'modal-auth-checkout';
        overlay.style.cssText = `
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.5); display: flex; align-items: center;
            justify-content: center; z-index: 2000; padding: 1rem;
        `;

        const caja = document.createElement('div');
        caja.style.cssText = `
            background: var(--color-blanco, #fff); padding: 2rem;
            border-radius: var(--radio-bordes, 8px);
            box-shadow: var(--sombra-hover, 0 10px 15px rgba(0,0,0,0.1));
            max-width: 380px; width: 100%; text-align: center;
        `;
        caja.innerHTML = `
            <h3 style="margin-bottom: 0.75rem;">Necesitas una cuenta</h3>
            <p style="margin-bottom: 1.5rem; color: var(--color-texto, #1e293b);">
                Para finalizar tu compra debes iniciar sesión. Si ya tienes una cuenta,
                inicia sesión; si no, regístrate para continuar.
            </p>
        `;

        const contenedorBotones = document.createElement('div');
        contenedorBotones.style.cssText = 'display: flex; gap: 0.75rem; justify-content: center; flex-wrap: wrap;';

        const btnLogin = document.createElement('button');
        btnLogin.type = 'button';
        btnLogin.textContent = 'Iniciar Sesión';
        btnLogin.style.cssText = `
            background: var(--color-primario, #2563eb); color: #fff; border: none;
            padding: 0.6rem 1.2rem; border-radius: var(--radio-bordes, 8px); cursor: pointer;
        `;
        btnLogin.addEventListener('click', () => {
            overlay.remove();
            window.router.navegar('/login');
        });

        const btnRegistro = document.createElement('button');
        btnRegistro.type = 'button';
        btnRegistro.textContent = 'Registrarse';
        btnRegistro.style.cssText = `
            background: var(--color-blanco, #fff); color: var(--color-primario, #2563eb);
            border: 2px solid var(--color-primario, #2563eb);
            padding: 0.6rem 1.2rem; border-radius: var(--radio-bordes, 8px); cursor: pointer;
        `;
        btnRegistro.addEventListener('click', () => {
            overlay.remove();
            window.router.navegar('/registro');
        });

        contenedorBotones.appendChild(btnLogin);
        contenedorBotones.appendChild(btnRegistro);
        caja.appendChild(contenedorBotones);
        overlay.appendChild(caja);
        document.body.appendChild(overlay);

        // Si hace clic fuera de la caja, cerramos el modal y lo mandamos al catálogo
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                overlay.remove();
                window.router.navegar('/catalogo');
            }
        });
    }

    mostrarResumen() {
        const resumen = document.getElementById('checkout-resumen');
        if (!resumen) return;

        let html = '<ul>';
        this.carrito.items.forEach(item => {
            html += `<li>${item.nombre} (x${item.cantidad}) - ${item.precio * item.cantidad}</li>`;
        });
        html += `</ul><hr><p><strong>Total: ${this.carrito.obtenerTotal()}</strong></p>`;
        resumen.innerHTML = html;
    }

    async procesarPago(evento) {
        evento.preventDefault();

        const esDomicilio = this.chkDomicilio.checked ? 1 : 0; // Solo se usa para la factura impresa
        const direccion = this.inputDireccion.value || "Recoger en tienda";
        const metodoPago = document.getElementById('metodo_pago').value;
        const total = this.carrito.obtenerTotal();

        // El backend no tiene un endpoint "/sales": una compra se registra en dos pasos:
        // 1) se crea el encargo (Order) con el total y la dirección
        const orden = await this.api.post('/orders/', {
            user_id: authManager.usuarioActual.id,
            address: direccion,
            total: total
        });

        if (!orden || !orden.id) {
            alert("Hubo un error al procesar la compra. Intente nuevamente.");
            return;
        }

        // 2) se crea una factura (Invoice) por cada producto del carrito, referenciando el encargo.
        // Esto es también lo que descuenta el stock en el backend.
        const resultadosItems = await Promise.all(
            this.carrito.items.map(item => this.api.post('/invoices/', {
                product_id: item.id,
                order_id: orden.id,
                quantity: item.cantidad,
                price: item.precio
            }))
        );

        if (resultadosItems.some(resultado => resultado === null)) {
            alert("El encargo se creó, pero hubo un error al registrar uno o más productos (revisa que haya stock suficiente).");
            return;
        }

        // Generar Factura antes de vaciar
        this.generarFacturaFisica({
            domicilio: esDomicilio,
            direccionEntrega: direccion,
            montoTotal: total,
            metodo_pago: metodoPago
        });
        this.carrito.vaciar();
        window.location.href = '/';
    }
    
    // Método para Generar Factura
    generarFacturaFisica(datosCompra) {
        const ventanaFactura = window.open('', '_blank');
        const nombreCliente = document.getElementById('nombre').value;
        
        let filasTabla = '';
        this.carrito.items.forEach(item => {
            filasTabla += `
                <tr>
                    <td>${item.nombre}</td>
                    <td>${item.cantidad}</td>
                    <td>L. ${item.precio}</td>
                    <td>L. ${item.precio * item.cantidad}</td>
                </tr>`;
        });

        const htmlFactura = `
            <html>
            <head>
                <title>Factura de Compra</title>
                <style>
                    body { font-family: Arial, sans-serif; padding: 2rem; color: #333; }
                    .header { text-align: center; border-bottom: 2px solid #2563eb; padding-bottom: 1rem; margin-bottom: 2rem; }
                    table { width: 100%; border-collapse: collapse; margin-bottom: 2rem; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    th { background-color: #f2f2f2; }
                    .total { text-align: right; font-size: 1.2rem; font-weight: bold; }
                </style>
            </head>
            <body>
                <div class="header">
                    <h1>Factura Comercial</h1>
                    <p>Fecha: ${new Date().toLocaleString()}</p>
                </div>
                <h3>Datos del Cliente</h3>
                <p><strong>Nombre:</strong> ${nombreCliente}</p>
                <p><strong>Tipo de Entrega:</strong> ${datosCompra.domicilio === 1 ? 'A Domicilio' : 'Recogida en Tienda'}</p>
                <p><strong>Dirección:</strong> ${datosCompra.direccionEntrega}</p>
                <p><strong>Método de Pago:</strong> ${datosCompra.metodo_pago}</p>
                
                <h3>Detalle de la Compra</h3>
                <table>
                    <thead>
                        <tr><th>Producto</th><th>Cantidad</th><th>Precio Unitario</th><th>Subtotal</th></tr>
                    </thead>
                    <tbody>${filasTabla}</tbody>
                </table>
                <div class="total">
                    Total a Pagar: L. ${datosCompra.montoTotal}
                </div>
                <script>
                    window.onload = function() { window.print(); }
                </script>
            </body>
            </html>
        `;
        
        ventanaFactura.document.write(htmlFactura);
        ventanaFactura.document.close();
    }
}

// La instancia se crea SIEMPRE al cargar la página 
let checkout;
document.addEventListener('DOMContentLoaded', () => {
    checkout = new CheckoutUI(api, carrito);
    if (window.location.pathname === '/checkout') {
        checkout.iniciar();
    }
});
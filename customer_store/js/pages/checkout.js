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
            alert("Debes iniciar sesión para finalizar tu compra.");
            window.location.href = '/login';
            return;
        }

        if (this.carrito.items.length === 0) {
            alert("Tu carrito está vacío. Redirigiendo al catálogo...");
            window.location.href = '/catalogo';
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

        const esDomicilio = this.chkDomicilio.checked ? 1 : 0; // Se alinea con int de CompraCliente
        const direccion = this.inputDireccion.value || "Recoger en tienda";

        const payload = {
            idUsuario: authManager.usuarioActual.id,
            domicilio: esDomicilio, 
            direccionEntrega: direccion,
            montoTotal: this.carrito.obtenerTotal(),
            fecha: new Date().toISOString(),
            metodo_pago: document.getElementById('metodo_pago').value,
            items: this.carrito.items.map(item => ({
                idProducto: item.id,
                cantidad: item.cantidad,
                precioUnidad: item.precio
            }))
        };

        const respuesta = await this.api.post('/sales', payload);

        if (respuesta) {
            // Generar Factura antes de vaciar
            this.generarFacturaFisica(payload);
            this.carrito.vaciar();
            window.location.href = '/'; 
        } else {
            alert("Hubo un error al procesar la compra. Intente nuevamente.");
        }
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

// Inicialización en la vista de checkout
if (window.location.pathname === '/checkout') {
    const checkout = new CheckoutUI(api, carrito);
    document.addEventListener('DOMContentLoaded', () => checkout.iniciar());
}
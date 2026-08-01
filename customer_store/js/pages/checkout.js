class CheckoutUI {
    constructor(apiService, carritoManager) {
        this.api = apiService;
        this.carrito = carritoManager;
        this.form = document.getElementById('form-checkout');
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

        // Recolección de datos del formulario de envío/facturación
        const datosCliente = {
            nombre: document.getElementById('nombre').value,
            direccion: document.getElementById('direccion').value,
            metodo_pago: document.getElementById('metodo_pago').value // Ej: 'Efectivo', 'Tarjeta'
        };

        const payload = {
            cliente: datosCliente,
            items: this.carrito.items,
            total: this.carrito.obtenerTotal()
        };

        // Lógica condicional según el método de pago
        if (datosCliente.metodo_pago === 'Efectivo') {
            console.log("Procesando orden para pago en efectivo al momento de la entrega.");
        }

        const respuesta = await this.api.post('/sales', payload);

        if (respuesta) {
            alert("¡Compra procesada con éxito!");
            this.carrito.vaciar();
            window.location.href = '/'; // Redirigir al inicio o a una página de confirmación
        } else {
            alert("Hubo un error al procesar la compra. Intente nuevamente.");
        }
    }
}

// Inicialización en la vista de checkout
if (window.location.pathname === '/checkout') {
    const checkout = new CheckoutUI(api, carrito);
    document.addEventListener('DOMContentLoaded', () => checkout.iniciar());
}
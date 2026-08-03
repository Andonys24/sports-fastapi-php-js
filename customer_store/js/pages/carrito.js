class CarritoManager {
    constructor() {
        this.items = JSON.parse(localStorage.getItem('carrito_compras')) || [];
        this.formateador = new Intl.NumberFormat('es-HN', { style: 'currency', currency: 'HNL' });
    }

    async agregar(id, nombre, precio) {
        const itemExistente = this.items.find(item => item.id === id);
        const cantidadDeseada = itemExistente ? itemExistente.cantidad + 1 : 1;

        const producto = await api.get(`/products/${id}`);
        if (producto && cantidadDeseada > producto.stock) {
            alert(`Ya tienes en el carrito todo el stock disponible de "${nombre}" (${producto.stock} unidades).`);
            return;
        }

        if (itemExistente) {
            itemExistente.cantidad += 1;
        } else {
            this.items.push({ id, nombre, precio, cantidad: 1 });
        }
        this.guardar();
        this.actualizarUI();
    }

    // Suma una unidad más de un producto que ya está en el carrito,
    // validando primero que haya stock suficiente en el backend
    async aumentarCantidad(id) {
        const item = this.items.find(item => item.id === id);
        if (!item) return;

        const producto = await api.get(`/products/${id}`);

        if (!producto) {
            alert("No se pudo verificar el stock disponible. Intenta de nuevo.");
            return;
        }

        if (item.cantidad + 1 > producto.stock) {
            alert(`Ya tienes en el carrito todo el stock disponible de "${item.nombre}" (${producto.stock} unidades).`);
            return;
        }

        item.cantidad += 1;
        this.guardar();
        this.actualizarUI();
    }

    // Resta una unidad; si llega a 0, el producto se quita del carrito
    disminuirCantidad(id) {
        const item = this.items.find(item => item.id === id);
        if (!item) return;

        item.cantidad -= 1;
        if (item.cantidad <= 0) {
            this.eliminar(id);
            return;
        }
        this.guardar();
        this.actualizarUI();
    }

    eliminar(id) {
        this.items = this.items.filter(item => item.id !== id);
        this.guardar();
        this.actualizarUI();
    }

    obtenerTotal() {
        return this.items.reduce((total, item) => total + (item.precio * item.cantidad), 0);
    }

    guardar() {
        localStorage.setItem('carrito_compras', JSON.stringify(this.items));
    }

    vaciar() {
        this.items = [];
        this.guardar();
        this.actualizarUI();
    }

    actualizarUI() {
        // Actualiza el contador del carrito en el header si existe
        const contador = document.getElementById('carrito-contador');
        if (contador) {
            contador.innerText = this.items.reduce((acc, item) => acc + item.cantidad, 0);
        }

        // Dibuja la lista de productos en la vista /carrito (si ese contenedor existe en la pantalla actual)
        this.renderizar();
    }

    renderizar() {
        const contenedor = document.getElementById('contenido-carrito');
        if (!contenedor) return; // No estamos en la vista del carrito, no hay nada que dibujar

        if (this.items.length === 0) {
            contenedor.innerHTML = '<p>Tu carrito está vacío.</p>';
            return;
        }

        let html = '<ul style="list-style: none; padding: 0;">';
        this.items.forEach(item => {
            const subtotal = item.precio * item.cantidad;
            html += `
                <li style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 0.75rem 0; border-bottom: 1px solid var(--color-borde, #e2e8f0); flex-wrap: wrap;">
                    <div style="flex: 1; min-width: 140px;">
                        <strong>${item.nombre}</strong><br>
                        <span>${this.formateador.format(item.precio)} c/u</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <button type="button" onclick="carrito.disminuirCantidad(${item.id})" aria-label="Quitar una unidad" style="width: 2rem;">-</button>
                        <span>${item.cantidad}</span>
                        <button type="button" onclick="carrito.aumentarCantidad(${item.id})" aria-label="Agregar una unidad" style="width: 2rem;">+</button>
                    </div>
                    <div style="min-width: 6rem; text-align: right;">
                        <strong>${this.formateador.format(subtotal)}</strong>
                    </div>
                    <button type="button" onclick="carrito.eliminar(${item.id})" title="Quitar producto del carrito">Eliminar</button>
                </li>`;
        });
        html += '</ul>';
        html += `<hr><p style="text-align: right;"><strong>Total: ${this.formateador.format(this.obtenerTotal())}</strong></p>`;

        contenedor.innerHTML = html;
    }
}

const carrito = new CarritoManager();
carrito.actualizarUI();
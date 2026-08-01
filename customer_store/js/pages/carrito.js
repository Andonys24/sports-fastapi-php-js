class CarritoManager {
    constructor() {
        this.items = JSON.parse(localStorage.getItem('carrito_compras')) || [];
    }

    agregar(id, nombre, precio) {
        const itemExistente = this.items.find(item => item.id === id);
        if (itemExistente) {
            itemExistente.cantidad += 1;
        } else {
            this.items.push({ id, nombre, precio, cantidad: 1 });
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
    }
}

const carrito = new CarritoManager();
carrito.actualizarUI();
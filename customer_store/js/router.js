class Router {
    constructor() {
        this.rutas = {
            '/': document.getElementById('vista-inicio'),
            '/catalogo': document.getElementById('vista-catalogo'),
            '/carrito': document.getElementById('vista-carrito'),
            '/checkout': document.getElementById('vista-checkout'),
            '/login': document.getElementById('vista-login'),
            '/registro': document.getElementById('vista-registro')
        };
        
        this.inicializar();
    }

    inicializar() {
        // Interceptar navegación de los enlaces
        document.body.addEventListener('click', (e) => {
            if (e.target.matches('[data-link]')) {
                e.preventDefault();
                this.navegar(e.target.getAttribute('href'));
            }
        });

        // Manejar los botones de retroceso/avance del navegador
        window.addEventListener('popstate', () => {
            this.cargarVista(window.location.pathname);
        });

        // Cargar la vista inicial según la URL actual
        this.cargarVista(window.location.pathname);
    }

    navegar(path) {
        window.history.pushState(null, null, path);
        this.cargarVista(path);
    }

    cargarVista(path) {
        // Ocultar todas las vistas
        Object.values(this.rutas).forEach(vista => {
            if (vista) vista.style.display = 'none';
        });

        // Mostrar la vista solicitada o redirigir al inicio si no existe
        const vistaActiva = this.rutas[path] || this.rutas['/'];
        if (vistaActiva) {
            vistaActiva.style.display = 'block';
        }

        // Ejecutar lógicas específicas según la ruta
        this.ejecutarLogicaDeRuta(path);
    }

    ejecutarLogicaDeRuta(path) {
        if (path === '/catalogo' && typeof catalogo !== 'undefined') {
            catalogo.inicializar();
        }
        if (path === '/checkout' && typeof checkout !== 'undefined') {
            checkout.iniciar();
        }
        if (path === '/carrito' && typeof carrito !== 'undefined') {
            carrito.actualizarUI(); // Refrescar vista del carrito
        }
    }
}

// Inicializar el router una vez que el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    const router = new Router();
});
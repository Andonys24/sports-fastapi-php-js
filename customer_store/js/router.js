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
            // Se usa closest() en vez de matches() porque algunos enlaces
            // tienen elementos anidados (ej. un <button> dentro del <a data-link>),
            // y el clic se registra sobre el hijo, no sobre el <a> en sí.
            const enlace = e.target.closest('[data-link]');
            if (enlace) {
                e.preventDefault();
                this.navegar(enlace.getAttribute('href'));
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
// Se expone en window para que otros modulos (auth.js, etc.) puedan navegar
// sin forzar una recarga completa de la pagina
document.addEventListener('DOMContentLoaded', () => {
    window.router = new Router();
});
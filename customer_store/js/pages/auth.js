class AuthManager {
    constructor(apiService) {
        this.api = apiService;
        // Recuperamos el usuario de la sesión actual si existe
        this.usuarioActual = JSON.parse(localStorage.getItem('usuario_actual')) || null;
    }

    estaAutenticado() {
        return this.usuarioActual !== null;
    }

    async registrar(datosUsuario) {
        // Enviar datos al endpoint /api/v1/register
        const respuesta = await this.api.post('/register', datosUsuario);
        
        if (respuesta && !respuesta.error) {
            // Asumimos que el backend devuelve los datos del usuario tras registrarse
            this.iniciarSesionLocal(respuesta.usuario);
            return true;
        }
        return false;
    }

    async login(email, password) {
        // Enviar credenciales al endpoint /api/v1/login
        const respuesta = await this.api.post('/login', { email, password });
        
        if (respuesta && !respuesta.error) {
            // Guardamos la información del usuario (nombre, id, email) en localStorage
            this.iniciarSesionLocal(respuesta.usuario);
            return true;
        }
        return false;
    }

    async logout() {
        // Opcional: Llamar al backend para destruir la cookie de sesión si existe
        await this.api.post('/logout', {}); 
        
        // Limpiamos el almacenamiento local
        this.usuarioActual = null;
        localStorage.removeItem('usuario_actual');
        
        // Redirigir al inicio
        window.location.href = '/';
    }

    iniciarSesionLocal(usuario) {
        this.usuarioActual = usuario;
        localStorage.setItem('usuario_actual', JSON.stringify(usuario));
    }
}

class AuthUI {
    constructor(authManager) {
        this.auth = authManager;
        
        // Elementos del DOM
        this.formLogin = document.getElementById('form-login');
        this.formRegistro = document.getElementById('form-registro');
        this.btnCerrarSesion = document.getElementById('btn-logout');
        
        this.inicializarEventos();
        this.actualizarInterfaz();
    }

    inicializarEventos() {
        if (this.formLogin) {
            this.formLogin.addEventListener('submit', (e) => this.manejarLogin(e));
        }

        if (this.formRegistro) {
            this.formRegistro.addEventListener('submit', (e) => this.manejarRegistro(e));
        }

        if (this.btnCerrarSesion) {
            this.btnCerrarSesion.addEventListener('click', (e) => {
                e.preventDefault();
                this.auth.logout();
            });
        }
    }

    async manejarLogin(evento) {
        evento.preventDefault();
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        const exito = await this.auth.login(email, password);
        
        if (exito) {
            alert(`¡Bienvenido de nuevo!`);
            window.location.href = '/catalogo'; // Redirigir tras login exitoso
        } else {
            alert('Credenciales incorrectas. Intenta de nuevo.');
        }
    }

    async manejarRegistro(evento) {
        evento.preventDefault();
        const datos = {
            nombre: document.getElementById('nombre_registro').value,
            email: document.getElementById('email_registro').value,
            password: document.getElementById('password_registro').value
        };

        const exito = await this.auth.registrar(datos);
        
        if (exito) {
            alert('Registro exitoso. Iniciando sesión...');
            window.location.href = '/catalogo';
        } else {
            alert('Hubo un error al registrar el usuario.');
        }
    }

    actualizarInterfaz() {
        // Muestra u oculta elementos dependiendo de si el usuario está logueado
        const elementosSoloVisitantes = document.querySelectorAll('.solo-visitantes');
        const elementosSoloUsuarios = document.querySelectorAll('.solo-usuarios');
        const nombreUsuarioSpan = document.getElementById('nombre-usuario-header');

        if (this.auth.estaAutenticado()) {
            elementosSoloVisitantes.forEach(el => el.style.display = 'none');
            elementosSoloUsuarios.forEach(el => el.style.display = 'block');
            
            if (nombreUsuarioSpan) {
                nombreUsuarioSpan.innerText = `Hola, ${this.auth.usuarioActual.nombre}`;
            }
        } else {
            elementosSoloVisitantes.forEach(el => el.style.display = 'block');
            elementosSoloUsuarios.forEach(el => el.style.display = 'none');
        }
    }
}

// Inicialización global
const authManager = new AuthManager(api); // 'api' viene de ApiService.js
const authUI = new AuthUI(authManager);
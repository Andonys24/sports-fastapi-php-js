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
        // El backend expone el registro en /api/v1/users/ (POST)
        // Ademas, UserCreate requiere: username, full_name, email, password
        const payload = {
            username: datosUsuario.email,   // se usa el correo como username
            full_name: datosUsuario.nombre,
            email: datosUsuario.email,
            password: datosUsuario.password
        };

        const respuesta = await this.api.post('/users/', payload);

        if (respuesta && !respuesta.error) {
            // El backend devuelve el usuario creado directamente (UserResponse), no envuelto en "usuario",
            // pero usa "full_name" en vez de "nombre". Lo normalizamos para el resto del frontend.
            const usuario = {
                id: respuesta.id,
                nombre: respuesta.full_name,
                email: respuesta.email,
                username: respuesta.username,
                tipo: respuesta.admin
            };
            this.iniciarSesionLocal(usuario);
            return true;
        }
        return false;
    }

    async login(email, password) {
    // 1. OAuth2 requiere estrictamente 'username' y 'password'
    const formData = new URLSearchParams();
    formData.append('username', email); // Pasamos el email en el campo username
    formData.append('password', password);

    try {
        // 2. Hacemos la petición enviando el Content-Type correcto
        const response = await fetch(`${this.api.baseUrl}/auth/login`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: formData.toString()
        });

        if (!response.ok) {
            console.error(`Error ${response.status}: Credenciales inválidas`);
            return false;
        }

        const respuesta = await response.json();
        
        
        if (respuesta && respuesta.access_token) {
            let usuario = { email: email, nombre: email.split('@')[0] }; // respaldo por si /auth/me falla

            try {
                const perfilResponse = await fetch(`${this.api.baseUrl}/auth/me`, {
                    headers: { 'Authorization': `Bearer ${respuesta.access_token}` }
                });

                if (perfilResponse.ok) {
                    const perfil = await perfilResponse.json(); // { id, username, full_name, email, admin }
                    usuario = {
                        id: perfil.id,
                        nombre: perfil.full_name,
                        email: perfil.email,
                        username: perfil.username,
                        tipo: perfil.admin
                    };
                }
            } catch (error) {
                console.error("No se pudo obtener el perfil desde /auth/me:", error);
            }

            this.iniciarSesionLocal(usuario);
            return true;
        }
    } catch (error) {
        console.error("Error durante el inicio de sesión:", error);
    }
    
    return false;
}

    logout() {
        // (no hay cookie de sesión ni blacklist), asi que "cerrar sesión" es
        // simplemente borrar los datos locales del navegador.
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
            // Validar si es administrador
            if (this.auth.usuarioActual.tipo === 1) { // Ajusta el número según tu lógica
                alert('Eres administrador. Por favor, dirígete al portal de gestión en PHP.');
                this.auth.logout(); // Cierra la sesión en el lado del cliente
                return;
            }
            alert(`¡Bienvenido de nuevo!`);
            this.actualizarInterfaz(); // Refrescar botones/menu del header tras iniciar sesion
            window.router.navegar('/catalogo'); // Redirigir tras login exitoso (via SPA router)
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
            this.actualizarInterfaz(); // Refrescar botones/menu del header tras registrarse
            window.router.navegar('/catalogo');
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
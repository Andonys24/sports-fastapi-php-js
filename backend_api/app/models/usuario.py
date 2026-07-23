class Usuario:
    # Metodo constructor
    def __init__(self, nombre_usuario: str, contrasena: str, nombre_completo: str, 
                correo: str) -> None:
        self._nombre_usuario = nombre_usuario
        self._contrasena = contrasena
        self._nombre_completo = nombre_completo
        self._correo = correo
        self._tipo  = 0

    # Metodos getter y setter 
    # Nombre de usuario
    @property
    def nombre_usuario(self) -> str:
        return self._nombre_usuario
    
    @nombre_usuario.setter
    def nombre_usuario(self, nuevo_usuario: str) -> None:
        self._nombre_usuario = nuevo_usuario

    # Contrasena
    @property
    def contrasena(self) -> str:
        return self._contrasena
    
    @contrasena.setter
    def contrasena(self, nueva_contrasena: str) -> None:
        self._contrasena = nueva_contrasena

    # Nombre completo del usuario
    @property
    def nombre_completo(self) -> str:
        return self._nombre_completo
    
    @nombre_completo.setter
    def nombre_completo(self, nuevo_nombre: str) -> None:
        self._nombre_completo = nuevo_nombre

    # Correo
    @property
    def correo(self) -> str:
        return self._correo
    
    @correo.setter
    def correo(self, nuevo_correo: str) -> None:
        self._correo = nuevo_correo

    # Tipo
    def es_administrador(self) -> bool:
        # Si el tipo es 1 el usuario es administrador, en caso contrario, es un cliente
        return True if (self._tipo == 1) else False
    
    def asignar_administrador(self, nuevo_tipo: int) -> None:
        # Validacion en caso que el usuario ya sea un administrador
        if (self._tipo != 1):
            raise ValueError("El usuario ya es un administrador")
        
        # En caso contrario que no se haya lanzado el error, se procede a cambiar los privilegios del usuario
        self._tipo = nuevo_tipo
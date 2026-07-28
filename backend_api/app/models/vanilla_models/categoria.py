class Categoria:
    # Metodo constructor
    def __init__(self, nombre: str, descripcion: str) -> None:
        self._nombre_categoria = nombre
        self._descripcion_categoria = descripcion

    # Metodos getter y setter
    # Nombre de la categoria
    @property
    def nombre(self) -> str:
        return self._nombre_categoria

    @nombre.setter
    def nombre(self, cambio_nombre: str) -> None:
        self._nombre_categoria = cambio_nombre

    # Descripcion
    @property
    def descripcion(self) -> str:
        return self._descripcion_categoria

    @descripcion.setter
    def descripcion(self, nueva_descripcion: str) -> None:
        self._descripcion_categoria = nueva_descripcion
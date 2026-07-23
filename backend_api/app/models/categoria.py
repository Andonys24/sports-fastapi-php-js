class Categoria:
    # Metodo constructor
    def __init__(self, id_categoria: int, nombre: str, descripcion: str) -> None:
        self._id_categoria = id_categoria
        self._nombre_categoria = nombre
        self._descripcion_categoria = descripcion

    # Metodos getter y setter
    # ID de la categoria
    @property
    def id_categoria(self) -> int:
        return self._id_categoria

    @id_categoria.setter
    def id_categoria(self, nuevo_id_categoria: int) -> None:
        self._id_categoria = nuevo_id_categoria 

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